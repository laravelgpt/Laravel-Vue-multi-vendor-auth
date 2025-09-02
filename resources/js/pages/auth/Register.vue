<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import TextLink from '@/components/TextLink.vue';
import PasswordStrengthMeter from '@/components/PasswordStrengthMeter.vue';
import RegistrationProgress from '@/components/RegistrationProgress.vue';
import { 
    Eye, EyeOff, Mail, Lock, User, Check, Shield, AlertCircle,
    ArrowRight, ArrowLeft, MapPin, Briefcase, Calendar, Phone, Globe, Building, Heart, Code,
    Loader2, XCircle
} from 'lucide-vue-next';
import { ref, computed, watch, nextTick } from 'vue';

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const currentStep = ref(1);
const totalSteps = 6;
const isTransitioning = ref(false);
const stepErrors = ref<Record<number, string[]>>({});

const registrationForm = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    first_name: '',
    last_name: '',
    phone: '',
    date_of_birth: '',
    gender: '',
    address_line_1: '',
    address_line_2: '',
    city: '',
    state: '',
    postal_code: '',
    country: '',
    company: '',
    job_title: '',
    department: '',
    employee_id: '',
    timezone: 'UTC',
    language: 'en',
    notification_preferences: 'email',
    bio: '',
    interests: '',
    skills: '',
    terms: false,
});

const passwordsMatch = computed(() => {
    return registrationForm.password && registrationForm.password_confirmation && 
           registrationForm.password === registrationForm.password_confirmation;
});

const getStepValidationErrors = (step: number): string[] => {
    const errors: string[] = [];
    
    switch (step) {
        case 1:
            if (!registrationForm.name?.trim()) errors.push('Full name is required');
            if (!registrationForm.username?.trim()) errors.push('Username is required');
            if (!registrationForm.email?.trim()) errors.push('Email is required');
            if (!registrationForm.password) errors.push('Password is required');
            if (!registrationForm.password_confirmation) errors.push('Password confirmation is required');
            if (registrationForm.password && registrationForm.password_confirmation && !passwordsMatch.value) {
                errors.push('Passwords do not match');
            }
            break;
        case 2:
            if (!registrationForm.first_name?.trim()) errors.push('First name is required');
            if (!registrationForm.last_name?.trim()) errors.push('Last name is required');
            if (!registrationForm.phone?.trim()) errors.push('Phone number is required');
            break;
        case 3:
            if (!registrationForm.address_line_1?.trim()) errors.push('Address is required');
            if (!registrationForm.city?.trim()) errors.push('City is required');
            if (!registrationForm.country?.trim()) errors.push('Country is required');
            break;
        case 6:
            if (!registrationForm.terms) errors.push('You must agree to the terms and conditions');
            break;
    }
    
    return errors;
};

const canProceedToNextStep = computed(() => {
    const errors = getStepValidationErrors(currentStep.value);
    return errors.length === 0;
});

const canSubmit = computed(() => {
    return canProceedToNextStep.value && currentStep.value === totalSteps;
});

const nextStep = async () => {
    if (!canProceedToNextStep.value || currentStep.value >= totalSteps) return;
    
    isTransitioning.value = true;
    await nextTick();
    
    setTimeout(() => {
        currentStep.value++;
        isTransitioning.value = false;
    }, 300);
};

const previousStep = async () => {
    if (currentStep.value <= 1) return;
    
    isTransitioning.value = true;
    await nextTick();
    
    setTimeout(() => {
        currentStep.value--;
        isTransitioning.value = false;
    }, 300);
};

const submitRegistration = () => {
    if (!canSubmit.value) return;
    
    registrationForm.post('/register', {
        onFinish: () => registrationForm.reset(),
    });
};

const getStepTitle = (step: number) => {
    const titles = [
        'Account Information',
        'Personal Details', 
        'Address Information',
        'Professional Information',
        'Preferences',
        'Review & Submit'
    ];
    return titles[step - 1] || '';
};

