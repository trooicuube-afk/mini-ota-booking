<?php

declare(strict_types=1);

namespace App\Models;

class Category extends Model
{
    public function all(): array
    {
        $statement = $this->db->prepare('SELECT * FROM categories ORDER BY name');
        $statement->execute();

        return $statement->fetchAll();
    }
}
