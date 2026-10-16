'use strict';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-faq-chat]').forEach((chat) => {
        const toggle = chat.querySelector('[data-faq-toggle]');
        const panel = chat.querySelector('[data-faq-panel]');
        const answer = chat.querySelector('[data-faq-answer]');
        const answers = {
            'How do I inquire about a car?': 'Open any available car and use the inquiry form on our contact page.',
            'Can I view a car in person?': 'Yes. Send us an inquiry and our team will arrange a convenient viewing.',
            'Do you accept trade-ins?': 'Trade-ins may be available. Contact us with your vehicle details to discuss options.',
        };

        const setOpen = (open) => {
            panel.hidden = !open;
            toggle.setAttribute('aria-expanded', String(open));
        };

        toggle.addEventListener('click', () => setOpen(panel.hidden));
        chat.querySelector('[data-faq-close]').addEventListener('click', () => setOpen(false));
        chat.querySelectorAll('[data-faq-question]').forEach((question) => {
            question.addEventListener('click', () => {
                answer.textContent = answers[question.dataset.faqQuestion];
            });
        });
    });
});
