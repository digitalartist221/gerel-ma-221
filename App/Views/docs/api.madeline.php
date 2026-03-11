@indi('docs/layout')

@def('pageTitle')Console API Interactive — Madeline@jeexdef

@def('extra_head')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
<style>
    :root {
        --sw-bg: #ffffff;
        --sw-text: #1f2937;
        --sw-title: #111827;
        --sw-border: rgba(0, 0, 0, 0.05);
        --sw-card-bg: rgba(255, 255, 255, 0.8);
        --sw-input-bg: #f9fafb;
    }

    .dark {
        --sw-bg: transparent;
        --sw-text: #9ca3af;
        --sw-title: #ffffff;
        --sw-border: rgba(255, 255, 255, 0.05);
        --sw-card-bg: rgba(255, 255, 255, 0.02);
        --sw-input-bg: rgba(255, 255, 255, 0.03);
    }

    /* Swagger UI Premium Overrides */
    #swagger-ui { background: transparent; padding-bottom: 5rem; width: 100%; max-width: 100%; }
    
    .swagger-ui { font-family: 'Plus Jakarta Sans', sans-serif !important; }
    
    /* Text & Titles */
    .swagger-ui .info .title, .swagger-ui .model-title, .swagger-ui .opblock-summary-path { 
        color: var(--sw-title) !important; font-weight: 800 !important; letter-spacing: -0.02em !important; 
    }
    .swagger-ui .info p, .swagger-ui .info li, .swagger-ui .info table, .swagger-ui .model, .swagger-ui .prop-type, .swagger-ui .opblock-summary-description { 
        color: var(--sw-text) !important; font-size: 13px !important; font-weight: 300 !important;
    }
    
    /* Global Containers */
    .swagger-ui .scheme-container { 
        background: var(--sw-card-bg) !important; border: 1px solid var(--sw-border) !important;
        border-radius: 2rem !important; box-shadow: 0 20px 50px rgba(0,0,0,0.05) !important; 
        margin: 3rem 0 !important; padding: 2rem !important; backdrop-filter: blur(10px);
    }
    .dark .swagger-ui .scheme-container { box-shadow: 0 20px 50px rgba(0,0,0,0.3) !important; }

    .swagger-ui select { 
        background: var(--sw-input-bg) !important; color: var(--sw-title) !important; border: 1px solid var(--sw-border) !important; 
        border-radius: 0.75rem !important; padding: 0.5rem 1rem !important;
    }
    
    /* Operation Blocks */
    .swagger-ui .opblock { 
        border-radius: 1.5rem !important; background: var(--sw-card-bg) !important; 
        border: 1px solid var(--sw-border) !important; box-shadow: 0 4px 20px rgba(0,0,0,0.02) !important; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 1.25rem !important; overflow: hidden;
    }
    .dark .swagger-ui .opblock { box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important; }
    
    .swagger-ui .opblock:hover { transform: translateY(-4px); border-color: rgba(139, 92, 246, 0.3) !important; }
    
    /* Method Badges */
    .swagger-ui .opblock .opblock-summary-method { 
        border-radius: 0.75rem !important; font-weight: 900 !important; text-transform: uppercase; 
        font-size: 10px !important; padding: 6px 12px !important; border: none !important;
    }
    .swagger-ui .opblock-get .opblock-summary-method { background: #8b5cf6 !important; color: white !important; }
    .swagger-ui .opblock-post .opblock-summary-method { background: #10b981 !important; color: white !important; }
    
    /* Buttons */
    .swagger-ui .btn { 
        border-radius: 1rem !important; font-weight: 700 !important; text-transform: none !important; 
        transition: all 0.2s !important; border: 1px solid var(--sw-border) !important;
        padding: 8px 20px !important; font-size: 12px !important; color: var(--sw-text) !important;
        background: transparent !important;
    }
    .swagger-ui .btn:hover { background: var(--sw-input-bg) !important; color: var(--sw-title) !important; }
    .swagger-ui .btn.execute { 
        background-color: #8b5cf6 !important; border: none !important; color: white !important; 
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2) !important;
    }
    .swagger-ui .btn.execute:hover { background-color: #7c3aed !important; transform: scale(1.02); }
    
    /* Internal sections */
    .swagger-ui .opblock-section-header { 
        background: var(--sw-input-bg) !important; border-radius: 1rem !important; 
        padding: 1rem !important; font-weight: 700 !important; color: var(--sw-title) !important;
    }
    .swagger-ui section.models { 
        border: 1px solid var(--sw-border) !important; border-radius: 2rem !important; 
        background: var(--sw-card-bg) !important; margin-top: 5rem !important; padding: 2rem !important;
    }
    .swagger-ui section.models .model-container { background: transparent !important; margin-top: 1rem; }
    
    /* Inputs */
    .swagger-ui input[type=text] {
        background: var(--sw-input-bg) !important; border: 1px solid var(--sw-border) !important;
        border-radius: 0.75rem !important; color: var(--sw-title) !important; padding: 10px 15px !important;
    }

    /* Table Colors */
    .swagger-ui table thead tr td, .swagger-ui table thead tr th { color: var(--sw-title) !important; border-bottom: 1px solid var(--sw-border) !important; }
    .swagger-ui .parameters-col_description textarea { background: var(--sw-input-bg) !important; color: var(--sw-title) !important; border: 1px solid var(--sw-border) !important; border-radius: 0.5rem; }

    /* Hide topbar */
    .swagger-ui .topbar { display: none !important; }

    /* Custom Animation */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@jeexdef

@def('toc')
    <a href="/docs" class="sidebar-link group">
        <svg class="w-4 h-4 mr-3 opacity-40 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Retour au Guide
    </a>
    <div class="pt-8 mt-8 border-t border-gray-100 dark:border-white/5">
        <h5 class="sidebar-title">Ressources API</h5>
        <div class="space-y-1">
            <a href="#/User" class="sidebar-link opacity-60 hover:opacity-100">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-500 mr-3"></span>
                Users API
            </a>
            <a href="#/Auth" class="sidebar-link opacity-60 hover:opacity-100">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-3"></span>
                Auth API
            </a>
        </div>
    </div>
@jeexdef

@def('doc_content')
<div class="mb-20 animate-slide-up">
    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-500 text-[10px] font-black uppercase tracking-[0.3em] mb-12">
        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
        Interactive Console Interface
    </div>
    <h1 class="text-6xl md:text-7xl font-black text-gray-900 dark:text-white mb-8 tracking-tighter leading-none">
        Console <span class="text-brand-500">API</span>.
    </h1>
    <p class="text-xl text-gray-500 dark:text-white/40 max-w-2xl leading-relaxed font-light">
        Testez vos endpoints en temps réel. Cette interface synchronise automatiquement 
        votre logique serveur avec la spécification OpenAPI.
    </p>
</div>

<!-- Placeholder Swagger UI -->
<div id="swagger-ui" class="animate-slide-up" style="animation-delay: 0.2s"></div>

<script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin></script>
<script>
    function initSwagger() {
        if (!window.SwaggerUIBundle) {
            console.log('Swagger bundle not ready, retrying...');
            setTimeout(initSwagger, 50);
            return;
        }

        fetch('/api/docs')
            .then(res => res.json())
            .then(spec => {
                const ui = SwaggerUIBundle({
                    spec: spec,
                    dom_id: '#swagger-ui',
                    deepLinking: true,
                    presets: [ SwaggerUIBundle.presets.apis, SwaggerUIBundle.SwaggerUIStandalonePreset ],
                    layout: "BaseLayout",
                    docExpansion: 'list',
                    filter: true,
                });
                window.ui = ui;
            });
    }
    
    initSwagger();
</script>
@jeexdef
