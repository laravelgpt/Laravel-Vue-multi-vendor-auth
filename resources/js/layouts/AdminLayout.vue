<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { 
    Menu, 
    X, 
    Home, 
    Users, 
    Settings, 
    BarChart3, 
    Shield, 
    LogOut, 
    Bell,
    Search,
    Sun,
    Moon,
    User,
    ChevronDown
} from 'lucide-vue-next';

interface Props {
    title?: string;
    showSidebar?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Admin Dashboard',
    showSidebar: true,
});

const page = usePage();
const sidebarOpen = ref(false);
const user = computed(() => page.props.auth?.user);

const navigation = [
    { name: 'Dashboard', href: '/admin/dashboard', icon: Home, current: true },
    { name: 'Users', href: '/admin/users', icon: Users, current: false },
    { name: 'Analytics', href: '/admin/analytics', icon: BarChart3, current: false },
    { name: 'Settings', href: '/admin/settings', icon: Settings, current: false },
];

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
    sidebarOpen.value = false;
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <Head :title="title" />
        
        <!-- Mobile sidebar overlay -->
        <div 
            v-if="sidebarOpen" 
            class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden animate-fade-in"
            @click="closeSidebar"
        ></div>

        <!-- Sidebar -->
        <div 
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 bg-white/80 backdrop-blur-xl border-r border-slate-200/50 dark:bg-slate-900/80 dark:border-slate-700/50 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-slate-200/50 dark:border-slate-700/50">
                <Link href="/admin/dashboard" class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-primary">
                        <Shield class="w-5 h-5 text-white" />
                    </div>
                    <span class="text-xl font-bold text-slate-900 dark:text-white">Admin</span>
                </Link>
                <Button 
                    variant="ghost" 
                    size="sm" 
                    class="lg:hidden"
                    @click="closeSidebar"
                >
                    <X class="w-5 h-5" />
                </Button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <div class="space-y-1">
                    <div 
                        v-for="item in navigation" 
                        :key="item.name"
                        class="group"
                    >
                        <Link 
                            :href="item.href"
                            :class="[
                                'flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group-hover:bg-slate-100 dark:group-hover:bg-slate-800',
                                item.current 
                                    ? 'bg-gradient-primary text-white shadow-lg' 
                                    : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'
                            ]"
                        >
                            <component :is="item.icon" class="w-5 h-5 mr-3" />
                            {{ item.name }}
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-slate-200/50 dark:border-slate-700/50">
                <div class="flex items-center space-x-3">
                    <Avatar class="w-10 h-10">
                        <AvatarImage :src="user?.avatar" />
                        <AvatarFallback class="bg-gradient-primary text-white">
                            {{ user?.name?.charAt(0)?.toUpperCase() }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                            {{ user?.name }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                            {{ user?.email }}
                        </p>
                    </div>
                    <Badge variant="secondary" class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Admin
                    </Badge>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navigation -->
            <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200/50 dark:bg-slate-900/80 dark:border-slate-700/50">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Left side -->
                    <div class="flex items-center">
                        <Button 
                            variant="ghost" 
                            size="sm" 
                            class="lg:hidden mr-2"
                            @click="toggleSidebar"
                        >
                            <Menu class="w-5 h-5" />
                        </Button>
                        <h1 class="text-xl font-semibold text-slate-900 dark:text-white">{{ title }}</h1>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <input
                                type="text"
                                placeholder="Search..."
                                class="w-64 pl-10 pr-4 py-2 text-sm bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>

                        <!-- Notifications -->
                        <Button variant="ghost" size="sm" class="relative">
                            <Bell class="w-5 h-5" />
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                        </Button>

                        <!-- Theme Toggle -->
                        <Button variant="ghost" size="sm">
                            <Sun class="w-5 h-5 dark:hidden" />
                            <Moon class="w-5 h-5 hidden dark:block" />
                        </Button>

                        <!-- User Menu -->
                        <div class="relative">
                            <Button variant="ghost" size="sm" class="flex items-center space-x-2">
                                <Avatar class="w-8 h-8">
                                    <AvatarImage :src="user?.avatar" />
                                    <AvatarFallback class="bg-gradient-primary text-white text-sm">
                                        {{ user?.name?.charAt(0)?.toUpperCase() }}
                                    </AvatarFallback>
                                </Avatar>
                                <ChevronDown class="w-4 h-4" />
                            </Button>
                        </div>

                        <!-- Logout -->
                        <Link href="/logout" method="post" as="button">
                            <Button variant="ghost" size="sm">
                                <LogOut class="w-5 h-5" />
                            </Button>
                        </Link>
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

