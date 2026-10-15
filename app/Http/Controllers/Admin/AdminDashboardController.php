<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $monthStart = now()->startOfMonth();
        $inquiryCounts = collect(range(5, 0))->map(function (int $monthsAgo) use ($monthStart): array {
            $date = $monthStart->copy()->subMonths($monthsAgo);

            return [
                'label' => $date->format('M'),
                'count' => Inquiry::whereBetween('created_at', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()])->count(),
            ];
        })->push([
            'label' => $monthStart->format('M'),
            'count' => Inquiry::whereBetween('created_at', [$monthStart, now()])->count(),
        ]);

        return view('admin.dashboard', [
            'stats' => [
                'cars' => Car::count(),
                'availableCars' => Car::where('status', 'available')->count(),
                'users' => User::where('role', 'customer')->count(),
                'inquiries' => Inquiry::where('status', 'pending')->count(),
                'categories' => Category::count(),
            ],
            'inquiryCounts' => $inquiryCounts,
            'recentCars' => Car::with('category')->latest()->limit(5)->get(),
            'recentInquiries' => Inquiry::with('car')->latest()->limit(5)->get(),
        ]);
    }
}
