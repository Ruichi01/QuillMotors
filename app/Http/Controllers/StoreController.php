<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController
{
    public function index(Request $request): View
    {
        $cars = Car::query()
            ->with(['category', 'images'])
            ->where('status', 'available')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->trim()->toString();
                $query->where(function ($query) use ($search): void {
                    $query->where('make', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), function ($query) use ($request): void {
                $category = $request->string('category')->toString();
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $category));
            })
            ->when($request->filled('year'), fn ($query) => $query->where('year', $request->integer('year')))
            ->when(
                $request->input('sort') === 'oldest',
                fn ($query) => $query->oldest(),
                fn ($query) => $query->latest()
            )
            ->paginate(9)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('store.index', compact('cars', 'categories'));
    }

    public function show(Car $car): View
    {
        abort_unless($car->status === 'available', 404);

        $car->load(['category', 'images']);

        return view('store.show', compact('car'));
    }
}
