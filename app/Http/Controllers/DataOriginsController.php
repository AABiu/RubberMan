<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\DataOriginCreateRequest;
use App\Http\Requests\DataOriginUpdateRequest;
use App\Repositories\DataOriginRepository;
use App\Validators\DataOriginValidator;

/**
 * Class DataOriginsController.
 *
 * @package namespace App\Http\Controllers;
 */
class DataOriginsController extends Controller
{
    /**
     * @var DataOriginRepository
     */
    protected $repository;

    /**
     * @var DataOriginValidator
     */
    protected $validator;

    /**
     * DataOriginsController constructor.
     *
     * @param DataOriginRepository $repository
     * @param DataOriginValidator $validator
     */
    public function __construct(DataOriginRepository $repository, DataOriginValidator $validator)
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
        $dataOrigins = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $dataOrigins,
            ]);
        }

        return view('dataOrigins.index', compact('dataOrigins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  DataOriginCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(DataOriginCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $dataOrigin = $this->repository->create($request->all());

            $response = [
                'message' => 'DataOrigin created.',
                'data'    => $dataOrigin->toArray(),
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
        $dataOrigin = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $dataOrigin,
            ]);
        }

        return view('dataOrigins.show', compact('dataOrigin'));
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
        $dataOrigin = $this->repository->find($id);

        return view('dataOrigins.edit', compact('dataOrigin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DataOriginUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(DataOriginUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $dataOrigin = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'DataOrigin updated.',
                'data'    => $dataOrigin->toArray(),
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
                'message' => 'DataOrigin deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'DataOrigin deleted.');
    }

    public function initData()
    {
        set_time_limit(0);
        $data = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        foreach ($data as $datum1) {
            foreach ($data as $datum2) {
                foreach ($data as $datum3) {
                    foreach ($data as $datum4) {
                        $this->repository->create([
                            'one' => $datum1,
                            'two' => $datum2,
                            'three' => $datum3,
                            'four' => $datum4,
                            'str' => $datum1 . $datum2 . $datum3 . $datum4,
                        ]);
                    }
                }
            }
        }
    }
}
