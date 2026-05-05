<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

$envPath = BASE_PATH . '/.env';
if (is_file($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $value = trim($value, "\"' ");
        putenv(trim($key) . '=' . $value);
    }
}

$config = require BASE_PATH . '/config/app.php';
$database = $config['database'];

$serverDsn = sprintf('mysql:host=%s;port=%s;charset=%s', $database['host'], $database['port'], $database['charset']);
$pdo = new PDO($serverDsn, $database['username'], $database['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
$pdo->exec('CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '``', $database['database']) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$pdo->exec('USE `' . str_replace('`', '``', $database['database']) . '`');
$pdo->exec(file_get_contents(BASE_PATH . '/database/schema.sql'));

$insert = static function (PDO $pdo, string $table, array $rows, string $uniqueColumn = 'slug'): void {
    foreach ($rows as $row) {
        $exists = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE {$uniqueColumn} = :value");
        $exists->execute(['value' => $row[$uniqueColumn]]);
        if ((int) $exists->fetchColumn() > 0) {
            continue;
        }

        $columns = array_keys($row);
        $sql = sprintf(
            'INSERT INTO %s (%s, created_at, updated_at) VALUES (%s, NOW(), NOW())',
            $table,
            implode(', ', $columns),
            implode(', ', array_map(static fn (string $column): string => ':' . $column, $columns))
        );
        $statement = $pdo->prepare($sql);
        $statement->execute($row);
    }
};

$insert($pdo, 'categories', [
    ['name' => 'Apartments', 'slug' => 'apartments'],
    ['name' => 'Townhouses', 'slug' => 'townhouses'],
    ['name' => 'Private Homes', 'slug' => 'private-homes'],
    ['name' => 'Land Lots', 'slug' => 'land-lots'],
    ['name' => 'Commercial Spaces', 'slug' => 'commercial-spaces'],
]);

$insert($pdo, 'provinces', [
    ['name' => 'Ho Chi Minh City', 'slug' => 'ho-chi-minh-city'],
    ['name' => 'Ha Noi', 'slug' => 'ha-noi'],
    ['name' => 'Da Nang', 'slug' => 'da-nang'],
    ['name' => 'Binh Duong', 'slug' => 'binh-duong'],
]);

$provinceIds = [];
foreach ($pdo->query('SELECT id, slug FROM provinces') as $province) {
    $provinceIds[$province['slug']] = (int) $province['id'];
}

$wards = [
    ['province_id' => $provinceIds['ho-chi-minh-city'], 'name' => 'Ben Nghe', 'type' => 'Ward', 'slug' => 'ben-nghe'],
    ['province_id' => $provinceIds['ho-chi-minh-city'], 'name' => 'Thao Dien', 'type' => 'Ward', 'slug' => 'thao-dien'],
    ['province_id' => $provinceIds['ha-noi'], 'name' => 'Cau Giay', 'type' => 'District', 'slug' => 'cau-giay'],
    ['province_id' => $provinceIds['ha-noi'], 'name' => 'Tay Ho', 'type' => 'District', 'slug' => 'tay-ho'],
    ['province_id' => $provinceIds['da-nang'], 'name' => 'My An', 'type' => 'Ward', 'slug' => 'my-an'],
    ['province_id' => $provinceIds['binh-duong'], 'name' => 'Di An', 'type' => 'City', 'slug' => 'di-an'],
];
foreach ($wards as $ward) {
    $exists = $pdo->prepare('SELECT COUNT(*) FROM wards WHERE province_id = :province_id AND slug = :slug');
    $exists->execute(['province_id' => $ward['province_id'], 'slug' => $ward['slug']]);
    if ((int) $exists->fetchColumn() === 0) {
        $statement = $pdo->prepare(
            'INSERT INTO wards (province_id, name, type, slug, created_at, updated_at)
             VALUES (:province_id, :name, :type, :slug, NOW(), NOW())'
        );
        $statement->execute($ward);
    }
}

$users = [
    ['name' => 'LHT Admin', 'email' => 'admin@lhtestate.test', 'password' => 'password123', 'role' => 'admin'],
    ['name' => 'Demo Seller', 'email' => 'seller@lhtestate.test', 'password' => 'password123', 'role' => 'user'],
    ['name' => 'Demo Buyer', 'email' => 'buyer@lhtestate.test', 'password' => 'password123', 'role' => 'user'],
];
foreach ($users as $user) {
    $exists = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
    $exists->execute(['email' => $user['email']]);
    if ((int) $exists->fetchColumn() === 0) {
        $statement = $pdo->prepare(
            'INSERT INTO users (name, email, password, role, status, created_at, updated_at)
             VALUES (:name, :email, :password, :role, "active", NOW(), NOW())'
        );
        $statement->execute([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => password_hash($user['password'], PASSWORD_DEFAULT),
            'role' => $user['role'],
        ]);
    }
}

$ids = [];
foreach (['users' => 'email', 'categories' => 'slug', 'provinces' => 'slug', 'wards' => 'slug'] as $table => $key) {
    foreach ($pdo->query("SELECT id, {$key} FROM {$table}") as $row) {
        $ids[$table][$row[$key]] = (int) $row['id'];
    }
}

$listings = [
    [
        'user_id' => $ids['users']['seller@lhtestate.test'],
        'category_id' => $ids['categories']['apartments'],
        'province_id' => $ids['provinces']['ho-chi-minh-city'],
        'ward_id' => $ids['wards']['thao-dien'],
        'title' => 'Sunlit two bedroom apartment near riverside park',
        'slug' => 'sunlit-two-bedroom-apartment-near-riverside-park',
        'description' => 'A bright apartment with open living space, balcony views, secure parking, and easy access to cafes, markets, and international schools.',
        'price' => 1850000000,
        'area' => 72,
        'address' => '12 Riverside Lane',
        'contact_name' => 'Demo Seller',
        'contact_phone' => '+84 900 111 222',
        'status' => 'approved',
        'demo_image' => 'assets/demo-listings/sunlit-apartment.jpg',
    ],
    [
        'user_id' => $ids['users']['seller@lhtestate.test'],
        'category_id' => $ids['categories']['townhouses'],
        'province_id' => $ids['provinces']['ha-noi'],
        'ward_id' => $ids['wards']['tay-ho'],
        'title' => 'Quiet townhouse with garden courtyard in Tay Ho',
        'slug' => 'quiet-townhouse-with-garden-courtyard-in-tay-ho',
        'description' => 'Three-level townhouse suitable for families, featuring a private courtyard, natural light, modern kitchen, and calm residential surroundings.',
        'price' => 4200000000,
        'area' => 118,
        'address' => '88 Lakeview Street',
        'contact_name' => 'Demo Seller',
        'contact_phone' => '+84 900 111 222',
        'status' => 'approved',
        'demo_image' => 'assets/demo-listings/garden-townhouse.jpg',
    ],
    [
        'user_id' => $ids['users']['seller@lhtestate.test'],
        'category_id' => $ids['categories']['land-lots'],
        'province_id' => $ids['provinces']['da-nang'],
        'ward_id' => $ids['wards']['my-an'],
        'title' => 'Corner land lot close to beach hospitality corridor',
        'slug' => 'corner-land-lot-close-to-beach-hospitality-corridor',
        'description' => 'Well-positioned corner land lot for a boutique lodging, townhouse, or long-term investment near restaurants and public beach access.',
        'price' => 5600000000,
        'area' => 160,
        'address' => '45 Coastal Avenue',
        'contact_name' => 'Demo Seller',
        'contact_phone' => '+84 900 111 222',
        'status' => 'pending',
        'demo_image' => 'assets/demo-listings/coastal-land-lot.jpg',
    ],
];

foreach ($listings as $listing) {
    $demoImage = $listing['demo_image'];
    unset($listing['demo_image']);

    $exists = $pdo->prepare('SELECT COUNT(*) FROM listings WHERE slug = :slug');
    $exists->execute(['slug' => $listing['slug']]);
    if ((int) $exists->fetchColumn() === 0) {
        $columns = array_keys($listing);
        $statement = $pdo->prepare(
            'INSERT INTO listings (' . implode(', ', $columns) . ', created_at, updated_at)
             VALUES (' . implode(', ', array_map(static fn (string $column): string => ':' . $column, $columns)) . ', NOW(), NOW())'
        );
        $statement->execute($listing);
    }

    $listingLookup = $pdo->prepare('SELECT id FROM listings WHERE slug = :slug LIMIT 1');
    $listingLookup->execute(['slug' => $listing['slug']]);
    $listingId = (int) $listingLookup->fetchColumn();
    if ($listingId > 0) {
        $imageExists = $pdo->prepare('SELECT COUNT(*) FROM listing_images WHERE listing_id = :listing_id AND path = :path');
        $imageExists->execute(['listing_id' => $listingId, 'path' => $demoImage]);
        if ((int) $imageExists->fetchColumn() === 0) {
            $imageInsert = $pdo->prepare(
                'INSERT INTO listing_images (listing_id, path, sort_order, created_at)
                 VALUES (:listing_id, :path, 0, NOW())'
            );
            $imageInsert->execute(['listing_id' => $listingId, 'path' => $demoImage]);
        }
    }
}

echo "LHT Estate database seeded.\n";
echo "Admin: admin@lhtestate.test / password123\n";
echo "Seller: seller@lhtestate.test / password123\n";
echo "Buyer: buyer@lhtestate.test / password123\n";
