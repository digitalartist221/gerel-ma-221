<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \Core\Config::get('app.name', 'Gerel Ma') }}</title>
    <link rel="icon" type="image/png" href="/img/logo.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe', 300:'#c4b5fd', 400:'#a78bfa', 500:'#8b5cf6', 600:'#7c3aed', 700:'#6d28d9', 800:'#5b21b6', 900:'#4c1d95' },
                        rose: { 400: '#ff6b95', 500: '#ff4d7d' },
                        amber: { 400: '#ffc107', 500: '#ffb300' },
                        ui: { bg:'#FFFFFF', card:'#F9FAFB', text:'#050510' }
                    },
                    borderRadius: { '4xl':'2rem', '5xl':'2.5rem', '6xl':'3rem' }
                }
            }
        }
    </script>

    <style>
        body { background-color: #F8FAFC; color: #0F172A; -webkit-font-smoothing: antialiased; overflow-x: hidden; font-family: 'Outfit', sans-serif; }
        .mesh-gradient-bg { position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1;
            background: radial-gradient(at 0% 0%, rgba(139,92,246,0.1) 0px, transparent 50%),
                        radial-gradient(at 100% 0%, rgba(255,107,149,0.08) 0px, transparent 50%),
                        radial-gradient(at 50% 100%, rgba(255,193,7,0.05) 0px, transparent 50%); }
        
        .floating-deco { position: absolute; pointer-events: none; opacity: 0.4; z-index: 0; }
        .notebook-edge { position: relative; }
        .notebook-edge::after { 
            content: ''; position: absolute; left: -8px; top: 20%; height: 60%; width: 4px;
            background: repeating-linear-gradient(to bottom, #E2E8F0, #E2E8F0 4px, transparent 4px, transparent 12px);
            border-radius: 2px;
        }
        
        .glass-sidebar {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            bottom: 1.5rem;
            width: 280px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (max-width: 1024px) {
            .glass-sidebar { transform: translateX(-120%); }
            .glass-sidebar.show { transform: translateX(0); }
            main#madeline-app { padding-left: 0 !important; }
        }

        .nav-link { 
            display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-radius:1.25rem; 
            transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .nav-link:hover { background: rgba(139, 92, 246, 0.05); color: #8b5cf6; transform: translateX(4px); }
        .nav-link.active { background: #8b5cf6; color: white; box-shadow: 0 10px 20px -5px rgba(139, 92, 246, 0.4); }
        .nav-link svg { width: 1.25rem; height: 1.25rem; transition: transform 0.3s; }
        .nav-link.active svg { transform: scale(1.1); color: white; }
        
        .sidebar-section-label { padding:0 1.25rem; font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:0.2em; color:#94a3b8; margin-bottom:0.75rem; margin-top: 2rem; }
        
        #madeline-loader { position:fixed; top:0; left:0; height:3px; background:linear-gradient(90deg, #8b5cf6, #3b82f6); z-index:9999; width:0; transition:width 0.3s ease; }

        /* Mobile Trigger */
        .mobile-trigger {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 4rem;
            height: 4rem;
            border-radius: 2rem;
            background: #0f172a;
            color: white;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 150;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        @media (max-width: 1024px) { .mobile-trigger { display: flex; } }
        /* Toast Notifications */
        #toast-container { position: fixed; top: 2rem; right: 2rem; z-index: 1000; display: flex; flex-direction: column; gap: 0.75rem; pointer-events: none; }
        .toast { 
            pointer-events: auto; padding: 1rem 1.5rem; border-radius: 1.25rem; background: #0f172a; color: white; 
            font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
            display: flex; align-items: center; gap: 0.75rem; min-width: 280px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: translateX(120%); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .toast.show { transform: translateX(0); }
        .toast.success { border-left: 4px solid #10b981; }
        .toast.error { border-left: 4px solid #ef4444; }
        .toast.warning { border-left: 4px solid #f59e0b; }

        /* Premium Scrollbars */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(15, 23, 42, 0.1); border-radius: 10px; transition: background 0.3s; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(139, 92, 246, 0.3); }
        
        /* Firefox */
        * { scrollbar-width: thin; scrollbar-color: rgba(15, 23, 42, 0.1) transparent; }
        
        html { scrollbar-gutter: stable; scroll-behavior: smooth; }
    </style>
    @biir('head')
</head>
<body>
<div class="mesh-gradient-bg"></div>
    <div id="toast-container"></div>
    @ndax(isset($_SESSION['success']))
        <div data-toast="{{ $_SESSION['success'] }}" data-toast-type="success"></div>
        <?php unset($_SESSION['success']); ?>
    @jeexndax
    @ndax(isset($_SESSION['error']))
        <div data-toast="{{ $_SESSION['error'] }}" data-toast-type="error"></div>
        <?php unset($_SESSION['error']); ?>
    @jeexndax
    <div id="madeline-loader"></div>

@miingi fi
    <!-- =============================== -->
    <!-- SIDEBAR — Floating Glass Design -->
    <!-- =============================== -->
    <aside class="glass-sidebar flex flex-col overflow-hidden">

        <!-- Header / Logo -->
        <div class="px-8 py-10 flex items-center gap-4">
            <div class="w-11 h-11 flex items-center justify-center">
                <div class="w-3 h-3 bg-brand-500 rounded-full animate-pulse"></div>
            </div>
            <div>
                <span class="text-xl font-black tracking-tighter text-slate-900">Gerel Ma<span class="text-brand-500">.</span></span>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Business Suite v1</p>
            </div>
        </div>

        <!-- Scrollable Nav -->
        <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto pb-10 custom-scrollbar">

            <p class="sidebar-section-label">Finance & Ops</p>
            <a href="/dashboard" class="nav-link {{ $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Cockpit</span>
            </a>
            <a href="/documents" class="nav-link {{ str_contains($_SERVER['REQUEST_URI'], '/documents') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Facturation</span>
            </a>
            <a href="/caisse" class="nav-link {{ str_contains($_SERVER['REQUEST_URI'], '/caisse') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 0a9.015 9.015 0 015.524 1.889m-5.524-1.889a9.015 9.015 0 00-5.524 1.889"/></svg>
                <span>Journal Caisse</span>
            </a>
            <a href="/contrats" class="nav-link {{ str_contains($_SERVER['REQUEST_URI'], '/contrats') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Contrats Scellés</span>
            </a>

            <p class="sidebar-section-label">Ecosystème</p>
            <a href="/clients" class="nav-link {{ str_contains($_SERVER['REQUEST_URI'], '/clients') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Base Clients</span>
            </a>
            <a href="/produits" class="nav-link {{ str_contains($_SERVER['REQUEST_URI'], '/produits') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Produits & Services</span>
            </a>

            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
            <p class="sidebar-section-label" style="color:#8b5cf6;">Gouvernance</p>
            <a href="/equipe" class="nav-link {{ str_contains($_SERVER['REQUEST_URI'], '/equipe') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Accès Équipe</span>
            </a>
            <?php endif; ?>

            <p class="sidebar-section-label">Configuration</p>
            <a href="/profile" class="nav-link {{ str_contains($_SERVER['REQUEST_URI'], '/profile') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Mon Profil</span>
            </a>
            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
            <a href="/entreprises" class="nav-link {{ str_contains($_SERVER['REQUEST_URI'], '/entreprises') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Identité Entreprise</span>
            </a>
            <?php endif; ?>
        </nav>

        <!-- Footer / Profile -->
        <div class="p-6 bg-white/40 border-t border-white/50 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-brand-500 text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-brand-500/20">
                    {{ strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-xs font-black text-slate-900 truncate">{{ $_SESSION['user_name'] ?? 'Utilisateur' }}</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $_SESSION['user_role'] ?? 'Membre' }}</p>
                </div>
                <a href="/logout" data-no-madeline="true" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
            </div>
        </div>
    </aside>

    <button class="mobile-trigger" onclick="document.querySelector('.glass-sidebar').classList.toggle('show')">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"/></svg>
    </button>

    <!-- Main Content Panel -->
    <main id="madeline-app" class="pl-0 lg:pl-[360px] min-h-screen transition-all duration-500">
        <div class="py-8 px-4 lg:py-12 lg:px-12 w-full">
            @biir('content')
        </div>

        <footer class="py-20 px-8 lg:px-12 border-t border-slate-50 mt-20">
            <div class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-300 mb-8">
                Gerel Ma Business Suite — Conçu pour les entrepreneurs africains
            </div>
            <div class="flex flex-wrap items-center gap-x-12 gap-y-6 text-[9px] font-black uppercase tracking-widest text-slate-400">
                <span class="flex items-center gap-2">
                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                    Rapide & Minimal
                </span>
                <span class="flex items-center gap-2">
                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                    Sécurisé
                </span>
                <span class="flex items-center gap-2">
                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                    Made in Dakar
                </span>
            </div>
            <div class="mt-8 text-[8px] font-bold text-slate-300 uppercase tracking-widest">
                Propulsé par <span class="text-slate-900">Gerel Ma</span> d <span class="text-slate-900">Digital Artists Studio</span>
            </div>
        </footer>
    </main>

@xaaj
    <!-- =============================== -->
    <!-- PUBLIC View (Login/Register)    -->
    <!-- =============================== -->
    <header class="fixed top-8 left-0 w-full z-50 px-4 md:px-10">
        <nav class="max-w-6xl mx-auto flex items-center justify-between px-6 py-3 rounded-full bg-white/90 backdrop-blur-md shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/50">
            <a href="/" class="text-xl font-black tracking-tighter flex items-center gap-3">
                <div class="w-2.5 h-2.5 bg-[#8b5cf6] rounded-full"></div>
                <span class="text-[#050510]">Gerel Ma<span class="text-[#8b5cf6]">.</span></span>
            </a>
            <div class="flex items-center gap-6 text-[10px] font-black uppercase tracking-widest">
                <a href="/login" class="text-slate-500 hover:text-[#8b5cf6] transition-colors">Client</a>
                <a href="#waitlist" class="px-6 py-3 rounded-full bg-[#050510] text-white hover:bg-slate-800 transition-all shadow-md">Accès Anticipé</a>
            </div>
        </nav>
    </header>

    <main id="madeline-app" class="w-full relative min-h-screen flex flex-col pt-0">
        <div class="w-full flex-1">
            @biir('content')
        </div>

        <footer class="py-12 border-t border-slate-100 mt-auto bg-white/50 backdrop-blur-sm relative z-20">
            <div class="max-w-6xl mx-auto px-6 md:px-10 flex flex-col md:flex-row items-center justify-between gap-6">
                 <div class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400">
                    Gerel Ma Business Suite — Conçu pour les entrepreneurs africains
                </div>
                <div class="flex flex-wrap items-center gap-6 text-[8px] font-black text-slate-400 uppercase tracking-widest">
                    <span>Rapide & Minimal</span>
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                    <span>Sécurisé</span>
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                    <span class="text-slate-500">Made in Dakar</span>
                </div>
            </div>
        </footer>
    </main>
@jeexmiingi

<script src="/js/madeline.js"></script>
<script>
    // API Globale Madeline pour les Toasts
    Madeline.toast = (message, type = 'success') => {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icon = type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12';
        toast.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="${icon}"/></svg>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    };

    // Auto-trigger Toast if set in session (handle data-toast attribute if rendered)
    function checkFlashes() {
        const flash = document.querySelector('[data-toast]');
        if (flash) {
            Madeline.toast(flash.dataset.toast, flash.dataset.toastType || 'success');
            flash.remove();
        }
    }
    document.addEventListener('madeline:refresh', checkFlashes);
    checkFlashes();

    // Synchronisation de la navbar avec le routage SPA Madeline.js
    document.addEventListener('madeline:refresh', function(e) {
        // Fermer la sidebar sur mobile après navigation
        document.querySelector('.glass-sidebar').classList.remove('show');

        let currentPath = new URL(e.detail.url || window.location.href).pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            const href = link.getAttribute('href');
            if (!href) return;
            
            // Logique d'activation
            if (href === '/dashboard' && currentPath === '/dashboard') {
                link.classList.add('active');
            } else if (href !== '/dashboard' && currentPath.startsWith(href)) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });
</script>
@biir('extra_head')
</body>
</html>
