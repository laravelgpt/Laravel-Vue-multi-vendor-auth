@auth
    <div class="relative">
        <button class="flex items-center space-x-2 p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" 
                onclick="toggleHeaderUserMenu()">
            <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </span>
            </div>
            <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ auth()->user()->name }}
            </span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <!-- Header User Menu Dropdown -->
        <div id="headerUserMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
            <div class="py-1">
                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" 
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Profile
                </a>
                <a href="{{ route('settings.profile.edit') }}" 
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Settings
                </a>
                <hr class="my-1 border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" 
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function toggleHeaderUserMenu() {
            const menu = document.getElementById('headerUserMenu');
            menu.classList.toggle('hidden');
        }
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('headerUserMenu');
            const button = event.target.closest('button');
            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleHeaderUserMenu') === -1) {
                menu.classList.add('hidden');
            }
        });
    </script>
@endauth
