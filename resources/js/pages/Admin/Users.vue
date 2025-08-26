<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { 
    Users, 
    Search, 
    Filter, 
    Plus, 
    MoreVertical, 
    Edit, 
    Trash2, 
    Eye,
    Mail,
    Calendar,
    Shield,
    UserCheck,
    UserX,
    Download,
    Upload
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

// Mock data for demonstration
const users = ref([
    {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        role: 'admin',
        status: 'active',
        avatar: null,
        created_at: '2024-01-15',
        last_login: '2024-01-20 10:30:00'
    },
    {
        id: 2,
        name: 'Jane Smith',
        email: 'jane@example.com',
        role: 'user',
        status: 'active',
        avatar: null,
        created_at: '2024-01-10',
        last_login: '2024-01-19 15:45:00'
    },
    {
        id: 3,
        name: 'Mike Johnson',
        email: 'mike@example.com',
        role: 'user',
        status: 'pending',
        avatar: null,
        created_at: '2024-01-18',
        last_login: null
    },
    {
        id: 4,
        name: 'Sarah Wilson',
        email: 'sarah@example.com',
        role: 'user',
        status: 'active',
        avatar: null,
        created_at: '2024-01-12',
        last_login: '2024-01-20 09:15:00'
    },
    {
        id: 5,
        name: 'David Brown',
        email: 'david@example.com',
        role: 'admin',
        status: 'inactive',
        avatar: null,
        created_at: '2024-01-05',
        last_login: '2024-01-15 14:20:00'
    }
]);

const searchQuery = ref('');
const statusFilter = ref('all');
const roleFilter = ref('all');
const showFilters = ref(false);

// Computed properties
const filteredUsers = computed(() => {
    return users.value.filter(user => {
        const matchesSearch = user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            user.email.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesStatus = statusFilter.value === 'all' || user.status === statusFilter.value;
        const matchesRole = roleFilter.value === 'all' || user.role === roleFilter.value;
        
        return matchesSearch && matchesStatus && matchesRole;
    });
});

const stats = computed(() => {
    const total = users.value.length;
    const active = users.value.filter(u => u.status === 'active').length;
    const admins = users.value.filter(u => u.role === 'admin').length;
    const pending = users.value.filter(u => u.status === 'pending').length;
    
    return { total, active, admins, pending };
});

// Actions
const handleSearch = () => {
    // Search functionality
    console.log('Searching for:', searchQuery.value);
};

const handleFilter = () => {
    showFilters.value = !showFilters.value;
};

const handleExport = () => {
    console.log('Exporting users...');
};

const handleImport = () => {
    console.log('Importing users...');
};

const handleEditUser = (userId: number) => {
    console.log('Editing user:', userId);
};

const handleDeleteUser = (userId: number) => {
    console.log('Deleting user:', userId);
};

const handleViewUser = (userId: number) => {
    console.log('Viewing user:', userId);
};
</script>

<template>
    <div class="space-y-6">
        <Head title="Users Management" />
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Users Management</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-2">Manage user accounts, roles, and permissions</p>
            </div>
            <div class="flex items-center space-x-3 mt-4 sm:mt-0">
                <Button variant="outline" @click="handleImport">
                    <Upload class="w-4 h-4 mr-2" />
                    Import
                </Button>
                <Button variant="outline" @click="handleExport">
                    <Download class="w-4 h-4 mr-2" />
                    Export
                </Button>
                <Button class="bg-gradient-to-r from-purple-600 to-navy-600 hover:from-purple-700 hover:to-navy-700 text-white">
                    <Plus class="w-4 h-4 mr-2" />
                    Add User
                </Button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <Card class="border-purple-200/50 dark:border-purple-700/50">
                <CardContent class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Users</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.total }}</p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                            <Users class="w-6 h-6 text-blue-600" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="border-purple-200/50 dark:border-purple-700/50">
                <CardContent class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Users</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.active }}</p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-50 dark:bg-green-900/20">
                            <UserCheck class="w-6 h-6 text-green-600" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="border-purple-200/50 dark:border-purple-700/50">
                <CardContent class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Administrators</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.admins }}</p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-50 dark:bg-purple-900/20">
                            <Shield class="w-6 h-6 text-purple-600" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="border-purple-200/50 dark:border-purple-700/50">
                <CardContent class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.pending }}</p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-yellow-50 dark:bg-yellow-900/20">
                            <UserX class="w-6 h-6 text-yellow-600" />
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Search and Filters -->
        <Card class="border-purple-200/50 dark:border-purple-700/50">
            <CardContent class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400" />
                        <Input
                            v-model="searchQuery"
                            @keyup.enter="handleSearch"
                            placeholder="Search users..."
                            class="pl-10"
                        />
                    </div>

                    <!-- Filters -->
                    <div class="flex items-center space-x-3">
                        <Button variant="outline" @click="handleFilter" class="flex items-center space-x-2">
                            <Filter class="w-4 h-4" />
                            <span>Filters</span>
                        </Button>
                    </div>
                </div>

                <!-- Filter Options -->
                <div v-if="showFilters" class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                            <select 
                                v-model="statusFilter"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-800 dark:text-white"
                            >
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Role</label>
                            <select 
                                v-model="roleFilter"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-800 dark:text-white"
                            >
                                <option value="all">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Users Table -->
        <Card class="border-purple-200/50 dark:border-purple-700/50">
            <CardHeader>
                <div class="flex items-center justify-between">
                    <div>
                        <CardTitle class="text-xl font-bold text-slate-900 dark:text-white">Users</CardTitle>
                        <CardDescription class="text-slate-600 dark:text-slate-400">
                            Showing {{ filteredUsers.length }} of {{ users.length }} users
                        </CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="text-left py-3 px-4 font-medium text-slate-700 dark:text-slate-300">User</th>
                                <th class="text-left py-3 px-4 font-medium text-slate-700 dark:text-slate-300">Role</th>
                                <th class="text-left py-3 px-4 font-medium text-slate-700 dark:text-slate-300">Status</th>
                                <th class="text-left py-3 px-4 font-medium text-slate-700 dark:text-slate-300">Created</th>
                                <th class="text-left py-3 px-4 font-medium text-slate-700 dark:text-slate-300">Last Login</th>
                                <th class="text-right py-3 px-4 font-medium text-slate-700 dark:text-slate-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                v-for="user in filteredUsers" 
                                :key="user.id"
                                class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                            >
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        <Avatar class="w-10 h-10">
                                            <AvatarFallback class="bg-gradient-to-r from-purple-600 to-navy-600 text-white">
                                                {{ user.name.charAt(0).toUpperCase() }}
                                            </AvatarFallback>
                                        </Avatar>
                                        <div>
                                            <p class="font-medium text-slate-900 dark:text-white">{{ user.name }}</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ user.email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <Badge 
                                        :variant="user.role === 'admin' ? 'default' : 'secondary'"
                                        :class="user.role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : ''"
                                    >
                                        {{ user.role }}
                                    </Badge>
                                </td>
                                <td class="py-4 px-4">
                                    <Badge 
                                        :variant="user.status === 'active' ? 'default' : 'secondary'"
                                        :class="user.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                               user.status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                               'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                    >
                                        {{ user.status }}
                                    </Badge>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-1 text-sm text-slate-500 dark:text-slate-400">
                                        <Calendar class="w-4 h-4" />
                                        <span>{{ new Date(user.created_at).toLocaleDateString() }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div v-if="user.last_login" class="flex items-center space-x-1 text-sm text-slate-500 dark:text-slate-400">
                                        <Mail class="w-4 h-4" />
                                        <span>{{ new Date(user.last_login).toLocaleDateString() }}</span>
                                    </div>
                                    <span v-else class="text-sm text-slate-400 dark:text-slate-500">Never</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-end space-x-2">
                                        <Button 
                                            variant="ghost" 
                                            size="sm"
                                            @click="handleViewUser(user.id)"
                                            class="text-slate-600 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </Button>
                                        <Button 
                                            variant="ghost" 
                                            size="sm"
                                            @click="handleEditUser(user.id)"
                                            class="text-slate-600 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Button>
                                        <Button 
                                            variant="ghost" 
                                            size="sm"
                                            @click="handleDeleteUser(user.id)"
                                            class="text-slate-600 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="filteredUsers.length === 0" class="text-center py-12">
                    <Users class="w-12 h-12 text-slate-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No users found</h3>
                    <p class="text-slate-500 dark:text-slate-400">Try adjusting your search or filter criteria.</p>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
