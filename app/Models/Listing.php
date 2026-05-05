<?php

declare(strict_types=1);

namespace App\Models;

class Listing extends Model
{
    public function search(array $filters): array
    {
        $where = ["l.status = 'approved'"];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(l.title LIKE :q_title OR l.description LIKE :q_description OR l.address LIKE :q_address)';
            $keyword = '%' . $filters['q'] . '%';
            $params['q_title'] = $keyword;
            $params['q_description'] = $keyword;
            $params['q_address'] = $keyword;
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'l.category_id = :category_id';
            $params['category_id'] = (int) $filters['category_id'];
        }
        if (!empty($filters['province_id'])) {
            $where[] = 'l.province_id = :province_id';
            $params['province_id'] = (int) $filters['province_id'];
        }
        if (!empty($filters['ward_id'])) {
            $where[] = 'l.ward_id = :ward_id';
            $params['ward_id'] = (int) $filters['ward_id'];
        }
        if (!empty($filters['min_price'])) {
            $where[] = 'l.price >= :min_price';
            $params['min_price'] = (float) $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = 'l.price <= :max_price';
            $params['max_price'] = (float) $filters['max_price'];
        }

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        $whereSql = implode(' AND ', $where);

        $count = $this->db->prepare("SELECT COUNT(*) FROM listings l WHERE {$whereSql}");
        $count->execute($params);

        $statement = $this->db->prepare(
            "SELECT l.*, c.name AS category_name, p.name AS province_name, w.name AS ward_name,
                    (SELECT path FROM listing_images WHERE listing_id = l.id ORDER BY sort_order, id LIMIT 1) AS cover_image
             FROM listings l
             INNER JOIN categories c ON c.id = l.category_id
             INNER JOIN provinces p ON p.id = l.province_id
             INNER JOIN wards w ON w.id = l.ward_id
             WHERE {$whereSql}
             ORDER BY l.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
        $statement->execute($params);

        return [
            'items' => $statement->fetchAll(),
            'total' => (int) $count->fetchColumn(),
            'page' => $page,
            'per_page' => $perPage,
        ];
    }

    public function latest(int $limit = 6): array
    {
        $limit = max(1, min(12, $limit));
        $statement = $this->db->prepare(
            "SELECT l.*, c.name AS category_name, p.name AS province_name, w.name AS ward_name,
                    (SELECT path FROM listing_images WHERE listing_id = l.id ORDER BY sort_order, id LIMIT 1) AS cover_image
             FROM listings l
             INNER JOIN categories c ON c.id = l.category_id
             INNER JOIN provinces p ON p.id = l.province_id
             INNER JOIN wards w ON w.id = l.ward_id
             WHERE l.status = 'approved'
             ORDER BY l.created_at DESC
             LIMIT {$limit}"
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findApprovedBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(
            "SELECT l.*, u.name AS owner_name, c.name AS category_name, p.name AS province_name, w.name AS ward_name
             FROM listings l
             INNER JOIN users u ON u.id = l.user_id
             INNER JOIN categories c ON c.id = l.category_id
             INNER JOIN provinces p ON p.id = l.province_id
             INNER JOIN wards w ON w.id = l.ward_id
             WHERE l.slug = :slug AND l.status = 'approved'
             LIMIT 1"
        );
        $statement->execute(['slug' => $slug]);
        $listing = $statement->fetch();

        return $listing ?: null;
    }

    public function findApproved(int $id): ?array
    {
        $statement = $this->db->prepare("SELECT * FROM listings WHERE id = :id AND status = 'approved' LIMIT 1");
        $statement->execute(['id' => $id]);
        $listing = $statement->fetch();

        return $listing ?: null;
    }

    public function findOwned(int $id, int $userId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT * FROM listings WHERE id = :id AND user_id = :user_id LIMIT 1'
        );
        $statement->execute(['id' => $id, 'user_id' => $userId]);
        $listing = $statement->fetch();

        return $listing ?: null;
    }

