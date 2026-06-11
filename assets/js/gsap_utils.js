
window.GSAPUtils = (function() {
    // Direkt nach window.GSAPUtils = (function() {
    window._gsap = window.gsap;
    window._ST = window.ScrollTrigger;

    const cache = {
        rem: null,
        remTimestamp: 0,
        styles: new WeakMap()
    };
    const REM_CACHE_TTL = 1000;
    const DEBUG = false;

    function log(...args) {
        if (DEBUG) console.log('[GSAP]', performance.now().toFixed(0) + 'ms', ...args);
    }

    function remPx() {
        const now = Date.now();
        if (cache.rem === null || now - cache.remTimestamp > REM_CACHE_TTL) {
            cache.rem = parseFloat(getComputedStyle(document.documentElement).fontSize) || 16;
            cache.remTimestamp = now;
        }
        return cache.rem;
    }

    function remToPx(rem) {
        return rem * remPx();
    }

    function viewportH() {
        return window.visualViewport?.height || window.innerHeight;
    }

    function viewportW() {
        return window.visualViewport?.width || window.innerWidth;
    }

    function getCachedStyle(el, prop) {
        if (!cache.styles.has(el)) cache.styles.set(el, {});
        const elCache = cache.styles.get(el);
        if (!(prop in elCache)) elCache[prop] = getComputedStyle(el)[prop];
        return elCache[prop];
    }

    function clearStyleCache(el) {
        if (el) cache.styles.delete(el);
        else cache.styles = new WeakMap();
    }

    function invalidateRemCache() {
        cache.rem = null;
    }

    const BREAKPOINTS = {
        mobile: 767,
        tablet: 768,
        desktop: 1024,
        desktopLg: 1025
    };

    function isMinWidth(bp) {
        return viewportW() >= (BREAKPOINTS[bp] || bp);
    }

    function isEditMode() {
        return window.elementorFrontend?.isEditMode?.();
    }

    function clamp01(v) {
        return Math.max(0, Math.min(1, v));
    }

    function smoothstep(t) {
        t = clamp01(t);
        return t * t * (3 - 2 * t);
    }

    function qs(sel, root = document) {
        return root.querySelector(sel);
    }

    function qsa(sel, root = document) {
        return Array.from(root.querySelectorAll(sel));
    }

    // ========== SCROLL LOCK ==========
    let scrollLocked = false;

    function lockScroll() {
        if (scrollLocked) return;
        scrollLocked = true;
        document.body.style.cssText += ';overflow:hidden!important;position:fixed!important;width:100%!important;top:0!important;';
        log('Scroll LOCKED');
    }

    function unlockScroll() {
        if (!scrollLocked) return;
        scrollLocked = false;
        document.body.style.overflow = '';
        document.body.style.position = '';
        document.body.style.width = '';
        document.body.style.top = '';

        log('Scroll UNLOCKING...');

        window.scrollTo(0, 0);

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                window.scrollTo(0, 0);
                log('Scroll position reset to 0');
                if (window.ScrollTrigger) {
                    ScrollTrigger.refresh();
                    log('ScrollTrigger refreshed');
                }
                log('Scroll UNLOCKED');
            });
        });
    }

    // ========== MEDIA LOADING ==========
    function waitForImages(container) {
        return new Promise(resolve => {
            // Skip lazy images - they will load when needed
            const images = container.querySelectorAll('img:not([loading="lazy"])');
            let loaded = 0,
                total = images.length;
            if (total === 0) {
                resolve();
                return;
            }
            const check = () => {
                loaded++;
                if (loaded >= total) resolve();
            };
            images.forEach(img => {
                if (img.complete) check();
                else {
                    img.addEventListener('load', check, {
                        once: true
                    });
                    img.addEventListener('error', check, {
                        once: true
                    });
                }
            });
            setTimeout(resolve, 5000);
        });
    }

    function waitForVideos(container) {
        return new Promise(resolve => {
            const videos = container.querySelectorAll('video');
            let ready = 0,
                total = videos.length;
            if (total === 0) {
                resolve();
                return;
            }
            const check = () => {
                ready++;
                if (ready >= total) resolve();
            };
            videos.forEach(video => {
                if (video.readyState >= 2) check();
                else {
                    video.addEventListener('loadeddata', check, {
                        once: true
                    });
                    video.addEventListener('error', check, {
                        once: true
                    });
                }
            });
            setTimeout(resolve, 3000);
        });
    }

    // ========== ANIMATION REGISTRY ==========
    const animations = new Map();

    function registerAnimation(id, {
        init,
        destroy,
        resetStyles
    }) {
        animations.set(id, {
            init,
            destroy,
            resetStyles,
            initialized: false
        });
    }

    function destroyAll() {
        log('Destroying all animations...');
        animations.forEach((anim, id) => {
            if (anim.initialized) {
                log(`  Destroying: ${id}`);
                if (anim.destroy) anim.destroy();
                if (anim.resetStyles) anim.resetStyles();
                anim.initialized = false;
            }
        });
        ScrollTrigger.getAll().forEach(st => st.kill());
        ScrollTrigger.clearScrollMemory();
        log('All animations destroyed');
    }

    async function initAll() {
        log('Initializing all animations...');

        document.body.offsetHeight;

        for (const [id, anim] of animations) {
            log(`  Initializing: ${id}`);
            if (anim.init) anim.init();
            anim.initialized = true;
            await new Promise(r => setTimeout(r, 30));
        }
        await new Promise(r => requestAnimationFrame(() => requestAnimationFrame(r)));

        ScrollTrigger.refresh(true);
        log('All animations initialized');
    }

    // ========== RESIZE HANDLING ==========
    let resizeTimeout = null;
    let lastWidth = viewportW();
    let isResizing = false;

    async function handleResize() {
        const newWidth = viewportW();
        if (Math.abs(newWidth - lastWidth) < 20) return;

        if (isResizing) return;
        isResizing = true;
        log(`Resize detected: ${lastWidth} -> ${newWidth}`);
        lastWidth = newWidth;
        lockScroll();
        invalidateRemCache();
        clearStyleCache();
        destroyAll()
        await new Promise(r => setTimeout(r, 200));
        document.body.offsetHeight;
        await initAll();
        unlockScroll();
        isResizing = false;
    }

    function setupResizeListener() {
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(handleResize, 300);
        });
        window.addEventListener('orientationchange', () => {
            setTimeout(handleResize, 500);
        });
    }

    // ========== SYSTEM INIT ==========
    let systemReady = false;
    const readyQueue = [];

    function onReady(callback) {
        if (systemReady) callback();
        else readyQueue.push(callback);
    }

    async function bootSystem() {
        if (isEditMode()) return;

        log('=== BOOT START ===');

        const isMobile = viewportW() < BREAKPOINTS.tablet;

        if (isMobile) {
            log('Skipping GSAP boot on mobile');
            return;
        }

        // Set lazy loading on hero secondary images (except video-target)
        qsa('img.hp-img-hero-secondary:not(.video-target)').forEach(img => {
            img.setAttribute('loading', 'lazy');
        });

        if (isMobile) {
            log('Mobile detected - skipping scroll lock and scroll reset');
        } else {
            //       lockScroll();
            if (history.scrollRestoration) history.scrollRestoration = 'manual';
            window.scrollTo(0, 0);
        }

        log('Waiting for GSAP...');
        let attempts = 0;
        while ((!window.gsap || !window.ScrollTrigger) && attempts < 100) {
            await new Promise(r => setTimeout(r, 50));
            attempts++;
        }
        if (!window.gsap || !window.ScrollTrigger) {
            log('ERROR: GSAP not loaded!');
            unlockScroll();
            return;
        }
        log('GSAP ready');

        if (!window.__GSAP_ST_REGISTERED__) {
            gsap.registerPlugin(ScrollTrigger);
            window.__GSAP_ST_REGISTERED__ = true;
        }
        ScrollTrigger.clearScrollMemory('manual');

        if (document.readyState !== 'complete') {
            log('Waiting for DOM...');
            await new Promise(r => window.addEventListener('load', r, {
                once: true
            }));
        }
        log('DOM ready');

        if (!isMobile) {
            const criticalSections = [{
                sel: '.hero-shell',
                name: 'Hero'
            }];

            for (const {
                    sel,
                    name
                }
                of criticalSections) {
                const el = qs(sel);
                if (el) {
                    log(`Loading media in ${name}...`);
                    await waitForImages(el);
                    log(`${name} media ready`);
                }
            }
            log('Critical sections loaded');
        } else {
            log('Mobile - skipping media wait');
        }

        await new Promise(r => setTimeout(r, 150));
        document.body.offsetHeight;
        if (!isMobile) {
            window.scrollTo(0, 0);
        }

        log('Running registration callbacks...');
        readyQueue.forEach(cb => cb());
        readyQueue.length = 0;
        systemReady = true;
        await initAll();
        setupResizeListener();
        await new Promise(r => setTimeout(r, 100));

        if (!isMobile) {
            //       unlockScroll();
        }

        log('=== BOOT COMPLETE ===');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootSystem);
    } else {
        bootSystem();
    }

    return {
        remPx,
        remToPx,
        viewportH,
        viewportW,
        getCachedStyle,
        clearStyleCache,
        invalidateRemCache,
        BREAKPOINTS,
        isMinWidth,
        isEditMode,
        clamp01,
        smoothstep,
        qs,
        qsa,
        onReady,
        registerAnimation,
        log
    };
})();