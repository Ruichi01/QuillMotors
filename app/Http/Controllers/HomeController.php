<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\View\View;

class HomeController
{
    public function __invoke(): View
    {
        $recentCars = Car::query()
            ->with(['category', 'images'])
            ->where('status', 'available')
            ->latest()
            ->limit(3)
            ->get();

        return view('welcome', compact('recentCars'));
    }
}
