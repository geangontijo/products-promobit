<?php

namespace App\Controller;

use App\Database\QueryBuilderFactory;
use App\Service\ProductService;
use App\Service\TagService;
use ClanCats\Hydrahon\Builder;
use PDO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsPageController extends AbstractController
{
    #[Route('/products', methods: ['GET'])]
    public function pageProducts(): Response
    {
        return $this->render('products.php');
    }

    #[Route('/api/products', methods: ['GET'])]
    public function listProducts(PDO $connection): Response
    {
        $productService = new ProductService();
        $tagService = new TagService();


        return new JsonResponse([
            'message' => 'success',
            'data' => [
                'products' => $productService->listProducts($connection),
                'tags' => $tagService->listTags($connection)
            ]
        ]);
    }

    #[Route('/api/products', methods: ['POST'])]
    public function saveProduct(Request $request, \PDO $connection): Response
    {
        $jsonData = $request->toArray();

        $productData = filter_var_array($jsonData, [
            'id' => FILTER_VALIDATE_INT,
            'name' => FILTER_SANITIZE_STRING
        ]);

        if ($productData['id'] === false) {
            return new JsonResponse([
                'message' => 'campo "id" deve ser um número'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $connection->beginTransaction();
        $queryBuilder = QueryBuilderFactory::create($connection);

        if ($productData['id']) {
            $queryBuilder->table('product')->update($productData)->where(['id' => $productData['id']])->execute();
        } else {
            $insert = $queryBuilder->table('product')->insert([$productData]);
            $productData['id'] = $insert->execute();
        }


        $insertTags = $queryBuilder->table('product_tag')->insert();
        foreach ($jsonData['tags_add'] as $tagId) {

            $insertTags->values([
                'product_id' => $productData['id'],
                'tag_id' => $tagId
            ]);
        }
        if (!empty($jsonData['tags_add'])) {
            $insertTags->execute();
        }

        $deleteTags = $queryBuilder->table('product_tag')->delete();
        foreach ($jsonData['tags_rm'] as $tagId) {

            $deleteTags->where([
                'product_id' => $productData['id'],
                'tag_id' => $tagId
            ]);
        }

        if (!empty($jsonData['tags_rm'])) {
            $deleteTags->execute();
        }


        $connection->commit();
        return new JsonResponse(['message' => 'success']);
    }

    #[Route('/api/products/{productId}', methods: ['DELETE'], requirements: ['productId' => '\d+'])]
    public function deleteProduct(int $productId, Builder $queryBuilder)
    {
        $queryBuilder->table('product')->delete()->where(['id' => $productId])->execute();

        return new JsonResponse(['message' => 'success']);
    }
}
