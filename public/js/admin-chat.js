'use strict';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-admin-chat]').forEach((chat) => {
        const toggle = chat.querySelector('[data-admin-chat-toggle]');
        const panel = chat.querySelector('[data-admin-chat-panel]');
        const conversations = chat.querySelector('[data-admin-chat-conversations]');
        const thread = chat.querySelector('[data-admin-chat-thread]');
        const form = chat.querySelector('[data-admin-chat-form]');
        const body = chat.querySelector('[data-admin-chat-body]');
        const status = chat.querySelector('[data-admin-chat-status]');
        const tabs = chat.querySelectorAll('[data-admin-chat-tab]');
        let inbox = [];
        let selectedId = null;
        let selectedStatus = 'open';

        const renderThread = () => {
            const conversation = inbox.find((item) => item.id === selectedId && item.status === selectedStatus);
            if (!conversation) {
                thread.innerHTML = '<p class="faq-chat-answer">Select a customer chat.</p>';
                form.hidden = true;
                return;
            }

            thread.innerHTML = '';
            conversation.messages.forEach((message) => {
                const item = document.createElement('div');
                item.className = `faq-chat-message faq-chat-message-${message.sender_type}`;
                item.innerHTML = `<strong></strong><small></small><p></p>`;
                item.querySelector('strong').textContent = message.sender_name;
                item.querySelector('small').textContent = `${message.relative_time} · ${message.time}`;
                item.querySelector('p').textContent = message.body;
                thread.appendChild(item);
            });
            form.hidden = conversation.status !== 'open';
            thread.scrollTop = thread.scrollHeight;
        };

        const renderConversations = () => {
            conversations.innerHTML = '';
            const visible = inbox.filter((conversation) => conversation.status === selectedStatus);
            if (!visible.length) {
                conversations.innerHTML = '<p class="faq-chat-answer">No customer chats yet.</p>';
                renderThread();
                return;
            }

            visible.forEach((conversation) => {
                const button = document.createElement('button');
                button.type = 'button';
                const latest = conversation.messages[conversation.messages.length - 1];
                const unread = conversation.status === 'open' && latest?.sender_type === 'customer';
                button.className = `admin-chat-conversation ${conversation.id === selectedId ? 'is-selected' : ''} ${unread ? 'is-unread' : ''}`;
                button.innerHTML = '<strong></strong><small></small>';
                button.querySelector('strong').textContent = conversation.name;
                button.querySelector('small').textContent = latest?.body || conversation.email || 'No messages';
                button.addEventListener('click', () => {
                    selectedId = conversation.id;
                    renderConversations();
                });
                conversations.appendChild(button);
            });
            renderThread();
        };

        const loadInbox = async () => {
            try {
                const response = await fetch(chat.dataset.inboxUrl, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('Unable to load customer chats.');
                inbox = (await response.json()).conversations;
                const visible = inbox.filter((conversation) => conversation.status === selectedStatus);
                if (!visible.some((conversation) => conversation.id === selectedId)) selectedId = visible[0]?.id || null;
                renderConversations();
            } catch (error) {
                status.textContent = error.message;
            }
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                selectedStatus = tab.dataset.adminChatTab;
                tabs.forEach((item) => item.classList.toggle('is-selected', item === tab));
                selectedId = null;
                renderConversations();
            });
        });

        toggle.addEventListener('click', () => {
            const open = panel.hidden;
            panel.hidden = !open;
            toggle.setAttribute('aria-expanded', String(open));
            if (open) loadInbox();
        });
        chat.querySelector('[data-admin-chat-close]').addEventListener('click', () => {
            panel.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        });
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (!selectedId || !body.value.trim()) return;
            status.textContent = 'Sending...';
            try {
                const response = await fetch(chat.dataset.sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': chat.querySelector('[data-admin-chat-csrf]').value,
                    },
                    body: JSON.stringify({ conversation_id: selectedId, body: body.value.trim() }),
                });
                if (!response.ok) throw new Error('Reply could not be sent.');
                body.value = '';
                status.textContent = 'Reply sent.';
                await loadInbox();
            } catch (error) {
                status.textContent = error.message;
            }
        });
        window.setInterval(() => {
            if (!panel.hidden) loadInbox();
        }, 8000);
    });
});
