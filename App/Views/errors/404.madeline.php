<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Introuvable</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        blob: "blob 8s infinite",
                    },
                    keyframes: {
                        blob: {
                            "0%": { transform: "translate(0px, 0px) scale(1)" },
                            "33%": { transform: "translate(30px, -50px) scale(1.1)" },
                            "66%": { transform: "translate(-20px, 20px) scale(0.9)" },
                            "100%": { transform: "translate(0px, 0px) scale(1)" },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#050507] text-white font-sans min-h-screen flex items-center justify-center relative overflow-hidden">
    
    <!-- Background Mesh Introuvable -->
    <div class="fixed inset-0 z-0 pointer-events-none opacity-60 mix-blend-screen">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-900/30 rounded-full filter blur-[100px] animate-blob"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-900/20 rounded-full filter blur-[120px] animate-blob" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative z-10 text-center px-6 selection:bg-purple-500/30">
        <div class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-white/5 border border-white/10 mb-8 shadow-xl backdrop-blur-sm">
            <svg class="w-8 h-8 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        
        <h1 class="text-8xl md:text-9xl font-bold tracking-tighter bg-clip-text text-transparent bg-gradient-to-br from-white to-white/40 mb-4 drop-shadow-lg">404</h1>
        <h2 class="text-2xl md:text-3xl font-medium mb-4 tracking-tight">Faranfàcce bi amul <br> <span class="text-gray-400 text-lg">(Page Introuvable)</span></h2>
        
        <p class="text-gray-400 text-sm md:text-base font-light mb-10 max-w-md mx-auto leading-relaxed">
            La route <strong class="text-white bg-white/10 px-2 py-0.5 rounded font-mono">{{ $url }}</strong> n'est pas définie dans votre application Madeline. 
        </p>
        
        <a href="/" class="group inline-flex items-center space-x-3 bg-white text-black px-6 py-3 rounded-full text-sm font-semibold hover:bg-gray-200 transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(255,255,255,0.15)]">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Retour à l'accueil</span>
        </a>
    </div>
    
</body>
</html>
