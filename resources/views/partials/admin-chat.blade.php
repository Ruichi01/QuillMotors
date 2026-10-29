<div class="faq-chat admin-chat" data-admin-chat data-inbox-url="{{ route('admin.chats.inbox') }}" data-send-url="{{ route('admin.chats.messages.store') }}">
    <button class="faq-chat-toggle" type="button" aria-expanded="false" aria-controls="adminChatPanel" data-admin-chat-toggle>Chats</button>
    <section class="faq-chat-panel" id="adminChatPanel" hidden data-admin-chat-panel>
        <div class="faq-chat-heading"><strong>Customer chats</strong><button type="button" aria-label="Close customer chats" data-admin-chat-close>×</button></div>
        <div class="admin-chat-tabs"><button type="button" class="is-selected" data-admin-chat-tab="open">Active</button><button type="button" data-admin-chat-tab="closed">Archived</button></div>
        <div class="admin-chat-layout">
            <div class="admin-chat-conversations" data-admin-chat-conversations><p class="faq-chat-answer">Loading chats...</p></div>
            <div class="admin-chat-thread" data-admin-chat-thread><p class="faq-chat-answer">Select a customer chat.</p></div>
        </div>
        <form class="admin-chat-form" data-admin-chat-form hidden>
            <textarea data-admin-chat-body rows="2" placeholder="Reply to customer..." aria-label="Reply to customer" required></textarea>
            <button type="submit">Send</button>
        </form>
        <p class="faq-chat-status" data-admin-chat-status></p>
        <input type="hidden" data-admin-chat-csrf value="{{ csrf_token() }}">
    </section>
</div>
<script src="{{ asset('js/admin-chat.js') }}" defer></script>
