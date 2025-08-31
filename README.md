# Laravel Vue Admin Dashboard

A modern, full-featured Laravel application with Vue.js frontend, featuring comprehensive admin dashboard, social authentication, OTP login, and beautiful UI design.

## 🚀 **Features**

### **Authentication & Authorization**
- ✅ **Multi-Provider Social Login**: Google, Facebook, GitHub, Apple
- ✅ **OTP Authentication**: Email-based one-time password login/registration
- ✅ **Traditional Authentication**: Email/password login and registration
- ✅ **Role-Based Access Control**: Admin and regular user roles
- ✅ **Password Reset**: Secure password recovery system
- ✅ **Email Verification**: Account verification system

### **Admin Dashboard**
- ✅ **Comprehensive Admin Panel**: Full-featured administration interface
- ✅ **User Management**: View, manage, and control user accounts
- ✅ **Profile Management**: Admin profile settings and updates
- ✅ **Responsive Design**: Mobile-first responsive layout
- ✅ **Real-time Notifications**: Live notification system
- ✅ **Search Functionality**: Global search across admin panel

### **User Features**
- ✅ **Profile Management**: Complete user profile customization
- ✅ **Password Updates**: Secure password change functionality
- ✅ **Account Deletion**: Safe account removal with confirmation
- ✅ **Social Links**: Connect social media accounts
- ✅ **Avatar Support**: Profile picture management

### **UI/UX Design**
- ✅ **Modern Gradient Design**: Purple, navy, and blue color palette
- ✅ **Glass Morphism**: Frosted glass effect components
- ✅ **Dark/Light Mode**: Theme switching capability
- ✅ **2D Animations**: Smooth transitions and micro-interactions
- ✅ **Responsive Layout**: Mobile, tablet, and desktop optimized
- ✅ **Accessibility**: WCAG compliant design

### **Technical Stack**
- ✅ **Laravel 12**: Latest Laravel framework
- ✅ **Vue 3**: Composition API with TypeScript
- ✅ **Inertia.js v2**: Modern SPA without API complexity
- ✅ **Tailwind CSS v4**: Utility-first CSS framework
- ✅ **Pest v4**: Modern PHP testing framework
- ✅ **Laravel Socialite**: Social authentication
- ✅ **JWT Support**: Apple Sign-In integration

## 📋 **Requirements**

- PHP 8.4+
- Composer
- Node.js 18+
- npm or yarn
- SQLite (default) or MySQL/PostgreSQL
- Laravel Herd (recommended) or Laravel Valet

## 🛠️ **Installation**

### **1. Clone the Repository**
```bash
git clone https://github.com/laravelgpt/Laravel-Vue-multi-vendor-auth.git
cd laravel-vue
```

### **2. Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### **3. Environment Setup**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### **4. Database Setup**
```bash
# Create database (SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### **5. Build Assets**
```bash
# Development
npm run dev

# Production
npm run build
```

### **6. Start Development Server**
```bash
# Using Laravel Herd (recommended)
# The site will be available at: http://laravel-vue.test

# Using Laravel's built-in server
php artisan serve
```

## ⚙️ **Configuration**

### **Social Authentication Setup**

#### **Google OAuth**
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add redirect URI: `http://laravel-vue.test/auth/google/callback`
6. Add to `.env`:
```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://laravel-vue.test/auth/google/callback
```

#### **Facebook OAuth**
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app
3. Add Facebook Login product
4. Configure redirect URI: `http://laravel-vue.test/auth/facebook/callback`
5. Add to `.env`:
```env
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
FACEBOOK_REDIRECT_URI=http://laravel-vue.test/auth/facebook/callback
```

