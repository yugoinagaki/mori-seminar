// ─── B. Scroll fade-in (IntersectionObserver) ───────────────────────────────
function initScrollObserver() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.07, rootMargin: '0px 0px -32px 0px' }
    );
    document.querySelectorAll('.fade-in:not(.is-visible)').forEach((el) => observer.observe(el));
    return observer;
}
window.initScrollObserver = initScrollObserver;

// ─── A. Page transition overlay ──────────────────────────────────────────────
const WIPE_PATHS = ['/theme', '/news', '/professor', '/blog', '/members', '/case-studies', '/faq', '/contact'];
const ANIM_MS    = 1800;

function createWipeOverlay() {
    const div = document.createElement('div');
    div.id        = 'wipe-overlay-nav';
    div.className = 'wipe-wrapper-static';
    div.style.backgroundImage = `url('${window.FOREST_IMG_URL || '/forest.png'}')`;
    div.innerHTML = '<div class="wipe-tint"></div>';
    document.body.appendChild(div);
    return div;
}

function isWipePath(pathname) {
    return WIPE_PATHS.some((p) => pathname === p || pathname.startsWith(p + '/') || pathname.startsWith(p + '?'));
}

function initPageTransition() {
    const overlay = document.getElementById('wipe-overlay');
    if (!overlay) return;

    // Add animation class — the mask on .wipe-wrapper itself grows transparent
    // and reveals the page. No background clearing needed; the mask handles it.
    overlay.classList.add('is-animating');
    overlay.addEventListener('animationend', () => overlay.remove(), { once: true });
    setTimeout(() => overlay.remove(), ANIM_MS + 200);
}

// リンククリック時に現ページ上でアニメーションを起動し 1.8s 後に遷移
function initWipeLinks() {
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('http') || href.startsWith('//') || href.startsWith('#') || href.startsWith('mailto:')) return;

        const url      = new URL(href, window.location.href);
        const pathname = url.pathname;

        if (!isWipePath(pathname)) return;

        // 同じページへのリンクは無視
        if (pathname === window.location.pathname && url.search === window.location.search) return;

        // すでにアニメーション中なら無視
        if (document.getElementById('wipe-overlay-nav') || document.getElementById('wipe-overlay')) return;

        e.preventDefault();

        createWipeOverlay();

        // 150ms: enough time for the browser to paint the static overlay
        // before the HTTP navigation starts, minimising the blank-page gap.
        setTimeout(() => { window.location.href = href; }, 150);
    }, { capture: true });
}

// ─── F. Navbar ────────────────────────────────────────────────────────────────
function initNavbar() {
    const nav = document.querySelector('[data-navbar]');
    if (!nav) return;

    const SCROLLED_BG = 'linear-gradient(to bottom, rgba(10, 30, 70, 0.96) 0%, rgba(10, 30, 70, 0.3) 100%)';

    function updateNavScroll() {
        if (window.scrollY > 60) {
            nav.classList.add('backdrop-blur-md', 'shadow-lg');
            nav.style.background = SCROLLED_BG;
        } else {
            nav.classList.remove('backdrop-blur-md', 'shadow-lg');
            nav.style.background = '';
        }
    }
    updateNavScroll();
    window.addEventListener('scroll', updateNavScroll, { passive: true });

    // Mobile menu toggle
    const toggle = document.querySelector('[data-mobile-toggle]');
    const menu   = document.querySelector('[data-mobile-menu]');
    const iconHamburger = document.querySelector('[data-icon-hamburger]');
    const iconClose     = document.querySelector('[data-icon-close]');
    if (!toggle || !menu) return;

    function openMenu() {
        menu.style.display = '';
        // Force reflow so transition plays
        menu.getBoundingClientRect();
        menu.classList.remove('opacity-0', '-translate-y-3');
        menu.classList.add('opacity-100', 'translate-y-0', 'mobile-menu-enter');
        if (iconHamburger) iconHamburger.style.display = 'none';
        if (iconClose)     iconClose.style.display = '';
        toggle.setAttribute('aria-expanded', 'true');
    }

    function closeMenu() {
        menu.classList.remove('opacity-100', 'translate-y-0');
        menu.classList.add('opacity-0', '-translate-y-3', 'mobile-menu-leave');
        if (iconHamburger) iconHamburger.style.display = '';
        if (iconClose)     iconClose.style.display = 'none';
        toggle.setAttribute('aria-expanded', 'false');
        setTimeout(() => {
            menu.style.display = 'none';
            menu.classList.remove('mobile-menu-enter', 'mobile-menu-leave');
        }, 200);
    }

    let isOpen = false;
    toggle.addEventListener('click', () => {
        isOpen = !isOpen;
        isOpen ? openMenu() : closeMenu();
    });

    // Close menu on link click
    menu.querySelectorAll('a').forEach((a) => {
        a.addEventListener('click', () => { isOpen = false; closeMenu(); });
    });
}

