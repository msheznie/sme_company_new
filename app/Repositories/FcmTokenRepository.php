<?php

namespace App\Repositories;

use App\Models\FcmToken;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class FcmTokenRepository
 * @package App\Repositories
 * @version September 12, 2024, 11:20 am +04
*/

class FcmTokenRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'userID',
        'fcm_token',
        'deviceType'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FcmToken::class;
    }
    public function getPortalRedirectUrl(Request $request)
    {
        $scheme = request()->secure() ? 'https' : 'http';
        $host = $request->getHttpHost();
        $subDomain = explode('.', $host)[0] === 'www' ? explode('.', $host)[1] : explode('.', $host)[0];
        $tenantDomain = explode('-', $subDomain)[0] ?? '';

        return ($tenantDomain !== 'localhost:8000')
            ? "{$scheme}://{$tenantDomain}" . env('APP_DOMAIN') . "/#/home"
            : null;
    }
}
