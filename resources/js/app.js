// PRT362S — Eben Supply | Group KN3
import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {

    // ── 1. User dropdown (click-based, closes on outside click / Escape) ──
    const userBtn      = document.getElementById('user-menu-btn');
    const userDropdown = document.getElementById('user-dropdown');
    const userChevron  = document.getElementById('user-chevron');

    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = !userDropdown.classList.contains('hidden');
            userDropdown.classList.toggle('hidden', isOpen);
            userBtn.setAttribute('aria-expanded', String(!isOpen));
            if (userChevron) userChevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        });

        document.addEventListener('click', (e) => {
            if (!document.getElementById('user-menu-wrapper')?.contains(e.target)) {
                userDropdown.classList.add('hidden');
                userBtn.setAttribute('aria-expanded', 'false');
                if (userChevron) userChevron.style.transform = '';
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                userDropdown.classList.add('hidden');
                if (userChevron) userChevron.style.transform = '';
            }
        });
    }

    // ── 2. Mobile menu toggle ─────────────────────────────────────────────
    const mobileBtn     = document.getElementById('mobile-btn');
    const mobileMenu    = document.getElementById('mobile-menu');
    const iconOpen      = document.getElementById('menu-icon-open');
    const iconClose     = document.getElementById('menu-icon-close');

    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => {
            const hidden = mobileMenu.classList.toggle('hidden');
            iconOpen?.classList.toggle('hidden', !hidden);
            iconClose?.classList.toggle('hidden', hidden);
        });
    }

    // ── 3. Search bar slide-down toggle ───────────────────────────────────
    const searchBtn   = document.getElementById('search-btn');
    const searchBar   = document.getElementById('search-bar');
    const searchInput = document.getElementById('search-input');

    if (searchBtn && searchBar) {
        searchBtn.addEventListener('click', () => {
            const nowHidden = searchBar.classList.toggle('hidden');
            if (!nowHidden && searchInput) setTimeout(() => searchInput.focus(), 50);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !searchBar.classList.contains('hidden')) {
                searchBar.classList.add('hidden');
            }
        });
    }

    // ── 4. Toast notifications — animate in + auto-dismiss ────────────────
    ['toast-success', 'toast-error'].forEach((id) => {
        const toast = document.getElementById(id);
        if (!toast) return;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'none';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            });
        });
        const dismiss = () => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 350);
        };
        setTimeout(dismiss, 4500);
    });

    // ── 5. Back-to-top button ─────────────────────────────────────────────
    const backToTop = document.getElementById('back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            backToTop.classList.toggle('hidden', window.scrollY < 380);
        }, { passive: true });
        backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // ── 6. Scroll-reveal (data-reveal attribute) ──────────────────────────
    const revealEls = document.querySelectorAll('[data-reveal]');
    if (revealEls.length && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((en) => {
                if (en.isIntersecting) {
                    en.target.classList.add('revealed');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.1 });
        revealEls.forEach((el) => io.observe(el));
    }

    // ── 7. Product card image zoom on hover ───────────────────────────────
    document.querySelectorAll('.product-img-wrap').forEach((wrap) => {
        const img = wrap.querySelector('img');
        if (!img) return;
        wrap.addEventListener('mouseenter', () => {
            img.style.transition = 'transform 0.55s cubic-bezier(0.25,0.46,0.45,0.94)';
            img.style.transform  = 'scale(1.08)';
        });
        wrap.addEventListener('mouseleave', () => { img.style.transform = 'scale(1)'; });
    });

    // ── 8. Size selector buttons ──────────────────────────────────────────
    document.querySelectorAll('[data-size-group] .size-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            btn.closest('[data-size-group]')
               .querySelectorAll('.size-btn')
               .forEach((b) => b.classList.remove('size-btn-active'));
            btn.classList.add('size-btn-active');
            const inp = document.getElementById('selected-size');
            if (inp) inp.value = btn.dataset.size;
        });
    });

    // ── 9. Quantity stepper (+/-) ─────────────────────────────────────────
    document.querySelectorAll('[data-qty-dec], [data-qty-inc]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const inputId = btn.dataset.qtyDec || btn.dataset.qtyInc;
            const input   = document.getElementById(inputId);
            if (!input) return;
            let v = parseInt(input.value, 10) || 1;
            if ('qtyDec' in btn.dataset) v = Math.max(1, v - 1);
            if ('qtyInc' in btn.dataset) v = Math.min(99, v + 1);
            input.value = v;
        });
    });

    // ── 10. Cart badge pulse when adding item ─────────────────────────────
    document.querySelectorAll('[data-add-cart]').forEach((form) => {
        form.addEventListener('submit', () => {
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.style.transition = 'transform 0.15s ease';
                badge.style.transform  = 'scale(1.6)';
                setTimeout(() => { badge.style.transform = 'scale(1)'; }, 200);
            }
        });
    });

    // ── 11. Highlight current page in mobile nav ──────────────────────────
    document.querySelectorAll('#mobile-menu nav a').forEach((link) => {
        if (link.href === window.location.href) {
            link.classList.add('font-semibold', 'text-[#333333]', 'bg-[#F5F5F5]');
        }
    });

});