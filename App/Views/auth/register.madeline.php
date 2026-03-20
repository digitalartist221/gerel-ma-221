@indi('layout')

@def('pageTitle')Inscription — Gerel Ma Business@jeexdef

@def('content')
<div class="min-h-[80vh] flex items-start lg:items-center justify-center pt-32 pb-20">
    <div class="max-w-md w-full floating-card p-12 rounded-6xl border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold text-[#050510] tracking-tight mb-4">Commencer.</h2>
            <p class="text-gray-400 text-sm font-medium">Créez votre compte Business en un instant.</p>
        </div>
        
        <form method="POST" action="/register" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Nom Complet</label>
                <input type="text" name="name" required placeholder="John Doe" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Email Address</label>
                <input type="email" name="email" required placeholder="name@company.com" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <button type="submit" class="w-full btn-dark rounded-full py-5 text-[11px] font-bold uppercase tracking-widest shadow-2xl shadow-black/10 mt-4">
                S'inscrire ↗
            </button>
        </form>
        
        <div class="mt-10 text-center">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                Déjà inscrit ? <a href="/login" class="text-brand-500 hover:underline">Se connecter —</a>
            </p>
        </div>
    </div>
</div>
@jeexdef
