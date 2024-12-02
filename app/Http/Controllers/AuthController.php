<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonException;
use App\Http\Controllers\AppBaseController;
use App\Models\Employees;
use App\Models\Users;
use App\Services\GeneralService;
use Illuminate\Http\Request;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use League\OAuth2\Server\Exception\OAuthServerException;
class AuthController extends AppBaseController
{
    private AccessTokenController $accessTokenController;

    public function __construct(AccessTokenController $accessTokenController)
    {
        $this->accessTokenController = $accessTokenController;
    }

    public function login(Request $request)
    {
        try
        {
            $psr17Factory = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
            $psr = $psrHttpFactory->createRequest($request);

            $data = [
                'grant_type' => 'password',
                'client_id' => config('passport.client_id', env('PASSPORT_CLIENT_ID')),
                'client_secret' => config('passport.client_secret', env('PASSPORT_CLIENT_SECRET')),
                'username' => $request->username,
                'password' => $request->password,
                'scope' => ''
            ];

            $modifiedPsr = $psr->withParsedBody($data);
            $this->checkAccess($request->username);

            $tokenResponse = $this->accessTokenController->issueToken($modifiedPsr);
            $json = json_decode($tokenResponse->getContent(), true);

            if ($json)
            {
                $user = Users::getUsers($request->username);
                if ($user)
                {
                    $this->unlockEmployee($user->employee_id);
                }
            }

            return response()->json($json);

        } catch (CommonException | \Exception $ex)
        {
            return $this->handleLoginError($ex->getMessage() ?: 'Invalid username or password.', $request->username);
        }
    }
    private function unlockEmployee($employeeId)
    {
        Employees::where('employeeSystemID', $employeeId)->update(['isLock' => 0]);
    }

    private function checkAccess($username)
    {
        $user = Users::getUsers($username);
        if (!$user)
        {
            GeneralService::sendException('Invalid username or password.');
        }

        $employee = Employees::find($user->employee_id);
        if (!$employee)
        {
            GeneralService::sendException('User not found');
        }

        if ($this->isEmployeeRestricted($employee))
        {
            $message = $employee->isLock >= 4 ? 'Your account is blocked'
                : trans('common.login_failed_the_user_is_not_activated_please_contact_admin');
            GeneralService::sendException($message);
        }
        return true;
    }

    private function isEmployeeRestricted($employee)
    {
        return $employee->discharegedYN
            || !$employee->ActivationFlag
            || $employee->isLock >= 4
            || $employee->empLoginActive != 1
            || $employee->empActive != 1;
    }

    private function handleLoginError($message, $username)
    {
        $user = Users::getUsers($username);
        if (!$user)
        {
            return $this->sendError('Invalid username or password.', 401);
        }
        $employee = Employees::find($user->employee_id);
        if (!$employee)
        {
            return $this->sendError('User not found', 401);

        }

        $employee->increment('isLock');
        $remainingAttempts = max(0, 4 - $employee->isLock);

        $message = $remainingAttempts > 0
            ? "{$message} You have {$remainingAttempts} more attempt(s)"
            : 'Your account is blocked';

        return $this->sendError($message, 401);
    }
}
