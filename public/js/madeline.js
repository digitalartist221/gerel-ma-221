/**
 * Madeline Framework — SPA Engine v6.5 "Incredible ScrollSpy"
 * Rendu par lots, gestion d'erreurs Router et synchronisation automatique des liens actifs.
 */

'use strict';

const Madeline = (() => {
    const CONFIG = {
        loaderId: 'madeline-loader',
        appContainerId: 'madeline-app',
        frameBudget: 16,
        chunkSize: 15,
        prefetchLimit: 25
    };

    let abortController = null;
    const prefetchCache = new Set();
    const routerState = { transitioning: false, lastUrl: window.location.href };

    const init = () => {
        if (window.location.hash) {
            setTimeout(() => handleHashScroll(window.location.hash), 400);
        }

        window.addEventListener('click', onIntercept, { capture: true });
        window.addEventListener('mouseover', onPrefetch, { passive: true });
        window.addEventListener('popstate', onPopState);

        setupAccessibility();
        setupScrollSpy();
    };

    /**
     * SCROLLSPY & ACTIVE LINKS (v6.5)
     * Synchronise la barre latérale avec le défilement et les clics.
     */
    const setupScrollSpy = () => {
        const links = document.querySelectorAll('.sidebar-link[href*="#"]');
        if (links.length === 0) return;

        const observerOptions = {
            root: null,
            rootMargin: '-10% 0px -80% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    updateActiveLink(id);
                }
            });
        }, observerOptions);

        links.forEach(link => {
            const href = link.getAttribute('href');
            // Support pour #id (guide) et #/id (swagger)
            const id = href.split('#')[1].replace('/', '');

            // On cherche l'élément par ID direct ou ID prefixé par Swagger
            const target = document.getElementById(id) || document.getElementById('operations-tag-' + id);
            if (target) observer.observe(target);
        });
    };

    /**
     * Met à jour visuellement le lien actif dans la sidebar
     */
    const updateActiveLink = (id) => {
        const links = document.querySelectorAll('.sidebar-link');
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (href.endsWith('#' + id) || href.endsWith('#/' + id))) {
                link.classList.add('active');
                // Support spécifique pour les liens sans classe CSS active (comme Swagger)
                link.style.opacity = '1';
            } else {
                if (href && href.includes('#')) {
                    link.classList.remove('active');
                    link.style.opacity = '';
                }
            }
        });
    };

    const onIntercept = (e) => {
        let el = e.target;
        let target = null;
        while (el && el !== document.documentElement) {
            if (el.tagName === 'A' || el.hasAttribute('data-href')) {
                target = el;
                break;
            }
            el = el.parentElement;
        }

        if (!target || target.hasAttribute('data-no-madeline')) return;
        if (target.getAttribute('target') === '_blank' || target.hasAttribute('download')) return;

        let href = (target.tagName === 'A') ? target.href : target.getAttribute('data-href');
        if (!href) return;

        // Gestion immédiate du clic sidebar pour réactivité instantanée
        if (target.classList.contains('sidebar-link') && href.includes('#')) {
            const id = href.split('#')[1].replace('/', '');
            updateActiveLink(id);
        }

        try {
            const url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) return;

            e.preventDefault();
            e.stopImmediatePropagation();

            if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) {
                handleHashScroll(url.hash);
                window.history.pushState(null, '', url.href);
                return;
            }

            dispatchNavigation(url.href);
        } catch (err) { }
    };

    const dispatchNavigation = async (url, pushState = true) => {
        if (routerState.transitioning) abortController?.abort();

        routerState.transitioning = true;
        abortController = new AbortController();

        const container = document.getElementById(CONFIG.appContainerId) || document.body;
        const loader = getOrCreateLoader();

        loader.style.opacity = '1';
        loader.style.width = '20%';
        container.style.opacity = '0.5';

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Madeline-Request': 'true',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/html'
                },
                signal: abortController.signal
            });

            const isError = !response.ok;
            const contentType = response.headers.get('content-type');
            let data;

            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                const text = await response.text();
                const doc = new DOMParser().parseFromString(text, 'text/html');
                const fragment = doc.getElementById(CONFIG.appContainerId);
                data = {
                    html: fragment ? fragment.innerHTML : doc.body.innerHTML,
                    title: doc.title || (isError ? 'Erreur — Madeline Framework' : document.title)
                };
            }

            document.title = data.title;
            if (pushState) window.history.pushState(null, '', url);
            loader.style.width = '60%';

            // Injection des styles dynamiques (pour les layouts imbriqués)
            if (data.head) {
                const headDoc = new DOMParser().parseFromString(data.head, 'text/html');
                const headNodes = Array.from(headDoc.head.childNodes);
                headNodes.forEach(node => {
                    if (node.tagName === 'STYLE' || node.tagName === 'LINK') {
                        document.head.appendChild(document.importNode(node, true));
                    }
                });
            }

            await sequentialRender(container, data.html);
            await runEmbeddedScripts(container);

            finalizeLifecycle(url, loader, container);

        } catch (err) {
            if (err.name !== 'AbortError') window.location.assign(url);
        } finally {
            routerState.transitioning = false;
        }
    };

    const sequentialRender = async (container, html) => {
        container.innerHTML = '';
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const nodes = Array.from(doc.body.childNodes);

        let i = 0;
        const renderBatch = () => {
            const start = performance.now();
            const fragment = document.createDocumentFragment();

            while (i < nodes.length && (performance.now() - start) < CONFIG.frameBudget) {
                const batchEnd = Math.min(i + CONFIG.chunkSize, nodes.length);
                for (; i < batchEnd; i++) {
                    fragment.appendChild(document.importNode(nodes[i], true));
                }
            }

            container.appendChild(fragment);
            if (i < nodes.length) {
                return new Promise(resolve => requestAnimationFrame(() => resolve(renderBatch())));
            }
        };

        return renderBatch();
    };

    const runEmbeddedScripts = async (element) => {
        const scripts = Array.from(element.querySelectorAll('script'));
        const scriptPromises = [];

        for (const s of scripts) {
            if (s.src.includes('madeline.js') || s.type === 'application/json') continue;

            const fresh = document.createElement('script');
            Array.from(s.attributes).forEach(a => fresh.setAttribute(a.name, a.value));

            if (s.src) {
                const p = new Promise((resolve) => {
                    fresh.onload = resolve;
                    fresh.onerror = resolve;
                });
                scriptPromises.push(p);
                s.parentNode.replaceChild(fresh, s);
            } else {
                fresh.text = s.text;
                s.parentNode.replaceChild(fresh, s);
            }
        }

        return Promise.all(scriptPromises);
    };

    const finalizeLifecycle = (url, loader, container) => {
        requestAnimationFrame(() => {
            loader.style.width = '100%';
            container.style.opacity = '1';

            document.dispatchEvent(new CustomEvent('madeline:refresh', { detail: { url } }));

            const targetUrl = new URL(url, window.location.origin);
            if (targetUrl.hash) {
                handleHashScroll(targetUrl.hash);
            } else {
                window.scrollTo({ top: 0, behavior: 'instant' });
            }

            // Ré-initialisation du ScrollSpy après chaque navigation SPA
            setupScrollSpy();

            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => loader.style.width = '0', 300);
            }, 250);
        });
    };

    const getOrCreateLoader = () => {
        let l = document.getElementById(CONFIG.loaderId);
        if (!l) {
            l = document.createElement('div');
            l.id = CONFIG.loaderId;
            l.style = 'position:fixed;top:0;left:0;height:2px;background:linear-gradient(90deg, #8b5cf6, #d946ef);z-index:999999;transition:width 0.4s ease, opacity 0.3s;width:0;pointer-events:none;';
            document.body.appendChild(l);
        }
        return l;
    };

    const handleHashScroll = (hash) => {
        const id = decodeURIComponent(hash.replace('#', ''));
        const target = document.getElementById(id) || document.getElementsByName(id)[0];
        if (target) {
            window.scrollTo({
                top: target.getBoundingClientRect().top + window.pageYOffset - 95,
                behavior: 'smooth'
            });
        }
    };

    const onPrefetch = (e) => {
        const target = e.target.closest('a, [data-href]');
        if (!target || prefetchCache.has(target.href) || prefetchCache.size > CONFIG.prefetchLimit || target.hash) return;

        const href = target.tagName === 'A' ? target.href : target.getAttribute('data-href');
        if (href) {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = href;
            document.head.appendChild(link);
            prefetchCache.add(href);
        }
    };

    const onPopState = () => {
        if (window.location.href !== routerState.lastUrl) {
            dispatchNavigation(window.location.href, false);
            routerState.lastUrl = window.location.href;
        }
    };

    const setupAccessibility = () => {
        const region = document.createElement('div');
        region.setAttribute('aria-live', 'polite');
        region.style = 'position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;';
        document.body.appendChild(region);
        document.addEventListener('madeline:refresh', () => {
            region.textContent = `Page : ${document.title} chargée.`;
        });
    };

    const toggleTheme = () => {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('madeline-theme', isDark ? 'dark' : 'light');
        document.dispatchEvent(new CustomEvent('madeline:theme-change', { detail: { theme: isDark ? 'dark' : 'light' } }));
    };

    return { init, toggleTheme };
})();

Madeline.init();