// ─── G. Members tab ──────────────────────────────────────────────────────────
function initMembersTab() {
    const tabs    = document.querySelectorAll('[data-tab]');
    const members = document.querySelectorAll('[data-member-status]');
    if (!tabs.length) return;

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const status = tab.dataset.tab;

            tabs.forEach((t) => {
                const active = t.dataset.tab === status;
                t.classList.toggle('border-primary-700', active);
                t.classList.toggle('text-primary-700',  active);
                t.classList.toggle('border-transparent', !active);
                t.classList.toggle('text-gray-400',      !active);
                t.classList.toggle('hover:text-gray-700', !active);
            });

            members.forEach((m) => {
                m.style.display = m.dataset.memberStatus === status ? '' : 'none';
            });

            // Re-run scroll observer after DOM is updated
            setTimeout(() => window.initScrollObserver(), 0);
        });
    });
}

// ─── FAQ accordion ────────────────────────────────────────────────────────────
function initFaq() {
    let openId = null;
    document.querySelectorAll('[data-faq-button]').forEach((button) => {
        button.addEventListener('click', () => {
            const id     = button.dataset.faqButton;
            const answer = document.querySelector(`[data-faq-answer="${id}"]`);
            const arrow  = button.querySelector('[data-faq-arrow]');

            if (openId === id) {
                answer.style.display = 'none';
                arrow.classList.remove('rotate-180');
                openId = null;
            } else {
                if (openId) {
                    const prevAnswer = document.querySelector(`[data-faq-answer="${openId}"]`);
                    const prevArrow  = document.querySelector(`[data-faq-button="${openId}"] [data-faq-arrow]`);
                    if (prevAnswer) prevAnswer.style.display = 'none';
                    if (prevArrow)  prevArrow.classList.remove('rotate-180');
                }
                answer.style.display = '';
                arrow.classList.add('rotate-180');
                openId = id;
            }
        });
    });
}

