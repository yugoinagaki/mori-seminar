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

function pickTransitionImage() {
    const imgs = window.TRANSITION_IMAGES;
    if (Array.isArray(imgs) && imgs.length > 0) {
        return imgs[Math.floor(Math.random() * imgs.length)];
    }
    return window.FOREST_IMG_URL || '/forest.jpg';
}

function createWipeOverlay() {
    const div = document.createElement('div');
    div.id        = 'wipe-overlay-nav';
    div.className = 'wipe-wrapper-static';
    const inner = document.createElement('div');
    inner.className = 'wipe-forest-static';
    const imgUrl = pickTransitionImage();
    if (imgUrl) {
        inner.style.backgroundImage = `url('${imgUrl}')`;
        // Pass the chosen image to the destination page so the arrival animation matches
        try { sessionStorage.setItem('wipe-pending-image', imgUrl); } catch {}
    }
    div.appendChild(inner);
    document.body.appendChild(div);
    return div;
}

function isWipePath(pathname) {
    // Exact match only — sub-paths like /news/123 do not get the animation
    const normalized = pathname.replace(/\/$/, '');
    return WIPE_PATHS.includes(normalized);
}

function initPageTransition() {
    const overlay = document.getElementById('wipe-overlay');
    if (!overlay) return;

    // Only animate if a departure animation was triggered on the previous page.
    // Tab switches and direct URL visits have no pending image → skip animation.
    let pending = null;
    try {
        pending = sessionStorage.getItem('wipe-pending-image');
        sessionStorage.removeItem('wipe-pending-image');
    } catch {}

    if (!pending) {
        overlay.remove();
        return;
    }

    const forest = overlay.querySelector('.wipe-forest');
    if (forest) forest.style.backgroundImage = `url('${pending}')`;

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

        // 同一パスへのリンクはタブ切り替え等も含めて無視（クエリが違っても）
        if (pathname === window.location.pathname) return;

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
        menu.style.display = 'block';
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
    const tabs    = document.querySelectorAll('.members-tab-btn[data-tab]');
    const members = document.querySelectorAll('[data-member-cohort]');
    if (!tabs.length) return;

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const selected = tab.dataset.tab;

            tabs.forEach((t) => {
                const active = t.dataset.tab === selected;
                t.classList.toggle('border-primary-700', active);
                t.classList.toggle('text-primary-700',  active);
                t.classList.toggle('border-transparent', !active);
                t.classList.toggle('text-gray-400',      !active);
                t.classList.toggle('hover:text-gray-700', !active);
            });

            members.forEach((m) => {
                const show = selected === 'all' || m.dataset.memberCohort === selected;
                m.style.display = show ? '' : 'none';
            });

            // Re-run scroll observer after DOM is updated
            setTimeout(() => window.initScrollObserver(), 0);
        });
    });
}

// ─── G2. Member detail modal ─────────────────────────────────────────────────
function initMemberModal() {
    const modal   = document.getElementById('member-modal');
    if (!modal) return;

    const overlay = document.getElementById('member-modal-overlay');
    const panel   = document.getElementById('member-modal-panel');
    const closeBtn = document.getElementById('member-modal-close');
    const nameEl    = document.getElementById('member-modal-name');
    const cohortEl  = document.getElementById('member-modal-cohort');
    const positionEl = document.getElementById('member-modal-position');
    const imageEl   = document.getElementById('member-modal-image');
    const initialWrap = document.getElementById('member-modal-initial');
    const initialChar = initialWrap?.querySelector('span');
    const bioEl     = document.getElementById('member-modal-bio');
    const bioEmpty  = document.getElementById('member-modal-bio-empty');

    let lastFocused = null;

    function open(trigger) {
        // Skip when inline edit mode is on — clicking a card in edit mode
        // is likely a mis-click; the editable text fields have their own handlers.
        if (window.__editMode) return;

        lastFocused = trigger;
        const { name, cohort, position, imageUrl, initial } = trigger.dataset;
        const bioTemplate = trigger.querySelector('.member-bio-template');
        const bioHtml = bioTemplate ? bioTemplate.innerHTML.trim() : '';

        nameEl.textContent = name || '';

        if (cohort) {
            cohortEl.textContent = `${cohort}期`;
            cohortEl.classList.remove('hidden');
        } else {
            cohortEl.classList.add('hidden');
        }

        if (position) {
            positionEl.textContent = position;
            positionEl.classList.remove('hidden');
        } else {
            positionEl.classList.add('hidden');
        }

        if (imageUrl) {
            imageEl.src = imageUrl;
            imageEl.alt = name || '';
            imageEl.classList.remove('hidden');
            initialWrap.classList.add('hidden');
            initialWrap.classList.remove('flex');
        } else {
            imageEl.classList.add('hidden');
            imageEl.removeAttribute('src');
            if (initialChar) initialChar.textContent = initial || (name ? name.charAt(0) : '');
            initialWrap.classList.remove('hidden');
            initialWrap.classList.add('flex');
        }

        if (bioHtml) {
            bioEl.innerHTML = bioHtml;
            bioEl.classList.remove('hidden');
            bioEmpty.classList.add('hidden');
        } else {
            bioEl.innerHTML = '';
            bioEl.classList.add('hidden');
            bioEmpty.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            overlay.classList.add('opacity-100');
            panel.classList.remove('opacity-0', 'translate-y-6', 'scale-[0.98]');
        });

        closeBtn?.focus({ preventScroll: true });
    }

    function close() {
        overlay.classList.remove('opacity-100');
        panel.classList.add('opacity-0', 'translate-y-6', 'scale-[0.98]');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            if (lastFocused) {
                try { lastFocused.focus({ preventScroll: true }); } catch {}
            }
        }, 320);
    }

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-open-member-modal]');
        if (!trigger) return;
        e.preventDefault();
        open(trigger);
    });

    closeBtn?.addEventListener('click', close);
    overlay?.addEventListener('click', close);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
    });
}

