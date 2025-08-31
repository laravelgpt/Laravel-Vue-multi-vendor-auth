# Laravel Vue Admin Dashboard - API Documentation

## Authentication API Endpoints

This document provides comprehensive documentation for the authentication API endpoints, including user registration, login, password management, and profile management.

## Base URL
```
http://localhost:8000/api
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_token}
```

---

## 🔐 Authentication Endpoints

### 1. User Registration
**POST** `/api/register`

Register a new user account with secure password validation.

#### Request Body
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "MySecurePassword123!",
    "password_confirmation": "MySecurePassword123!"
}
```

#### Response (201 Created)
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "is_active": true,
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer"
}
```

#### Validation Rules
- `name`: Required, string, max 255 characters
- `email`: Required, valid email, unique
- `password`: Required, confirmed, must pass SecurePassword validation
  - Minimum 8 characters
  - Must contain lowercase, uppercase, numbers, and symbols
  - Must not be found in data breaches

#### Error Response (422 Unprocessable Entity)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password has been found in data breaches and cannot be used."]
    }
}
```

---

### 2. User Login
**POST** `/api/login`

Authenticate user and receive access token.

#### Request Body
```json
{
    "email": "john@example.com",
    "password": "MySecurePassword123!"
}
```

#### Response (200 OK)
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "is_active": true,
        "avatar": "https://ui-avatars.com/api/?name=John+Doe&color=7C3AED&background=EBF4FF",
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer"
}
```

#### Error Responses
**Invalid Credentials (422)**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The provided credentials are incorrect."]
    }
}
```

**Account Deactivated (403)**
```json
{
    "message": "Account is deactivated"
}
```

---

### 3. User Logout
**POST** `/api/logout`

Revoke the current access token.

#### Headers
```
Authorization: Bearer {your_token}
```

#### Response (200 OK)
```json
{
    "message": "Successfully logged out"
}
```

---

### 4. Get User Profile
**GET** `/api/user`

Get the authenticated user's profile information.

#### Headers
```
Authorization: Bearer {your_token}
```

#### Response (200 OK)
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "is_active": true,
        "avatar": "https://ui-avatars.com/api/?name=John+Doe&color=7C3AED&background=EBF4FF",
        "phone": "+1234567890",
        "bio": "Software Developer",
        "location": "New York, NY",
        "website": "https://johndoe.com",
        "twitter": "https://twitter.com/johndoe",
        "linkedin": "https://linkedin.com/in/johndoe",
        "github": "https://github.com/johndoe",
        "email_verified_at": "2024-01-01T00:00:00.000000Z",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 5. Update User Profile
**PUT** `/api/profile`

Update the authenticated user's profile information.

#### Headers
```
Authorization: Bearer {your_token}
```

#### Request Body
```json
{
    "name": "John Smith",
    "bio": "Full-stack developer with 5 years of experience",
    "location": "San Francisco, CA",
    "website": "https://johnsmith.dev",
    "twitter": "https://twitter.com/johnsmith",
    "linkedin": "https://linkedin.com/in/johnsmith",
    "github": "https://github.com/johnsmith"
}
```

#### Response (200 OK)
```json
{
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "name": "John Smith",
        "email": "john@example.com",
        "role": "user",
        "is_active": true,
        "avatar": "https://ui-avatars.com/api/?name=John+Smith&color=7C3AED&background=EBF4FF",
        "phone": "+1234567890",
        "bio": "Full-stack developer with 5 years of experience",
        "location": "San Francisco, CA",
        "website": "https://johnsmith.dev",
        "twitter": "https://twitter.com/johnsmith",
        "linkedin": "https://linkedin.com/in/johnsmith",
        "github": "https://github.com/johnsmith",
        "email_verified_at": "2024-01-01T00:00:00.000000Z",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

#### Validation Rules
- `name`: Optional, string, max 255 characters
- `email`: Optional, valid email, unique (excluding current user)
- `phone`: Optional, string, max 20 characters
- `bio`: Optional, string, max 500 characters
- `location`: Optional, string, max 255 characters
- `website`: Optional, valid URL, max 255 characters
- `twitter`: Optional, valid URL, max 255 characters
- `linkedin`: Optional, valid URL, max 255 characters
- `github`: Optional, valid URL, max 255 characters

---

### 6. Change Password
**POST** `/api/change-password`

Change the authenticated user's password.

#### Headers
```
Authorization: Bearer {your_token}
```

#### Request Body
```json
{
    "current_password": "OldPassword123!",
    "password": "NewSecurePassword123!",
    "password_confirmation": "NewSecurePassword123!"
}
```

#### Response (200 OK)
```json
{
    "message": "Password changed successfully"
}
```

#### Error Responses
**Wrong Current Password (400)**
```json
{
    "message": "Current password is incorrect"
}
```

**Weak Password (422)**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "password": ["The password has been found in data breaches and cannot be used."]
    }
}
```

---

### 7. Refresh Token
**POST** `/api/refresh`

Refresh the current access token.

#### Headers
```
Authorization: Bearer {your_token}
```

#### Response (200 OK)
```json
{
    "message": "Token refreshed successfully",
    "token": "2|def456ghi789...",
    "token_type": "Bearer"
}
```

---

### 8. Forgot Password
**POST** `/api/forgot-password`

Send password reset link to user's email.

#### Request Body
```json
{
    "email": "john@example.com"
}
```

#### Response (200 OK)
```json
{
    "message": "Password reset link sent to your email"
}
```

#### Error Response (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field must be a valid email address."]
    }
}
```