// ─── E. World news card rotation ─────────────────────────────────────────────
function initNewsCard() {
    const dataEl = document.getElementById('news-articles-data');
    if (!dataEl) return;

    let articles;
    try { articles = JSON.parse(dataEl.textContent || '[]'); } catch { return; }
    if (!articles.length) return;

    const cardWrapper     = document.getElementById('news-card-wrapper');
    const cardContainer   = document.getElementById('news-card-container');
    const showBtn         = document.getElementById('news-card-show-btn');
    const closeBtn        = document.getElementById('news-card-close-btn');
    const titleEl         = document.getElementById('news-card-title');
    const timeEl          = document.getElementById('news-card-time');
    const counterEl       = document.getElementById('news-card-counter');
    const progressBar     = document.getElementById('news-card-progress');
    const dotsContainer   = document.getElementById('news-card-dots');

    if (!cardWrapper) return;

    const INTERVAL = 7000;
    let currentIndex = 0;
    let progress = 0;
    let rotateTimer = null;
    let progressTimer = null;
    let cardVisible = true;

    function timeAgo(dateStr) {
        if (!dateStr) return '';
        const diff = Date.now() - new Date(dateStr).getTime();
        const h = Math.floor(diff / 3600000);
        const d = Math.floor(h / 24);
        if (d > 0) return `${d}日前`;
        if (h > 0) return `${h}時間前`;
        return 'たった今';
    }

    function buildDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        articles.forEach((_, i) => {
            const dot = document.createElement('span');
            dot.className = 'block rounded-full transition-all duration-300';
            dot.dataset.dotIndex = i;
            dotsContainer.appendChild(dot);
        });
    }

    function updateDots() {
        if (!dotsContainer) return;
        dotsContainer.querySelectorAll('[data-dot-index]').forEach((dot) => {
            const i = parseInt(dot.dataset.dotIndex, 10);
            if (i === currentIndex) {
                dot.className = 'block rounded-full transition-all duration-300 w-3 h-1 bg-white/60';
            } else {
                dot.className = 'block rounded-full transition-all duration-300 w-1 h-1 bg-white/20';
            }
        });
    }

    function renderCard(animate) {
        const article = articles[currentIndex];
        if (!article) return;

        if (animate && cardContainer) {
            cardContainer.classList.add('news-card-leave-active', 'news-card-leave-to');
            setTimeout(() => {
                cardContainer.classList.remove('news-card-leave-active', 'news-card-leave-to');
                applyCardData(article);
                cardContainer.classList.add('news-card-enter-from');
                cardContainer.getBoundingClientRect();
                cardContainer.classList.add('news-card-enter-active');
                cardContainer.classList.remove('news-card-enter-from');
                setTimeout(() => cardContainer.classList.remove('news-card-enter-active'), 500);
            }, 300);
        } else {
            applyCardData(article);
        }

        updateDots();
    }

    function applyCardData(article) {
        if (titleEl)   titleEl.textContent = article.title;
        if (timeEl)    timeEl.textContent  = timeAgo(article.pubDate);
        if (counterEl) counterEl.textContent = `${currentIndex + 1} / ${articles.length}`;
        if (cardContainer) cardContainer.href = article.link;
    }

    function startRotation() {
        rotateTimer = setInterval(() => {
            currentIndex = (currentIndex + 1) % articles.length;
            progress = 0;
            if (progressBar) progressBar.style.width = '0%';
            renderCard(true);
        }, INTERVAL);

        progressTimer = setInterval(() => {
            progress = Math.min(progress + (100 / (INTERVAL / 100)), 100);
            if (progressBar) progressBar.style.width = `${progress}%`;
        }, 100);
    }

    function stopRotation() {
        if (rotateTimer)   clearInterval(rotateTimer);
        if (progressTimer) clearInterval(progressTimer);
    }

    function showCard() {
        cardVisible = true;
        if (showBtn)       showBtn.style.display = 'none';
        if (closeBtn)      closeBtn.style.display = '';
        if (cardContainer) {
            cardContainer.style.display = '';
            cardContainer.classList.add('news-card-enter-from');
            cardContainer.getBoundingClientRect();
            cardContainer.classList.add('news-card-enter-active');
            cardContainer.classList.remove('news-card-enter-from');
            setTimeout(() => cardContainer.classList.remove('news-card-enter-active'), 500);
        }
        startRotation();
    }

    function hideCard() {
        cardVisible = false;
        stopRotation();
        if (cardContainer) {
            cardContainer.classList.add('news-card-leave-active', 'news-card-leave-to');
            setTimeout(() => {
                cardContainer.style.display = 'none';
                cardContainer.classList.remove('news-card-leave-active', 'news-card-leave-to');
                if (showBtn) {
                    showBtn.style.display = '';
                    showBtn.classList.add('news-card-enter-from');
                    showBtn.getBoundingClientRect();
                    showBtn.classList.add('news-card-enter-active');
                    showBtn.classList.remove('news-card-enter-from');
                    setTimeout(() => showBtn.classList.remove('news-card-enter-active'), 500);
                }
                if (closeBtn) closeBtn.style.display = 'none';
            }, 300);
        }
    }

    buildDots();
    renderCard(false);
    startRotation();

    if (closeBtn) closeBtn.addEventListener('click', () => hideCard());
    if (showBtn)  showBtn.addEventListener('click',  () => showCard());
}

