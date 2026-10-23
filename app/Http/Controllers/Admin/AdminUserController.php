<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::withCount(['favorites', 'inquiries'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->trim()->toString();
                $query->where(fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')->toString()))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load([
            'inquiries' => fn ($query) => $query->with('car')->latest(),
            'favorites' => fn ($query) => $query->with('car')->latest(),
            'chatConversations' => fn ($query) => $query->with(['messages' => fn ($messages) => $messages->oldest()])->latest('last_message_at'),
        ]);

        return view('admin.users.show', compact('user'));
    }
}
