window.GSAPUtils = (function() {

  function viewportH() {
    return window.visualViewport?.height || window.innerHeight;
  }

  function viewportW() {
    return window.visualViewport?.width || window.innerWidth;
  }

  function remToPx(rem) {
    return rem * (parseFloat(getComputedStyle(document.documentElement).fontSize) || 16);
  }

  function qs(sel, root = document) {
    return root.querySelector(sel);
  }

  function isEditMode() {
    return window.elementorFrontend?.isEditMode?.();
  }

  function isMinWidth(bp) {
    const breakpoints = { desktopLg: 1025, desktop: 1024, tablet: 768, mobile: 767 };
    return viewportW() >= (breakpoints[bp] || bp);
  }

  const animations = new Map();
  const readyQueue = [];
  let systemReady = false;

  function registerAnimation(id, { init, destroy, resetStyles }) {
    animations.set(id, { init, destroy, resetStyles });
  }

  function onReady(callback) {
    if (systemReady) callback();
    else readyQueue.push(callback);
  }

  function log(...args) {}

  window.addEventListener('load', function() {
    if (!window.gsap || !window.ScrollTrigger) {
      console.error('GSAP oder ScrollTrigger nicht geladen');
      return;
    }

    gsap.registerPlugin(ScrollTrigger);

    readyQueue.forEach(cb => cb());
    readyQueue.length = 0;
    systemReady = true;

    animations.forEach(anim => {
      if (anim.init) anim.init();
    });

    ScrollTrigger.refresh();
  });

  return {
    viewportH, viewportW, remToPx, qs, isEditMode, isMinWidth,
    registerAnimation, onReady, log,
    BREAKPOINTS: { desktopLg: 1025, desktop: 1024, tablet: 768, mobile: 767 }
  };

})();