// ─── H. Inline editing ────────────────────────────────────────────────────────
function applyDraftValues() {
    const drafts = window.__drafts || [];
    drafts.forEach((d) => {
        const el = document.querySelector(
            `[data-editable][data-model="${d.model_type}"][data-id="${d.model_id}"][data-field="${d.field}"]`
        );
        if (el) el.textContent = d.value;
    });
}

function saveDraft(modelType, modelId, field, value) {
    return fetch('/drafts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.__csrfToken || '',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ model_type: modelType, model_id: modelId, field, value }),
    });
}

let editTextarea = null;
let editTarget   = null;

function openTextarea(el) {
    if (editTextarea) closeTextarea(true);

    editTarget = el;
    const rect = el.getBoundingClientRect();

    const ta = document.createElement('textarea');
    ta.value = el.textContent.trim();
    ta.style.cssText = [
        `position:fixed`,
        `top:${rect.top}px`,
        `left:${rect.left}px`,
        `width:${Math.max(rect.width, 200)}px`,
        `min-height:${Math.max(rect.height, 60)}px`,
        `z-index:99998`,
        `font:inherit`,
        `font-size:${getComputedStyle(el).fontSize}`,
        `color:${getComputedStyle(el).color}`,
        `background:rgba(4,28,51,0.92)`,
        `border:2px solid #f59e0b`,
        `border-radius:4px`,
        `padding:6px 10px`,
        `resize:both`,
        `outline:none`,
        `box-shadow:0 4px 24px rgba(0,0,0,0.4)`,
    ].join(';');

    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    editTextarea = ta;

    ta.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeTextarea(false); }
        if (e.key === 'Enter' && !e.shiftKey && !el.dataset.multiline) { e.preventDefault(); closeTextarea(true); }
    });
}

function closeTextarea(save) {
    if (!editTextarea || !editTarget) return;

    const newValue = editTextarea.value;
    const el       = editTarget;

    editTextarea.remove();
    editTextarea = null;
    editTarget   = null;

    if (!save) return;

    el.textContent = newValue;

    const { model, id, field } = el.dataset;
    saveDraft(model, id || null, field, newValue).then(async (res) => {
        const data = await res.json().catch(() => ({}));
        if (data.draft_count !== undefined) {
            updateDraftCount(data.draft_count);
        }
    });
}

function enableEditMode() {
    document.querySelectorAll('[data-editable]').forEach((el) => {
        el.classList.add('editable-active');
        el.addEventListener('click', handleEditableClick);
    });
    applyDraftValues();
}

function disableEditMode() {
    if (editTextarea) closeTextarea(false);
    document.querySelectorAll('[data-editable]').forEach((el) => {
        el.classList.remove('editable-active');
        el.removeEventListener('click', handleEditableClick);
    });
}

function handleEditableClick(e) {
    e.stopPropagation();
    openTextarea(e.currentTarget);
}

