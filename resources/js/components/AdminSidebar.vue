<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { 
    X, 
    Home, 
    Users, 
    Settings, 
    BarChart3, 
    Activity,
    Database,
    FileText,
    Calendar,
    Mail,
    Shield,
    User,
    Crown,
    UserCheck
} from 'lucide-vue-next';

interface Props {
    isOpen: boolean;
    onClose: () => void;
}

const props = defineProps<Props>();

const page = usePage();
const user = computed(() => page.props.auth?.user);

// Get current route for navigation highlighting
const currentRoute = computed(() => page.url);

const navigation = [
    { 
        name: 'Dashboard', 
        href: '/admin/dashboard', 
        icon: Home, 
        description: 'Overview and analytics',
        badge: null
    },
    { 
        name: 'Users', 
        href: '/admin/users', 
        icon: Users, 
        description: 'Manage user accounts',
        badge: '12'
    },
    { 
        name: 'Analytics', 
        href: '/admin/analytics', 
        icon: BarChart3, 
        description: 'Data insights and reports',
        badge: null
    },
    { 
        name: 'Activity', 
        href: '/admin/activity', 
        icon: Activity, 
        description: 'System activity logs',
        badge: '3'
    },
    { 
        name: 'Database', 
        href: '/admin/database', 
        icon: Database, 
        description: 'Database management',
        badge: null
    },
    { 
        name: 'Reports', 
        href: '/admin/reports', 
        icon: FileText, 
        description: 'Generate reports',
        badge: '5'
    },
    { 
        name: 'Calendar', 
        href: '/admin/calendar', 
        icon: Calendar, 
        description: 'Event scheduling',
        badge: null
    },
    { 
        name: 'Messages', 
        href: '/admin/messages', 
        icon: Mail, 
        description: 'Communication center',
        badge: '8'
    },
    { 
        name: 'Settings', 
        href: '/admin/settings', 
        icon: Settings, 
        description: 'System configuration',
        badge: null
    },
];

// Quick actions for the sidebar
const quickActions = [
    { name: 'Add User', href: '/admin/users/create', icon: User },
    { name: 'View Reports', href: '/admin/reports', icon: FileText },
    { name: 'System Status', href: '/admin/status', icon: Shield },
];

// Watch for route changes to close mobile sidebar
watch(currentRoute, () => {
    if (window.innerWidth < 1024) {
        props.onClose();
    }
});
</script>

<template>
    <!-- Mobile sidebar overlay -->
    <div 
        v-if="isOpen" 
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden animate-fade-in"
        @click="onClose"
    ></div>

    <!-- Sidebar -->
    <div 
        :class="[
            'fixed inset-y-0 left-0 z-50 w-80 bg-white/95 backdrop-blur-xl border-r border-purple-200/50 dark:bg-slate-900/95 dark:border-purple-700/50 transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-2xl',
            isOpen ? 'translate-x-0' : '-translate-x-full'
        ]"
    >
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-purple-200/50 dark:border-purple-700/50 bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600">
            <Link href="/admin/dashboard" class="flex items-center space-x-3 group">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300 transform group-hover:scale-110">
                    <Crown class="w-7 h-7 text-white" />
                </div>
                <div class="text-white">
                    <span class="text-xl font-bold">Admin</span>
                    <span class="block text-xs text-purple-200">Dashboard</span>
                </div>
            </Link>
            <Button 
                variant="ghost" 
                size="sm" 
                class="lg:hidden text-white hover:bg-white/20 transition-all duration-300 rounded-xl"
                @click="onClose"
            >
                <X class="w-5 h-5" />
            </Button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-4 overflow-y-auto">
            <!-- Main Navigation -->
            <div class="space-y-2">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-3">
                    Main Navigation
                </h3>
                <div class="space-y-1">
                    <div 
                        v-for="item in navigation" 
                        :key="item.name"
                        class="group"
                    >
                        <Link 
                            :href="item.href"
                            :class="[
                                'flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group-hover:bg-gradient-to-r group-hover:from-purple-50 group-hover:to-navy-50 dark:group-hover:from-purple-900/20 dark:group-hover:to-navy-900/20 border border-transparent group-hover:border-purple-200 dark:group-hover:border-purple-700 relative transform group-hover:scale-[1.02]',
                                currentRoute.startsWith(item.href)
                                    ? 'bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 text-white shadow-lg border-purple-500' 
                                    : 'text-slate-700 dark:text-slate-300 hover:text-purple-700 dark:hover:text-purple-300'
                            ]"
                        >
                            <component :is="item.icon" class="w-5 h-5 mr-3" />
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span>{{ item.name }}</span>
                                    <Badge 
                                        v-if="item.badge" 
                                        variant="secondary" 
                                        class="ml-2 bg-gradient-to-r from-purple-100 to-navy-100 text-purple-800 dark:from-purple-900 dark:to-navy-900 dark:text-purple-200 text-xs"
                                    >
                                        {{ item.badge }}
                                    </Badge>
                                </div>
                                <div class="text-xs opacity-75 mt-1">{{ item.description }}</div>
                            </div>
                            <div 
                                v-if="currentRoute.startsWith(item.href)"
                                class="absolute right-2 w-2 h-2 bg-white rounded-full animate-pulse"
                            ></div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-2">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-3">
                    Quick Actions
                </h3>
                <div class="space-y-1">
                    <div 
                        v-for="action in quickActions" 
                        :key="action.name"
                        class="group"
                    >
                        <Link 
                            :href="action.href"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 group-hover:bg-gradient-to-r group-hover:from-slate-100 group-hover:to-slate-200 dark:group-hover:from-slate-800 dark:group-hover:to-slate-700 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transform group-hover:scale-[1.02]"
                        >
                            <component :is="action.icon" class="w-4 h-4 mr-3" />
                            <span>{{ action.name }}</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200/50 dark:border-green-800/50">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-green-800 dark:text-green-200">System Online</span>
                </div>
                <p class="text-xs text-green-600 dark:text-green-300 mt-1">All services running smoothly</p>
            </div>
        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-purple-200/50 dark:border-purple-700/50 bg-gradient-to-r from-purple-50 via-navy-50 to-blue-50 dark:from-purple-900/20 dark:via-navy-900/20 dark:to-blue-900/20">
            <div class="flex items-center space-x-3">
                <Avatar class="w-12 h-12 ring-2 ring-purple-200 dark:ring-purple-700">
                    <AvatarImage :src="user?.avatar" />
                    <AvatarFallback class="bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 text-white">
                        {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                    </AvatarFallback>
                </Avatar>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                        {{ user?.name || 'User' }}
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                        {{ user?.email }}
                    </p>
                    <div class="flex items-center mt-1">
                        <Badge variant="secondary" class="bg-gradient-to-r from-purple-100 to-navy-100 text-purple-800 dark:from-purple-900 dark:to-navy-900 dark:text-purple-200 text-xs">
                            <Crown class="w-3 h-3 mr-1" />
                            Admin
                        </Badge>
                        <Badge variant="secondary" class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900 dark:to-emerald-900 dark:text-green-200 text-xs ml-2">
                            <UserCheck class="w-3 h-3 mr-1" />
                            Active
                        </Badge>
                    </div>
                </div>
            </div>
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

/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
    width: 4px;
}

nav::-webkit-scrollbar-track {
    background: transparent;
}

nav::-webkit-scrollbar-thumb {
    background: rgba(139, 92, 246, 0.3);
    border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
    background: rgba(139, 92, 246, 0.5);
}
</style>
