<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminChatController extends Controller
{
    public function index(Request $request): View
    {
        $conversations = ChatConversation::where('status', 'open')
            ->with(['user', 'messages' => fn ($query) => $query->latest()->limit(1)])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->trim()->toString();
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('messages', fn ($query) => $query->where('body', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('last_message_at')
            ->paginate(15)
            ->withQueryString();

        $selected = $request->filled('conversation')
            ? ChatConversation::where('status', 'open')->with(['user', 'messages' => fn ($query) => $query->oldest()])->find($request->integer('conversation'))
            : null;

        return view('admin.chats.index', compact('conversations', 'selected'));
    }

}
