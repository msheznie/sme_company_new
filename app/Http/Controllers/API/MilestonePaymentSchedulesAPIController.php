<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateMilestonePaymentSchedulesAPIRequest;
use App\Http\Requests\API\UpdateMilestonePaymentSchedulesAPIRequest;
use App\Models\ContractMilestonePenaltyDetail;
use App\Models\MilestonePaymentSchedules;
use App\Repositories\MilestonePaymentSchedulesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MilestonePaymentSchedulesResource;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MilestonePaymentSchedulesController
 * @package App\Http\Controllers\API
 */

class MilestonePaymentSchedulesAPIController extends AppBaseController
{
    /** @var  MilestonePaymentSchedulesRepository */
    private $milestonePaymentSchedulesRepository;

    public function __construct(MilestonePaymentSchedulesRepository $milestonePaymentSchedulesRepo)
    {
        $this->milestonePaymentSchedulesRepository = $milestonePaymentSchedulesRepo;
    }

    /**
     * Display a listing of the MilestonePaymentSchedules.
     * GET|HEAD /milestonePaymentSchedules
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(MilestonePaymentSchedulesResource::collection($milestonePaymentSchedules),
            'Milestone Payment Schedules retrieved successfully');
    }

    /**
     * Store a newly created MilestonePaymentSchedules in storage.
     * POST /milestonePaymentSchedules
     *
     * @param CreateMilestonePaymentSchedulesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMilestonePaymentSchedulesAPIRequest $request)
    {
        $input = $request->all();
        $selectedCompanyID = $request->input('selectedCompanyID') ?? 0;
        $contractUuid = $input['contract_id'] ?? null;
        try
        {
            $this->milestonePaymentSchedulesRepository->createPaymentSchedule(
                $input,
                $contractUuid,
                $selectedCompanyID
            );
            return $this->sendResponse([], 'Milestone payment schedule created successfully');
        } catch(CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        } catch(\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), '404');
        }
    }

    /**
     * Display the specified MilestonePaymentSchedules.
     * GET|HEAD /milestonePaymentSchedules/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MilestonePaymentSchedules $milestonePaymentSchedules */
        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->find($id);

        if (empty($milestonePaymentSchedules))
        {
            return $this->sendError(trans('common.milestone_payment_schedule_not_found'));
        }

        return $this->sendResponse(new MilestonePaymentSchedulesResource($milestonePaymentSchedules),
            'Milestone Payment Schedules retrieved successfully');
    }

    /**
     * Update the specified MilestonePaymentSchedules in storage.
     * PUT/PATCH /milestonePaymentSchedules/{id}
     *
     * @param int $id
     * @param UpdateMilestonePaymentSchedulesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMilestonePaymentSchedulesAPIRequest $request)
    {
        $input = $request->all();
        try
        {
            $paymentSchedule = $this->milestonePaymentSchedulesRepository->findByUuid($id, ['id']);

            if (empty($paymentSchedule))
            {
                throw new CommonException('Milestone payment schedule not found.');
            }
            $this->milestonePaymentSchedulesRepository->updateMilestonePaymentSchedule($input, $paymentSchedule['id']);
            return $this->sendResponse([], trans('Milestone payment schedule updated successfully.'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified MilestonePaymentSchedules from storage.
     * DELETE /milestonePaymentSchedules/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MilestonePaymentSchedules $milestonePaymentSchedules */
        $milestonePaymentSchedules = $this->milestonePaymentSchedulesRepository->findByUuid($id,
            ['id', 'milestone_id', 'contract_id', 'company_id']);
        try
        {
            if (empty($milestonePaymentSchedules))
            {
                throw new CommonException('Milestone Payment Schedules not found');
            }
            $checkMilestonePRUsed = MilestonePaymentSchedules::checkMilestoneUsedInRetention(
                $milestonePaymentSchedules['milestone_id']
            );
            if($checkMilestonePRUsed)
            {
                throw new CommonException('Cannot delete milestone payment schedule. Milestone is used in
                 milestone retention');
            }
            $penaltyExists = ContractMilestonePenaltyDetail::getMilestoneTitle(
                $milestonePaymentSchedules['milestone_id'],
                $milestonePaymentSchedules['contract_id'],
                $milestonePaymentSchedules['company_id']);
            if($penaltyExists)
            {
                throw new CommonException('Cannot delete milestone payment schedule. Milestone is used in
                 milestone penalty');
            }
            $milestonePaymentSchedules->delete();

            return $this->sendSuccess('Milestone Payment Schedules deleted successfully');
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage());
        }
    }

    public function getPaymentScheduleFormData(Request $request)
    {
        $contractUuid = $request->input('contractUuid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        $uuid = $request->input('uuid') ?? null;
        $isEdit = $request->input('isEdit') ?? 0;
        try
        {
            $response = $this->milestonePaymentSchedulesRepository->getPaymentScheduleFormData(
                $contractUuid, $companySystemID, $uuid, $isEdit);
            return $this->sendResponse($response, trans('common.retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }
    public function getMilestonePaymentSchedules(Request $request)
    {
        return $this->milestonePaymentSchedulesRepository->getMilestonePaymentSchedules($request);
    }
    public function getMilestoneDetailsReport(Request $request)
    {
        return $this->milestonePaymentSchedulesRepository->getMilestoneDetailsReport($request);
    }
    public function exportContractMilestoneReport(Request $request)
    {
        $type = $request->input('type');
        $disk = $request->input('disk');
        $docName = $request->input('doc_name');
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        $milestones = $this->milestonePaymentSchedulesRepository->exportContractMilestoneReport($request);
        $companyCode = $companySystemID > 0 ? General::getCompanyById($companySystemID) ?? 'common' : 'common';
        $detailArray = array(
            'company_code' => $companyCode
        );

        $export = new ContractManagmentExport($milestones);
        $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

        if ($basePath == '')
        {
            return $this->sendError('unable_to_export_excel');
        } else
        {
            return $this->sendResponse($basePath, trans('success_export'));
        }
    }
}
