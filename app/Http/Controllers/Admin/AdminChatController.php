<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AdminChatController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'open');
        $conversations = ChatConversation::when(in_array($status, ['open', 'closed'], true), fn ($query) => $query->where('status', $status))
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
            ? ChatConversation::when(in_array($status, ['open', 'closed'], true), fn ($query) => $query->where('status', $status))->with(['user', 'messages.car.images', 'messages' => fn ($query) => $query->oldest()])->find($request->integer('conversation'))
            : null;

        return view('admin.chats.index', compact('conversations', 'selected', 'status'));
    }

    public function inbox(): JsonResponse
    {
        $conversations = ChatConversation::query()
            ->with(['user', 'messages.car.images', 'messages' => fn ($query) => $query->oldest()])
            ->orderByDesc('last_message_at')
            ->get();

        return response()->json([
            'conversations' => $conversations->map(fn (ChatConversation $conversation): array => [
                'id' => $conversation->id,
                'status' => $conversation->status,
                'name' => $conversation->user?->name ?? $conversation->name ?? 'Guest visitor',
                'email' => $conversation->user?->email ?? $conversation->email,
                'messages' => $conversation->messages->map(fn (ChatMessage $message): array => $this->messageData($message)),
            ]),
        ]);
    }

    public function storeMessage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'conversation_id' => ['required', 'integer', 'exists:chat_conversations,id'],
            'body' => ['required', 'string', 'max:3000'],
        ]);

        $conversation = ChatConversation::where('status', 'open')->findOrFail($validated['conversation_id']);
        $message = $conversation->messages()->create([
            'sender_type' => 'admin',
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);
        $conversation->update(['last_message_at' => $message->created_at]);

        return response()->json(['message' => $this->messageData($message)], 201);
    }

    private function messageData(ChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'sender_type' => $message->sender_type,
            'sender_name' => $message->sender_type === 'customer'
                ? ($message->conversation->user?->name ?? $message->conversation->name ?? 'Guest visitor')
                : ($message->user?->name ?? 'Quill Motors'),
            'body' => $message->body,
            'car' => $message->car ? [
                'id' => $message->car->id,
                'name' => "{$message->car->make} {$message->car->model}",
                'price' => '₱ '.number_format($message->car->price, 0),
                'url' => route('store.show', $message->car),
                'image' => ($image = ($message->car->images->firstWhere('is_primary', true) ?? $message->car->images->first()))
                    ? asset($image->image_path)
                    : null,
            ] : null,
            'time' => $message->created_at->format('M j, g:i A'),
            'relative_time' => $message->created_at->diffForHumans(),
        ];
    }

}
