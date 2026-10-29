<div class="faq-chat" data-faq-chat data-messages-url="{{ route('chat.messages') }}" data-send-url="{{ route('chat.messages.store') }}" data-close-url="{{ route('chat.close') }}">
    <button class="faq-chat-toggle" type="button" aria-expanded="false" aria-controls="faqChatPanel" data-faq-toggle>?</button>
    <section class="faq-chat-panel" id="faqChatPanel" hidden data-faq-panel>
        <div class="faq-chat-heading"><strong data-faq-title>Quick answers</strong><button type="button" aria-label="Close FAQ" data-faq-close>×</button></div>
        <p class="faq-chat-intro">How can we help?</p>
        <div class="faq-chat-questions" data-faq-questions>
            <button type="button" data-faq-question="How do I inquire about a car?">How do I inquire about a car?</button>
            <button type="button" data-faq-question="Can I view a car in person?">Can I view a car in person?</button>
            <button type="button" data-faq-question="Do you accept trade-ins?">Do you accept trade-ins?</button>
        </div>
        <div class="faq-chat-messages" data-faq-messages aria-live="polite"><p class="faq-chat-answer">Select a question to see an answer.</p></div>
        <button class="faq-chat-connect" type="button" data-faq-connect hidden>Need more help? I can connect you directly to the owner</button>
        <div class="faq-chat-live" data-faq-live hidden>
            @guest
                <div class="faq-chat-contact"><input type="text" data-chat-name placeholder="Your name" aria-label="Your name"><input type="email" data-chat-email placeholder="Your email" aria-label="Your email"></div>
            @endguest
            <form data-faq-form><textarea data-chat-body rows="2" placeholder="Type your message..." aria-label="Chat message" required></textarea><button type="submit">Send</button></form>
            <p class="faq-chat-status" data-chat-status></p>
        </div>
        <button class="faq-chat-close-thread" type="button" data-faq-close-thread hidden>Close and archive conversation</button>
        <input type="hidden" data-chat-csrf value="{{ csrf_token() }}">
    </section>
</div>
<script src="{{ asset('js/faq-chat.js') }}" defer></script>
