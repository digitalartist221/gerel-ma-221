@indi('layout')

@def('head')
    <style>
        :root {
            --doc-title-color: #111827;
            --doc-text-color: #4b5563;
            --doc-bg-glass: rgba(255, 255, 255, 0.4);
            --doc-border: rgba(0, 0, 0, 0.05);
            --doc-code-bg: rgba(139, 92, 246, 0.08);
            --doc-pre-bg: #f9fafb;
        }

        .dark {
            --doc-title-color: #ffffff;
            --doc-text-color: #9ca3af;
            --doc-bg-glass: rgba(3, 3, 5, 0.4);
            --doc-border: rgba(255, 255, 255, 0.03);
            --doc-code-bg: rgba(139, 92, 246, 0.15);
            --doc-pre-bg: #050508;
        }

        /* Typography Premium spécifique à la Documentation */
        .prose-premium h2 { 
            scroll-margin-top: 7rem; font-weight: 800; letter-spacing: -0.04em; 
            color: var(--doc-title-color); border-bottom: 1px solid var(--doc-border); 
            padding-bottom: 0.75rem; margin-top: 5rem; margin-bottom: 2rem; font-size: 2.25rem; 
        }
        .prose-premium h3 { 
            scroll-margin-top: 7rem; font-weight: 700; color: var(--doc-title-color); 
            margin-top: 3rem; margin-bottom: 1.25rem; font-size: 1.5rem; letter-spacing: -0.02em; 
        }
        .prose-premium p { color: var(--doc-text-color); line-height: 1.9; margin-bottom: 1.5rem; font-size: 1.05rem; font-weight: 300; }
        .prose-premium code { background: var(--doc-code-bg); color: #8b5cf6; padding: 0.2em 0.45em; border-radius: 8px; font-size: 0.85em; font-weight: 500; font-family: 'JetBrains Mono', monospace; border: 1px solid rgba(139, 92, 246, 0.1); }
        .prose-premium pre { background: var(--doc-pre-bg) !important; border: 1px solid var(--doc-border); border-radius: 1.5rem; padding: 2rem; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); margin-top: 2rem; margin-bottom: 3rem; overflow-x: auto; position: relative; }
        .dark .prose-premium pre { box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6); }
        .prose-premium pre::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.3), transparent); }
        .prose-premium blockquote { border-left: 2px solid #8b5cf6; background: linear-gradient(90deg, rgba(139, 92, 246, 0.05), transparent); border-radius: 0 1rem 1rem 0; padding: 1.5rem 2rem; font-style: italic; color: var(--doc-text-color); margin: 3rem 0; font-family: 'Playfair Display', serif; font-size: 1.25rem; }
        .prose-premium ul { list-style-type: none; padding-left: 0; margin-bottom: 2rem; }
        .prose-premium ul li { position: relative; padding-left: 1.75rem; color: var(--doc-text-color); margin-bottom: 0.75rem; font-weight: 300; }
        .prose-premium ul li::before { content: "•"; position: absolute; left: 0; color: #8b5cf6; font-size: 1.5rem; line-height: 1; top: -0.1rem; }
        
        /* Sidebar Link Styling */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--doc-text-color);
            transition: all 0.3s cubic-bezier(0.1, 0.5, 0.5, 1);
            border: 1px solid transparent;
            margin-bottom: 0.25rem;
        }
        .sidebar-link:hover {
            color: var(--doc-title-color);
            background: rgba(139, 92, 246, 0.05);
            transform: translateX(4px);
        }
        .sidebar-link.active {
            color: #8b5cf6;
            background: linear-gradient(90deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.02));
            border-color: rgba(139, 92, 246, 0.1);
        }

        .sidebar-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            font-weight: 800;
            color: var(--doc-text-color);
            opacity: 0.5;
            padding: 0 1.25rem;
            margin-bottom: 1.5rem;
        }

        .doc-aside-glass {
            background: var(--doc-bg-glass);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            padding: 1.5rem;
            border: 1px solid var(--doc-border);
        }
    </style>
    @biir('extra_head')
@jeexdef

@def('content')
    <div class="max-w-[1400px] mx-auto px-6 py-12 lg:py-16 flex flex-col lg:flex-row gap-20 min-h-screen">
        
        <!-- Sidebar Navigation -->
        <aside class="w-full lg:w-80 flex-shrink-0">
            <div class="sticky top-32 space-y-12">
                <div class="animate-fade-in">
                    <h4 class="sidebar-title">Architecture & Guide</h4>
                    <div class="space-y-1">
                        @biir('toc')
                    </div>
                </div>

                <div class="doc-aside-glass group transition-all hover:border-brand-500/20 shadow-2xl overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-brand-500 uppercase tracking-[0.2em] mb-3">Statut du Système</p>
                        <p class="text-[13px] text-gray-500 dark:text-white/60 leading-relaxed mb-6 font-light">Madeline <strong>v1.0.0 Stable</strong> est optimisé pour les déploiements industriels.</p>
                        <a href="https://github.com" class="text-[10px] font-bold text-gray-900 dark:text-white hover:text-brand-400 transition-all uppercase tracking-widest flex items-center gap-2 group/btn">
                            GitHub Repository
                            <svg class="w-3 h-3 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0 max-w-4xl animate-fade-in" style="animation-delay: 0.1s">
            @biir('doc_content')
            
            <!-- Page Navigation Footnote -->
            <div class="mt-32 pt-12 border-t border-gray-100 dark:border-white/[0.05] flex justify-between items-center text-sm">
                <div class="text-gray-400 dark:text-white/20">© Madeline Framework</div>
                <div class="flex gap-6 uppercase tracking-widest text-[10px] font-bold">
                    <a href="/" class="hover:text-brand-500 dark:hover:text-white transition-colors">Accueil</a>
                    <a href="/api/docs/ui" class="hover:text-brand-500 dark:hover:text-white transition-colors">API Console</a>
                </div>
            </div>
        </main>

    </div>
@jeexdef