    public function images(int $listingId): array
    {
        $statement = $this->db->prepare('SELECT * FROM listing_images WHERE listing_id = :listing_id ORDER BY sort_order, id');
        $statement->execute(['listing_id' => $listingId]);

        return $statement->fetchAll();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            "INSERT INTO listings
             (user_id, category_id, province_id, ward_id, title, slug, description, price, area, address, contact_name, contact_phone, status, created_at, updated_at)
             VALUES
             (:user_id, :category_id, :province_id, :ward_id, :title, :slug, :description, :price, :area, :address, :contact_name, :contact_phone, 'pending', NOW(), NOW())"
        );
        $statement->execute([
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
            'province_id' => $data['province_id'],
            'ward_id' => $data['ward_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'price' => $data['price'],
            'area' => $data['area'],
            'address' => $data['address'],
            'contact_name' => $data['contact_name'],
            'contact_phone' => $data['contact_phone'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateOwned(int $id, int $userId, array $data): void
    {
        $statement = $this->db->prepare(
            "UPDATE listings SET
                category_id = :category_id,
                province_id = :province_id,
                ward_id = :ward_id,
                title = :title,
                slug = :slug,
                description = :description,
                price = :price,
                area = :area,
                address = :address,
                contact_name = :contact_name,
                contact_phone = :contact_phone,
                status = 'pending',
                updated_at = NOW()
             WHERE id = :id AND user_id = :user_id"
        );
        $statement->execute([
            'id' => $id,
            'user_id' => $userId,
            'category_id' => $data['category_id'],
            'province_id' => $data['province_id'],
            'ward_id' => $data['ward_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'price' => $data['price'],
            'area' => $data['area'],
            'address' => $data['address'],
            'contact_name' => $data['contact_name'],
            'contact_phone' => $data['contact_phone'],
        ]);
    }

    public function addImage(int $listingId, string $path, int $sortOrder): void
    {
        $statement = $this->db->prepare(
            'INSERT INTO listing_images (listing_id, path, sort_order, created_at)
             VALUES (:listing_id, :path, :sort_order, NOW())'
        );
        $statement->execute([
            'listing_id' => $listingId,
            'path' => $path,
            'sort_order' => $sortOrder,
        ]);
    }

    public function forUser(int $userId): array
    {
        $statement = $this->db->prepare(
            "SELECT l.*, c.name AS category_name, p.name AS province_name, w.name AS ward_name,
                    (SELECT path FROM listing_images WHERE listing_id = l.id ORDER BY sort_order, id LIMIT 1) AS cover_image
             FROM listings l
             INNER JOIN categories c ON c.id = l.category_id
             INNER JOIN provinces p ON p.id = l.province_id
             INNER JOIN wards w ON w.id = l.ward_id
             WHERE l.user_id = :user_id
             ORDER BY l.created_at DESC"
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }

    public function deleteOwned(int $id, int $userId): void
    {
        $statement = $this->db->prepare('DELETE FROM listings WHERE id = :id AND user_id = :user_id');
        $statement->execute(['id' => $id, 'user_id' => $userId]);
    }

    public function allForAdmin(?string $status = null): array
    {
        $params = [];
        $where = '';
        if ($status !== null && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $where = 'WHERE l.status = :status';
            $params['status'] = $status;
        }

        $statement = $this->db->prepare(
            "SELECT l.*, u.name AS owner_name, u.email AS owner_email, c.name AS category_name, p.name AS province_name, w.name AS ward_name
             FROM listings l
             INNER JOIN users u ON u.id = l.user_id
             INNER JOIN categories c ON c.id = l.category_id
             INNER JOIN provinces p ON p.id = l.province_id
             INNER JOIN wards w ON w.id = l.ward_id
             {$where}
             ORDER BY l.created_at DESC"
        );
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function countByStatus(?string $status = null): int
    {
        if ($status === null) {
            $statement = $this->db->prepare('SELECT COUNT(*) FROM listings');
            $statement->execute();

            return (int) $statement->fetchColumn();
        }

        $statement = $this->db->prepare('SELECT COUNT(*) FROM listings WHERE status = :status');
        $statement->execute(['status' => $status]);

        return (int) $statement->fetchColumn();
    }

    public function setStatus(int $id, string $status): void
    {
        $statement = $this->db->prepare('UPDATE listings SET status = :status, updated_at = NOW() WHERE id = :id');
        $statement->execute(['id' => $id, 'status' => $status]);
    }

    public function deleteAsAdmin(int $id): void
    {
        $statement = $this->db->prepare('DELETE FROM listings WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
