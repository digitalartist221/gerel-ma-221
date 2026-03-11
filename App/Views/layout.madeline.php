<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@biir('pageTitle') — {{ \Core\Config::get('app.name', 'Madeline Framework') }}</title>
    <meta name="description" content="{{ \Core\Config::get('app.description', 'Infrastructure PHP 8.3 Industrielle') }}">
    
    <!-- Core Scripts -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script src="/js/madeline.js"></script>
    
    <!-- Modern Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script>
        // Theme Initialization (Blocking to avoid flicker)
        (function() {
            const theme = localStorage.getItem('madeline-theme') || 'dark';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd',
                            400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9',
                            800: '#5b21b6', 900: '#4c1d95',
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #1f2937;
            --glass-bg: rgba(255, 255, 255, 0.8);
            --border-color: rgba(0, 0, 0, 0.05);
        }

        .dark {
            --bg-color: #030305;
            --text-color: #e5e7eb;
            --glass-bg: rgba(3, 3, 5, 0.75);
            --border-color: rgba(255, 255, 255, 0.05);
        }

        body { 
            background-color: var(--bg-color); 
            color: var(--text-color); 
            overflow-x: hidden; 
            scroll-behavior: smooth;
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.4s;
        }

        /* Custom Scrollbar Premium */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(139, 92, 246, 0.2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(139, 92, 246, 0.5); }

        /* Glassmorphism Classes */
        .glass-header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            transition: background 0.4s, border-color 0.4s;
        }

        /* Dynamic Background Blobs */
        .blob {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(140px);
            z-index: -1;
            opacity: 0.15;
            pointer-events: none;
            transition: opacity 1s;
        }

        .dark .blob { opacity: 0.08; }

        .blob-1 { top: -200px; left: -100px; background: #6d28d9; }
        .blob-2 { bottom: -200px; right: -100px; background: #3b82f6; }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
            background: var(--glass-bg);
            color: var(--text-color);
            transition: all 0.3s cubic-bezier(0.1, 0.5, 0.5, 1);
            cursor: pointer;
        }
        .theme-toggle-btn:hover {
            transform: scale(1.1) rotate(12deg);
            border-color: rgba(139, 92, 246, 0.5);
            color: #8b5cf6;
        }
    </style>
    @biir('head')
</head>
<body class="selection:bg-brand-500/30">

    <!-- Ambient Background -->
    <div class="blob blob-1 animate-pulse-slow"></div>
    <div class="blob blob-2 animate-pulse-slow" style="animation-delay: 2s"></div>

    <!-- Master Header -->
    <header class="sticky top-0 z-[100] glass-header">
        <div class="max-w-[1400px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <a href="/" class="flex items-center group transition-transform hover:scale-[1.02]">
                    <span class="font-serif italic text-2xl tracking-tight text-gray-900 dark:text-white/90 group-hover:text-brand-500 transition-colors">{{ \Core\Config::get('app.name', 'Madeline') }}</span>
                </a>
                
                <nav class="hidden lg:flex items-center gap-2">
                    <a href="/docs" class="px-5 py-2 text-sm font-medium text-gray-500 dark:text-white/50 hover:text-brand-600 dark:hover:text-white transition-all hover:bg-gray-100 dark:hover:bg-white/[0.03] rounded-full">Guide Framework</a>
                    <a href="/api/docs/ui" class="px-5 py-2 text-sm font-medium text-gray-500 dark:text-white/50 hover:text-brand-600 dark:hover:text-white transition-all hover:bg-gray-100 dark:hover:bg-white/[0.03] rounded-full">Console API</a>
                </nav>
            </div>

            <div class="flex items-center gap-4 lg:gap-6">
                <!-- Theme Switcher -->
                <button onclick="Madeline.toggleTheme()" class="theme-toggle-btn" aria-label="Toggle Theme">
                    <svg class="dark:hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg class="hidden dark:block w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.243 16.243l.707.707M7.05 7.05l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                </button>

                <a href="https://github.com" target="_blank" class="hidden sm:flex text-gray-400 dark:text-white/30 hover:text-brand-500 dark:hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2A10 10 0 002 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33s1.71.11 2.5.33c1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0012 2z"></path></svg>
                </a>
                <?php if (file_exists(__DIR__ . '/../Controllers/AuthController.php')): ?>
                <a href="/login" class="px-6 py-2.5 text-sm font-bold bg-gray-900 dark:bg-white text-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all active:scale-95 shadow-xl shadow-gray-200 dark:shadow-white/5">
                    Connexion
                </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- PIVOT CENTRAL SPA -->
    <div id="madeline-app">
        @biir('content')
    </div>

    <!-- Master Footer -->
    <footer class="border-t border-gray-100 dark:border-white/[0.05] py-24 bg-gray-50 dark:bg-[#050508] transition-colors">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-16 mb-20">
                <div class="col-span-2 space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="font-serif italic text-2xl text-gray-900 dark:text-white">{{ \Core\Config::get('app.name', 'Madeline') }}</span>
                    </div>
                    <p class="text-gray-500 dark:text-white/40 text-sm max-w-sm leading-relaxed font-light">
                        L'art du code rencontre l'ingénierie de pointe. Madeline est conçu pour transformer vos idées en expériences numériques d'exception.
                    </p>
                </div>
                <div class="space-y-6">
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 dark:text-white/20">Explorer</h4>
                    <ul class="space-y-3 text-sm text-gray-500 dark:text-white/40 font-medium">
                        <li><a href="/docs" class="hover:text-brand-500 dark:hover:text-white transition-colors">Architecture</a></li>
                        <li><a href="/docs#orm" class="hover:text-brand-500 dark:hover:text-white transition-colors">Zéro-Migration</a></li>
                        <li><a href="/api/docs/ui" class="hover:text-brand-500 dark:hover:text-white transition-colors">Console API</a></li>
                    </ul>
                </div>
                <div class="space-y-6">
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 dark:text-white/20">Laboratoire</h4>
                    <ul class="space-y-3 text-sm text-gray-500 dark:text-white/40 font-medium">
                        <li><a href="https://github.com" class="hover:text-brand-500 dark:hover:text-white transition-colors">Dépôt GitHub</a></li>
                        <li><a href="#" class="hover:text-brand-500 dark:hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-brand-500 dark:hover:text-white transition-colors">Défis de Code</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-10 border-t border-gray-200 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 dark:text-white/20">
                <p>&copy; {{ date('Y') }} — Un chef-d'œuvre de <span class="text-gray-900 dark:text-white/40 font-serif italic lowercase tracking-normal text-sm">Digital Artist Studio</span> / Dakar</p>
                <div class="flex gap-10 items-center">
                    <span class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-green-500"></span> Stable v1.0.0</span>
                    <span class="hover:text-brand-500 dark:hover:text-white transition-colors cursor-pointer">Licence MIT</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
