# Apple Sign-In Setup Guide

## Overview
This guide will help you set up Apple Sign-In for your Laravel application using the `socialiteproviders/apple` package.

## Prerequisites
1. Apple Developer Account
2. Laravel application with Laravel Socialite installed
3. `socialiteproviders/apple` package (already installed)

## Step 1: Apple Developer Console Setup

### 1.1 Create App ID
1. Go to [Apple Developer Console](https://developer.apple.com/account/)
2. Navigate to "Certificates, Identifiers & Profiles"
3. Click "Identifiers" → "+" → "App IDs"
4. Select "App" and click "Continue"
5. Fill in the details:
   - **Description**: Your app name
   - **Bundle ID**: `com.yourcompany.yourapp` (for web apps, use a reverse domain)
   - **Capabilities**: Enable "Sign In with Apple"
6. Click "Continue" and "Register"

### 1.2 Create Service ID
1. In the same section, click "+" → "Services IDs"
2. Fill in the details:
   - **Description**: Your service name
   - **Identifier**: `com.yourcompany.yourapp.web` (unique identifier)
3. Click "Continue" and "Register"
4. Edit the Service ID and enable "Sign In with Apple"
5. Configure the domain and redirect URL:
   - **Domains**: `yourdomain.com`
   - **Return URLs**: `https://yourdomain.com/auth/apple/callback`

### 1.3 Create Private Key
1. Go to "Keys" section
2. Click "+" → "Sign In with Apple"
3. Fill in the details:
   - **Key Name**: `Sign In with Apple Key`
   - **Key ID**: Note this down (you'll need it)
4. Click "Configure" and select your App ID
5. Click "Continue" and "Register"
6. **Download the .p8 file** (you can only download it once!)

## Step 2: Environment Configuration

Add these variables to your `.env` file:

```env
# Apple Sign-In Configuration
APPLE_CLIENT_ID=com.yourcompany.yourapp.web
APPLE_CLIENT_SECRET=your_client_secret_here
APPLE_REDIRECT_URI=https://yourdomain.com/auth/apple/callback
APPLE_TEAM_ID=your_team_id_here
APPLE_KEY_ID=your_key_id_here
APPLE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----
MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQg+s07iAcuGEu8rxoN
... (your private key content) ...
-----END PRIVATE KEY-----"
```

### 2.1 Generate Client Secret
Apple requires a JWT token as the client secret. You can generate this using:

```php
// In a command or controller
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$payload = [
    'iss' => env('APPLE_TEAM_ID'),
    'iat' => time(),
    'exp' => time() + 86400*180, // 6 months
    'aud' => 'https://appleid.apple.com',
    'sub' => env('APPLE_CLIENT_ID'),
];

$client_secret = JWT::encode($payload, env('APPLE_PRIVATE_KEY'), 'ES256', env('APPLE_KEY_ID'));
```

## Step 3: Install JWT Library (Optional)

If you want to generate client secrets dynamically:

```bash
composer require firebase/php-jwt
```

## Step 4: Update Configuration Files

### 4.1 Services Configuration
The `config/services.php` file has been updated with Apple configuration.

### 4.2 Service Providers
The `bootstrap/providers.php` file has been updated to include the Apple provider.

### 4.3 Routes
The routes in `routes/auth.php` have been updated to support Apple Sign-In.

## Step 5: Frontend Integration

The frontend components (`Login.vue` and `Register.vue`) have been updated to include functional Apple Sign-In buttons.

## Step 6: Testing

### 6.1 Test the Integration
1. Visit your login/register page
2. Click the Apple Sign-In button
3. You should be redirected to Apple's authorization page
4. After authorization, you should be redirected back to your callback URL

### 6.2 Common Issues

#### Issue: "Invalid client" error
- Check that your `APPLE_CLIENT_ID` matches your Service ID
- Ensure your domain is properly configured in Apple Developer Console

#### Issue: "Invalid redirect URI" error
- Verify your `APPLE_REDIRECT_URI` matches exactly what's configured in Apple Developer Console
- Check for trailing slashes or protocol mismatches

#### Issue: "Invalid client secret" error
- Ensure your private key is properly formatted in the `.env` file
- Check that your `APPLE_TEAM_ID` and `APPLE_KEY_ID` are correct

## Step 7: Production Deployment

### 7.1 Update Production Environment
1. Update your production `.env` file with the correct Apple credentials
2. Ensure your production domain is added to Apple Developer Console
3. Update the redirect URI to use your production domain

### 7.2 Security Considerations
1. Keep your private key secure and never commit it to version control
2. Use environment variables for all sensitive configuration
3. Regularly rotate your Apple private keys
4. Monitor your Apple Sign-In usage in the Apple Developer Console

## Troubleshooting

### Debug Mode
Enable debug mode in your `.env` file to see detailed error messages:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Check Logs
Monitor your Laravel logs for detailed error information:

```bash
tail -f storage/logs/laravel.log
```

### Verify Configuration
You can verify your configuration by checking the routes:

```bash
php artisan route:list --name=social
```

This should show Apple routes along with other social login routes.

## Additional Resources

- [Apple Sign In Documentation](https://developer.apple.com/sign-in-with-apple/)
- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [SocialiteProviders Apple Package](https://socialiteproviders.com/apple/)

## Support

If you encounter issues:
1. Check the Laravel logs for detailed error messages
2. Verify your Apple Developer Console configuration
3. Ensure all environment variables are correctly set
4. Test with a fresh browser session (clear cookies/cache)
