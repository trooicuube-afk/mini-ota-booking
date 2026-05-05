<?php

declare(strict_types=1);

namespace App\Models;

class Location extends Model
{
    public function provinces(): array
    {
        $statement = $this->db->prepare('SELECT * FROM provinces ORDER BY name');
        $statement->execute();

        return $statement->fetchAll();
    }

    public function wards(?int $provinceId = null): array
    {
        if ($provinceId !== null) {
            $statement = $this->db->prepare('SELECT * FROM wards WHERE province_id = :province_id ORDER BY name');
            $statement->execute(['province_id' => $provinceId]);

            return $statement->fetchAll();
        }

        $statement = $this->db->prepare('SELECT * FROM wards ORDER BY name');
        $statement->execute();

        return $statement->fetchAll();
    }
}