// ─── G3. Intro splash (first visit in a session, curtain reveal) ─────────────
function initIntroSplash() {
    const splash = document.getElementById('intro-splash');
    if (!splash) return;

    // If html has .intro-seen (inline-script set it), just remove the node
    if (document.documentElement.classList.contains('intro-seen')) {
        splash.remove();
        return;
    }

    try { sessionStorage.setItem('intro-splash-seen', '1'); } catch (e) {}

    const top    = document.getElementById('intro-top');
    const bottom = document.getElementById('intro-bottom');
    const logo   = document.getElementById('intro-logo');

    // Phase 1: logo fades + scales in
    requestAnimationFrame(() => {
        if (logo) {
            logo.classList.remove('opacity-0', 'scale-95');
            logo.classList.add('opacity-100', 'scale-100');
        }
    });

    // Phase 2: curtains split apart + logo fades out
    setTimeout(() => {
        if (top)    top.style.transform    = 'translateY(-100%)';
        if (bottom) bottom.style.transform = 'translateY(100%)';
        if (logo) {
            logo.style.transition = 'opacity 0.45s ease-out';
            logo.style.opacity    = '0';
        }
    }, 1400);

    // Phase 3: remove after curtains have fully left the viewport
    setTimeout(() => splash.remove(), 2500);
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
    const articles = window.__worldNews;
    if (!Array.isArray(articles) || !articles.length) return;

    const widget      = document.getElementById('news-widget');
    const header      = document.getElementById('news-widget-header');
    const cardLink    = document.getElementById('news-card-container');
    const closeBtn    = document.getElementById('news-widget-close');
    const showBtn     = document.getElementById('news-widget-show');
    const titleEl     = document.getElementById('news-title');
    const timeEl      = document.getElementById('news-time');
    const counterEl   = document.getElementById('news-counter');
    const progressBar = document.getElementById('news-progress');
    const dotsContainer = document.getElementById('news-dots');

    if (!cardLink) return;

    // Reveal the widget after intro/hero animation completes. If splash is playing
    // this is later (post-curtain + hero-title done); otherwise, matches the
    // previous hero-sub timing (~2.6s from load).
    const splashPlaying = document.getElementById('intro-splash')
        && !document.documentElement.classList.contains('intro-seen');
    const revealDelay = splashPlaying ? (window.__heroAnimEnd ?? 5400) : 2600;
    if (widget) {
        setTimeout(() => {
            widget.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            widget.style.opacity    = '1';
            widget.style.transform  = 'translateY(0)';
        }, revealDelay);
    }

    const INTERVAL = 7000;
    let currentIndex = 0;
    let progress = 0;
    let rotateTimer = null;
    let progressTimer = null;

    function timeAgo(dateStr) {
        if (!dateStr) return '';
        const diff = Date.now() - new Date(dateStr).getTime();
        const h = Math.floor(diff / 3600000);
        const d = Math.floor(h / 24);
        if (d > 0) return `${d}日前`;
        if (h > 0) return `${h}時間前`;
        return 'たった今';
    }

    function updateDots() {
        if (!dotsContainer) return;
        dotsContainer.querySelectorAll('span').forEach((dot, i) => {
            dot.className = i === currentIndex
                ? 'block rounded-full transition-all duration-300 w-3 h-1 bg-white/60'
                : 'block rounded-full transition-all duration-300 w-1 h-1 bg-white/20';
        });
    }

    function applyArticle() {
        const article = articles[currentIndex];
        if (titleEl)   titleEl.textContent     = article.title;
        if (timeEl)    timeEl.textContent      = timeAgo(article.pubDate);
        if (counterEl) counterEl.textContent   = `${currentIndex + 1} / ${articles.length}`;
        if (cardLink)  cardLink.href           = article.link;
        updateDots();
    }

    function startRotation() {
        rotateTimer = setInterval(() => {
            currentIndex = (currentIndex + 1) % articles.length;
            progress = 0;
            if (progressBar) progressBar.style.width = '0%';
            applyArticle();
        }, INTERVAL);
        progressTimer = setInterval(() => {
            progress = Math.min(progress + (100 / (INTERVAL / 100)), 100);
            if (progressBar) progressBar.style.width = `${progress}%`;
        }, 100);
    }

    function stopRotation() {
        clearInterval(rotateTimer);
        clearInterval(progressTimer);
    }

    function hideWidget() {
        stopRotation();
        if (header)   header.style.display   = 'none';
        if (cardLink) cardLink.style.display  = 'none';
        if (showBtn)  { showBtn.classList.remove('hidden'); showBtn.style.display = 'flex'; }
    }

    function showWidget() {
        if (header)   header.style.display   = '';
        if (cardLink) cardLink.style.display  = '';
        if (showBtn)  { showBtn.classList.add('hidden'); showBtn.style.display = 'none'; }
        startRotation();
    }

    applyArticle();
    startRotation();

    if (closeBtn) closeBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); hideWidget(); });
    if (showBtn)  showBtn.addEventListener('click', () => showWidget());
}

