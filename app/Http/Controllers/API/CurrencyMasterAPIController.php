<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCurrencyMasterAPIRequest;
use App\Http\Requests\API\UpdateCurrencyMasterAPIRequest;
use App\Models\CurrencyMaster;
use App\Repositories\CurrencyMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CurrencyMasterResource;
use Response;

/**
 * Class CurrencyMasterController
 * @package App\Http\Controllers\API
 */

class CurrencyMasterAPIController extends AppBaseController
{
    /** @var  CurrencyMasterRepository */
    private $currencyMasterRepository;

    public function __construct(CurrencyMasterRepository $currencyMasterRepo)
    {
        $this->currencyMasterRepository = $currencyMasterRepo;
    }

    /**
     * Display a listing of the CurrencyMaster.
     * GET|HEAD /currencyMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $currencyMasters = $this->currencyMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CurrencyMasterResource::collection($currencyMasters), 'Currency Masters retrieved successfully');
    }

    /**
     * Store a newly created CurrencyMaster in storage.
     * POST /currencyMasters
     *
     * @param CreateCurrencyMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCurrencyMasterAPIRequest $request)
    {
        $input = $request->all();

        $currencyMaster = $this->currencyMasterRepository->create($input);

        return $this->sendResponse(new CurrencyMasterResource($currencyMaster), 'Currency Master saved successfully');
    }

    /**
     * Display the specified CurrencyMaster.
     * GET|HEAD /currencyMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CurrencyMaster $currencyMaster */
        $currencyMaster = $this->currencyMasterRepository->find($id);

        if (empty($currencyMaster)) {
            return $this->sendError('Currency Master not found');
        }

        return $this->sendResponse(new CurrencyMasterResource($currencyMaster), 'Currency Master retrieved successfully');
    }

    /**
     * Update the specified CurrencyMaster in storage.
     * PUT/PATCH /currencyMasters/{id}
     *
     * @param int $id
     * @param UpdateCurrencyMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCurrencyMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var CurrencyMaster $currencyMaster */
        $currencyMaster = $this->currencyMasterRepository->find($id);

        if (empty($currencyMaster)) {
            return $this->sendError('Currency Master not found');
        }

        $currencyMaster = $this->currencyMasterRepository->update($input, $id);

        return $this->sendResponse(new CurrencyMasterResource($currencyMaster), 'CurrencyMaster updated successfully');
    }

    /**
     * Remove the specified CurrencyMaster from storage.
     * DELETE /currencyMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CurrencyMaster $currencyMaster */
        $currencyMaster = $this->currencyMasterRepository->find($id);

        if (empty($currencyMaster)) {
            return $this->sendError('Currency Master not found');
        }

        $currencyMaster->delete();

        return $this->sendSuccess('Currency Master deleted successfully');
    }
}
