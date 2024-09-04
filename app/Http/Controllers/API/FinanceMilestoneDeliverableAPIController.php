<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Http\Requests\API\CreateFinanceMilestoneDeliverableAPIRequest;
use App\Http\Requests\API\UpdateFinanceMilestoneDeliverableAPIRequest;
use App\Models\FinanceMilestoneDeliverable;
use App\Repositories\FinanceMilestoneDeliverableRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FinanceMilestoneDeliverableResource;
use Response;

/**
 * Class FinanceMilestoneDeliverableController
 * @package App\Http\Controllers\API
 */

class FinanceMilestoneDeliverableAPIController extends AppBaseController
{
    /** @var  FinanceMilestoneDeliverableRepository */
    private $financeMilestoneDeliverableRepository;
    protected $message = 'Finance Milestone Deliverable not found';

    public function __construct(FinanceMilestoneDeliverableRepository $financeMilestoneDeliverableRepo)
    {
        $this->financeMilestoneDeliverableRepository = $financeMilestoneDeliverableRepo;
    }

    /**
     * Display a listing of the FinanceMilestoneDeliverable.
     * GET|HEAD /financeMilestoneDeliverables
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $financeMilestoneDeliverables = $this->financeMilestoneDeliverableRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FinanceMilestoneDeliverableResource::collection($financeMilestoneDeliverables),
            'Finance Milestone Deliverables retrieved successfully');
    }

    /**
     * Store a newly created FinanceMilestoneDeliverable in storage.
     * POST /financeMilestoneDeliverables
     *
     * @param CreateFinanceMilestoneDeliverableAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFinanceMilestoneDeliverableAPIRequest $request)
    {
        $input = $request->all();
        try
        {
            $financeMilestoneDeliverable = $this->financeMilestoneDeliverableRepository->createFinanceMD($input);

            return $this->sendResponse($financeMilestoneDeliverable,
                'Finance Milestone Deliverable saved successfully');
        } catch (CommonException $exception)
        {
            return $this->sendError($exception->getMessage());
        } catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }

    /**
     * Display the specified FinanceMilestoneDeliverable.
     * GET|HEAD /financeMilestoneDeliverables/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FinanceMilestoneDeliverable $financeMilestoneDeliverable */
        $financeMilestoneDeliverable = $this->financeMilestoneDeliverableRepository->find($id);

        if (empty($financeMilestoneDeliverable))
        {
            return $this->sendError('Finance Milestone Deliverable not found');
        }

        return $this->sendResponse(new FinanceMilestoneDeliverableResource($financeMilestoneDeliverable),
            'Finance Milestone Deliverable retrieved successfully');
    }

    /**
     * Update the specified FinanceMilestoneDeliverable in storage.
     * PUT/PATCH /financeMilestoneDeliverables/{id}
     *
     * @param int $id
     * @param UpdateFinanceMilestoneDeliverableAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFinanceMilestoneDeliverableAPIRequest $request)
    {
        $input = $request->all();

        /** @var FinanceMilestoneDeliverable $financeMilestoneDeliverable */
        $financeMilestoneDeliverable = $this->financeMilestoneDeliverableRepository->find($id);

        if (empty($financeMilestoneDeliverable))
        {
            return $this->sendError('Finance Milestone Deliverable not found');
        }

        $financeMilestoneDeliverable = $this->financeMilestoneDeliverableRepository->update($input, $id);

        return $this->sendResponse(new FinanceMilestoneDeliverableResource($financeMilestoneDeliverable),
            'FinanceMilestoneDeliverable updated successfully');
    }

    /**
     * Remove the specified FinanceMilestoneDeliverable from storage.
     * DELETE /financeMilestoneDeliverables/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FinanceMilestoneDeliverable $financeMilestoneDeliverable */
        $financeMilestoneDeliverable = $this->financeMilestoneDeliverableRepository->find($id);

        if (empty($financeMilestoneDeliverable))
        {
            return $this->sendError($this->message);
        }

        $financeMilestoneDeliverable->delete();

        return $this->sendSuccess('Finance Milestone Deliverable deleted successfully');
    }
}
