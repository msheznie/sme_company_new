<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCompanyPolicyMasterAPIRequest;
use App\Http\Requests\API\UpdateCompanyPolicyMasterAPIRequest;
use App\Models\CompanyPolicyMaster;
use App\Repositories\CompanyPolicyMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CompanyPolicyMasterResource;
use Response;

/**
 * Class CompanyPolicyMasterController
 * @package App\Http\Controllers\API
 */

class CompanyPolicyMasterAPIController extends AppBaseController
{
    /** @var  CompanyPolicyMasterRepository */
    private $companyPolicyMasterRepository;
    private $notFoundMessage = 'Company Policy Master not found';

    public function __construct(CompanyPolicyMasterRepository $companyPolicyMasterRepo)
    {
        $this->companyPolicyMasterRepository = $companyPolicyMasterRepo;
    }

    /**
     * Display a listing of the CompanyPolicyMaster.
     * GET|HEAD /companyPolicyMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $companyPolicyMasters = $this->companyPolicyMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CompanyPolicyMasterResource::collection($companyPolicyMasters),
            'Company Policy Masters retrieved successfully');
    }

    /**
     * Store a newly created CompanyPolicyMaster in storage.
     * POST /companyPolicyMasters
     *
     * @param CreateCompanyPolicyMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCompanyPolicyMasterAPIRequest $request)
    {
        $input = $request->all();

        $companyPolicyMaster = $this->companyPolicyMasterRepository->create($input);

        return $this->sendResponse(new CompanyPolicyMasterResource($companyPolicyMaster),
            'Company Policy Master saved successfully');
    }

    /**
     * Display the specified CompanyPolicyMaster.
     * GET|HEAD /companyPolicyMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CompanyPolicyMaster $companyPolicyMaster */
        $companyPolicyMaster = $this->companyPolicyMasterRepository->find($id);

        if (empty($companyPolicyMaster))
        {
            return $this->sendError('Company Policy Master not found');
        }

        return $this->sendResponse(new CompanyPolicyMasterResource($companyPolicyMaster),
            'Company Policy Master retrieved successfully');
    }

    /**
     * Update the specified CompanyPolicyMaster in storage.
     * PUT/PATCH /companyPolicyMasters/{id}
     *
     * @param int $id
     * @param UpdateCompanyPolicyMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompanyPolicyMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var CompanyPolicyMaster $companyPolicyMaster */
        $companyPolicyMaster = $this->companyPolicyMasterRepository->find($id);

        if (empty($companyPolicyMaster))
        {
            return $this->sendError($this->notFoundMessage);
        }

        $companyPolicyMaster = $this->companyPolicyMasterRepository->update($input, $id);

        return $this->sendResponse(new CompanyPolicyMasterResource($companyPolicyMaster),
            'CompanyPolicyMaster updated successfully');
    }

    /**
     * Remove the specified CompanyPolicyMaster from storage.
     * DELETE /companyPolicyMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CompanyPolicyMaster $companyPolicyMaster */
        $companyPolicyMaster = $this->companyPolicyMasterRepository->find($id);

        if (empty($companyPolicyMaster))
        {
            return $this->sendError($this->notFoundMessage);
        }

        $companyPolicyMaster->delete();

        return $this->sendSuccess('Company Policy Master deleted successfully');
    }
}
