<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFcmTokenAPIRequest;
use App\Http\Requests\API\UpdateFcmTokenAPIRequest;
use App\Models\FcmToken;
use App\Repositories\FcmTokenRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FcmTokenResource;
use Response;

/**
 * Class FcmTokenController
 * @package App\Http\Controllers\API
 */

class FcmTokenAPIController extends AppBaseController
{
    /** @var  FcmTokenRepository */
    private $fcmTokenRepository;

    public function __construct(FcmTokenRepository $fcmTokenRepo)
    {
        $this->fcmTokenRepository = $fcmTokenRepo;
    }

    /**
     * Display a listing of the FcmToken.
     * GET|HEAD /fcmTokens
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $fcmTokens = $this->fcmTokenRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FcmTokenResource::collection($fcmTokens), 'Fcm Tokens retrieved successfully');
    }

    /**
     * Store a newly created FcmToken in storage.
     * POST /fcmTokens
     *
     * @param CreateFcmTokenAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFcmTokenAPIRequest $request)
    {
        $input = $request->all();

        $fcmToken = $this->fcmTokenRepository->create($input);

        return $this->sendResponse(new FcmTokenResource($fcmToken), 'Fcm Token saved successfully');
    }

    /**
     * Display the specified FcmToken.
     * GET|HEAD /fcmTokens/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FcmToken $fcmToken */
        $fcmToken = $this->fcmTokenRepository->find($id);

        if (empty($fcmToken))
        {
            return $this->sendError(trans('common.fcm_token_not_found'));
        }

        return $this->sendResponse(new FcmTokenResource($fcmToken), 'Fcm Token retrieved successfully');
    }

    /**
     * Update the specified FcmToken in storage.
     * PUT/PATCH /fcmTokens/{id}
     *
     * @param int $id
     * @param UpdateFcmTokenAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFcmTokenAPIRequest $request)
    {
        $input = $request->all();

        /** @var FcmToken $fcmToken */
        $fcmToken = $this->fcmTokenRepository->find($id);

        if (empty($fcmToken))
        {
            return $this->sendError(trans('common.fcm_token_not_found'));
        }

        $fcmToken = $this->fcmTokenRepository->update($input, $id);

        return $this->sendResponse(new FcmTokenResource($fcmToken), 'FcmToken updated successfully');
    }

    /**
     * Remove the specified FcmToken from storage.
     * DELETE /fcmTokens/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FcmToken $fcmToken */
        $fcmToken = $this->fcmTokenRepository->find($id);

        if (empty($fcmToken))
        {
            return $this->sendError(trans('common.fcm_token_not_found'));
        }

        $fcmToken->delete();

        return $this->sendSuccess('Fcm Token deleted successfully');
    }
    public function getPortalRedirectUrl(Request $request)
    {
        try
        {
            $portalUrl = $this->fcmTokenRepository->getPortalRedirectUrl($request);
            return $this->sendResponse(['portalUrl' => $portalUrl], 'Successfully Redirected to Portal');
        } catch (\Exception $exception)
        {
            return $this->sendError('Something went wrong');
        }
    }
}