const getStepDescription = (step: number) => {
    const descriptions = [
        'Create your account with username and email',
        'Tell us about yourself',
        'Where are you located?',
        'Your professional background',
        'Customize your experience',
        'Review your information and submit'
    ];
    return descriptions[step - 1] || '';
};

// Watch for form errors and update step errors
watch(() => registrationForm.errors, (errors) => {
    if (Object.keys(errors).length > 0) {
        const stepErrorMap: Record<number, string[]> = {};
        
        Object.entries(errors).forEach(([field, error]) => {
            let step = 1;
            
            if (['first_name', 'last_name', 'phone', 'date_of_birth', 'gender'].includes(field)) {
                step = 2;
            } else if (['address_line_1', 'address_line_2', 'city', 'state', 'postal_code', 'country'].includes(field)) {
                step = 3;
            } else if (['company', 'job_title', 'department', 'employee_id'].includes(field)) {
                step = 4;
            } else if (['timezone', 'language', 'notification_preferences', 'bio', 'interests', 'skills'].includes(field)) {
                step = 5;
            }
            
            if (!stepErrorMap[step]) stepErrorMap[step] = [];
            stepErrorMap[step].push(error);
        });
        
        stepErrors.value = { ...stepErrors.value, ...stepErrorMap };
    }
}, { deep: true });

