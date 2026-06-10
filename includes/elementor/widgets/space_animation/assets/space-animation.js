const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- Starfield ---------- */
(function() {
    const c = document.getElementById('stars');
    if (!c) return;
    const ctx = c.getContext('2d');
    let W, H, stars = [],
        mx = 0,
        my = 0,
        scrollY = 0;

    function size() {
        const host = document.getElementById('story') || c;
        const r = host.getBoundingClientRect();
        W = Math.max(r.width, window.innerWidth);
        H = Math.max(r.height, window.innerHeight);
        c.width = W;
        c.height = H;
        c.style.width = W + 'px';
        c.style.height = H + 'px';
        const n = Math.max(120, Math.min(260, Math.floor(W / 6)));
        stars = Array.from({
            length: n
        }, () => ({
            x: Math.random() * W,
            y: Math.random() * H,
            z: Math.random() + 0.2,
            r: Math.random() * 1.4 + 0.3,
            tw: Math.random() * Math.PI * 2
        }));
    }
    size();
    addEventListener('resize', size);
    addEventListener('load', size); /* nach vollem Layout erneut verteilen */
    setTimeout(size, 300); /* Sicherheitsnetz, falls Fonts/Layout nachrücken */
    addEventListener('mousemove', e => {
        mx = e.clientX / innerWidth - .5;
        my = e.clientY / innerHeight - .5
    });
    addEventListener('scroll', () => scrollY = window.scrollY, {
        passive: true
    });
    (function draw(t) {
        ctx.clearRect(0, 0, W, H);
        for (const s of stars) {
            const tw = reduced ? 1 : (.55 + .45 * Math.sin(t / 700 + s.tw));
            const px = s.x + mx * 40 * s.z,
                py = s.y + my * 40 * s.z - scrollY * .1 * s.z;
            ctx.globalAlpha = tw * s.z;
            ctx.fillStyle = '#fff';
            ctx.beginPath();
            ctx.arc(((px % W) + W) % W, ((py % H) + H) % H, s.r * s.z, 0, 7);
            ctx.fill();
        }
        requestAnimationFrame(draw);
    })(0);
})();

if (!reduced && window.gsap) {
    gsap.registerPlugin(ScrollTrigger);

    /* Progress + Nav */
    const bar = document.querySelector('#progress .bar'),
        dot = document.querySelector('#progress .dot'),
        nav = document.getElementById('nav');
    addEventListener('scroll', () => {
        if (bar && dot) {
            const p = window.scrollY / (document.body.scrollHeight - innerHeight) * 100;
            bar.style.width = p + '%';
            dot.style.left = p + '%';
        }
        if (nav) nav.classList.toggle('scrolled', window.scrollY > 40);
    }, {
        passive: true
    });

    /* Initialzustand: GSAP übernimmt transform vollständig */
    gsap.set('#astroWrap', { xPercent: -50, yPercent: -50, x: '26vw', y: '-22vh', rotate: 160, scale: 0.5 });

    /* ========== HERO-STORY-TIMELINE (gepinnt, scrub) ==========
       Akt 1 (0–.3): verlorener, taumelnder Astronaut
       Akt 2 (.3–.62): Leine zeichnet sich, fängt ihn, stabilisiert
       Akt 3 (.62–1): Marke landet                                   */
    const story = gsap.timeline({
        scrollTrigger: {
            trigger: '#story',
            start: 'top top',
            end: '+=320%',
            pin: true,
            scrub: .7,
            anticipatePin: 1
        }
    });
    story
        /* Astronaut driftet & taumelt herein */
        .to('#astroWrap', {
            keyframes: [{
                    x: '18vw',
                    y: '-10vh',
                    rotate: 80,
                    scale: .7,
                    duration: .30
                },
                {
                    x: '4vw',
                    y: '-2vh',
                    rotate: 25,
                    scale: .9,
                    duration: .18
                }
            ],
            ease: 'none'
        }, 0)
        /* Akt-1-Text raus */
        .to('#t1', {
            opacity: 0,
            y: -60,
            duration: .14,
            ease: 'power2.in'
        }, .18)
        /* Leine schießt los und erreicht ihn */
        .to('#lifePath', {
            strokeDashoffset: 0,
            duration: .26,
            ease: 'power2.inOut'
        }, .26)
        /* Der Fang: Stabilisierung + Puls-Glow */
        .to('#astroWrap', {
            x: '0vw',
            y: '0vh',
            rotate: 0,
            scale: 1.05,
            duration: .14,
            ease: 'back.out(1.6)'
        }, .50)
        .to('#astroWrap img,#astroWrap svg', {
            filter: 'drop-shadow(0 0 26px rgba(255,36,66,.65))',
            duration: .1
        }, .52)
        .fromTo('#catchGlow', {
            opacity: 0,
            scale: .4
        }, {
            opacity: 1,
            scale: 1,
            duration: .1,
            ease: 'power2.out'
        }, .52)
        .to('#catchGlow', {
            opacity: .35,
            scale: 1.25,
            duration: .12
        }, .62)
        /* Akt-2-Text */
        .fromTo('#t2', {
            opacity: 0,
            y: 50
        }, {
            opacity: 1,
            y: 0,
            duration: .1,
            ease: 'power3.out'
        }, .54)
        .to('#t2', {
            opacity: 0,
            y: -60,
            duration: .1,
            ease: 'power2.in'
        }, .70)
        /* Akt 3: Astronaut macht Platz, Marke landet */
        .to('#astroWrap', {
            x: '26vw',
            y: '4vh',
            scale: .82,
            rotate: -5,
            duration: .18,
            ease: 'power2.inOut'
        }, .72)
        .to('#lifeline', {
            opacity: .35,
            duration: .12
        }, .72)
        .fromTo('#brand', {
            opacity: 0,
            y: 60
        }, {
            opacity: 1,
            y: 0,
            duration: .16,
            ease: 'power3.out',
            onStart: () => document.getElementById('brand').classList.add('live')
        }, .80);

    /* sanftes Eigen-Taumeln zusätzlich zum Scroll (nur Akt 1, klingt aus) */
    const tumble = gsap.to('#astroWrap img,#astroWrap svg', {
        rotate: 14,
        yoyo: true,
        repeat: -1,
        duration: 2.4,
        ease: 'sine.inOut'
    });
    ScrollTrigger.create({
        trigger: '#story',
        start: 'top top',
        end: '+=160%',
        onLeave: () => tumble.pause(),
        onEnterBack: () => tumble.play()
    });
}