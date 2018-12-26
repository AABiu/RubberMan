<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\WechatSessionCreateRequest;
use App\Http\Requests\WechatSessionUpdateRequest;
use App\Repositories\WechatSessionRepository;
use App\Validators\WechatSessionValidator;

/**
 * Class WechatSessionsController.
 *
 * @package namespace App\Http\Controllers;
 */
class WechatSessionsController extends Controller
{
    /**
     * @var WechatSessionRepository
     */
    protected $repository;

    /**
     * @var WechatSessionValidator
     */
    protected $validator;

    /**
     * WechatSessionsController constructor.
     *
     * @param WechatSessionRepository $repository
     * @param WechatSessionValidator $validator
     */
    public function __construct(WechatSessionRepository $repository, WechatSessionValidator $validator)
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
        $wechatSessions = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $wechatSessions,
            ]);
        }

        return view('wechatSessions.index', compact('wechatSessions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WechatSessionCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(WechatSessionCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $wechatSession = $this->repository->create($request->all());

            $response = [
                'message' => 'WechatSession created.',
                'data'    => $wechatSession->toArray(),
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
        $wechatSession = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $wechatSession,
            ]);
        }

        return view('wechatSessions.show', compact('wechatSession'));
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
        $wechatSession = $this->repository->find($id);

        return view('wechatSessions.edit', compact('wechatSession'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WechatSessionUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(WechatSessionUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $wechatSession = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'WechatSession updated.',
                'data'    => $wechatSession->toArray(),
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
                'message' => 'WechatSession deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'WechatSession deleted.');
    }
}
