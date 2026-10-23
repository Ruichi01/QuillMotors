<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function messages(Request $request): JsonResponse
    {
        $conversation = $this->conversation($request);

        return response()->json([
            'conversation_id' => $conversation?->id,
            'messages' => $conversation?->messages()->with('conversation.user')->oldest()->get()->map(fn (ChatMessage $message): array => $this->messageData($message)) ?? [],
        ]);
    }

    public function store(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:3000'],
            'faq_question' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
        ]);

        $conversation = $this->conversation($request, $validated);
        $conversation->update(['status' => 'open']);
        $message = $conversation->messages()->create([
            'sender_type' => 'customer',
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'faq_question' => $validated['faq_question'] ?? null,
        ]);
        $conversation->update(['last_message_at' => $message->created_at]);

        if (! empty($validated['faq_question'])) {
            $answer = [
                'How do I inquire about a car?' => 'Open any available car and use the inquiry form on our contact page.',
                'Can I view a car in person?' => 'Yes. Send us an inquiry and our team will arrange a convenient viewing.',
                'Do you accept trade-ins?' => 'Trade-ins may be available. Contact us with your vehicle details to discuss options.',
            ][$validated['faq_question']] ?? null;

            if ($answer) {
                $answerMessage = $conversation->messages()->create([
                    'sender_type' => 'assistant',
                    'body' => $answer,
                    'faq_question' => $validated['faq_question'],
                ]);
                $conversation->update(['last_message_at' => $answerMessage->created_at]);
            }
        }

        $response = [
            'conversation_id' => $conversation->id,
            'message' => $this->messageData($message),
        ];

        if ($request->expectsJson()) {
            return response()->json($response, 201);
        }

        return redirect()->route('account', ['conversation' => $conversation->id])->with('success', 'Message sent.');
    }

    public function close(Request $request): JsonResponse
    {
        $conversation = $this->conversation($request);

        if ($conversation) {
            $conversation->delete();
        }

        return response()->json(['closed' => true]);
    }

    private function conversation(Request $request, array $details = [], bool $includeClosed = false): ?ChatConversation
    {
        $user = $request->user();
        $guestToken = session()->get('chat_guest_token');

        if (! $user && ! $guestToken) {
            $guestToken = (string) Str::uuid();
            session()->put('chat_guest_token', $guestToken);
        }

        $conversation = $user
            ? ChatConversation::where('user_id', $user->id)
            : ChatConversation::where('guest_token', $guestToken);
        if (! $includeClosed) {
            $conversation->where('status', 'open');
        }
        $conversation = $conversation->latest('last_message_at')->first();

        if ($conversation && $details) {
            $conversation->update(array_filter([
                'name' => $details['name'] ?? null,
                'email' => $details['email'] ?? null,
            ]));
        }

        if ($conversation || ! $details) {
            return $conversation;
        }

        return ChatConversation::create([
            'user_id' => $user?->id,
            'guest_token' => $user ? null : $guestToken,
            'name' => $details['name'] ?? $user?->name,
            'email' => $details['email'] ?? $user?->email,
            'last_message_at' => now(),
        ]);
    }

    private function messageData(ChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'sender_type' => $message->sender_type,
            'sender_name' => $message->sender_type === 'customer'
                ? ($message->conversation->user?->name ?? $message->conversation->name ?? 'You')
                : ($message->sender_type === 'assistant' ? 'Quill Motors' : 'Owner'),
            'body' => $message->body,
            'time' => $message->created_at->format('M j, g:i A'),
            'relative_time' => $message->created_at->diffForHumans(),
            'created_at' => $message->created_at->toIso8601String(),
        ];
    }
}
