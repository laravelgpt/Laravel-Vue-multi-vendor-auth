# Laravel Vue Multi-Vendor Authentication System

## Project Overview
A full-featured authentication system built with Laravel and Vue.js, supporting multiple authentication methods and user roles.

## Core Features

### 1. Authentication System
- **Multi-role Support**: Admin and regular user roles
- **Email/Password Login**: Traditional authentication
- **OTP (One-Time Password) Login**: Phone-based authentication
- **Social Authentication**:
  - Google
  - Facebook
  - GitHub
  - Apple
- **Password Management**:
  - Secure password hashing
  - Password strength validation
  - Breached password detection
  - Password reset functionality
  - Password change functionality

### 2. User Management
- **User Profiles**:
  - Basic info (name, email, username)
  - Contact details (phone, address)
  - Professional details (company, job title)
  - Social media links
  - Profile picture/avatar
- **Account Settings**:
  - Email verification
  - Password management
  - Notification preferences
  - Language and timezone settings

### 3. Security Features
- CSRF protection
- XSS protection
- Secure session management
- Rate limiting for authentication attempts
- Secure password policies
- Breached password checking
- Session management

### 4. API Endpoints
- User registration and authentication
- Profile management
- Password reset and change
- Social authentication
- OTP verification

## Technical Stack

### Backend (Laravel)
- PHP 8.4+
- Laravel 12.x
- Laravel Sanctum for API authentication
- Eloquent ORM
- Database migrations and seeders
- Queue system for background jobs
- RESTful API endpoints
- JSON:API specification compliance

### Frontend (Vue.js)
- Vue 3 with Composition API
- Vue Router for SPA navigation
- State management with Pinia
- Axios for API communication
- Vite for ultra-fast development and building
- Vue 3's `<script setup>` syntax
- Vue 3's `<style scoped>` for component-scoped styles
- Vue 3's Teleport for modals and dialogs
- Vue 3's Suspense for async components
- Vue 3's Fragments and multiple root nodes
- Vue 3's `v-model` updates and arguments

### Database
- MySQL/PostgreSQL/SQLite compatible
- User and authentication tables
- OTP codes table
- Personal access tokens

## Project Structure

```
laravel-vue/
├── app/                  # Laravel application code
│   ├── Http/
│   │   ├── Controllers/  # API Controllers
│   │   └── Middleware/   # API Middleware
│   ├── Models/           # Eloquent models
│   └── Services/         # Business logic services
├── config/              # Configuration files
├── database/            # Migrations, seeders, factories
├── public/              # Public assets and entry point
├── resources/
│   ├── js/
│   │   ├── assets/      # Static assets
│   │   ├── components/  # Reusable Vue components
│   │   │   ├── auth/    # Auth components
│   │   │   ├── common/  # Common UI components
│   │   │   └── layout/  # Layout components
│   │   ├── composables/ # Composable functions
│   │   ├── router/      # Vue Router configuration
│   │   ├── stores/      # Pinia stores
│   │   ├── views/       # Page components
│   │   ├── App.vue      # Root Vue component
│   │   └── main.js      # Application entry point
│   └── views/           # Minimal Blade templates
├── routes/              # API routes
├── tests/               # Test suites
└── vite.config.js       # Vite configuration
```

## Setup Instructions

### Prerequisites
- PHP 8.4 or higher
- Composer 2.5+
- Node.js 22+ and npm 10+/yarn 1.22+
- MySQL 8.0+/PostgreSQL 14+/SQLite 3.37+

### Installation

1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd laravel-vue
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   # or
   yarn
   ```

4. Copy environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure database in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_vue
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

8. Generate JWT secret key:
   ```bash
   php artisan jwt:secret
   ```

9. Configure social login credentials in `.env`:
   ```
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   FACEBOOK_CLIENT_ID=your_facebook_client_id
   FACEBOOK_CLIENT_SECRET=your_facebook_client_secret
   GITHUB_CLIENT_ID=your_github_client_id
   GITHUB_CLIENT_SECRET=your_github_client_secret
   ```

10. Build assets:
    ```bash
    npm run build
    # or for development
    npm run dev
    ```

11. Start the development server:
    ```bash
    php artisan serve
    ```

## Testing

Run PHPUnit tests:
```bash
php artisan test
```

Run JavaScript tests:
```bash
npm test
```

## Deployment

1. Set `APP_ENV=production` in `.env`
2. Generate optimized autoloader:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
3. Cache configuration:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. Build production assets:
   ```bash
   npm run build
   ```

## Environment Variables

Key environment variables to configure:

```
APP_NAME="Laravel Vue Auth"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_vue
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Social Login
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
APPLE_CLIENT_ID=
APPLE_TEAM_ID=
APPLE_KEY_ID=
APPLE_PRIVATE_KEY=
```

## Frontend Architecture

### Component Structure
- **Atomic Design** methodology for component organization
- Reusable base components (buttons, inputs, modals)
- Composable functions for business logic reuse
- Layout components for consistent page structures

### State Management
- **Pinia** for global state management
  - Auth store for user authentication state
  - UI store for application-wide UI state
  - Separate stores for different domains (users, settings, etc.)

### API Integration
- Axios instance with interceptors for auth tokens
- TypeScript interfaces for API responses
- Centralized API service layer
- Error handling and loading states

### Authentication Flow
1. User submits login/register form
2. Vuex action dispatches API request
3. On success, store user data and token in Pinia and localStorage
4. Set Authorization header for subsequent requests
5. Handle token refresh flow
6. Route guards for protected routes

### Performance Optimization
- Code splitting with dynamic imports
- Lazy-loaded routes
- Optimized asset loading with Vite
- Caching strategies for API responses

## API Documentation

### Authentication Endpoints

#### Register a new user
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "securePassword123!",
    "password_confirmation": "securePassword123!"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "securePassword123!"
}
```

#### Get authenticated user
```http
GET /api/user
Authorization: Bearer {token}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

### User Profile Endpoints

#### Update profile
```http
PUT /api/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Updated",
    "email": "john.updated@example.com"
}
```

#### Change password
```http
POST /api/change-password
Authorization: Bearer {token}
Content-Type: application/json

{
    "current_password": "oldPassword123!",
    "password": "newSecurePassword123!",
    "password_confirmation": "newSecurePassword123!"
}
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is open-source and available under the [MIT License](LICENSE).

## Support

For support, please open an issue in the GitHub repository.
