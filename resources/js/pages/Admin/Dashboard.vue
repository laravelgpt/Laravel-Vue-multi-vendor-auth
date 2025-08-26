<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { 
    Users, 
    UserCheck, 
    Shield, 
    TrendingUp, 
    Calendar,
    Mail,
    Phone,
    MoreVertical,
    Activity,
    DollarSign,
    Eye
} from 'lucide-vue-next';

interface Props {
    stats: {
        total_users: number;
        active_users: number;
        admin_users: number;
        recent_users: Array<{
            id: number;
            name: string;
            email: string;
            role: string;
            created_at: string;
            avatar?: string;
        }>;
    };
}

defineProps<Props>();
</script>

<template>
    <AdminLayout title="Dashboard">
        <div class="space-y-6">
            <!-- Welcome Section -->
            <div class="bg-gradient-primary rounded-xl p-6 text-white animate-bounce-in">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Welcome back, Admin!</h2>
                        <p class="text-blue-100 mt-1">Here's what's happening with your application today.</p>
                    </div>
                    <div class="hidden md:block">
                        <Activity class="w-16 h-16 text-blue-200" />
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <Card class="glass card-hover animate-scale-in">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-slate-600 dark:text-slate-400">
                            Total Users
                        </CardTitle>
                        <div class="p-2 rounded-lg bg-gradient-primary">
                            <Users class="h-4 w-4 text-white" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ stats.total_users }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            All registered users
                        </p>
                    </CardContent>
                </Card>

                <!-- Active Users -->
                <Card class="glass card-hover animate-scale-in" style="animation-delay: 0.1s;">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-slate-600 dark:text-slate-400">
                            Active Users
                        </CardTitle>
                        <div class="p-2 rounded-lg bg-gradient-success">
                            <UserCheck class="h-4 w-4 text-white" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ stats.active_users }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Currently active accounts
                        </p>
                    </CardContent>
                </Card>

                <!-- Admin Users -->
                <Card class="glass card-hover animate-scale-in" style="animation-delay: 0.2s;">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-slate-600 dark:text-slate-400">
                            Admin Users
                        </CardTitle>
                        <div class="p-2 rounded-lg bg-gradient-warning">
                            <Shield class="h-4 w-4 text-white" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ stats.admin_users }}</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Users with admin privileges
                        </p>
                    </CardContent>
                </Card>

                <!-- Revenue (Mock) -->
                <Card class="glass card-hover animate-scale-in" style="animation-delay: 0.3s;">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-slate-600 dark:text-slate-400">
                            Monthly Revenue
                        </CardTitle>
                        <div class="p-2 rounded-lg bg-gradient-danger">
                            <DollarSign class="h-4 w-4 text-white" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">$12,450</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            +12% from last month
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Recent Users -->
            <Card class="glass card-hover animate-slide-in-right">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="text-xl font-semibold text-slate-900 dark:text-white">Recent Users</CardTitle>
                            <CardDescription class="mt-1">
                                Latest registered users in the system
                            </CardDescription>
                        </div>
                        <Button variant="outline" size="sm" class="hover-lift">
                            <Eye class="w-4 h-4 mr-2" />
                            View All
                        </Button>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div v-for="(user, index) in stats.recent_users" :key="user.id" 
                             class="flex items-center justify-between p-4 rounded-lg border border-slate-200/50 dark:border-slate-700/50 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-all duration-200 hover-lift"
                             :style="{ animationDelay: `${index * 0.1}s` }"
                             :class="'animate-slide-in-left'">
                            <div class="flex items-center space-x-4">
                                <Avatar class="h-10 w-10 ring-2 ring-slate-200 dark:ring-slate-700">
                                    <AvatarImage :src="user.avatar" />
                                    <AvatarFallback class="bg-gradient-primary text-white">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </AvatarFallback>
                                </Avatar>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <p class="font-medium text-slate-900 dark:text-white">{{ user.name }}</p>
                                        <Badge :variant="user.role === 'admin' ? 'default' : 'secondary'" 
                                               :class="user.role === 'admin' ? 'bg-gradient-warning text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300'">
                                            {{ user.role }}
                                        </Badge>
                                    </div>
                                    <div class="flex items-center space-x-4 text-sm text-slate-500 dark:text-slate-400 mt-1">
                                        <div class="flex items-center space-x-1">
                                            <Mail class="h-3 w-3" />
                                            <span>{{ user.email }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <Calendar class="h-3 w-3" />
                                            <span>{{ new Date(user.created_at).toLocaleDateString() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <Button variant="ghost" size="sm" class="hover-scale">
                                <MoreVertical class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
