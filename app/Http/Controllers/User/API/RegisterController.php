<?php
/**
 * @author Lahiru Dilshan
 * @date 2021-10-21
 */

namespace App\Http\Controllers\User\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Services\Shared\SharedService;
use App\Services\User\UserService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller {
    private $userService;
    private $sharedService;

    public function __construct(UserService $userService, SharedService $sharedService){
        $this->userService = $userService;
        $this->sharedService = $sharedService;
    }

    /**
     * register user
     * @param UserStoreRequest $request
     * @return mixed
     */
    public function store(UserStoreRequest $request)
    {

        $email = $request['email'];
        $companyId = $request['companyId'];
        $apiKey = $request->input('apiKey');
        $validation = $this->validateSupplierData($email,$companyId,$apiKey,$request['name']);

        if(!$validation['status']){
            return response()->json([
                'success'   => false,
                'data'      => [],
                'message'   => $validation['message']
            ]);
        }

        return DB::transaction(function() use($request,$email) {
            $response = $this->userService->registerUser([
                'name' => $request['name'],
                'email' => $email,
                'password' => $request['password'],
                'registration_number' => $request['registration_number'],
            ]);

            return response()->json([
                'success'   => true,
                'data'      => $response,
                'message'   => 'User register and authenticated'
            ]);
        });
    }

    public function checkValidationUserCreation($users, $email, $registration_number)
    {
        if (sizeof($users) === 0){
            return [
                'success'   => true,
                'message'   => 'Valid Invitation Link',
                'data'      => null,
                'user_id'   => null
            ];
        }

        foreach($users as $user){
            if($user->email === $email && $user->registration_number === $registration_number){
                return [
                    'success'   => false,
                    'message'   => 'Use Same Login',
                    'data'      => $user->email,
                    'extra'     => 1,
                    'user_id'   => $user->id
                ];
            } else if($user->email != $email && $user->registration_number === $registration_number){
                return [
                    'success'   => false,
                    'message'   => 'User already exist with provided registration number. Do you want to create a new login or use an existing login',
                    'data'      => $user->email,
                    'extra'     => 0,
                    'user_id'   => $user->id
                ];
            } else if($user->email == $email && $user->registration_number != $registration_number){
                return [
                    'success'   => false,
                    'message'   => 'Sorry, This Email has already been used',
                    'data'      => 'Invalid',

                ];
            } else {
                return [
                    'success'   => false,
                    'message'   => 'Sorry, This link has already been used or expired',
                    'data'      => "Invalid"
                ];
            }
        }
    }

    public function validateSupplierData($email,$companyId,$apiKey,$name)
    {
        $nameCount = strlen($name);
        if($nameCount < 4){
            return ['status' => false, 'message' => 'The name must be at least 4 characters'];
        }

        $ERPFormData = $this->sharedService->fetch([
            'url' => env('ERP_ENDPOINT'),
            'method' => 'POST',
            'data' => [
                'api_key'       => $apiKey,
                'request'       => 'GET_SUPPLIER_REGISTRATION_DATA',
                'companyId'     => $companyId
            ]
        ]);
        $emailCollection = collect($ERPFormData->data);
        $emails = $emailCollection->pluck('email')->toArray();

        if (in_array($email, $emails)) {
            return ['status' => false, 'message' => 'Email already exists'];
        }

        return ['status' => true, 'message' => 'Success'];
    }
}
