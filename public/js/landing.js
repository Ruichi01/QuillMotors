'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');

    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });

        navLinks.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                navLinks.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            }

        });
    }

    document.querySelectorAll('.profile-menu-toggle').forEach((toggle) => {
        toggle.addEventListener('click', (event) => {
            event.stopPropagation();
            const panel = document.getElementById(toggle.getAttribute('aria-controls'));
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';

            document.querySelectorAll('.profile-menu-toggle').forEach((otherToggle) => {
                otherToggle.setAttribute('aria-expanded', 'false');
                const otherPanel = document.getElementById(otherToggle.getAttribute('aria-controls'));
                if (otherPanel) {
                    otherPanel.hidden = true;
                }
            });

            toggle.setAttribute('aria-expanded', String(!isOpen));
            panel.hidden = isOpen;
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.profile-menu-toggle').forEach((toggle) => {
            toggle.setAttribute('aria-expanded', 'false');
            const panel = document.getElementById(toggle.getAttribute('aria-controls'));
            if (panel) {
                panel.hidden = true;
            }
        });
    });

    const recentVehicles = document.getElementById('recentVehicles');
    const detailImage = document.querySelector('.detail-image');
    document.querySelectorAll('[data-detail-image]').forEach((thumbnail) => {
        thumbnail.addEventListener('click', () => {
            if (detailImage) {
                detailImage.src = thumbnail.dataset.detailImage;
            }
        });
    });

    document.querySelectorAll('[data-carousel-direction]').forEach((button) => {
        button.addEventListener('click', () => {
            if (!recentVehicles || !recentVehicles.children.length) {
                return;
            }

            const direction = Number(button.dataset.carouselDirection);
            const cards = [...recentVehicles.children];
            const visibleCards = window.matchMedia('(max-width: 720px)').matches ? 1 : window.matchMedia('(max-width: 950px)').matches ? 2 : 3;
            const maxIndex = Math.max(0, cards.length - visibleCards);
            const activeIndex = Number(recentVehicles.dataset.carouselIndex || 0);
            const nextIndex = Math.max(0, Math.min(maxIndex, activeIndex + direction));
            const gap = 22;
            const cardWidth = (recentVehicles.parentElement.clientWidth - gap * (visibleCards - 1)) / visibleCards;

            recentVehicles.dataset.carouselIndex = String(nextIndex);
            recentVehicles.style.transform = `translateX(-${nextIndex * (cardWidth + gap)}px)`;
        });
    });
});
