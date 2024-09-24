<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChartOfAccountAPIRequest;
use App\Http\Requests\API\UpdateChartOfAccountAPIRequest;
use App\Models\ChartOfAccount;
use App\Repositories\ChartOfAccountRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ChartOfAccountResource;
use Response;

/**
 * Class ChartOfAccountController
 * @package App\Http\Controllers\API
 */

class ChartOfAccountAPIController extends AppBaseController
{
    /** @var  ChartOfAccountRepository */
    private $chartOfAccountRepository;
    protected $error = 'Chart Of Account not found';

    public function __construct(ChartOfAccountRepository $chartOfAccountRepo)
    {
        $this->chartOfAccountRepository = $chartOfAccountRepo;
    }

    /**
     * Display a listing of the ChartOfAccount.
     * GET|HEAD /chartOfAccounts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $chartOfAccounts = $this->chartOfAccountRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ChartOfAccountResource::collection($chartOfAccounts),
            'Chart Of Accounts retrieved successfully');
    }

    /**
     * Store a newly created ChartOfAccount in storage.
     * POST /chartOfAccounts
     *
     * @param CreateChartOfAccountAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateChartOfAccountAPIRequest $request)
    {
        $input = $request->all();

        $chartOfAccount = $this->chartOfAccountRepository->create($input);

        return $this->sendResponse(new ChartOfAccountResource($chartOfAccount),
            'Chart Of Account saved successfully');
    }

    /**
     * Display the specified ChartOfAccount.
     * GET|HEAD /chartOfAccounts/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ChartOfAccount $chartOfAccount */
        $chartOfAccount = $this->chartOfAccountRepository->find($id);

        if (empty($chartOfAccount))
        {
            return $this->sendError($this->error);
        }

        return $this->sendResponse(new ChartOfAccountResource($chartOfAccount),
            'Chart Of Account retrieved successfully');
    }

    /**
     * Update the specified ChartOfAccount in storage.
     * PUT/PATCH /chartOfAccounts/{id}
     *
     * @param int $id
     * @param UpdateChartOfAccountAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChartOfAccountAPIRequest $request)
    {
        $input = $request->all();

        /** @var ChartOfAccount $chartOfAccount */
        $chartOfAccount = $this->chartOfAccountRepository->find($id);

        if (empty($chartOfAccount))
        {
            return $this->sendError($this->error);
        }

        $chartOfAccount = $this->chartOfAccountRepository->update($input, $id);

        return $this->sendResponse(new ChartOfAccountResource($chartOfAccount), 'ChartOfAccount updated successfully');
    }

    /**
     * Remove the specified ChartOfAccount from storage.
     * DELETE /chartOfAccounts/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ChartOfAccount $chartOfAccount */
        $chartOfAccount = $this->chartOfAccountRepository->find($id);

        if (empty($chartOfAccount))
        {
            return $this->sendError($this->error);
        }

        $chartOfAccount->delete();

        return $this->sendSuccess('Chart Of Account deleted successfully');
    }
}