function updateEditBarUI(active) {
    const dot      = document.getElementById('edit-bar-dot');
    const label    = document.getElementById('edit-bar-label');
    const status   = document.getElementById('edit-bar-status');
    const toggle   = document.getElementById('edit-bar-toggle');
    const actions  = document.getElementById('edit-bar-actions');
    const newBtn   = document.getElementById('edit-bar-new');

    if (!toggle) return;

    if (active) {
        dot?.classList.replace('bg-white/30', 'bg-amber-400');
        status?.classList.replace('text-white/60', 'text-amber-300');
        if (label) label.textContent = '編集モード';
        toggle.textContent = '終了';
        toggle.className = toggle.className.replace('bg-amber-500 text-white hover:bg-amber-400', 'bg-white/15 text-white hover:bg-white/25');
        actions?.classList.remove('hidden');
        newBtn?.classList.remove('hidden');
    } else {
        dot?.classList.replace('bg-amber-400', 'bg-white/30');
        status?.classList.replace('text-amber-300', 'text-white/60');
        if (label) label.textContent = 'プレビュー';
        toggle.textContent = '編集開始';
        toggle.className = toggle.className.replace('bg-white/15 text-white hover:bg-white/25', 'bg-amber-500 text-white hover:bg-amber-400');
        actions?.classList.add('hidden');
        newBtn?.classList.add('hidden');
    }
}

function updateDraftCount(count) {
    const countEl = document.getElementById('edit-bar-count');
    const sepEl   = document.getElementById('edit-bar-sep');
    const applyBtn = document.getElementById('edit-bar-apply');
    const discardBtn = document.getElementById('edit-bar-discard');

    if (countEl) {
        countEl.textContent = `変更 ${count} 件`;
        countEl.classList.toggle('hidden', count === 0);
    }
    if (sepEl) sepEl.classList.toggle('hidden', count === 0);
    if (applyBtn) applyBtn.disabled = count === 0;
    if (discardBtn) discardBtn.disabled = count === 0;
}

function initInlineEdit() {
    if (window.__editMode) {
        enableEditMode();
    }

    const toggle = document.getElementById('edit-bar-toggle');
    if (toggle) {
        toggle.addEventListener('click', async () => {
            const res = await fetch('/edit-mode/toggle', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.__csrfToken || '',
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();
            const active = data.edit_mode;
            window.__editMode = active;
            updateEditBarUI(active);
            active ? enableEditMode() : disableEditMode();
        });
    }

    // Close textarea when clicking outside
    document.addEventListener('click', (e) => {
        if (editTextarea && !editTextarea.contains(e.target) && !e.target.closest('[data-editable]')) {
            closeTextarea(true);
        }
    });
}

// ─── I. New-content modal ─────────────────────────────────────────────────────
function initCreateModal() {
    const modal   = document.getElementById('create-modal');
    const overlay = document.getElementById('create-modal-overlay');
    const panel   = document.getElementById('create-modal-panel');
    const closeBtn = document.getElementById('create-modal-close');
    const newBtn  = document.getElementById('edit-bar-new');
    const formArea = document.getElementById('create-form-area');

    if (!modal) return;

    function openModal() {
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            overlay.classList.add('opacity-100');
            panel.classList.remove('opacity-0', 'translate-y-4');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        overlay.classList.remove('opacity-100');
        panel.classList.add('opacity-0', 'translate-y-4');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            // Reset type selector
            document.querySelectorAll('.create-type-btn').forEach((b) => b.classList.remove('border-primary-500', 'bg-primary-50'));
            document.querySelectorAll('.create-form').forEach((f) => f.classList.add('hidden'));
            formArea.classList.add('hidden');
        }, 200);
    }

    if (newBtn) newBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    overlay?.addEventListener('click', closeModal);

    // Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    // Type selector
    document.querySelectorAll('.create-type-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.type;

            // Update button styles
            document.querySelectorAll('.create-type-btn').forEach((b) => {
                b.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-700');
                b.querySelector('svg')?.classList.remove('text-primary-700');
            });
            btn.classList.add('border-primary-500', 'bg-primary-50');

            // Show matching form
            document.querySelectorAll('.create-form').forEach((f) => f.classList.add('hidden'));
            const form = document.getElementById(`form-${type}`);
            if (form) {
                form.classList.remove('hidden');
                formArea.classList.remove('hidden');
            }
        });
    });
}

// ─── Bootstrap ────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initScrollObserver();
    initPageTransition();
    initWipeLinks();
    initNavbar();
    initMembersTab();
    initFaq();
    initNewsCard();
    initInlineEdit();
    initCreateModal();
});