// Update step errors when current step changes
watch(currentStep, () => {
    const errors = getStepValidationErrors(currentStep.value);
    stepErrors.value[currentStep.value] = errors;
});
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-navy-100 dark:from-slate-900 dark:via-purple-900/20 dark:to-navy-900 flex items-center justify-center p-4">
        <Head title="Register" />
        
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-purple-400/40 to-navy-600/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-blue-400/40 to-purple-600/30 rounded-full blur-2xl"></div>
            <div class="absolute top-1/2 left-1/2 w-80 h-80 -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-navy-400/20 to-purple-600/20 rounded-full blur-2xl"></div>
        </div>

        <Card class="w-full max-w-2xl glass-strong border border-purple-200/50 dark:border-purple-700/50 card-hover">
            <CardHeader class="space-y-1 text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 via-navy-600 to-blue-600 rounded-2xl flex items-center justify-center shadow-2xl transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                        <Shield class="w-10 h-10 text-white" />
                    </div>
                </div>
                <CardTitle class="text-3xl font-bold bg-gradient-to-r from-purple-600 via-navy-600 to-blue-600 bg-clip-text text-transparent">
                    {{ getStepTitle(currentStep) }}
                </CardTitle>
                <CardDescription class="text-slate-600 dark:text-slate-400 text-lg">
                    {{ getStepDescription(currentStep) }}
                </CardDescription>
            </CardHeader>

            <CardContent class="space-y-6">
                <RegistrationProgress 
                    :current-step="currentStep" 
                    :total-steps="totalSteps" 
                />

                <div v-if="stepErrors[currentStep] && stepErrors[currentStep].length > 0" 
                     class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center space-x-2 text-red-600 dark:text-red-400 mb-2">
                        <XCircle class="w-5 h-5" />
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                        <li v-for="error in stepErrors[currentStep]" :key="error" class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>{{ error }}</span>
                        </li>
                    </ul>
                </div>

                <div class="step-content" :class="{ 'transitioning': isTransitioning }">
                    <!-- Step 1: Account Information -->
                    <div v-if="currentStep === 1" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="name">Full Name *</Label>
                                <div class="relative">
                                    <User class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="name"
                                        v-model="registrationForm.name"
                                        type="text"
                                        required
                                        class="pl-10 h-12"
                                        placeholder="Enter your full name"
                                    />
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <Label for="username">Username *</Label>
                                <div class="relative">
                                    <User class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="username"
                                        v-model="registrationForm.username"
                                        type="text"
                                        required
                                        class="pl-10 h-12"
                                        placeholder="Choose a username"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="email">Email Address *</Label>
                            <div class="relative">
                                <Mail class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                <Input
                                    id="email"
                                    v-model="registrationForm.email"
                                    type="email"
                                    required
                                    class="pl-10 h-12"
                                    placeholder="Enter your email"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="password">Password *</Label>
                                <div class="relative">
                                    <Lock class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="password"
                                        v-model="registrationForm.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        required
                                        class="pl-10 pr-10 h-12"
                                        placeholder="Create a strong password"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute right-3 top-3 text-slate-400 hover:text-slate-600"
                                    >
                                        <Eye v-if="!showPassword" class="h-5 w-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <PasswordStrengthMeter :password="registrationForm.password" />
                            </div>

                            <div class="space-y-2">
                                <Label for="password_confirmation">Confirm Password *</Label>
                                <div class="relative">
                                    <Lock class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="password_confirmation"
                                        v-model="registrationForm.password_confirmation"
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        required
                                        class="pl-10 pr-10"
                                        :class="[
                                            registrationForm.password_confirmation
                                                ? passwordsMatch
                                                    ? 'border-green-500 focus:border-green-500'
                                                    : 'border-red-500 focus:border-red-500'
                                                : 'border-slate-300 dark:border-slate-600 focus:border-purple-500'
                                        ]"
                                        placeholder="Confirm your password"
                                    />
                                    <button
                                        type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute right-3 top-3 text-slate-400 hover:text-slate-600"
                                    >
                                        <Eye v-if="!showConfirmPassword" class="w-5 h-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <div v-if="registrationForm.password_confirmation" class="flex items-center space-x-2 text-sm mt-2">
                                    <Check v-if="passwordsMatch" class="w-4 h-4 text-green-500" />
                                    <XCircle v-else class="w-4 h-4 text-red-500" />
                                    <span :class="passwordsMatch ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                        {{ passwordsMatch ? 'Passwords match' : 'Passwords do not match' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Personal Details -->
                    <div v-if="currentStep === 2" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label for="first_name">First Name *</Label>
                                <Input
                                    id="first_name"
                                    v-model="registrationForm.first_name"
                                    type="text"
                                    required
                                    placeholder="Enter your first name"
                                />
                            </div>
                            
                            <div>
                                <Label for="last_name">Last Name *</Label>
                                <Input
                                    id="last_name"
                                    v-model="registrationForm.last_name"
                                    type="text"
                                    required
                                    placeholder="Enter your last name"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label for="phone">Phone Number *</Label>
                                <div class="relative">
                                    <Phone class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="phone"
                                        v-model="registrationForm.phone"
                                        type="tel"
                                        required
                                        class="pl-10"
                                        placeholder="Enter your phone number"
                                    />
                                </div>
                            </div>
                            
                            <div>
                                <Label for="date_of_birth">Date of Birth</Label>
                                <div class="relative">
                                    <Calendar class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="date_of_birth"
                                        v-model="registrationForm.date_of_birth"
                                        type="date"
                                        class="pl-10"
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <Label for="gender">Gender</Label>
                            <select
                                id="gender"
                                v-model="registrationForm.gender"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-800 dark:text-white"
                            >
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                                <option value="prefer_not_to_say">Prefer not to say</option>
                            </select>
                        </div>
                    </div>

                    <!-- Step 3: Address Information -->
                    <div v-if="currentStep === 3" class="space-y-4">
                        <div>
                            <Label for="address_line_1">Address Line 1 *</Label>
                            <div class="relative">
                                <MapPin class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                <Input
                                    id="address_line_1"
                                    v-model="registrationForm.address_line_1"
                                    type="text"
                                    required
                                    class="pl-10"
                                    placeholder="Street address, P.O. box, company name"
                                />
                            </div>
                        </div>

                        <div>
                            <Label for="address_line_2">Address Line 2</Label>
                            <Input
                                id="address_line_2"
                                v-model="registrationForm.address_line_2"
                                type="text"
                                placeholder="Apartment, suite, unit, building, floor, etc."
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <Label for="city">City *</Label>
                                <Input
                                    id="city"
                                    v-model="registrationForm.city"
                                    type="text"
                                    required
                                    placeholder="Enter city"
                                />
                            </div>
                            
                            <div>
                                <Label for="state">State/Province</Label>
                                <Input
                                    id="state"
                                    v-model="registrationForm.state"
                                    type="text"
                                    placeholder="Enter state"
                                />
                            </div>
                            
                            <div>
                                <Label for="postal_code">Postal Code</Label>
                                <Input
                                    id="postal_code"
                                    v-model="registrationForm.postal_code"
                                    type="text"
                                    placeholder="Enter postal code"
                                />
                            </div>
                        </div>

                        <div>
                            <Label for="country">Country *</Label>
                            <div class="relative">
                                <Globe class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                <Input
                                    id="country"
                                    v-model="registrationForm.country"
                                    type="text"
                                    required
                                    class="pl-10"
                                    placeholder="Enter country"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Professional Information -->
                    <div v-if="currentStep === 4" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label for="company">Company</Label>
                                <div class="relative">
                                    <Building class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="company"
                                        v-model="registrationForm.company"
                                        type="text"
                                        class="pl-10"
                                        placeholder="Enter company name"
                                    />
                                </div>
                            </div>
                            
                            <div>
                                <Label for="job_title">Job Title</Label>
                                <div class="relative">
                                    <Briefcase class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="job_title"
                                        v-model="registrationForm.job_title"
                                        type="text"
                                        class="pl-10"
                                        placeholder="Enter job title"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label for="department">Department</Label>
                                <Input
                                    id="department"
                                    v-model="registrationForm.department"
                                    type="text"
                                    placeholder="Enter department"
                                />
                            </div>
                            
                            <div>
                                <Label for="employee_id">Employee ID</Label>
                                <Input
                                    id="employee_id"
                                    v-model="registrationForm.employee_id"
                                    type="text"
                                    placeholder="Enter employee ID"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Preferences -->
                    <div v-if="currentStep === 5" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <Label for="timezone">Timezone</Label>
                                <select
                                    id="timezone"
                                    v-model="registrationForm.timezone"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-800 dark:text-white"
                                >
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">Eastern Time</option>
                                    <option value="America/Chicago">Central Time</option>
                                    <option value="America/Denver">Mountain Time</option>
                                    <option value="America/Los_Angeles">Pacific Time</option>
                                    <option value="Europe/London">London</option>
                                    <option value="Europe/Paris">Paris</option>
                                    <option value="Asia/Tokyo">Tokyo</option>
                                </select>
                            </div>
                            
                            <div>
                                <Label for="language">Language</Label>
                                <select
                                    id="language"
                                    v-model="registrationForm.language"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-800 dark:text-white"
                                >
                                    <option value="en">English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                    <option value="de">German</option>
                                    <option value="it">Italian</option>
                                    <option value="pt">Portuguese</option>
                                    <option value="ru">Russian</option>
                                    <option value="ja">Japanese</option>
                                    <option value="ko">Korean</option>
                                    <option value="zh">Chinese</option>
                                </select>
                            </div>
                            
                            <div>
                                <Label for="notification_preferences">Notifications</Label>
                                <select
                                    id="notification_preferences"
                                    v-model="registrationForm.notification_preferences"
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-800 dark:text-white"
                                >
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="push">Push</option>
                                    <option value="all">All</option>
                                    <option value="none">None</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <Label for="bio">Bio</Label>
                            <textarea
                                id="bio"
                                v-model="registrationForm.bio"
                                rows="3"
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-800 dark:text-white"
                                placeholder="Tell us about yourself..."
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label for="interests">Interests</Label>
                                <div class="relative">
                                    <Heart class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="interests"
                                        v-model="registrationForm.interests"
                                        type="text"
                                        class="pl-10"
                                        placeholder="e.g., Reading, Travel, Music"
                                    />
                                </div>
                            </div>
                            
                            <div>
                                <Label for="skills">Skills</Label>
                                <div class="relative">
                                    <Code class="absolute left-3 top-3 h-5 w-5 text-slate-400" />
                                    <Input
                                        id="skills"
                                        v-model="registrationForm.skills"
                                        type="text"
                                        class="pl-10"
                                        placeholder="e.g., JavaScript, Python, Design"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 6: Review & Submit -->
                    <div v-if="currentStep === 6" class="space-y-4">
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4 space-y-3">
                            <h3 class="font-semibold text-lg">Review Your Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-slate-600 dark:text-slate-400">Name:</span>
                                    <p>{{ registrationForm.name }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-600 dark:text-slate-400">Username:</span>
                                    <p>{{ registrationForm.username }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-600 dark:text-slate-400">Email:</span>
                                    <p>{{ registrationForm.email }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-600 dark:text-slate-400">Phone:</span>
                                    <p>{{ registrationForm.phone }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-600 dark:text-slate-400">Full Name:</span>
                                    <p>{{ registrationForm.first_name }} {{ registrationForm.last_name }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-slate-600 dark:text-slate-400">Location:</span>
                                    <p>{{ registrationForm.city }}, {{ registrationForm.country }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input
                                id="terms"
                                v-model="registrationForm.terms"
                                type="checkbox"
                                required
                                class="rounded border-slate-300 text-purple-600 focus:ring-purple-500"
                            />
                            <Label for="terms" class="text-sm">
                                I agree to the 
                                <TextLink href="/terms" class="text-purple-600 hover:text-purple-500">
                                    Terms of Service
                                </TextLink>
                                and
                                <TextLink href="/privacy" class="text-purple-600 hover:text-purple-500">
                                    Privacy Policy
                                </TextLink>
                            </Label>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-6">
                    <Button
                        v-if="currentStep > 1"
                        @click="previousStep"
                        variant="outline"
                        class="flex items-center space-x-2"
                        :disabled="isTransitioning"
                    >
                        <ArrowLeft class="w-4 h-4" />
                        Previous
                    </Button>
                    <div v-else></div>

                    <Button
                        v-if="currentStep < totalSteps"
                        @click="nextStep"
                        :disabled="!canProceedToNextStep || isTransitioning"
                        class="flex items-center space-x-2"
                    >
                        <Loader2 v-if="isTransitioning" class="w-4 h-4 animate-spin" />
                        <span v-else>Next</span>
                        <ArrowRight v-if="!isTransitioning" class="w-4 h-4" />
                    </Button>

                    <Button
                        v-if="currentStep === totalSteps"
                        @click="submitRegistration"
                        :disabled="!canSubmit || registrationForm.processing"
                        class="flex items-center space-x-2"
                    >
                        <Loader2 v-if="registrationForm.processing" class="w-4 h-4 animate-spin" />
                        <Check v-else class="w-4 h-4" />
                        <span>{{ registrationForm.processing ? 'Creating Account...' : 'Create Account' }}</span>
                    </Button>
                </div>

                <!-- Form Error Messages -->
                <div v-if="Object.keys(registrationForm.errors).length > 0" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center space-x-2 text-red-600 dark:text-red-400">
                        <AlertCircle class="w-5 h-5" />
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">
                        <li v-for="error in Object.values(registrationForm.errors)" :key="error" class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>{{ error }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Login Link -->
                <div class="text-center pt-4">
                    <p class="text-slate-600 dark:text-slate-400">
                        Already have an account?
                        <TextLink href="/login" class="text-purple-600 hover:text-purple-500 font-medium">
                            Sign in
                        </TextLink>
                    </p>
                </div>
            </CardContent>
        </Card>
    </div>
</template>

<style scoped>
.glass-strong {
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.step-content {
    min-height: 400px;
    transition: all 0.3s ease;
    position: relative;
}

.step-content.transitioning {
    opacity: 0.5;
    transform: translateX(20px);
}

.step-content > div {
    opacity: 1;
    visibility: visible;
}
</style>
