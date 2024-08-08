<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFinanceDocumentsAPIRequest;
use App\Http\Requests\API\UpdateFinanceDocumentsAPIRequest;
use App\Models\FinanceDocuments;
use App\Repositories\FinanceDocumentsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FinanceDocumentsResource;
use Response;

/**
 * Class FinanceDocumentsController
 * @package App\Http\Controllers\API
 */

class FinanceDocumentsAPIController extends AppBaseController
{
    /** @var  FinanceDocumentsRepository */
    private $financeDocumentsRepository;
    protected $errorMessage = 'Finance Documents not found';

    public function __construct(FinanceDocumentsRepository $financeDocumentsRepo)
    {
        $this->financeDocumentsRepository = $financeDocumentsRepo;
    }

    /**
     * Display a listing of the FinanceDocuments.
     * GET|HEAD /financeDocuments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $financeDocuments = $this->financeDocumentsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FinanceDocumentsResource::collection($financeDocuments),
            'Finance Documents retrieved successfully');
    }

    /**
     * Store a newly created FinanceDocuments in storage.
     * POST /financeDocuments
     *
     * @param CreateFinanceDocumentsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFinanceDocumentsAPIRequest $request)
    {
        $input = $request->all();

        $financeDocuments = $this->financeDocumentsRepository->create($input);

        return $this->sendResponse(new FinanceDocumentsResource($financeDocuments),
            'Finance Documents saved successfully');
    }

    /**
     * Display the specified FinanceDocuments.
     * GET|HEAD /financeDocuments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FinanceDocuments $financeDocuments */
        $financeDocuments = $this->financeDocumentsRepository->find($id);

        if (empty($financeDocuments))
        {
            return $this->sendError($this->errorMessage);
        }

        return $this->sendResponse(new FinanceDocumentsResource($financeDocuments),
            'Finance Documents retrieved successfully');
    }

    /**
     * Update the specified FinanceDocuments in storage.
     * PUT/PATCH /financeDocuments/{id}
     *
     * @param int $id
     * @param UpdateFinanceDocumentsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFinanceDocumentsAPIRequest $request)
    {
        $input = $request->all();

        /** @var FinanceDocuments $financeDocuments */
        $financeDocuments = $this->financeDocumentsRepository->find($id);

        if (empty($financeDocuments))
        {
            return $this->sendError($this->errorMessage);
        }

        $financeDocuments = $this->financeDocumentsRepository->update($input, $id);

        return $this->sendResponse(new FinanceDocumentsResource($financeDocuments),
            'FinanceDocuments updated successfully');
    }

    /**
     * Remove the specified FinanceDocuments from storage.
     * DELETE /financeDocuments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FinanceDocuments $financeDocuments */
        $financeDocuments = $this->financeDocumentsRepository->find($id);

        if (empty($financeDocuments))
        {
            return $this->sendError($this->errorMessage);
        }

        $financeDocuments->delete();

        return $this->sendSuccess('Finance Documents deleted successfully');
    }
}
