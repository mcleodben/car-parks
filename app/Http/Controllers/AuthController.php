<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        $token = $user->createToken('main')->plainTextToken;

        return response()->json([
            [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
            Response::HTTP_CREATED,
        ]);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Email or password is incorrect.',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return response()->json(
            [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
            Response::HTTP_OK
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
