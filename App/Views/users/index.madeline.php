@indi('layout')

@def('content')
<div class="mb-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-4">
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Liste des Utilisateurs</h2>
    <span class="text-sm bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 px-3 py-1 rounded-full border border-blue-200 dark:border-blue-800">
        Méthode Wolof fari()
    </span>
</div>

<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
        @ndax(empty($users))
            <li class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                Aucun utilisateur enregistré dans la base de données.
            </li>
        @xaaj
            @baat($users as $user)
            <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                <div>
                    <p class="text-lg font-medium text-madeline">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400">ID: {{ $user->id }}</span>
                </div>
            </li>
            @jeexbaat
        @jeexndax
    </ul>
</div>
@jeexdef
