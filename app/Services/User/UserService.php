<?php
/**
 * @author Lahiru Dilshan
 * @date 2021-10-21
 */

namespace App\Services\User;

use App\Interfaces\UserTenantRepositoryInterface;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenant;
use App\Repositories\UserTenantRepository;
use App\Services\NavigationService;
use App\Services\Shared\TenantService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

define('SUPPLIER_ROLE', 4);

class UserService {
    private $userDefaultRoles = [];

    public function __construct() {
        $this->userDefaultRoles = [SUPPLIER_ROLE];
    }

    /**
     * register user
     * @param $data : [name, email, password]
     * @throws Exception
     * @throws Throwable
     */
    public function registerUser($data): array {

        $response = [];

        $user = $this->createUserAccount($data);

        throw_unless($user, "User couldn't created");

        $response['authUser'] = $user;

        // send verification email
        // $user->sendEmailVerificationNotification();

        $tokenInfo = $this->authenticate([
            'username'      => $data['email'],
            'password'      => $data['password']
        ]);

        throw_unless($tokenInfo->access_token, "Something went wrong!");

        $response['tokenInfo'] = $tokenInfo;

        return $response;
    }

    /**
     * create user account
     * @param $data
     * @return mixed
     */
    public function createUserAccount($data){
        return User::create([
            'uuid' => Str::uuid(),
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => bcrypt($data['password']),
            'registration_number' => $data['registration_number']
        ]);
    }

    /**
     * handle passport authentication
     * @param array $data : [email, password]
     * @return mixed
     * @throws Exception
     */
    public function authenticate(Array $data){
        $tokenRequest = Request::create('/oauth/token', 'post', [
            'grant_type'    => 'password',
            'client_id'     => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'username'      => $data['username'],
            'password'      => $data['password']
        ]);

        return json_decode(app()->handle($tokenRequest)->getContent());
    }

    /**
     * get user's permissions
     * @param $params
     * @return array|Authenticatable
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function getUserPermissions($params) {
        $kycStatus = 0 ;
        $user = $params->user() ?? null;
        $is_bid_tender = 0;
        if(!$user) $user = Auth::user();

        $data = $user;

        throw_unless($user, "Something went wrong!, Auth user couldn't found!");

        $role_id = $user->fetchUserRole->role_id;
        $role = Role::findById($role_id);

        $tenantId = (isset($params['tenantId']) && $params['tenantId'] !='null') ? $params['tenantId'] : 0;

        if($tenantId == 0 && isset($params['apiKey']) && $params['apiKey'] != 'null'){
            $tenant = Tenant::where('api_key',$params['apiKey'])->first();
            if(!empty($tenant)){
                $tenantId = $tenant['id'];
            }
        } else if($tenantId == 0 && $role_id!=1){
            $user_tenant = UserTenant::where('user_id','=',$user->id)
                ->orderBy('id','ASC')
                ->first();
            if(!empty($user_tenant)){
                $tenantId = $user_tenant['tenant_id'];
            }
        }

        $permissions = Permission::select('name')->with(['RoleHasPermission'])
            ->whereHas('RoleHasPermission', function ($q) use ($role_id, $tenantId) {
                $q->where('role_id', $role_id);
                if($role_id != 1) {
                    $q->where('tenant_id', $tenantId);
                }
            })->get();

        $permissions = $permissions->mapWithKeys(function ($item) {
            return [$item['name'] => true];
        });

        $navigations = app()->make(NavigationService::class, ['role' => $role, 'tenantId' => $tenantId])->handle();


        if($role_id!=1){
            $userTenantRepository = new UserTenantRepository();
            $is_bid_tender = $userTenantRepository->getUserTenantData($user->id, $tenantId);
            try{
                $kycStatus = $userTenantRepository->getKycStatus($user->id, $tenantId);
            } catch (\Exception $exception){
                Log::info($exception);
            }
        }

         if($is_bid_tender == 1){
             $navigations = app()->make(NavigationService::class, ['role' => $role, 'tenantId' => $tenantId])->bidTenderHandle();
         }
        $data['permissions'] = $permissions;
        $data['navigations'] = $navigations;
        $data['kycStatus'] = $kycStatus;
        $data['is_bid_tender'] = $is_bid_tender;
        $data['tenant_id'] =  (int)$tenantId;

        return $data;
    }

    /**
     * add default roles to user account
     * @param $user
     * @return bool
     * @throws Throwable
     */
    public function addDefaultRoleToUser($user): bool {
        throw_unless($user, 'User not found!');

        $rows = [];

        foreach ($this->userDefaultRoles as $role) {
            array_push($rows, [
                'user_id' => $user->id,
                'role_id' => $role,
            ]);
        }

        throw_unless($rows, 'User default roles not found');

        return DB::table('role_users')->insert($rows);
    }

    /**
     * check is super admin or not
     * @param $user 'logged user'
     * @throws Throwable
     */
    public function isSuperAdmin($user): bool {
        throw_unless($user, 'User not found');

        $role = $user->fetchUserRole->role;

        if($role && ($role->name === 'Super Admin' || $role->id === 1)) return true;

        return false;
    }
}