#### **GitHub OAuth**
1. Go to [GitHub Settings](https://github.com/settings/developers)
2. Create new OAuth App
3. Set callback URL: `http://laravel-vue.test/auth/github/callback`
4. Add to `.env`:
```env
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI=http://laravel-vue.test/auth/github/callback
```

#### **Apple Sign-In**
1. Go to [Apple Developer Console](https://developer.apple.com/account/)
2. Create App ID with "Sign In with Apple" capability
3. Create Service ID and private key
4. Generate JWT client secret:
```bash
php artisan apple:generate-secret
```
5. Add to `.env`:
```env
APPLE_CLIENT_ID=com.yourcompany.yourapp.web
APPLE_CLIENT_SECRET=your-jwt-client-secret
APPLE_REDIRECT_URI=http://laravel-vue.test/auth/apple/callback
APPLE_TEAM_ID=your-team-id
APPLE_KEY_ID=your-key-id
APPLE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----..."
```

### **Mail Configuration**
```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🧪 **Testing**

### **Run All Tests**
```bash
php artisan test
```

### **Run Specific Test Suites**
```bash
# Authentication tests
php artisan test tests/Feature/Auth/

# Admin tests
php artisan test tests/Feature/Settings/AdminProfileTest.php

# Social login tests
php artisan test tests/Feature/SocialLoginTest.php
```

### **Test Coverage**
- ✅ **Authentication**: Login, registration, password reset
- ✅ **Admin Features**: Dashboard, user management, profile updates
- ✅ **Social Login**: All providers (Google, Facebook, GitHub, Apple)
- ✅ **User Features**: Profile management, password updates
- ✅ **Validation**: Form validation and error handling

## 🛠️ **Security** 

### **Authentication & Authorization**
- ✅ **Multi-Factor Authentication**: OTP-based email verification
- ✅ **Social Authentication**: Secure OAuth 2.0 integration with major providers
- ✅ **Password Security**: Bcrypt hashing with configurable rounds
- ✅ **Session Management**: Secure session handling with CSRF protection
- ✅ **Role-Based Access Control**: Granular permissions system
- ✅ **Account Lockout**: Rate limiting and brute force protection

### **Data Protection**
- ✅ **Input Validation**: Comprehensive form validation with custom rules
- ✅ **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- ✅ **XSS Protection**: Content Security Policy (CSP) headers
- ✅ **CSRF Protection**: Cross-Site Request Forgery token validation
- ✅ **Data Encryption**: Sensitive data encryption at rest
- ✅ **Secure Headers**: Security headers implementation

### **API Security**
- ✅ **Rate Limiting**: Configurable rate limits for API endpoints
- ✅ **Request Validation**: Form request validation classes
- ✅ **Error Handling**: Secure error responses without sensitive data exposure
- ✅ **CORS Configuration**: Cross-Origin Resource Sharing setup
- ✅ **API Authentication**: Token-based authentication for API access

### **Infrastructure Security**
- ✅ **Environment Security**: Secure environment variable management
- ✅ **Database Security**: Prepared statements and connection encryption
- ✅ **File Upload Security**: Secure file upload with validation
- ✅ **Logging Security**: Secure logging without sensitive data exposure
- ✅ **Backup Security**: Encrypted backup storage

### **Compliance & Standards**
- ✅ **OWASP Guidelines**: Following OWASP security best practices
- ✅ **GDPR Compliance**: Data protection and privacy compliance
- ✅ **Security Headers**: Implementation of security headers
- ✅ **HTTPS Enforcement**: SSL/TLS encryption enforcement
- ✅ **Security Auditing**: Regular security audits and vulnerability scanning 

## 🖼️ **Screenshots**

### **Authentication Screens**

#### **Login Screen**
<img width="1920" height="944" alt="image" src="https://github.com/user-attachments/assets/d40cfe57-a79b-41a7-9688-4b0fa9128f9b" />

*Modern login interface with social authentication options and OTP support*

#### **Registration Screen**
<img width="1920" height="950" alt="image" src="https://github.com/user-attachments/assets/72506f83-4620-4197-8b76-1181283ba245" />

## 🎨 **UI Components**
- `AdminLayout.vue` - Main admin layout with sidebar
- `AdminSidebar.vue` - Responsive admin navigation
- `SocialLoginButton.vue` - Social authentication buttons
- `Card.vue` - Reusable card component
- `Input.vue` - Form input component
- `Button.vue` - Button component with variants

### **Design System**
- **Colors**: Purple, navy, and blue gradient palette
- **Typography**: Modern, readable fonts
- **Spacing**: Consistent spacing system
- **Animations**: Smooth transitions and micro-interactions
- **Responsive**: Mobile-first design approach

## 📁 **Project Structure**

```
laravel-vue/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── ProfileController.php
│   │   │   ├── Auth/
│   │   │   │   ├── SocialLoginController.php
│   │   │   │   └── OtpLoginController.php
│   │   │   └── ProfileController.php
│   │   └── Requests/
│   │       ├── PasswordUpdateRequest.php
│   │       └── ProfileUpdateRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   └── OtpCode.php
│   └── Console/Commands/
│       └── GenerateAppleClientSecret.php
├── resources/
│   └── js/
│       ├── components/
│       │   ├── AdminSidebar.vue
│       │   ├── SocialLoginButton.vue
│       │   └── ui/
│       ├── layouts/
│       │   └── AdminLayout.vue
│       └── pages/
│           ├── Admin/
│           │   ├── Dashboard.vue
│           │   ├── Profile.vue
│           │   └── Users.vue
│           ├── auth/
│           │   ├── Login.vue
│           │   ├── Register.vue
│           │   ├── ForgotPassword.vue
│           │   └── ResetPassword.vue
│           └── Profile.vue
├── routes/
│   ├── web.php
│   └── auth.php
├── tests/
│   └── Feature/
│       ├── Auth/
│       ├── Settings/
│       └── SocialLoginTest.php
└── config/
    └── services.php
```

## 🔧 **Development**

### **Code Quality**
```bash
# Format code with Laravel Pint
vendor/bin/pint

# Run tests
php artisan test

# Build assets
npm run build
```

### **Available Commands**
```bash
# Generate Apple Sign-In client secret
php artisan apple:generate-secret

# List all routes
php artisan route:list

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## 🚀 **Deployment**

### **Production Setup**
1. Update environment variables for production
2. Set `APP_ENV=production` and `APP_DEBUG=false`
3. Update social login redirect URIs to production domain
4. Configure production database
5. Set up SSL certificates
6. Build assets: `npm run build`

### **Environment Variables**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Update redirect URIs for production
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
FACEBOOK_REDIRECT_URI=https://yourdomain.com/auth/facebook/callback
GITHUB_REDIRECT_URI=https://yourdomain.com/auth/github/callback
APPLE_REDIRECT_URI=https://yourdomain.com/auth/apple/callback
```

## 📚 **Documentation**

- [Laravel Best Practices](./LARAVEL_BEST_PRACTICES.md) - Comprehensive best practices guide
- [Environment Configuration](./ENV_CONFIG.md) - Complete environment setup guide
- [Apple Sign-In Setup](./APPLE_SIGNIN_SETUP.md) - Detailed Apple Sign-In configuration

## 🤝 **Contributing**

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new features
5. Ensure all tests pass
6. Submit a pull request

## 📄 **License**

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 **Support**

For support and questions:
- Check the documentation files
- Review the test files for usage examples
- Open an issue on GitHub

## 🎯 **Roadmap**

- [ ] Real-time notifications with WebSockets
- [ ] Advanced user analytics dashboard
- [ ] Multi-language support
- [ ] Advanced role permissions system
- [ ] API documentation with OpenAPI
- [ ] Docker containerization
- [ ] CI/CD pipeline setup

---

**Built with ❤️ using Laravel, Vue.js, and Tailwind CSS**
