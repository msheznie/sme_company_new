<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFormTemplateMasterAPIRequest;
use App\Http\Requests\API\UpdateFormTemplateMasterAPIRequest;
use App\Models\FormTemplateMaster;
use App\Repositories\FormTemplateMasterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FormTemplateMasterResource;
use Response;

/**
 * Class FormTemplateMasterController
 * @package App\Http\Controllers\API
 */

class FormTemplateMasterAPIController extends AppBaseController
{
    /** @var  FormTemplateMasterRepository */
    private $formTemplateMasterRepository;

    public function __construct(FormTemplateMasterRepository $formTemplateMasterRepo)
    {
        $this->formTemplateMasterRepository = $formTemplateMasterRepo;
    }

    /**
     * Display a listing of the FormTemplateMaster.
     * GET|HEAD /formTemplateMasters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $formTemplateMasters = $this->formTemplateMasterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FormTemplateMasterResource::collection($formTemplateMasters), 'Form Template Masters retrieved successfully');
    }

    /**
     * Store a newly created FormTemplateMaster in storage.
     * POST /formTemplateMasters
     *
     * @param CreateFormTemplateMasterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFormTemplateMasterAPIRequest $request)
    {
        $input = $request->all();

        $formTemplateMaster = $this->formTemplateMasterRepository->create($input);

        return $this->sendResponse(new FormTemplateMasterResource($formTemplateMaster), 'Form Template Master saved successfully');
    }

    /**
     * Display the specified FormTemplateMaster.
     * GET|HEAD /formTemplateMasters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FormTemplateMaster $formTemplateMaster */
        $formTemplateMaster = $this->formTemplateMasterRepository->find($id);

        if (empty($formTemplateMaster)) {
            return $this->sendError('Form Template Master not found');
        }

        return $this->sendResponse(new FormTemplateMasterResource($formTemplateMaster), 'Form Template Master retrieved successfully');
    }

    /**
     * Update the specified FormTemplateMaster in storage.
     * PUT/PATCH /formTemplateMasters/{id}
     *
     * @param int $id
     * @param UpdateFormTemplateMasterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFormTemplateMasterAPIRequest $request)
    {
        $input = $request->all();

        /** @var FormTemplateMaster $formTemplateMaster */
        $formTemplateMaster = $this->formTemplateMasterRepository->find($id);

        if (empty($formTemplateMaster)) {
            return $this->sendError('Form Template Master not found');
        }

        $formTemplateMaster = $this->formTemplateMasterRepository->update($input, $id);

        return $this->sendResponse(new FormTemplateMasterResource($formTemplateMaster), 'FormTemplateMaster updated successfully');
    }

    /**
     * Remove the specified FormTemplateMaster from storage.
     * DELETE /formTemplateMasters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FormTemplateMaster $formTemplateMaster */
        $formTemplateMaster = $this->formTemplateMasterRepository->find($id);

        if (empty($formTemplateMaster)) {
            return $this->sendError('Form Template Master not found');
        }

        $formTemplateMaster->delete();

        return $this->sendSuccess('Form Template Master deleted successfully');
    }
}
