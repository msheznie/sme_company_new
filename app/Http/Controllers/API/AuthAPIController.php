<?php

namespace App\Http\Controllers\API;

use App\Helpers\General;
use App\Models\Employees;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use \Laravel\Passport\Http\Controllers\AccessTokenController as PassportAccessTokenController;
use Response;
use InfyOm\Generator\Utils\ResponseUtil;

class AuthAPIController extends PassportAccessTokenController
{
    public function auth(ServerRequestInterface $request, Request $request2){
        $user = Users::where('email',$request2->username)->first();
        if($user) {
            $employees = Employees::find($user->employee_id);

            if(empty($employees)){
                return Response::json(ResponseUtil::makeError('User not found',array('type' => '')), 401);
            }

            if($employees->discharegedYN){
                return Response::json(ResponseUtil::makeError('Login failed! The user is discharged. Please contact admin.',array('type' => '')), 401);
            }

            if(!$employees->ActivationFlag){
                return Response::json(ResponseUtil::makeError('Login failed! The user is not activated. Please contact admin.',array('type' => '')), 401);
            }

            if($employees->isLock == 4){
                return Response::json(ResponseUtil::makeError('Your account is blocked',array('type' => '')), 401);
            }

            if($employees->empLoginActive != 1){
                return Response::json(ResponseUtil::makeError('Login failed! The user is not activated. Please contact admin.',array('type' => '')), 401);
            }

            if($employees->empActive != 1){
                return Response::json(ResponseUtil::makeError('Login failed! The user is not activated. Please contact admin.',array('type' => '')), 401);
            }
        }
        try {
            $response = $this->server->respondToAccessTokenRequest($request, new \GuzzleHttp\Psr7\Response);
            if($response){
                $user = Users::where('email',$request2->username)->first();
                if($user){
                    Employees::find($user->employee_id)->update(['isLock' => 0]);
                }
            }
            return $response;
        } catch (OAuthServerException $exception) {
            $user = Users::where('email',$request2->username)->first();
            if($user){
                $employees = Employees::find($user->employee_id);

                if(empty($employees)){
                    return Response::json(ResponseUtil::makeError('User not found',array('type' => '')), 401);
                }

                if($employees->discharegedYN){
                    return Response::json(ResponseUtil::makeError('Login failed! The user is discharged. Please contact admin.',array('type' => '')), 401);
                }

                if(!$employees->ActivationFlag){
                    return Response::json(ResponseUtil::makeError('Login failed! The user is not activated. Please contact admin.',array('type' => '')), 401);
                }

                if($employees->empLoginActive != 1){
                    return Response::json(ResponseUtil::makeError('Login failed! The user is not activated. Please contact admin.',array('type' => '')), 401);
                }

                if($employees->empActive != 1){
                    return Response::json(ResponseUtil::makeError('Login failed! The user is not activated. Please contact admin.',array('type' => '')), 401);
                }

                Employees::find($user->employee_id)->increment('isLock');
            }
            return $this->withErrorHandling(function () use($exception,$user) {

                if($user) {
                    $employees = Employees::find($user->employee_id);
                    $totalAttempt = 4 - $employees->isLock;
                    if ($totalAttempt == 0) {
                        return Response::json(ResponseUtil::makeError('Your account is blocked', array('type' => '')), 401);
                    } else {
                        return response(["message" => 'Invalid username or password. You have ' . $totalAttempt . ' more attempt'], 401);
                    }
                }else{
                    return response(["message" => 'Invalid username or password.'], 401);
                }
            });
        }
    }

    public function authWithToken(ServerRequestInterface $request, Request $request2)
    {

        $input = $request2->all();
        $validator = Validator::make($input, [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json(ResponseUtil::makeError($validator->messages(), array('type' => '')), 422);
        }
        $user = Users::where(['login_token' => $input['token']])->first();
        if (empty($user)) {
            return Response::json(ResponseUtil::makeError('Token expired', array('type' => '')), 500);
        }

        if($user){
            $employees = Employees::find($user->employee_id);

            if(empty($employees)){
                return Response::json(ResponseUtil::makeError('User not found',array('type' => '')), 401);
            }

            if($employees->discharegedYN){
                return Response::json(ResponseUtil::makeError('Login failed! The user is discharged. Please contact admin.',array('type' => '')), 401);
            }

            if(!$employees->ActivationFlag){
                return Response::json(ResponseUtil::makeError('Login failed! The user is not activated. Please contact admin.',array('type' => '')), 401);
            }

            if($employees->isLock == 4){
                return Response::json(ResponseUtil::makeError('Your account is blocked',array('type' => '')), 401);
            }
        }
        try {
            $user->login_token = null;
            $user->save();
            return  $user->createToken('personal');
        } catch (OAuthServerException $exception) {
            return $this->withErrorHandling(function () use($exception) {
                return response(["message" => 'Error'], 401);
            });
        }
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        $attempts = 3;
        $lockoutMinutes = 10;

        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $attempts,
            $lockoutMinutes
        );
    }



}
