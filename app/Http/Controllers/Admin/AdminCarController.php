<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCarController extends Controller
{
    public function index(): View
    {
        return view('admin.cars.index', [
            'cars' => Car::with('category')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.cars.form', ['car' => new Car(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Car::create($this->validated($request));

        return redirect()->route('admin.cars.index')->with('success', 'Vehicle added to the inventory.');
    }

    public function edit(Car $car): View
    {
        return view('admin.cars.form', ['car' => $car, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $request, Car $car): RedirectResponse
    {
        $car->update($this->validated($request));

        return redirect()->route('admin.cars.index')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Car $car): RedirectResponse
    {
        $car->delete();

        return redirect()->route('admin.cars.index')->with('success', 'Vehicle removed from the inventory.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'price' => ['required', 'numeric', 'min:0'],
            'mileage' => ['required', 'integer', 'min:0'],
            'transmission' => ['nullable', 'string', 'max:60'],
            'fuel_type' => ['nullable', 'string', 'max:60'],
            'condition' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'in:available,reserved,sold'],
        ]);
    }
}
