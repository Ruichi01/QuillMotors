<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminCarController extends Controller
{
    public function index(Request $request): View
    {
        $cars = Car::query()
            ->with('category')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->trim()->toString();
                $query->where(fn ($query) => $query->where('make', 'like', "%{$search}%")->orWhere('model', 'like', "%{$search}%"));
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.cars.index', [
            'cars' => $cars,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.cars.form', ['car' => new Car(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $car = Car::create($this->validated($request));
        $this->storeImages($request, $car);

        return redirect()->route('admin.cars.index')->with('success', 'Vehicle added to the inventory.');
    }

    public function edit(Car $car): View
    {
        return view('admin.cars.form', ['car' => $car, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $request, Car $car): RedirectResponse
    {
        $validated = $this->validated($request);
        $removedImageIds = $this->validatedRemovedImageIds($request, $car);
        $this->ensureImageLimit($request, $car, $removedImageIds);

        if ((float) $validated['price'] !== (float) $car->price) {
            $validated['original_price'] ??= $car->original_price ?: $car->price;
        } else {
            unset($validated['original_price']);
        }

        $car->update($validated);
        $this->removeImages($car, $removedImageIds);
        $this->storeImages($request, $car);

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
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'mileage' => ['required', 'integer', 'min:0'],
            'transmission' => ['nullable', 'string', 'max:60'],
            'fuel_type' => ['nullable', 'string', 'max:60'],
            'condition' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'in:available,reserved,sold'],
            'photos' => ['nullable', 'array', 'max:4'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'removed_image_ids' => ['nullable', 'array'],
            'removed_image_ids.*' => ['integer'],
        ]);
    }

    private function storeImages(Request $request, Car $car): void
    {
        foreach ($request->file('photos', []) as $index => $photo) {
            $path = $photo->store('cars', 'public');
            $car->images()->create([
                'image_path' => 'storage/'.$path,
                'is_primary' => !$car->images()->exists() && $index === 0,
                'sort_order' => $car->images()->count(),
            ]);
        }
    }

    private function validatedRemovedImageIds(Request $request, Car $car): array
    {
        $ids = $request->input('removed_image_ids', []);

        if (!is_array($ids)) {
            return [];
        }

        return $car->images()->whereIn('id', array_map('intval', $ids))->pluck('id')->all();
    }

    private function ensureImageLimit(Request $request, Car $car, array $removedImageIds): void
    {
        $uploaded = count($request->file('photos', []));

        if ($car->images()->count() - count($removedImageIds) + $uploaded > 4) {
            throw ValidationException::withMessages([
                'photos' => 'Each car can have up to 4 images. Remove an existing image before adding more.',
            ]);
        }
    }

    private function removeImages(Car $car, array $imageIds): void
    {
        $images = $car->images()->whereIn('id', $imageIds)->get();
        $removedPrimary = $images->contains('is_primary', true);

        foreach ($images as $image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $image->image_path));
            $image->delete();
        }

        if ($removedPrimary && ($replacement = $car->images()->orderBy('sort_order')->first())) {
            $replacement->update(['is_primary' => true]);
        }
    }
}