// ─── H. Inline editing ────────────────────────────────────────────────────────
function applyDraftValues() {
    const drafts = Array.isArray(window.__drafts) ? window.__drafts : [];
    drafts.forEach((d) => {
        const el = document.querySelector(
            `[data-editable][data-model="${d.model_type}"][data-id="${d.model_id}"][data-field="${d.field}"]`
        );
        if (el) el.textContent = d.value;
    });
    updateDraftCount(drafts.length);
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
    const countEl    = document.getElementById('edit-bar-count');
    const sepEl      = document.getElementById('edit-bar-sep');
    const applyBtn   = document.getElementById('edit-bar-apply');
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
        updateEditBarUI(true);
    }

    const toggle = document.getElementById('edit-bar-toggle');
    if (toggle) {
        toggle.addEventListener('click', async () => {
            // 編集モードをオフにしようとしている＆未適用の変更がある場合は警告
            if (window.__editMode) {
                const applyBtn = document.getElementById('edit-bar-apply');
                if (applyBtn && !applyBtn.disabled) {
                    if (!confirm('未反映の変更があります。変更を破棄して編集モードを終了しますか？\n（「反映する」ボタンで保存してから終了することをお勧めします）')) {
                        return;
                    }
                }
            }

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
    const modal    = document.getElementById('create-modal');
    const overlay  = document.getElementById('create-modal-overlay');
    const panel    = document.getElementById('create-modal-panel');
    const closeBtn = document.getElementById('create-modal-close');
    const newBtn   = document.getElementById('edit-bar-new');
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

// ─── K. Home slideshow (Ken Burns) ───────────────────────────────────────────
function initHomeSlideshow() {
    const container = document.getElementById('home-slideshow');
    if (!container) return;

    const slides = [...container.querySelectorAll('.kb-slide')];
    const dots   = [...container.querySelectorAll('.kb-dot')];
    if (slides.length < 2) return;

    const KB_ANIMS    = ['kb-zoom-in', 'kb-zoom-out', 'kb-pan-right', 'kb-pan-left'];
    const INTERVAL    = 5000;
    const FADE_MS     = 1200;
    let current       = 0;
    let timer         = null;
    let transitioning = false;

    function applyKb(slide, index) {
        const inner = slide.querySelector('.kb-inner');
        if (!inner) return;
        inner.style.animation = 'none';
        inner.getBoundingClientRect(); // force reflow to restart animation
        inner.style.animation = `${KB_ANIMS[index % KB_ANIMS.length]} 6s ease forwards`;
    }

    // Lazy-load: set background-image from data-bg on demand
    function loadBg(slide) {
        const inner = slide.querySelector('.kb-inner');
        if (inner && inner.dataset.bg) {
            inner.style.backgroundImage = `url('${inner.dataset.bg}')`;
            delete inner.dataset.bg;
        }
    }

    function goTo(next) {
        if (transitioning || next === current) return;
        transitioning = true;

        const prev = current;
        current = next;

        loadBg(slides[next]); // load image just before showing
        // preload the one after that too
        loadBg(slides[(next + 1) % slides.length]);

        slides[prev].style.opacity = '0';
        slides[next].style.opacity = '1';
        applyKb(slides[next], next);

        dots.forEach((d, i) => {
            d.className = i === next
                ? 'kb-dot transition-all duration-300 rounded-full w-5 h-1.5 bg-white'
                : 'kb-dot transition-all duration-300 rounded-full w-1.5 h-1.5 bg-white/40';
        });

        setTimeout(() => { transitioning = false; }, FADE_MS);
    }

    function advance() { goTo((current + 1) % slides.length); }

    function startTimer() { timer = setInterval(advance, INTERVAL); }
    function stopTimer()  { clearInterval(timer); }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => { stopTimer(); goTo(i); startTimer(); });
    });

    container.addEventListener('mouseenter', stopTimer);
    container.addEventListener('mouseleave', startTimer);

    applyKb(slides[0], 0);
    startTimer();
}

