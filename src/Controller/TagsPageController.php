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

class TagsPageController extends AbstractController
{
    #[Route('/tags', methods: ['GET'])]
    public function pageTags(): Response
    {
        return $this->render('tags.php');
    }

    #[Route('/api/tags', methods: ['GET'])]
    public function listTags(PDO $connection): Response
    {
        $tagService = new TagService();

        return new JsonResponse([
            'message' => 'success',
            'data' => [
                'tags' => $tagService->listTags($connection)
            ]
        ]);
    }

    #[Route('/api/tags', methods: ['POST'])]
    public function saveTag(Request $request, Builder $builder): Response
    {
        $jsonData = $request->toArray();

        $tagData = filter_var_array($jsonData, [
            'id' => FILTER_VALIDATE_INT,
            'name' => FILTER_SANITIZE_STRING
        ]);

        if ($tagData['id'] === false) {
            return new JsonResponse([
                'message' => 'campo "id" deve ser um número'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($tagData['id']) {
            $builder->table('tag')->update($tagData)->where(['id' => $tagData['id']])->execute();
        } else {
            $builder->table('tag')->insert($tagData)->execute();
        }

        return new JsonResponse(['message' => 'success']);
    }

    #[Route('/api/tags/{tagId}', methods: ['DELETE'], requirements: ['tagId' => '\d+'])]
    public function deleteTag(int $tagId, Builder $queryBuilder)
    {
        $queryBuilder->table('tag')->delete()->where(['id' => $tagId])->execute();

        return new JsonResponse(['message' => 'success']);
    }
}
