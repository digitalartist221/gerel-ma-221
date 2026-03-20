@indi('layout')

@def('pageTitle')Connexion — Gerel Ma Business@jeexdef

@def('content')
<div class="min-h-[70vh] flex items-center justify-center pt-32 pb-20">
    <div class="max-w-md w-full floating-card p-12 rounded-6xl border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold text-[#050510] tracking-tight mb-4">Bon retour.</h2>
            <p class="text-gray-400 text-sm font-medium">Accédez à votre cockpit de gestion.</p>
        </div>
        
        @ndax(isset($error))
            <div class="bg-red-50 text-red-500 text-[11px] font-bold uppercase tracking-widest p-4 rounded-2xl mb-8 border border-red-100 text-center">
                {{ $error }}
            </div>
        @jeexndax
        
        <form method="POST" action="/login" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Email Address</label>
                <input type="email" name="email" required placeholder="name@company.com" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <button type="submit" class="w-full btn-dark rounded-full py-5 text-[11px] font-bold uppercase tracking-widest shadow-2xl shadow-black/10 mt-4 inline-flex items-center justify-center gap-2">
                Se Connecter
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </button>
        </form>
        
        <div class="mt-10 text-center">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                Nouveau ici ? <a href="/register" class="text-brand-500 hover:underline">Créer un compte</a>
            </p>
        </div>
    </div>
</div>
@jeexdef
