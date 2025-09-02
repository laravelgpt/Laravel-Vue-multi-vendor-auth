<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { 
    Card, 
    CardContent, 
    CardDescription, 
    CardHeader, 
    CardTitle 
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { 
    Users, 
    UserPlus, 
    Search, 
    Filter, 
    MoreHorizontal,
    Edit,
    Trash2,
    Eye,
    Shield,
    Mail,
    Phone,
    Calendar,
    MapPin,
    Globe,
    Settings,
    Download,
    RefreshCw,
    Plus,
    X,
    Check,
    AlertCircle
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Users',
        href: '#',
    },
];

// Mock data for demonstration - in real app this would come from API
const users = ref([
    {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        role: 'admin',
        status: 'active',
        phone: '+1 (555) 123-4567',
        location: 'New York, USA',
        joined: '2024-01-15',
        lastLogin: '2024-01-20 10:30 AM',
        avatar: null
    },
    {
        id: 2,
        name: 'Jane Smith',
        email: 'jane@example.com',
        role: 'user',
        status: 'active',
        phone: '+1 (555) 987-6543',
        location: 'Los Angeles, USA',
        joined: '2024-01-10',
        lastLogin: '2024-01-19 2:15 PM',
        avatar: null
    },
    {
        id: 3,
        name: 'Mike Johnson',
        email: 'mike@example.com',
        role: 'user',
        status: 'inactive',
        phone: '+1 (555) 456-7890',
        location: 'Chicago, USA',
        joined: '2024-01-05',
        lastLogin: '2024-01-15 9:45 AM',
        avatar: null
    },
    {
        id: 4,
        name: 'Sarah Wilson',
        email: 'sarah@example.com',
        role: 'moderator',
        status: 'active',
        phone: '+1 (555) 789-0123',
        location: 'Miami, USA',
        joined: '2024-01-12',
        lastLogin: '2024-01-20 11:20 AM',
        avatar: null
    }
]);

const searchQuery = ref('');
const selectedRole = ref('all');
const selectedStatus = ref('all');
const selectedUser = ref(null);
const showUserModal = ref(false);
const showDeleteModal = ref(false);
const showAddUserModal = ref(false);

const userForm = useForm({
    name: '',
    email: '',
    role: 'user',
    phone: '',
    location: '',
    password: '',
    password_confirmation: ''
});

