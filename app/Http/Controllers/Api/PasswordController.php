<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PasswordBreachService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    private PasswordBreachService $breachService;

    public function __construct(PasswordBreachService $breachService)
    {
        $this->breachService = $breachService;
    }

    /**
     * Check password strength and breach status
     */
    public function checkStrength(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string|max:255',
        ]);

        $password = $request->input('password');
        $strength = $this->breachService->getPasswordStrength($password);

        return response()->json($strength);
    }

    /**
     * Check if password is compromised
     */
    public function checkBreach(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string|max:255',
        ]);

        $password = $request->input('password');
        $isCompromised = $this->breachService->isPasswordCompromised($password);
        $breachCount = $this->breachService->getBreachCount($password);

        return response()->json([
            'compromised' => $isCompromised,
            'breach_count' => $breachCount,
        ]);
    }
}
