<script setup lang="ts">
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { 
    Card, 
    CardContent, 
    CardDescription, 
    CardHeader, 
    CardTitle 
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    Users, 
    UserCheck, 
    Shield, 
    Activity, 
    TrendingUp, 
    Calendar,
    Clock,
    MapPin,
    Mail,
    Phone,
    Globe,
    Settings,
    Eye,
    Edit,
    Trash2,
    Plus,
    Search,
    Filter,
    Download,
    RefreshCw
} from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

// Mock data for demonstration - in real app this would come from API
const stats = ref([
    { 
        title: 'Total Users', 
        value: '1,234', 
        change: '+12%', 
        changeType: 'positive',
        icon: Users,
        color: 'blue'
    },
    { 
        title: 'Active Users', 
        value: '892', 
        change: '+8%', 
        changeType: 'positive',
        icon: UserCheck,
        color: 'green'
    },
    { 
        title: 'Security Score', 
        value: '98.5%', 
        change: '+2.1%', 
        changeType: 'positive',
        icon: Shield,
        color: 'purple'
    },
    { 
        title: 'System Uptime', 
        value: '99.9%', 
        change: '+0.1%', 
        changeType: 'positive',
        icon: Activity,
        color: 'orange'
    }
]);

const recentActivities = ref([
    { 
        user: 'John Doe', 
        action: 'Profile updated', 
        time: '2 minutes ago',
        type: 'profile'
    },
    { 
        user: 'Jane Smith', 
        action: 'Password changed', 
        time: '15 minutes ago',
        type: 'security'
    },
    { 
        user: 'Mike Johnson', 
        action: 'New user registered', 
        time: '1 hour ago',
        type: 'user'
    },
    { 
        user: 'Sarah Wilson', 
        action: 'Login from new device', 
        time: '2 hours ago',
        type: 'security'
    }
]);

const quickActions = [
    { title: 'Add User', icon: Plus, action: 'add-user', color: 'blue' },
    { title: 'View Reports', icon: TrendingUp, action: 'reports', color: 'green' },
    { title: 'System Settings', icon: Settings, action: 'settings', color: 'purple' },
    { title: 'Security Audit', icon: Shield, action: 'security', color: 'orange' }
];

const searchQuery = ref('');
const selectedFilter = ref('all');

