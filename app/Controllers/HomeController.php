<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Location;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home', [
            'title' => 'LHT Estate — Real estate classifieds made simple',
            'listings' => (new Listing())->latest(6),
            'categories' => (new Category())->all(),
            'provinces' => (new Location())->provinces(),
        ]);
    }

    public function error(int $status, string $message): string
    {
        return $this->render('errors/status', [
            'title' => $status . ' — ' . $message,
            'status' => $status,
            'message' => $message,
        ], $status);
    }
}