const filteredUsers = computed(() => {
    let filtered = users.value;
    
    if (searchQuery.value) {
        filtered = filtered.filter(user => 
            user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            user.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            user.location.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    if (selectedRole.value !== 'all') {
        filtered = filtered.filter(user => user.role === selectedRole.value);
    }
    
    if (selectedStatus.value !== 'all') {
        filtered = filtered.filter(user => user.status === selectedStatus.value);
    }
    
    return filtered;
});

const getRoleBadgeVariant = (role: string) => {
    switch (role) {
        case 'admin': return 'default';
        case 'moderator': return 'secondary';
        case 'user': return 'outline';
        default: return 'outline';
    }
};

const getStatusBadgeVariant = (status: string) => {
    switch (status) {
        case 'active': return 'default';
        case 'inactive': return 'destructive';
        case 'suspended': return 'secondary';
        default: return 'outline';
    }
};

const getRoleColor = (role: string) => {
    switch (role) {
        case 'admin': return 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400';
        case 'moderator': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400';
        case 'user': return 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
    }
};

const openUserModal = (user: any) => {
    selectedUser.value = user;
    showUserModal.value = true;
};

const openEditModal = (user: any) => {
    userForm.name = user.name;
    userForm.email = user.email;
    userForm.role = user.role;
    userForm.phone = user.phone;
    userForm.location = user.location;
    selectedUser.value = user;
    showAddUserModal.value = true;
};

const openDeleteModal = (user: any) => {
    selectedUser.value = user;
    showDeleteModal.value = true;
};

const deleteUser = () => {
    if (selectedUser.value) {
        // In real app, this would be an API call
        users.value = users.value.filter(u => u.id !== selectedUser.value.id);
        showDeleteModal.value = false;
        selectedUser.value = null;
    }
};

const saveUser = () => {
    if (selectedUser.value) {
        // Update existing user
        const index = users.value.findIndex(u => u.id === selectedUser.value.id);
        if (index !== -1) {
            users.value[index] = { ...users.value[index], ...userForm.data() };
        }
    } else {
        // Add new user
        const newUser = {
            id: users.value.length + 1,
            ...userForm.data(),
            status: 'active',
            joined: new Date().toISOString().split('T')[0],
            lastLogin: 'Never',
            avatar: null
        };
        users.value.unshift(newUser);
    }
    
    userForm.reset();
    showAddUserModal.value = false;
    selectedUser.value = null;
};

const exportUsers = () => {
    // In real app, this would export to CSV/Excel
    console.log('Exporting users...');
};

const refreshUsers = () => {
    // In real app, this would refresh from API
    console.log('Refreshing users...');
};

const clearFilters = () => {
    searchQuery.value = '';
    selectedRole.value = 'all';
    selectedStatus.value = 'all';
};
</script>

<template>
    <Head title="User Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">User Management</h1>
                    <p class="text-slate-600 dark:text-slate-400">
                        Manage your application users, roles, and permissions
                    </p>
                </div>
                <div class="flex space-x-2">
                    <Button variant="outline" @click="exportUsers">
                        <Download class="mr-2 h-4 w-4" />
                        Export
                    </Button>
                    <Button variant="outline" @click="refreshUsers">
                        <RefreshCw class="mr-2 h-4 w-4" />
                        Refresh
                    </Button>
                    <Button @click="showAddUserModal = true">
                        <UserPlus class="mr-2 h-4 w-4" />
                        Add User
                    </Button>
                </div>
            </div>

            <!-- Filters and Search -->
            <Card>
                <CardContent class="p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-1 items-center space-x-4">
                            <div class="relative flex-1 max-w-sm">
                                <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <Input
                                    v-model="searchQuery"
                                    placeholder="Search users..."
                                    class="pl-10"
                                />
                            </div>
                            <select
                                v-model="selectedRole"
                                class="rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                            >
                                <option value="all">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="moderator">Moderator</option>
                                <option value="user">User</option>
                            </select>
                            <select
                                v-model="selectedStatus"
                                class="rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                            >
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <Button variant="outline" @click="clearFilters">
                            <X class="mr-2 h-4 w-4" />
                            Clear Filters
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Users Table -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <Users class="h-5 w-5" />
                        <span>Users ({{ filteredUsers.length }})</span>
                    </CardTitle>
                    <CardDescription>
                        Manage user accounts, roles, and permissions
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-900 dark:text-white">User</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-900 dark:text-white">Role</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-900 dark:text-white">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-900 dark:text-white">Location</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-900 dark:text-white">Joined</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-900 dark:text-white">Last Login</th>
                                    <th class="px-4 py-3 text-right text-sm font-medium text-slate-900 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr 
                                    v-for="user in filteredUsers" 
                                    :key="user.id"
                                    class="border-b border-slate-100 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"
                                >
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                                                <span class="text-sm font-bold">{{ user.name.charAt(0).toUpperCase() }}</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-slate-900 dark:text-white">{{ user.name }}</div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">{{ user.email }}</div>
                                                <div v-if="user.phone" class="text-sm text-slate-500 dark:text-slate-400">{{ user.phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <Badge :variant="getRoleBadgeVariant(user.role)" :class="getRoleColor(user.role)">
                                            {{ user.role }}
                                        </Badge>
                                    </td>
                                    <td class="px-4 py-4">
                                        <Badge :variant="getStatusBadgeVariant(user.status)">
                                            {{ user.status }}
                                        </Badge>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2 text-sm text-slate-600 dark:text-slate-400">
                                            <MapPin class="h-4 w-4" />
                                            <span>{{ user.location }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ new Date(user.joined).toLocaleDateString() }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ user.lastLogin }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <Button variant="outline" size="sm" @click="openUserModal(user)">
                                                <Eye class="h-4 w-4" />
                                            </Button>
                                            <Button variant="outline" size="sm" @click="openEditModal(user)">
                                                <Edit class="h-4 w-4" />
                                            </Button>
                                            <Button variant="outline" size="sm" @click="openDeleteModal(user)">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- User Detail Modal -->
        <div v-if="showUserModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="mx-4 w-full max-w-2xl rounded-lg bg-white p-6 dark:bg-slate-800">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">User Details</h3>
                    <Button variant="outline" size="sm" @click="showUserModal = false">
                        <X class="h-4 w-4" />
                    </Button>
                </div>
                
                <div v-if="selectedUser" class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                            <span class="text-2xl font-bold">{{ selectedUser.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-slate-900 dark:text-white">{{ selectedUser.name }}</h4>
                            <p class="text-slate-600 dark:text-slate-400">{{ selectedUser.email }}</p>
                            <div class="mt-2 flex space-x-2">
                                <Badge :variant="getRoleBadgeVariant(selectedUser.role)" :class="getRoleColor(selectedUser.role)">
                                    {{ selectedUser.role }}
                                </Badge>
                                <Badge :variant="getStatusBadgeVariant(selectedUser.status)">
                                    {{ selectedUser.status }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex items-center space-x-3">
                            <Mail class="h-4 w-4 text-slate-400" />
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ selectedUser.email }}</span>
                        </div>
                        <div v-if="selectedUser.phone" class="flex items-center space-x-3">
                            <Phone class="h-4 w-4 text-slate-400" />
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ selectedUser.phone }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <MapPin class="h-4 w-4 text-slate-400" />
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ selectedUser.location }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <Calendar class="h-4 w-4 text-slate-400" />
                            <span class="text-sm text-slate-600 dark:text-slate-400">
                                Joined {{ new Date(selectedUser.joined).toLocaleDateString() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit User Modal -->
        <div v-if="showAddUserModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="mx-4 w-full max-w-2xl rounded-lg bg-white p-6 dark:bg-slate-800">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ selectedUser ? 'Edit User' : 'Add New User' }}
                    </h3>
                    <Button variant="outline" size="sm" @click="showAddUserModal = false">
                        <X class="h-4 w-4" />
                    </Button>
                </div>
                
                <form @submit.prevent="saveUser" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <Label for="name">Full Name</Label>
                            <Input
                                id="name"
                                v-model="userForm.name"
                                type="text"
                                required
                                class="mt-1"
                            />
                        </div>
                        <div>
                            <Label for="email">Email</Label>
                            <Input
                                id="email"
                                v-model="userForm.email"
                                type="email"
                                required
                                class="mt-1"
                            />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <Label for="role">Role</Label>
                            <select
                                id="role"
                                v-model="userForm.role"
                                class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                            >
                                <option value="user">User</option>
                                <option value="moderator">Moderator</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <Label for="phone">Phone</Label>
                            <Input
                                id="phone"
                                v-model="userForm.phone"
                                type="tel"
                                class="mt-1"
                            />
                        </div>
                    </div>
                    
                    <div>
                        <Label for="location">Location</Label>
                        <Input
                            id="location"
                            v-model="userForm.location"
                            type="text"
                            class="mt-1"
                            placeholder="City, Country"
                        />
                    </div>
                    
                    <div v-if="!selectedUser" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <Label for="password">Password</Label>
                            <Input
                                id="password"
                                v-model="userForm.password"
                                type="password"
                                required
                                class="mt-1"
                            />
                        </div>
                        <div>
                            <Label for="password_confirmation">Confirm Password</Label>
                            <Input
                                id="password_confirmation"
                                v-model="userForm.password_confirmation"
                                type="password"
                                required
                                class="mt-1"
                            />
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4">
                        <Button variant="outline" @click="showAddUserModal = false">
                            Cancel
                        </Button>
                        <Button type="submit">
                            {{ selectedUser ? 'Update User' : 'Add User' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6 dark:bg-slate-800">
                <div class="mb-6 flex items-center space-x-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
                        <AlertCircle class="h-6 w-6 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Delete User</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Are you sure you want to delete this user? This action cannot be undone.
                        </p>
                    </div>
                </div>
                
                <div v-if="selectedUser" class="mb-6 rounded-lg bg-slate-50 p-4 dark:bg-slate-700">
                    <p class="text-sm text-slate-900 dark:text-white">
                        <strong>{{ selectedUser.name }}</strong> ({{ selectedUser.email }})
                    </p>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <Button variant="outline" @click="showDeleteModal = false">
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="deleteUser">
                        Delete User
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
