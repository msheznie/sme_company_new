<?php

namespace App\Http\Controllers\User\API;

use App\Http\Controllers\Controller;
use App\Mail\EmailForQueuing;
use App\Models\User;
use App\Services\User\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class ResetPasswordController extends Controller
{
    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    /**
     * send password reset link
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse {
        $request->validate(['email' => 'required|exists:users,email']);
        $input = $request->all();

        $user = User::where('email', $input['email']['email'])->first();
        $token = Password::getRepository()->create($user);

        Log::info('API Email send start');
        $loginUrl = env('FRONTEND_URL').'/reset-password/'.$token. '/'. $input['email']['email'];
        Mail::to($input['email']['email'])->send(new EmailForQueuing("Reset Password Notification", "Dear Supplier,"."<br /><br />"." You are receiving this email because we received a password reset request for your account. "."<br /><br />"."Click Here: "."</b><a href='".$loginUrl."'>".$loginUrl."</a><br /><br /> This password reset link will expire in 60 minutes. <br /><br /> If you did not request a password reset, no further action is required. <br /><br />"." Thank You"."<br /><br /><b>"));
        Log::info('API email sent success fully to :' . $input['email']['email']);
        Log::info('API Email send end');

        return response()->json([
            'success' => 'true',
            'data' => $input['email']['email'],
            'message' => 'The password reset link has been sent to the request email'
        ]);
    }

    /**
     * send password reset link
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @throws Throwable
     */
    public function update(Request $request): JsonResponse {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $response = [];
        $message = 'The Password has been reset';
        $status = 404;

        $isSend = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        if($isSend === Password::PASSWORD_RESET){
            $tokenInfo = $this->userService->authenticate([
                'username'      => $request->input('email'),
                'password'      => $request->input('password')
            ]);

            throw_unless($tokenInfo && $tokenInfo->access_token, "Something went wrong!");

            $response['tokenInfo'] = $tokenInfo;
            $status = 200;
        }else{
            $message = 'The Password reset link is expired or invalid!';
            $status = 404;
        }


        return response()->json([
            'success' => $isSend == Password::PASSWORD_RESET,
            'data' => $response,
            'message' => $message
        ], $status);
    }

    public function isValidAttempt(Request $request)
    {
        $requestEmail = $request->only('email');

        $result = DB::table('password_resets')
            ->where('email','=', $requestEmail['email'])
            ->where('created_at','>',Carbon::now()->subHours(1))
            ->first();

        if(is_null($result)){
            return response()->json([
                'success' => false,
                'message' => 'The Password reset link is expired or invalid!'
            ], 412);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'The Password reset link is valid!'
            ], 200);
        }
    }
}
