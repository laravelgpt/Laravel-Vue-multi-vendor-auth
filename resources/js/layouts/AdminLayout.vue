<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import AdminSidebar from '@/components/AdminSidebar.vue';
import { 
    Menu, 
    LogOut, 
    Bell,
    Search,
    Sun,
    Moon,
    User,
    ChevronDown,
    Crown,
    Settings,
    HelpCircle
} from 'lucide-vue-next';

interface Props {
    title?: string;
    showSidebar?: boolean;
}

withDefaults(defineProps<Props>(), {
    title: 'Admin Dashboard',
    showSidebar: true,
});

const page = usePage();
const sidebarOpen = ref(false);
const user = computed(() => page.props.auth?.user);
const userMenuOpen = ref(false);
const searchQuery = ref('');

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
    sidebarOpen.value = false;
};

const toggleUserMenu = () => {
    userMenuOpen.value = !userMenuOpen.value;
};

const closeUserMenu = () => {
    userMenuOpen.value = false;
};

// Handle search
const handleSearch = () => {
    if (searchQuery.value.trim()) {
        // Implement search functionality
        console.log('Searching for:', searchQuery.value);
    }
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 via-navy-50 to-blue-100 dark:from-slate-900 dark:via-purple-900/20 dark:via-navy-900/20 dark:to-blue-900/20">
        <Head :title="title" />
        
        <!-- Enhanced Background Pattern -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-purple-400/30 via-navy-400/20 to-blue-400/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-blue-400/30 via-purple-400/20 to-navy-400/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-1/2 left-1/2 w-80 h-80 -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-navy-400/15 via-purple-400/15 to-blue-400/15 rounded-full blur-2xl"></div>
        </div>
        
        <!-- Admin Sidebar Component -->
        <AdminSidebar 
            :is-open="sidebarOpen" 
            :on-close="closeSidebar" 
        />

        <!-- Main Content -->
        <div class="lg:pl-80">
            <!-- Top Navigation -->
            <header class="bg-white/95 backdrop-blur-xl border-b border-purple-200/50 dark:bg-slate-900/95 dark:border-purple-700/50 shadow-lg sticky top-0 z-30">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Left side -->
                    <div class="flex items-center">
                        <Button 
                            variant="ghost" 
                            size="sm" 
                            class="lg:hidden mr-3 text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-900/20 transition-all duration-300"
                            @click="toggleSidebar"
                        >
                            <Menu class="w-5 h-5" />
                        </Button>
                        <div>
                            <h1 class="text-xl font-bold bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 bg-clip-text text-transparent">{{ title }}</h1>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Administration Panel</p>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <input
                                v-model="searchQuery"
                                @keyup.enter="handleSearch"
                                type="text"
                                placeholder="Search admin panel..."
                                class="w-80 pl-10 pr-4 py-2 text-sm bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300"
                            />
                        </div>

                        <!-- Notifications -->
                        <Button variant="ghost" size="sm" class="relative text-slate-600 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition-all duration-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl">
                            <Bell class="w-5 h-5" />
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        </Button>

                        <!-- Theme Toggle -->
                        <Button variant="ghost" size="sm" class="text-slate-600 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition-all duration-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl">
                            <Sun class="w-5 h-5 dark:hidden" />
                            <Moon class="w-5 h-5 hidden dark:block" />
                        </Button>

                        <!-- Help -->
                        <Button variant="ghost" size="sm" class="text-slate-600 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition-all duration-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl">
                            <HelpCircle class="w-5 h-5" />
                        </Button>

                        <!-- User Menu -->
                        <div class="relative">
                            <Button 
                                variant="ghost" 
                                size="sm" 
                                class="flex items-center space-x-2 text-slate-600 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition-all duration-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl"
                                @click="toggleUserMenu"
                            >
                                <Avatar class="w-8 h-8 ring-2 ring-purple-200 dark:ring-purple-700">
                                    <AvatarImage v-if="user?.avatar" :src="user.avatar" />
                                    <AvatarFallback class="bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 text-white text-sm">
                                        {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                                    </AvatarFallback>
                                </Avatar>
                                <ChevronDown class="w-4 h-4" />
                            </Button>
                            
                            <!-- User Dropdown Menu -->
                            <div 
                                v-if="userMenuOpen"
                                class="absolute right-0 mt-2 w-64 bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-xl shadow-2xl border border-slate-200/50 dark:border-slate-700/50 py-2 z-50"
                                @click="closeUserMenu"
                            >
                                <div class="px-4 py-3 border-b border-slate-200/50 dark:border-slate-700/50">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ user?.name || 'User' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ user?.email }}</p>
                                    <div class="flex items-center mt-2">
                                        <Badge variant="secondary" class="bg-gradient-to-r from-purple-100 to-navy-100 text-purple-800 dark:from-purple-900 dark:to-navy-900 dark:text-purple-200 text-xs">
                                            <Crown class="w-3 h-3 mr-1" />
                                            Administrator
                                        </Badge>
                                    </div>
                                </div>
                                <Link href="/admin/profile" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-purple-50 hover:to-navy-50 dark:hover:from-purple-900/20 dark:hover:to-navy-900/20 transition-all duration-300">
                                    <User class="w-4 h-4 mr-2 inline" />
                                    Profile Settings
                                </Link>
                                <Link href="/admin/settings" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-purple-50 hover:to-navy-50 dark:hover:from-purple-900/20 dark:hover:to-navy-900/20 transition-all duration-300">
                                    <Settings class="w-4 h-4 mr-2 inline" />
                                    Admin Settings
                                </Link>
                                <Link href="/admin/help" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-purple-50 hover:to-navy-50 dark:hover:from-purple-900/20 dark:hover:to-navy-900/20 transition-all duration-300">
                                    <HelpCircle class="w-4 h-4 mr-2 inline" />
                                    Help & Support
                                </Link>
                                <div class="border-t border-slate-200/50 dark:border-slate-700/50 mt-2 pt-2">
                                    <Link href="/logout" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 dark:hover:from-red-900/20 dark:hover:to-red-800/20 transition-all duration-300">
                                        <LogOut class="w-4 h-4 mr-2 inline" />
                                        Sign Out
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                <div class="animate-fade-in">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

