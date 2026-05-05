<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Listing;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard(): void
    {
        Auth::requireAdmin();
        $listing = new Listing();

        $this->render('admin/dashboard', [
            'title' => 'Admin dashboard — LHT Estate',
            'totalListings' => $listing->countByStatus(),
            'pendingListings' => $listing->countByStatus('pending'),
            'approvedListings' => $listing->countByStatus('approved'),
            'rejectedListings' => $listing->countByStatus('rejected'),
            'usersCount' => (new User())->count(),
        ]);
    }

    public function listings(): void
    {
        Auth::requireAdmin();
        $status = isset($_GET['status']) ? (string) $_GET['status'] : null;

        $this->render('admin/listings', [
            'title' => 'Manage listings — LHT Estate',
            'listings' => (new Listing())->allForAdmin($status),
            'status' => $status,
        ]);
    }

    public function approveListing(string $id): void
    {
        $this->setListingStatus((int) $id, 'approved', 'Listing approved.');
    }

    public function rejectListing(string $id): void
    {
        $this->setListingStatus((int) $id, 'rejected', 'Listing rejected.');
    }

    public function deleteListing(string $id): void
    {
        Auth::requireAdmin();
        $this->requireCsrf();
        (new Listing())->deleteAsAdmin((int) $id);
        flash('success', 'Listing deleted.');
        redirect('/admin/listings');
    }

    public function users(): void
    {
        Auth::requireAdmin();

        $this->render('admin/users', [
            'title' => 'Manage users — LHT Estate',
            'users' => (new User())->all(),
        ]);
    }

    public function updateUser(string $id): void
    {
        Auth::requireAdmin();
        $this->requireCsrf();

        $role = (string) ($_POST['role'] ?? 'user');
        $status = (string) ($_POST['status'] ?? 'active');
        if (!in_array($role, ['user', 'admin'], true) || !in_array($status, ['active', 'disabled'], true)) {
            flash('error', 'Invalid role or status.');
            redirect('/admin/users');
        }

        (new User())->updateAdmin((int) $id, $role, $status);
        flash('success', 'User updated.');
        redirect('/admin/users');
    }

    private function setListingStatus(int $id, string $status, string $message): void
    {
        Auth::requireAdmin();
        $this->requireCsrf();
        (new Listing())->setStatus($id, $status);
        flash('success', $message);
        redirect('/admin/listings');
    }
}