const filteredActivities = computed(() => {
    let filtered = recentActivities.value;
    
    if (searchQuery.value) {
        filtered = filtered.filter(activity => 
            activity.user.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            activity.action.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    if (selectedFilter.value !== 'all') {
        filtered = filtered.filter(activity => activity.type === selectedFilter.value);
    }
    
    return filtered;
});

const getActivityIcon = (type: string) => {
    switch (type) {
        case 'profile': return Edit;
        case 'security': return Shield;
        case 'user': return Users;
        default: return Activity;
    }
};

const getActivityColor = (type: string) => {
    switch (type) {
        case 'profile': return 'text-blue-600 bg-blue-100 dark:bg-blue-900/20';
        case 'security': return 'text-green-600 bg-green-100 dark:bg-green-900/20';
        case 'user': return 'text-purple-600 bg-purple-100 dark:bg-purple-900/20';
        default: return 'text-gray-600 bg-gray-100 dark:bg-gray-900/20';
    }
};

const handleQuickAction = (action: string) => {
    // Handle quick actions
    console.log('Quick action:', action);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Welcome Section -->
            <div class="rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Welcome back, {{ user?.name }}! 👋</h1>
                        <p class="mt-2 text-blue-100">Here's what's happening with your application today.</p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="flex items-center space-x-2 text-blue-100">
                            <Clock class="h-4 w-4" />
                            <span>{{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <Card 
                    v-for="stat in stats" 
                    :key="stat.title"
                    class="group cursor-pointer transition-all duration-300 hover:shadow-lg hover:scale-105"
                >
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">
                                    {{ stat.title }}
                                </p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">
                                    {{ stat.value }}
                                </p>
                                <div class="flex items-center mt-2">
                                    <Badge 
                                        :variant="stat.changeType === 'positive' ? 'default' : 'destructive'"
                                        class="text-xs"
                                    >
                                        {{ stat.change }}
                                    </Badge>
                                    <span class="ml-2 text-xs text-slate-500 dark:text-slate-400">
                                        from last month
                                    </span>
                                </div>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                <component :is="stat.icon" class="h-6 w-6 text-slate-600 dark:text-slate-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <Settings class="h-5 w-5" />
                        <span>Quick Actions</span>
                    </CardTitle>
                    <CardDescription>
                        Common tasks and shortcuts for quick access
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <Button
                            v-for="action in quickActions"
                            :key="action.title"
                            variant="outline"
                            class="h-20 flex-col space-y-2 p-4 hover:bg-slate-50 dark:hover:bg-slate-800"
                            @click="handleQuickAction(action.action)"
                        >
                            <component :is="action.icon" class="h-6 w-6" />
                            <span class="text-sm font-medium">{{ action.title }}</span>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Recent Activity and User Profile -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Recent Activity -->
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle class="flex items-center space-x-2">
                                    <Activity class="h-5 w-5" />
                                    <span>Recent Activity</span>
                                </CardTitle>
                                <CardDescription>
                                    Latest actions and system events
                                </CardDescription>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    <input
                                        v-model="searchQuery"
                                        type="text"
                                        placeholder="Search activities..."
                                        class="h-9 w-48 rounded-md border border-slate-300 bg-white pl-10 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                                    />
                                </div>
                                <Button variant="outline" size="sm">
                                    <Filter class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div 
                                v-for="activity in filteredActivities" 
                                :key="`${activity.user}-${activity.time}`"
                                class="flex items-center space-x-3 rounded-lg p-3 hover:bg-slate-50 dark:hover:bg-slate-800"
                            >
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700">
                                    <component :is="getActivityIcon(activity.type)" class="h-4 w-4 text-slate-600 dark:text-slate-400" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">
                                        {{ activity.user }}
                                    </p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ activity.action }}
                                    </p>
                                </div>
                                <Badge :class="getActivityColor(activity.type)" variant="secondary">
                                    {{ activity.type }}
                                </Badge>
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ activity.time }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <Button variant="outline" size="sm">
                                View All Activities
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- User Profile Summary -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <UserCheck class="h-5 w-5" />
                            <span>Profile Summary</span>
                        </CardTitle>
                        <CardDescription>
                            Your account information and status
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                                    <span class="text-lg font-bold">{{ user?.name?.charAt(0)?.toUpperCase() }}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ user?.name }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ user?.email }}</p>
                                    <div class="mt-1 flex items-center space-x-2">
                                        <Badge :variant="user?.role === 'admin' ? 'default' : 'secondary'">
                                            {{ user?.role }}
                                        </Badge>
                                        <Badge variant="outline" class="text-green-600 border-green-200">
                                            Active
                                        </Badge>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center space-x-3 text-sm">
                                    <Mail class="h-4 w-4 text-slate-400" />
                                    <span class="text-slate-600 dark:text-slate-400">{{ user?.email }}</span>
                                </div>
                                <div v-if="user?.phone" class="flex items-center space-x-3 text-sm">
                                    <Phone class="h-4 w-4 text-slate-400" />
                                    <span class="text-slate-600 dark:text-slate-400">{{ user?.phone }}</span>
                                </div>
                                <div v-if="user?.location" class="flex items-center space-x-3 text-sm">
                                    <MapPin class="h-4 w-4 text-slate-400" />
                                    <span class="text-slate-600 dark:text-slate-400">{{ user?.location }}</span>
                                </div>
                                <div class="flex items-center space-x-3 text-sm">
                                    <Calendar class="h-4 w-4 text-slate-400" />
                                    <span class="text-slate-600 dark:text-slate-400">
                                        Joined {{ new Date(user?.created_at).toLocaleDateString() }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-4">
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-400">Profile Completion</span>
                                    <span class="font-medium text-slate-900 dark:text-white">85%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                                    <div class="h-2 w-4/5 rounded-full bg-gradient-to-r from-blue-600 to-purple-600"></div>
                                </div>
                            </div>

                            <div class="flex space-x-2 pt-2">
                                <Button variant="outline" size="sm" class="flex-1">
                                    <Eye class="mr-2 h-4 w-4" />
                                    View Profile
                                </Button>
                                <Button variant="outline" size="sm" class="flex-1">
                                    <Edit class="mr-2 h-4 w-4" />
                                    Edit Profile
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- System Status -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <Shield class="h-5 w-5" />
                        <span>System Status</span>
                    </CardTitle>
                    <CardDescription>
                        Current system health and performance metrics
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex items-center space-x-2">
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                <span class="text-sm font-medium text-slate-900 dark:text-white">Database</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">All systems operational</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex items-center space-x-2">
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                <span class="text-sm font-medium text-slate-900 dark:text-white">API</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Response time: 45ms</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex items-center space-x-2">
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                <span class="text-sm font-medium text-slate-900 dark:text-white">Storage</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">85% available</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
