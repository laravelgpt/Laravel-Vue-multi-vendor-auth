<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { 
    User, 
    Mail, 
    Phone, 
    Shield, 
    Camera, 
    Save, 
    Key, 
    Eye, 
    EyeOff,
    Calendar,
    MapPin,
    Globe,
    Twitter,
    Linkedin,
    Github
} from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const profileForm = useForm({
    name: user.value?.name || '',
    email: user.value?.email || '',
    phone: user.value?.phone || '',
    bio: user.value?.bio || '',
    location: user.value?.location || '',
    website: user.value?.website || '',
    twitter: user.value?.twitter || '',
    linkedin: user.value?.linkedin || '',
    github: user.value?.github || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    profileForm.put('/profile', {
        onSuccess: () => {
            // Show success message
        },
    });
};

const updatePassword = () => {
    passwordForm.put('/profile/password', {
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const toggleConfirmPassword = () => {
    showConfirmPassword.value = !showConfirmPassword.value;
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <Head title="Profile" />
        
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-gradient-to-br from-blue-400/20 to-purple-600/20 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-gradient-to-tr from-indigo-400/20 to-pink-600/20 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 h-60 w-60 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-r from-cyan-400/10 to-blue-600/10 blur-2xl"></div>
        </div>

        <!-- Main Content -->
        <div class="relative min-h-screen p-4 sm:p-6 lg:p-8">
            <div class="max-w-4xl mx-auto space-y-6">
                <!-- Header -->
                <div class="text-center animate-fade-in">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Profile Settings</h1>
                    <p class="text-slate-600 dark:text-slate-400 mt-2">Manage your account settings and preferences</p>
                </div>

                <!-- Profile Overview Card -->
                <Card class="glass card-hover animate-bounce-in">
                    <CardContent class="p-6">
                        <div class="flex items-center space-x-6">
                            <div class="relative">
                                <Avatar class="w-24 h-24 ring-4 ring-white dark:ring-slate-800">
                                    <AvatarImage :src="user?.avatar" />
                                    <AvatarFallback class="bg-gradient-primary text-white text-2xl">
                                        {{ user?.name?.charAt(0)?.toUpperCase() }}
                                    </AvatarFallback>
                                </Avatar>
                                <Button size="sm" variant="outline" class="absolute -bottom-2 -right-2 rounded-full w-8 h-8 p-0">
                                    <Camera class="w-4 h-4" />
                                </Button>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ user?.name }}</h2>
                                    <Badge :variant="user?.role === 'admin' ? 'default' : 'secondary'" 
                                           :class="user?.role === 'admin' ? 'bg-gradient-warning text-white' : ''">
                                        {{ user?.role }}
                                    </Badge>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 mb-3">{{ user?.email }}</p>
                                <div class="flex items-center space-x-4 text-sm text-slate-500 dark:text-slate-400">
                                    <div class="flex items-center space-x-1">
                                        <Calendar class="w-4 h-4" />
                                        <span>Joined {{ new Date(user?.created_at).toLocaleDateString() }}</span>
                                    </div>
                                    <div v-if="user?.phone" class="flex items-center space-x-1">
                                        <Phone class="w-4 h-4" />
                                        <span>{{ user.phone }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Profile Information -->
                    <Card class="glass card-hover animate-slide-in-left">
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <User class="w-5 h-5" />
                                <span>Profile Information</span>
                            </CardTitle>
                            <CardDescription>
                                Update your account's profile information and email address.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div>
                                <Label for="name">Full Name</Label>
                                <Input 
                                    id="name" 
                                    v-model="profileForm.name" 
                                    type="text" 
                                    class="mt-1"
                                    :class="{ 'border-red-500': profileForm.errors.name }"
                                />
                                <p v-if="profileForm.errors.name" class="text-red-500 text-sm mt-1">{{ profileForm.errors.name }}</p>
                            </div>

                            <div>
                                <Label for="email">Email Address</Label>
                                <Input 
                                    id="email" 
                                    v-model="profileForm.email" 
                                    type="email" 
                                    class="mt-1"
                                    :class="{ 'border-red-500': profileForm.errors.email }"
                                />
                                <p v-if="profileForm.errors.email" class="text-red-500 text-sm mt-1">{{ profileForm.errors.email }}</p>
                            </div>

                            <div>
                                <Label for="phone">Phone Number</Label>
                                <Input 
                                    id="phone" 
                                    v-model="profileForm.phone" 
                                    type="tel" 
                                    class="mt-1"
                                    :class="{ 'border-red-500': profileForm.errors.phone }"
                                />
                                <p v-if="profileForm.errors.phone" class="text-red-500 text-sm mt-1">{{ profileForm.errors.phone }}</p>
                            </div>

                            <div>
                                <Label for="bio">Bio</Label>
                                <textarea 
                                    id="bio" 
                                    v-model="profileForm.bio" 
                                    rows="3" 
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-800 dark:text-white"
                                    placeholder="Tell us about yourself..."
                                ></textarea>
                            </div>

                            <div>
                                <Label for="location">Location</Label>
                                <Input 
                                    id="location" 
                                    v-model="profileForm.location" 
                                    type="text" 
                                    class="mt-1"
                                    placeholder="City, Country"
                                />
                            </div>

                            <Button 
                                @click="updateProfile" 
                                :disabled="profileForm.processing"
                                class="w-full btn-gradient"
                            >
                                <Save v-if="!profileForm.processing" class="w-4 h-4 mr-2" />
                                <span v-else class="animate-pulse">Saving...</span>
                                Update Profile
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Change Password -->
                    <Card class="glass card-hover animate-slide-in-right">
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Key class="w-5 h-5" />
                                <span>Change Password</span>
                            </CardTitle>
                            <CardDescription>
                                Ensure your account is using a long, random password to stay secure.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div>
                                <Label for="current_password">Current Password</Label>
                                <div class="relative mt-1">
                                    <Input 
                                        id="current_password" 
                                        v-model="passwordForm.current_password" 
                                        :type="showPassword ? 'text' : 'password'"
                                        :class="{ 'border-red-500': passwordForm.errors.current_password }"
                                    />
                                    <button 
                                        type="button" 
                                        @click="togglePassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                    >
                                        <Eye v-if="!showPassword" class="w-4 h-4" />
                                        <EyeOff v-else class="w-4 h-4" />
                                    </button>
                                </div>
                                <p v-if="passwordForm.errors.current_password" class="text-red-500 text-sm mt-1">{{ passwordForm.errors.current_password }}</p>
                            </div>

                            <div>
                                <Label for="password">New Password</Label>
                                <div class="relative mt-1">
                                    <Input 
                                        id="password" 
                                        v-model="passwordForm.password" 
                                        :type="showPassword ? 'text' : 'password'"
                                        :class="{ 'border-red-500': passwordForm.errors.password }"
                                    />
                                </div>
                                <p v-if="passwordForm.errors.password" class="text-red-500 text-sm mt-1">{{ passwordForm.errors.password }}</p>
                            </div>

                            <div>
                                <Label for="password_confirmation">Confirm New Password</Label>
                                <div class="relative mt-1">
                                    <Input 
                                        id="password_confirmation" 
                                        v-model="passwordForm.password_confirmation" 
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        :class="{ 'border-red-500': passwordForm.errors.password_confirmation }"
                                    />
                                    <button 
                                        type="button" 
                                        @click="toggleConfirmPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                    >
                                        <Eye v-if="!showConfirmPassword" class="w-4 h-4" />
                                        <EyeOff v-else class="w-4 h-4" />
                                    </button>
                                </div>
                                <p v-if="passwordForm.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ passwordForm.errors.password_confirmation }}</p>
                            </div>

                            <Button 
                                @click="updatePassword" 
                                :disabled="passwordForm.processing"
                                class="w-full btn-gradient"
                            >
                                <Key v-if="!passwordForm.processing" class="w-4 h-4 mr-2" />
                                <span v-else class="animate-pulse">Updating...</span>
                                Update Password
                            </Button>
                        </CardContent>
                    </Card>
                </div>

                <!-- Social Links -->
                <Card class="glass card-hover animate-fade-in">
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Globe class="w-5 h-5" />
                            <span>Social Links</span>
                        </CardTitle>
                        <CardDescription>
                            Add your social media profiles to your account.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label for="website">Website</Label>
                                <Input 
                                    id="website" 
                                    v-model="profileForm.website" 
                                    type="url" 
                                    class="mt-1"
                                    placeholder="https://yourwebsite.com"
                                />
                            </div>
                            <div>
                                <Label for="twitter" class="flex items-center space-x-2">
                                    <Twitter class="w-4 h-4 text-blue-400" />
                                    <span>Twitter</span>
                                </Label>
                                <Input 
                                    id="twitter" 
                                    v-model="profileForm.twitter" 
                                    type="text" 
                                    class="mt-1"
                                    placeholder="@username"
                                />
                            </div>
                            <div>
                                <Label for="linkedin" class="flex items-center space-x-2">
                                    <Linkedin class="w-4 h-4 text-blue-600" />
                                    <span>LinkedIn</span>
                                </Label>
                                <Input 
                                    id="linkedin" 
                                    v-model="profileForm.linkedin" 
                                    type="url" 
                                    class="mt-1"
                                    placeholder="https://linkedin.com/in/username"
                                />
                            </div>
                            <div>
                                <Label for="github" class="flex items-center space-x-2">
                                    <Github class="w-4 h-4 text-gray-800 dark:text-gray-200" />
                                    <span>GitHub</span>
                                </Label>
                                <Input 
                                    id="github" 
                                    v-model="profileForm.github" 
                                    type="text" 
                                    class="mt-1"
                                    placeholder="username"
                                />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>