---

### 9. Reset Password
**POST** `/api/reset-password`

Reset password using token from email.

#### Request Body
```json
{
    "token": "reset_token_from_email",
    "email": "john@example.com",
    "password": "NewSecurePassword123!",
    "password_confirmation": "NewSecurePassword123!"
}
```

#### Response (200 OK)
```json
{
    "message": "Password reset successfully"
}
```

#### Error Response (400)
```json
{
    "message": "Unable to reset password"
}
```

---

## 🔒 Password Security Endpoints

### 10. Check Password Strength
**POST** `/api/password/check-strength`

Check password strength and breach status in real-time.

#### Request Body
```json
{
    "password": "MyPassword123!"
}
```

#### Response (200 OK)
```json
{
    "score": 65,
    "strength": "Strong",
    "breach_count": 0,
    "feedback": [
        "Consider using a longer password (12+ characters)"
    ]
}
```

### 11. Check Password Breach
**POST** `/api/password/check-breach`

Check if password has been compromised in data breaches.

#### Request Body
```json
{
    "password": "password123"
}
```

#### Response (200 OK)
```json
{
    "compromised": true,
    "breach_count": 12345
}
```

---

## 📝 Usage Examples

### JavaScript/Fetch Example
```javascript
// Register a new user
const registerUser = async (userData) => {
    const response = await fetch('/api/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(userData)
    });
    
    return await response.json();
};

// Login user
const loginUser = async (credentials) => {
    const response = await fetch('/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(credentials)
    });
    
    const data = await response.json();
    
    if (response.ok) {
        // Store token
        localStorage.setItem('auth_token', data.token);
    }
    
    return data;
};

// Get user profile (authenticated)
const getUserProfile = async () => {
    const token = localStorage.getItem('auth_token');
    
    const response = await fetch('/api/user', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
        }
    });
    
    return await response.json();
};

// Update profile (authenticated)
const updateProfile = async (profileData) => {
    const token = localStorage.getItem('auth_token');
    
    const response = await fetch('/api/profile', {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(profileData)
    });
    
    return await response.json();
};
```

### cURL Examples
```bash
# Register user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "MySecurePassword123!",
    "password_confirmation": "MySecurePassword123!"
  }'

# Login user
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "MySecurePassword123!"
  }'

# Get user profile (with token)
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# Update profile (with token)
curl -X PUT http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Smith",
    "bio": "Software Developer"
  }'
```

---

## 🔧 Error Handling

### Common HTTP Status Codes
- `200` - Success
- `201` - Created (Registration)
- `400` - Bad Request
- `401` - Unauthorized (Missing/Invalid token)
- `403` - Forbidden (Account deactivated)
- `422` - Validation Error
- `500` - Server Error

### Error Response Format
```json
{
    "message": "Error description",
    "errors": {
        "field_name": ["Error message"]
    },
    "error_id": "422-20240101-123456-abc123",
    "timestamp": "2024-01-01T12:34:56.789012Z"
}
```

---

## 🛡️ Security Features

### Password Security
- **Real-time breach checking** using Have I Been Pwned API
- **Password strength validation** with multiple criteria
- **Secure password requirements**:
  - Minimum 8 characters
  - Must contain lowercase and uppercase letters
  - Must contain numbers
  - Must contain special characters
  - Must not be found in data breaches

### Token Security
- **Laravel Sanctum** for secure API authentication
- **Bearer token authentication**
- **Token refresh capability**
- **Automatic token revocation on logout**

### Rate Limiting
- **Login attempts** are rate limited
- **API endpoints** have appropriate rate limiting
- **DDoS protection** enabled

---

## 📊 Testing

All API endpoints are thoroughly tested with comprehensive test suites:

```bash
# Run all API tests
php artisan test --filter="AuthApiTest"

# Run specific test
php artisan test --filter="test_user_can_register_via_api"
```

---

## 🔄 Integration with Frontend

The API is designed to work seamlessly with the Vue.js frontend:

- **Real-time password strength checking** during registration
- **Automatic token management** in localStorage
- **Error handling** with user-friendly messages
- **Profile management** with avatar generation
- **Social login integration** (Google, Facebook, GitHub, Apple)

---

## 📞 Support

For API support or questions:
- Check the test files for usage examples
- Review the validation rules for each endpoint
- Ensure proper error handling in your client applications
- Use the password strength endpoints for real-time validation
