<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Location;
use App\Models\SavedListing;

class ListingController extends Controller
{
    public function index(): void
    {
        $filters = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'category_id' => (int) ($_GET['category_id'] ?? 0),
            'province_id' => (int) ($_GET['province_id'] ?? 0),
            'ward_id' => (int) ($_GET['ward_id'] ?? 0),
            'min_price' => (float) ($_GET['min_price'] ?? 0),
            'max_price' => (float) ($_GET['max_price'] ?? 0),
            'page' => (int) ($_GET['page'] ?? 1),
        ];

        $this->render('listings/index', [
            'title' => 'Browse listings — LHT Estate',
            'result' => (new Listing())->search($filters),
            'filters' => $filters,
            'categories' => (new Category())->all(),
            'provinces' => (new Location())->provinces(),
            'wards' => (new Location())->wards($filters['province_id'] > 0 ? $filters['province_id'] : null),
        ]);
    }

    public function show(string $slug): void
    {
        $listingModel = new Listing();
        $listing = $listingModel->findApprovedBySlug($slug);
        if ($listing === null) {
            (new HomeController())->error(404, 'Listing not found.');
            return;
        }

        $saved = false;
        $userId = Auth::id();
        if ($userId !== null) {
            $saved = (new SavedListing())->isSaved($userId, (int) $listing['id']);
        }

        $this->render('listings/show', [
            'title' => $listing['title'] . ' — LHT Estate',
            'listing' => $listing,
            'images' => $listingModel->images((int) $listing['id']),
            'saved' => $saved,
        ]);
    }

    public function create(): void
    {
        Auth::requireAuth();

        $this->render('listings/form', [
            'title' => 'Post a listing — LHT Estate',
            'listing' => null,
            'action' => '/post',
            'categories' => (new Category())->all(),
            'provinces' => (new Location())->provinces(),
            'wards' => (new Location())->wards(),
            'errors' => $this->validationErrors(),
        ]);
    }

    public function store(): void
    {
        Auth::requireAuth();
        $this->requireCsrf();

        $data = $this->validatedListingData();
        $listingModel = new Listing();
        $data['user_id'] = Auth::id();
        $data['slug'] = $this->uniqueSlug($data['title']);
        $listingId = $listingModel->create($data);
        $this->storeImages($listingModel, $listingId);

        flash('success', 'Listing submitted for admin review.');
        redirect('/my-listings');
    }

    public function myListings(): void
    {
        Auth::requireAuth();

        $this->render('listings/my-listings', [
            'title' => 'My listings — LHT Estate',
            'listings' => (new Listing())->forUser((int) Auth::id()),
        ]);
    }

    public function edit(string $id): void
    {
        Auth::requireAuth();
        $listing = (new Listing())->findOwned((int) $id, (int) Auth::id());
        if ($listing === null) {
            (new HomeController())->error(404, 'Listing not found.');
            return;
        }

        $this->render('listings/form', [
            'title' => 'Edit listing — LHT Estate',
            'listing' => $listing,
            'action' => '/listing/' . (int) $id . '/edit',
            'categories' => (new Category())->all(),
            'provinces' => (new Location())->provinces(),
            'wards' => (new Location())->wards(),
            'errors' => $this->validationErrors(),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireAuth();
        $this->requireCsrf();

        $listingModel = new Listing();
        $listing = $listingModel->findOwned((int) $id, (int) Auth::id());
        if ($listing === null) {
            (new HomeController())->error(404, 'Listing not found.');
            return;
        }

        $data = $this->validatedListingData();
        $data['slug'] = $this->uniqueSlug($data['title'], (int) $id);
        $listingModel->updateOwned((int) $id, (int) Auth::id(), $data);
        $this->storeImages($listingModel, (int) $id);

        flash('success', 'Listing updated and moved back to pending review.');
        redirect('/my-listings');
    }

    public function delete(string $id): void
    {
        Auth::requireAuth();
        $this->requireCsrf();
        (new Listing())->deleteOwned((int) $id, (int) Auth::id());
        flash('success', 'Listing deleted.');
        redirect('/my-listings');
    }

    public function saved(): void
    {
        Auth::requireAuth();

        $this->render('listings/saved', [
            'title' => 'Saved listings — LHT Estate',
            'listings' => (new SavedListing())->forUser((int) Auth::id()),
        ]);
    }

    public function save(string $id): void
    {
        Auth::requireAuth();
        $this->requireCsrf();
        if ((new Listing())->findApproved((int) $id) !== null) {
            (new SavedListing())->save((int) Auth::id(), (int) $id);
            flash('success', 'Listing saved.');
        }
        redirect_back('/saved');
    }

    public function unsave(string $id): void
    {
        Auth::requireAuth();
        $this->requireCsrf();
        (new SavedListing())->unsave((int) Auth::id(), (int) $id);
        flash('success', 'Listing removed from saved items.');
        redirect_back('/saved');
    }

    private function validatedListingData(): array
    {
        $title = trim((string) ($_POST['title'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $address = trim((string) ($_POST['address'] ?? ''));
        $contactName = trim((string) ($_POST['contact_name'] ?? ''));
        $contactPhone = trim((string) ($_POST['contact_phone'] ?? ''));
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $provinceId = (int) ($_POST['province_id'] ?? 0);
        $wardId = (int) ($_POST['ward_id'] ?? 0);
        $price = (float) ($_POST['price'] ?? 0);
        $area = (float) ($_POST['area'] ?? 0);
        $errors = [];

        if (strlen($title) < 8 || strlen($title) > 180) {
            $errors['title'] = 'Title must be 8–180 characters.';
        }
        if (strlen($description) < 30) {
            $errors['description'] = 'Description must be at least 30 characters.';
        }
        if ($categoryId < 1) {
            $errors['category_id'] = 'Choose a category.';
        }
        if ($provinceId < 1) {
            $errors['province_id'] = 'Choose a province.';
        }
        if ($wardId < 1) {
            $errors['ward_id'] = 'Choose a ward.';
        }
        if ($price < 100000) {
            $errors['price'] = 'Price must be at least 100,000 VND.';
        }
        if ($area <= 0) {
            $errors['area'] = 'Area must be greater than zero.';
        }
        if (strlen($address) < 6) {
            $errors['address'] = 'Address is required.';
        }
        if (strlen($contactName) < 2) {
            $errors['contact_name'] = 'Contact name is required.';
        }
        if (!preg_match('/^[0-9+()\-\s]{8,20}$/', $contactPhone)) {
            $errors['contact_phone'] = 'Enter a valid contact phone.';
        }
        $imageErrors = $this->validateUploadedImages();
        if ($imageErrors !== []) {
            $errors['images'] = implode(' ', $imageErrors);
        }

        if ($errors !== []) {
            $this->backWithErrors($errors);
        }

        return compact(
            'title',
            'description',
            'categoryId',
            'provinceId',
            'wardId',
            'price',
            'area',
            'address',
            'contactName',
            'contactPhone'
        ) + [
            'category_id' => $categoryId,
            'province_id' => $provinceId,
            'ward_id' => $wardId,
            'contact_name' => $contactName,
            'contact_phone' => $contactPhone,
        ];
    }

    private function validateUploadedImages(): array
    {
        if (empty($_FILES['images']) || !is_array($_FILES['images']['name'])) {
            return [];
        }

        $errors = [];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);

        foreach ($_FILES['images']['name'] as $index => $name) {
            if ($_FILES['images']['error'][$index] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if ($_FILES['images']['error'][$index] !== UPLOAD_ERR_OK) {
                $errors[] = 'An image failed to upload.';
                continue;
            }
            if ($_FILES['images']['size'][$index] > 3 * 1024 * 1024) {
                $errors[] = (string) $name . ' is larger than 3 MB.';
            }

            $extension = strtolower(pathinfo((string) $name, PATHINFO_EXTENSION));
            $mimeType = $fileInfo->file((string) $_FILES['images']['tmp_name'][$index]);
            if (!in_array($extension, $allowedExtensions, true) || !in_array($mimeType, $allowedMimeTypes, true)) {
                $errors[] = (string) $name . ' must be a JPG, PNG, or WebP image.';
            }
        }

        return $errors;
    }

    private function storeImages(Listing $listingModel, int $listingId): void
    {
        if (empty($_FILES['images']) || !is_array($_FILES['images']['name'])) {
            return;
        }

        $uploadDir = BASE_PATH . '/public/assets/uploads';
        foreach ($_FILES['images']['name'] as $index => $name) {
            if ($_FILES['images']['error'][$index] !== UPLOAD_ERR_OK) {
                continue;
            }

            $extension = strtolower(pathinfo((string) $name, PATHINFO_EXTENSION));
            $fileName = $listingId . '-' . bin2hex(random_bytes(8)) . '.' . $extension;
            $target = $uploadDir . '/' . $fileName;
            if (move_uploaded_file((string) $_FILES['images']['tmp_name'][$index], $target)) {
                $listingModel->addImage($listingId, '/assets/uploads/' . $fileName, $index);
            }
        }
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = slugify($title);
        $slug = $base;
        $suffix = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId): bool
    {
        $sql = 'SELECT COUNT(*) FROM listings WHERE slug = :slug';
        $params = ['slug' => $slug];
        if ($ignoreId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $ignoreId;
        }

        $statement = \App\Core\Database::connection()->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }
}
