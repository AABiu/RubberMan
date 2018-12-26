<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FormInfoCreateRequest;
use App\Http\Requests\FormInfoUpdateRequest;
use App\Repositories\FormInfoRepository;
use App\Validators\FormInfoValidator;

/**
 * Class FormInfosController.
 *
 * @package namespace App\Http\Controllers;
 */
class FormInfosController extends Controller
{
    /**
     * @var FormInfoRepository
     */
    protected $repository;

    /**
     * @var FormInfoValidator
     */
    protected $validator;

    /**
     * FormInfosController constructor.
     *
     * @param FormInfoRepository $repository
     * @param FormInfoValidator $validator
     */
    public function __construct(FormInfoRepository $repository, FormInfoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $formInfos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $formInfos,
            ]);
        }

        return view('formInfos.index', compact('formInfos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FormInfoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(FormInfoCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $formInfo = $this->repository->create($request->all());

            $response = [
                'message' => 'FormInfo created.',
                'data'    => $formInfo->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $formInfo = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $formInfo,
            ]);
        }

        return view('formInfos.show', compact('formInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formInfo = $this->repository->find($id);

        return view('formInfos.edit', compact('formInfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FormInfoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(FormInfoUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $formInfo = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'FormInfo updated.',
                'data'    => $formInfo->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'FormInfo deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'FormInfo deleted.');
    }
}
