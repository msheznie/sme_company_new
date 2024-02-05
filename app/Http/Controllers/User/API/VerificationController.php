<?php
/**
 * @author Lahiru Dilshan
 * @date 2021-10-21
 */

namespace App\Http\Controllers\User\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class VerificationController extends Controller
{
    /**
     * verify user email
     * @param $user_id
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return view('errors.illustrated-layout')->with([
                'message' => 'Invalid token or token has been expired.',
                'code' => 401
            ]);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect(env('FRONTEND_URL').'/dashboard');
    }

    /**
     * resend verification email
     * @return JsonResponse
     */
    public function resend(): JsonResponse
    {
        if (auth()->guard('api')->user()->hasVerifiedEmail()) {
            return response()->json(["msg" => "Email already verified."], 400);
        }

        auth()->guard('api')->user()->sendEmailVerificationNotification();

        return response()->json(["msg" => "Email verification link sent on your email id"]);
    }
}
