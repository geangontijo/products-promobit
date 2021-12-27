<?php

namespace App\Service;

class TagService
{
    public function listTags(\PDO $connection): array
    {
        return $connection->query(
            "SELECT 
                tag.id,
                tag.name
            FROM tag"
        )->fetchAll(\PDO::FETCH_ASSOC);
    }
}
