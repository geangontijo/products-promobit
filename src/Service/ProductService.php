<?php

namespace App\Service;

class ProductService
{
    public function listProducts(\PDO $connection): array
    {
        $query = $connection->query(
            "SELECT 
                product.id,
                product.name,
                GROUP_CONCAT(DISTINCT product_tag.tag_id) tags
            FROM product
            LEFT JOIN product_tag ON product_tag.product_id = product.id
            GROUP BY product.id"
        );

        $result = [];
        while ($item = $query->fetch(\PDO::FETCH_ASSOC)) {
            $item['tags'] = array_filter(explode(',', $item['tags']));
            $result[] = $item;
        }

        return $result;
    }
}
