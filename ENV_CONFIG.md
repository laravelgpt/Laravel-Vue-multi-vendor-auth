# Environment Configuration Guide

## Overview
This guide provides the complete environment configuration for all social login providers in your Laravel application.

## Base Application Configuration

```env
# Application Configuration
APP_NAME="Laravel Vue App"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://laravel-vue.test

# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Social Login Configuration

### Google OAuth Configuration

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

**Setup Instructions:**
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Go to "Credentials" → "Create Credentials" → "OAuth 2.0 Client IDs"
5. Set Application Type to "Web application"
6. Add Authorized redirect URIs: `http://laravel-vue.test/auth/google/callback`
7. Copy Client ID and Client Secret to your `.env` file

### Facebook OAuth Configuration

```env
# Facebook OAuth Configuration
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
FACEBOOK_REDIRECT_URI="${APP_URL}/auth/facebook/callback"
```

**Setup Instructions:**
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app or select existing one
3. Add Facebook Login product
4. Go to "Settings" → "Basic"
5. Copy App ID and App Secret
6. Go to "Facebook Login" → "Settings"
7. Add Valid OAuth Redirect URIs: `http://laravel-vue.test/auth/facebook/callback`
8. Copy values to your `.env` file

### GitHub OAuth Configuration

```env
# GitHub OAuth Configuration
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI="${APP_URL}/auth/github/callback"
```

**Setup Instructions:**
1. Go to [GitHub Settings](https://github.com/settings/developers)
2. Click "New OAuth App"
3. Fill in the details:
   - Application name: Your app name
   - Homepage URL: `http://laravel-vue.test`
   - Authorization callback URL: `http://laravel-vue.test/auth/github/callback`
4. Click "Register application"
5. Copy Client ID and Client Secret to your `.env` file

### Apple Sign-In Configuration

```env
# Apple Sign-In Configuration
APPLE_CLIENT_ID=com.yourcompany.yourapp.web
APPLE_CLIENT_SECRET=your-jwt-client-secret
APPLE_REDIRECT_URI="${APP_URL}/auth/apple/callback"
APPLE_TEAM_ID=your-team-id
APPLE_KEY_ID=your-key-id
APPLE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----
MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQg+s07iAcuGEu8rxoN
... (your private key content) ...
-----END PRIVATE KEY-----"
```

**Setup Instructions:**
1. Go to [Apple Developer Console](https://developer.apple.com/account/)
2. Create App ID with "Sign In with Apple" capability
3. Create Service ID: `com.yourcompany.yourapp.web`
4. Configure domain and redirect URL: `http://laravel-vue.test/auth/apple/callback`
5. Create private key (.p8 file)
6. Generate JWT client secret (see JWT section below)
7. Copy all values to your `.env` file

## JWT Configuration for Apple Sign-In

### Install JWT Library
```bash
composer require firebase/php-jwt
```

### Generate Apple Client Secret
Create a command to generate the JWT client secret:

```bash
php artisan make:command GenerateAppleClientSecret
```

Then add this code to the command:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Firebase\JWT\JWT;

class GenerateAppleClientSecret extends Command
{
    protected $signature = 'apple:generate-secret';
    protected $description = 'Generate Apple Sign-In client secret';

    public function handle()
    {
        $payload = [
            'iss' => env('APPLE_TEAM_ID'),
            'iat' => time(),
            'exp' => time() + 86400*180, // 6 months
            'aud' => 'https://appleid.apple.com',
            'sub' => env('APPLE_CLIENT_ID'),
        ];

        $client_secret = JWT::encode($payload, env('APPLE_PRIVATE_KEY'), 'ES256', env('APPLE_KEY_ID'));
        
        $this->info('Apple Client Secret:');
        $this->line($client_secret);
        $this->info('Add this to your .env file as APPLE_CLIENT_SECRET');
    }
}
```

Run the command:
```bash
php artisan apple:generate-secret
```

## Complete .env File Example

```env
# Application Configuration
APP_NAME="Laravel Vue App"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://laravel-vue.test

# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# Facebook OAuth Configuration
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
FACEBOOK_REDIRECT_URI="${APP_URL}/auth/facebook/callback"

# GitHub OAuth Configuration
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI="${APP_URL}/auth/github/callback"

# Apple Sign-In Configuration
APPLE_CLIENT_ID=com.yourcompany.yourapp.web
APPLE_CLIENT_SECRET=your-jwt-client-secret
APPLE_REDIRECT_URI="${APP_URL}/auth/apple/callback"
APPLE_TEAM_ID=your-team-id
APPLE_KEY_ID=your-key-id
APPLE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----
MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQg+s07iAcuGEu8rxoN
... (your private key content) ...
-----END PRIVATE KEY-----"
```

## Production Configuration

For production, update these values:

```env
# Production Settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Update redirect URIs for production
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
FACEBOOK_REDIRECT_URI=https://yourdomain.com/auth/facebook/callback
GITHUB_REDIRECT_URI=https://yourdomain.com/auth/github/callback
APPLE_REDIRECT_URI=https://yourdomain.com/auth/apple/callback
```

## Security Best Practices

1. **Never commit `.env` file** to version control
2. **Use strong, unique secrets** for each provider
3. **Rotate secrets regularly** (especially Apple private keys)
4. **Use HTTPS in production** for all redirect URIs
5. **Validate redirect URIs** in provider developer consoles
6. **Monitor OAuth usage** in provider dashboards

## Testing Configuration

### Local Development
```env
APP_URL=http://laravel-vue.test
```

### Testing with Laravel Herd
```env
APP_URL=https://laravel-vue.test
```

### Testing with Laravel Valet
```env
APP_URL=https://laravel-vue.test
```

## Troubleshooting

### Common Issues

1. **"Invalid redirect URI" error**
   - Check that redirect URIs match exactly in provider settings
   - Ensure no trailing slashes or protocol mismatches

2. **"Invalid client" error**
   - Verify Client ID and Client Secret are correct
   - Check that domain is properly configured in provider console

3. **"Invalid client secret" error (Apple)**
   - Ensure private key is properly formatted
   - Check that Team ID and Key ID are correct
   - Verify JWT client secret is valid

### Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

## Verification Commands

### Check Routes
```bash
php artisan route:list --name=auth.
```

### Test Social Login
```bash
php artisan test tests/Feature/SocialLoginTest.php
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Additional Resources

- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Google OAuth Setup](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Setup](https://developers.facebook.com/docs/facebook-login)
- [GitHub OAuth Setup](https://docs.github.com/en/developers/apps/building-oauth-apps)
- [Apple Sign-In Setup](https://developer.apple.com/sign-in-with-apple/)