// ─── J. Hero title: English words fade in → crossfade to Japanese ────────────
function initMorphTitle() {
    const h1     = document.getElementById('hero-title');
    const enWrap = document.getElementById('hero-en');
    const jpWrap = document.getElementById('hero-jp');
    const btns   = document.getElementById('hero-buttons');
    if (!h1 || !enWrap || !jpWrap) return;

    // Fill English container with a span per word so we can stagger fades.
    // Insert a mobile-only <br> before "SEMINAR" so mobile shows two lines:
    //   MORI SATORU
    //   SEMINAR
    const words = ['MORI', 'SATORU', 'SEMINAR'];
    enWrap.innerHTML = '';
    const wordSpans = words.map((word, i) => {
        if (i > 0) {
            // Always add a space so desktop keeps proper word separation
            enWrap.appendChild(document.createTextNode(' '));
            // Before "SEMINAR", also add a mobile-only line break
            if (word === 'SEMINAR') {
                const br = document.createElement('br');
                br.className = 'md:hidden';
                enWrap.appendChild(br);
            }
        }
        const span = document.createElement('span');
        span.style.cssText = 'opacity:0; display:inline-block; transition:opacity 0.7s ease';
        span.textContent = word;
        enWrap.appendChild(span);
        return span;
    });
    enWrap.style.opacity = '1'; // container visible; children fade in one by one

    // If intro splash is running (first visit), wait until curtains have fully
    // retracted so the hero animation plays on a clean stage.
    const splashPlaying = document.getElementById('intro-splash')
        && !document.documentElement.classList.contains('intro-seen');
    const START = splashPlaying ? 2400 : 0;

    // Step 1: stagger fade-in each English word (slower, more editorial pace)
    wordSpans.forEach((span, i) => {
        setTimeout(() => { span.style.opacity = '1'; }, START + 200 + i * 350);
    });

    // Step 2: crossfade English → Japanese in place (no scale, no slide)
    setTimeout(() => {
        enWrap.style.transition = 'opacity 1s ease';
        jpWrap.style.transition = 'opacity 1s ease';
        enWrap.style.opacity    = '0';
        jpWrap.style.opacity    = '1';
    }, START + 2000);

    // Step 3: reveal buttons during the last part of the crossfade
    setTimeout(() => {
        if (!btns) return;
        btns.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        btns.style.transform  = 'translateY(0)';
        btns.style.opacity    = '1';
    }, START + 2700);

    // Expose completion time so other widgets can sync
    window.__heroAnimEnd = START + 3000; // buttons visible + small buffer
}

// ─── Bootstrap ────────────────────────────────────────────────────────────────

// When browser restores a page from bfcache (back/forward swipe), remove any
// leftover wipe overlays so the user sees the actual page content.
window.addEventListener('pageshow', (e) => {
    if (e.persisted) {
        document.getElementById('wipe-overlay-nav')?.remove();
        document.getElementById('wipe-overlay')?.remove();
        try { sessionStorage.removeItem('wipe-pending-image'); } catch {}
    }
});

document.addEventListener('DOMContentLoaded', () => {
    initIntroSplash();
    initScrollObserver();
    initPageTransition();
    initWipeLinks();
    initNavbar();
    initMembersTab();
    initMemberModal();
    initFaq();
    initNewsCard();
    initInlineEdit();
    initCreateModal();
    initMorphTitle();
    initHomeSlideshow();
});
