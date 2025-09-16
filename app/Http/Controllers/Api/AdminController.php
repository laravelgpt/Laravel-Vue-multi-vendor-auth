<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * API Response Helper
     */
    private function apiResponse(string $status, string $message, $data = null, int $code = 200): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Admin Dashboard Data
     */
    public function dashboard(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'regular_users' => User::where('is_admin', false)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'users_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return $this->apiResponse('success', 'Dashboard data retrieved successfully', [
            'stats' => $stats,
        ]);
    }

    /**
     * Get All Users (Admin)
     */
    public function users(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $filter = $request->get('filter'); // admin, regular, verified, unverified

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($filter) {
            switch ($filter) {
                case 'admin':
                    $query->where('is_admin', true);
                    break;
                case 'regular':
                    $query->where('is_admin', false);
                    break;
                case 'verified':
                    $query->whereNotNull('email_verified_at');
                    break;
                case 'unverified':
                    $query->whereNull('email_verified_at');
                    break;
            }
        }

        $users = $query->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        return $this->apiResponse('success', 'Users retrieved successfully', [
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
        ]);
    }

    /**
     * Create User (Admin)
     */
    public function createUser(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $request->boolean('is_admin', false),
            'email_verified_at' => now(),
        ]);

        return $this->apiResponse('success', 'User created successfully', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ], 201);
    }

    /**
     * Get Specific User (Admin)
     */
    public function showUser(User $user): JsonResponse
    {
        return $this->apiResponse('success', 'User retrieved successfully', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    /**
     * Update User (Admin)
     */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$user->id,
            'is_admin' => 'sometimes|boolean',
        ]);

        $user->update($request->only(['name', 'email', 'is_admin']));

        return $this->apiResponse('success', 'User updated successfully', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    /**
     * Delete User (Admin)
     */
    public function deleteUser(User $user): JsonResponse
    {
        // Prevent admins from deleting themselves
        if ($user->id === auth()->id()) {
            return $this->apiResponse('error', 'You cannot delete your own account.', null, 400);
        }

        $user->delete();

        return $this->apiResponse('success', 'User deleted successfully');
    }

    /**
     * Toggle User Status (Admin)
     */
    public function toggleUserStatus(User $user): JsonResponse
    {
        // Prevent admins from changing their own status
        if ($user->id === auth()->id()) {
            return $this->apiResponse('error', 'You cannot change your own admin status.', null, 400);
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        return $this->apiResponse('success', 'User status updated successfully', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
            ],
        ]);
    }

    /**
     * Overview Statistics
     */
    public function overviewStats(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'regular_users' => User::where('is_admin', false)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
        ];

        return $this->apiResponse('success', 'Overview statistics retrieved successfully', [
            'stats' => $stats,
        ]);
    }

    /**
     * User Statistics
     */
    public function userStats(): JsonResponse
    {
        $stats = [
            'users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'users_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'users_yesterday' => User::whereDate('created_at', yesterday())->count(),
        ];

        return $this->apiResponse('success', 'User statistics retrieved successfully', [
            'stats' => $stats,
        ]);
    }

    /**
     * Activity Statistics
     */
    public function activityStats(): JsonResponse
    {
        $stats = [
            'recent_logins' => 0, // This would require a login tracking system
            'active_users' => 0, // This would require activity tracking
            'new_registrations_today' => User::whereDate('created_at', today())->count(),
            'new_registrations_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return $this->apiResponse('success', 'Activity statistics retrieved successfully', [
            'stats' => $stats,
        ]);
    }
}
