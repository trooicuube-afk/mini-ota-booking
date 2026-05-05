<?php

declare(strict_types=1);

namespace App\Models;

class SavedListing extends Model
{
    public function save(int $userId, int $listingId): void
    {
        $statement = $this->db->prepare(
            'INSERT IGNORE INTO saved_listings (user_id, listing_id, created_at)
             VALUES (:user_id, :listing_id, NOW())'
        );
        $statement->execute(['user_id' => $userId, 'listing_id' => $listingId]);
    }

    public function unsave(int $userId, int $listingId): void
    {
        $statement = $this->db->prepare('DELETE FROM saved_listings WHERE user_id = :user_id AND listing_id = :listing_id');
        $statement->execute(['user_id' => $userId, 'listing_id' => $listingId]);
    }

    public function isSaved(int $userId, int $listingId): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM saved_listings WHERE user_id = :user_id AND listing_id = :listing_id');
        $statement->execute(['user_id' => $userId, 'listing_id' => $listingId]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function forUser(int $userId): array
    {
        $statement = $this->db->prepare(
            "SELECT l.*, c.name AS category_name, p.name AS province_name, w.name AS ward_name,
                    (SELECT path FROM listing_images WHERE listing_id = l.id ORDER BY sort_order, id LIMIT 1) AS cover_image
             FROM saved_listings s
             INNER JOIN listings l ON l.id = s.listing_id
             INNER JOIN categories c ON c.id = l.category_id
             INNER JOIN provinces p ON p.id = l.province_id
             INNER JOIN wards w ON w.id = l.ward_id
             WHERE s.user_id = :user_id AND l.status = 'approved'
             ORDER BY s.created_at DESC"
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }
}
