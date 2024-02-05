<?php

namespace App\Http\Controllers\User\API;

use App\Models\Permission;
use App\Models\Role;
use App\Services\NavigationService;
use App\Services\User\UserService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController
{
    public $userService = null;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * get authenticated user
     * @param Request $request
     * @return array|\Illuminate\Contracts\Auth\Authenticatable
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function index(Request $request)
    {
        $data =  $this->userService->getUserPermissions($request);

        throw_unless($data, 'Something went wrong!');

        return $data;
    }

    /**
     * change user password
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $user = $request->user('api');

            if (!Hash::check($request->input('current_password'), $user->password)) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'errors' => [
                            'current_password' => [
                                'Current password is incorrect'
                            ]
                        ]
                    ],
                    'message' => 'Current password is incorrect'
                ], 404);
            }

            if (Hash::check($request->input('new_password'), $user->password)) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'errors' => [
                            'new_password' => [
                                'New password cannot be same as the old password'
                            ]
                        ]
                    ],
                    'message' => 'New password cannot be same as the old password'
                ], 404);
            }

            $user->password = bcrypt($request->input('new_password'));
            $user->save();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'The Password has been changed'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $error->getMessage()
            ], 404);
        }
    }

    /**
     * send activation email to user
     * @param Request $request
     * @return JsonResponse
     */
    public function sendActivationEmail(Request $request): JsonResponse {
        $isSend = $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'success'   => $isSend,
            'data'      => $isSend,
            'message'   => 'The activation email has been sent'
        ]);
    }
}
