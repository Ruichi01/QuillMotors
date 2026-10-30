'use strict';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-faq-chat]').forEach((chat) => {
        const toggle = chat.querySelector('[data-faq-toggle]');
        const panel = chat.querySelector('[data-faq-panel]');
        const messages = chat.querySelector('[data-faq-messages]');
        const questions = chat.querySelector('[data-faq-questions]');
        const connect = chat.querySelector('[data-faq-connect]');
        const live = chat.querySelector('[data-faq-live]');
        const closeThread = chat.querySelector('[data-faq-close-thread]');
        const title = chat.querySelector('[data-faq-title]');
        const form = chat.querySelector('[data-faq-form]');
        const body = chat.querySelector('[data-chat-body]');
        const status = chat.querySelector('[data-chat-status]');
        let selectedQuestion = null;
        const sharedCars = new Set();
        const answers = {
            'How do I inquire about a car?': 'Open any available car and use the inquiry form on our contact page.',
            'Can I view a car in person?': 'Yes. Send us an inquiry and our team will arrange a convenient viewing.',
            'Do you accept trade-ins?': 'Trade-ins may be available. Contact us with your vehicle details to discuss options.',
        };

        const request = async (payload) => {
            const response = await fetch(chat.dataset.sendUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': chat.querySelector('[data-chat-csrf]').value },
                body: JSON.stringify(payload),
            });
            if (!response.ok) throw new Error('Your message could not be sent.');
            return response.json();
        };

        const render = (serverMessages = []) => {
            messages.innerHTML = '';
            serverMessages.forEach((message) => {
                const item = document.createElement('div');
                item.className = `faq-chat-message faq-chat-message-${message.sender_type}`;
                const name = document.createElement('strong');
                const time = document.createElement('small');
                const text = document.createElement('p');
                name.textContent = message.sender_name;
                time.textContent = message.relative_time ? `${message.relative_time} · ${message.time}` : message.time;
                text.textContent = message.body;
                item.append(name, time);
                if (message.car) {
                    const card = document.createElement('a');
                    card.className = 'faq-chat-vehicle';
                    card.href = message.car.url;
                    card.target = '_blank';
                    card.rel = 'noopener';
                    if (message.car.image) {
                        const image = document.createElement('img');
                        image.src = message.car.image;
                        image.alt = message.car.name;
                        card.appendChild(image);
                    }
                    const details = document.createElement('span');
                    details.innerHTML = `<strong></strong><small></small><em>View vehicle</em>`;
                    details.querySelector('strong').textContent = message.car.name;
                    details.querySelector('small').textContent = message.car.price;
                    card.appendChild(details);
                    item.appendChild(card);
                }
                item.appendChild(text);
                messages.appendChild(item);
            });
            if (!serverMessages.length) {
                messages.innerHTML = '<p class="faq-chat-answer">Select a question to see an answer.</p>';
            }
            messages.scrollTop = messages.scrollHeight;
        };

        const loadMessages = async () => {
            try {
                const response = await fetch(chat.dataset.messagesUrl, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('Unable to load chat.');
                const data = await response.json();
                render(data.messages);
                const hasConversation = Boolean(data.conversation_id && data.messages.length);
                questions.hidden = hasConversation;
                connect.hidden = !hasConversation;
                live.hidden = !hasConversation;
                closeThread.hidden = !hasConversation;
                title.textContent = hasConversation ? 'Conversation with Quill Motors' : 'Quick answers';
            } catch (error) {
                status.textContent = error.message;
            }
        };

        const setOpen = (open) => {
            panel.hidden = !open;
            toggle.setAttribute('aria-expanded', String(open));
            if (open) loadMessages();
        };

        toggle.addEventListener('click', () => setOpen(panel.hidden));
        chat.querySelector('[data-faq-close]').addEventListener('click', () => setOpen(false));
        chat.querySelectorAll('[data-faq-question]').forEach((question) => {
            question.addEventListener('click', async () => {
                selectedQuestion = question.dataset.faqQuestion;
                render([
                    { sender_type: 'customer', sender_name: 'You', time: 'Now', body: selectedQuestion },
                    { sender_type: 'assistant', sender_name: 'Quill Motors', time: 'Now', body: answers[selectedQuestion] },
                ]);
                connect.hidden = false;
            });
        });
        connect.addEventListener('click', async () => {
            if (!selectedQuestion) return;

            const name = chat.querySelector('[data-chat-name]');
            const email = chat.querySelector('[data-chat-email]');
            status.textContent = 'Connecting you to the owner...';
            try {
                await request({
                    body: selectedQuestion,
                    faq_question: selectedQuestion,
                    name: name?.value,
                    email: email?.value,
                });
                live.hidden = false;
                connect.hidden = true;
                closeThread.hidden = false;
                questions.hidden = true;
                status.textContent = 'You are now connected to the owner.';
                await loadMessages();
                body.focus();
            } catch (error) {
                status.textContent = error.message;
            }
        });
        closeThread.addEventListener('click', async () => {
            await fetch(chat.dataset.closeUrl, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': chat.querySelector('[data-chat-csrf]').value },
            });
            selectedQuestion = null;
            questions.hidden = false;
            connect.hidden = true;
            live.hidden = true;
            closeThread.hidden = true;
            title.textContent = 'Quick answers';
            render([]);
        });
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const content = body.value.trim();
            if (!content) return;
            const payload = { body: content };
            const name = chat.querySelector('[data-chat-name]');
            const email = chat.querySelector('[data-chat-email]');
            if (name) payload.name = name.value;
            if (email) payload.email = email.value;
            status.textContent = 'Sending...';
            try {
                await request(payload);
                body.value = '';
                status.textContent = 'Message sent. We will reply as soon as possible.';
                await loadMessages();
            } catch (error) {
                status.textContent = error.message;
            }
        });
        chat.addEventListener('faq:open-vehicle', async (event) => {
            const carId = Number(event.detail.carId);
            if (!carId || sharedCars.has(carId)) return;
            sharedCars.add(carId);
            setOpen(true);
            questions.hidden = true;
            connect.hidden = true;
            live.hidden = false;
            closeThread.hidden = false;
            title.textContent = 'Message about this car';
            status.textContent = 'Sharing this vehicle...';
            try {
                await request({ body: 'Hi, I’m interested in this car.', car_id: carId });
                status.textContent = 'Vehicle shared. Send a message to Quill Motors.';
                await loadMessages();
                body.focus();
            } catch (error) {
                sharedCars.delete(carId);
                status.textContent = error.message;
            }
        });
        window.setInterval(() => {
            if (!panel.hidden) loadMessages();
        }, 8000);
    });
    document.querySelectorAll('[data-open-chat]').forEach((button) => {
        button.addEventListener('click', () => {
            const chat = document.querySelector('[data-faq-chat]');
            chat?.dispatchEvent(new CustomEvent('faq:open-vehicle', {
                detail: { carId: button.dataset.chatCarId },
            }));
        });
    });
});
