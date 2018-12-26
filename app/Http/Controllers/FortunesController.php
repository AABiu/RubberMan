<?php

namespace App\Http\Controllers;

use App\Entities\DataOrigin;
use App\Entities\Fortune;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FortuneCreateRequest;
use App\Http\Requests\FortuneUpdateRequest;
use App\Repositories\FortuneRepository;
use App\Validators\FortuneValidator;

/**
 * Class FortunesController.
 *
 * @package namespace App\Http\Controllers;
 */
class FortunesController extends Controller
{
    /**
     * @var FortuneRepository
     */
    protected $repository;

    /**
     * @var FortuneValidator
     */
    protected $validator;

    /**
     * FortunesController constructor.
     *
     * @param FortuneRepository $repository
     * @param FortuneValidator $validator
     */
    public function __construct(FortuneRepository $repository, FortuneValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $fortunes = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $fortunes,
            ]);
        }

        return view('fortunes.index', compact('fortunes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FortuneCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(FortuneCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $fortune = $this->repository->create($request->all());

            $response = [
                'message' => 'Fortune created.',
                'data' => $fortune->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
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
        $fortune = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $fortune,
            ]);
        }

        return view('fortunes.show', compact('fortune'));
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
        $fortune = $this->repository->find($id);

        return view('fortunes.edit', compact('fortune'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FortuneUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(FortuneUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $fortune = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Fortune updated.',
                'data' => $fortune->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error' => true,
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
                'message' => 'Fortune deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Fortune deleted.');
    }

    public function getFortuneCodeHistory(Request $request)
    {
        set_time_limit(0);
        $page = $request->get('page', 1);
        $fortunes = Fortune::all();
        $fortunes_arr = array_column($fortunes->toArray(), null, 'qs');
        for ($index = $page; $index > 0; $index--) {
            $url = "http://www.lottery.gov.cn/historykj/history_$index.jspx?_ltype=qxc";
            set_time_limit(0);
            $html = file_get_contents($url);
            $pattern = '/<td.*? class="red">(.+?)<\/td>/is';
            preg_match_all($pattern, $html, $m);
            $pattern1 = '/<td.*? width="40" height="23" align="center".*?>(.+?)<\/td>/is';
            preg_match_all($pattern1, $html, $q);
            $data = $m[1];
            $data_q = $q[1];
            for ($in = count($data); $in > 0; $in--) {
                if (!isset($fortunes_arr[$data_q[$in - 1]])) {
                    $str = $data[$in - 1];
                    $this->repository->create([
                        'qs' => $data_q[$in - 1],
                        'one' => $str[0],
                        'two' => $str[1],
                        'three' => $str[2],
                        'four' => $str[3],
                        'str' => substr($str, 0, 4),
                    ]);
                }

            }
        }
    }

    public function getNewFortuneList(Request $request)
    {
        $data = $request->all();
        if (isset($data['isHistory']) && $data['isHistory']) {
            $minutes = 60 * 24 * 365;
            $fortunes = Cache::remember('ORIGIN_DATA_QXC', $minutes, function () {
                $origin = DataOrigin::all();
                return $origin->toArray();
            });
        } else {
            $minutes = 60 * 24 * 1;
            $fortunes = Cache::remember('NO_HISTORY_ORIGIN_DATA_QXC', $minutes, function () {
                $history = Fortune::all()->pluck('str')->toArray();
                $origin = DataOrigin::whereNotIn('str', $history)->get();
                return $origin->toArray();
            });
        }
        if (isset($data['oneOddEven']) && $data['oneOddEven']) {
            $fortunes = $this->oneOddEven($fortunes, $data['oneOddEven']);
        }
        if (isset($data['twoOddEven']) && $data['twoOddEven']) {
            $fortunes = $this->twoOddEven($fortunes, $data['twoOddEven']);
        }
        if (isset($data['threeOddEven']) && $data['threeOddEven']) {
            $fortunes = $this->threeOddEven($fortunes, $data['threeOddEven']);
        }
        if (isset($data['fourOddEven']) && $data['fourOddEven']) {
            $fortunes = $this->fourOddEven($fortunes, $data['fourOddEven']);
        }
        if (isset($data['ExcludeNumber']) && $data['ExcludeNumber']) {
            $fortunes = $this->computeExcludeNumber($fortunes, $data['ExcludeNumber']);
        }
        if (isset($data['IncludeNumber']) && $data['IncludeNumber']) {
            $fortunes = $this->computeIncludeNumber($fortunes, $data['IncludeNumber']);
        }
        if (isset($data['LocationExcludeNumber']) && $data['LocationExcludeNumber']) {
            $fortunes = $this->computeLocationExcludeNumber($fortunes, json_decode($data['LocationExcludeNumber'], true));
        }
        if (isset($data['LocationIncludeNumber']) && $data['LocationIncludeNumber']) {

            $fortunes = $this->computeLocationIncludeNumber($fortunes, json_decode($data['LocationIncludeNumber'], true));
        }
        if (isset($data['TwoSum']) && $data['TwoSum']) {
            $fortunes = $this->computeTwoSum($fortunes, json_decode($data['TwoSum'], true));
        }
        if (isset($data['ThreeSum']) && $data['ThreeSum']) {
            $fortunes = $this->computeThreeSum($fortunes, json_decode($data['ThreeSum'], true));
        }
        if (isset($data['FourSum']) && $data['FourSum']) {
            $fortunes = $this->computeThreeSum($fortunes, json_decode($data['FourSum'], true));
        }
        if (isset($data['isDuplicate']) && $data['isDuplicate']) {
            $fortunes = $this->computeDuplication($fortunes, $data['isDuplicate']);
        }

        return response()->json(collect($fortunes)->pluck('str')->chunk(100)->toArray());
    }

    /**
     * 千位单双
     *
     * @param  Array $array 源数据
     * @param  Array $options 包含的数字数组
     *
     * @return Array
     */
    public function oneOddEven($array, $options)
    {
        if ($options == 1) {
            $newArray = collect($array)->whereIn('one', [1, 3, 5, 7, 9])->toArray();
        } else {
            $newArray = collect($array)->whereIn('one', [0, 2, 4, 6, 8])->toArray();
        }
        return $newArray;
    }

    /**
     * 百位单双
     *
     * @param  Array $array 源数据
     * @param  Array $options 包含的数字数组
     *
     * @return Array
     */
    public function twoOddEven($array, $options)
    {
        if ($options == 1) {
            $newArray = collect($array)->whereIn('two', [1, 3, 5, 7, 9])->toArray();
        } else {
            $newArray = collect($array)->whereIn('two', [0, 2, 4, 6, 8])->toArray();
        }
        return $newArray;
    }

    /**
     * 十位单双
     *
     * @param  Array $array 源数据
     * @param  Array $options 包含的数字数组
     *
     * @return Array
     */
    public function threeOddEven($array, $options)
    {
        if ($options == 1) {
            $newArray = collect($array)->whereIn('three', [1, 3, 5, 7, 9])->toArray();
        } else {
            $newArray = collect($array)->whereIn('three', [0, 2, 4, 6, 8])->toArray();
        }
        return $newArray;
    }

    /**
     * 各位单双
     *
     * @param  Array $array 源数据
     * @param  Array $options 包含的数字数组
     *
     * @return Array
     */
    public function fourOddEven($array, $options)
    {
        if ($options == 1) {
            $newArray = collect($array)->whereIn('four', [1, 3, 5, 7, 9])->toArray();
        } else {
            $newArray = collect($array)->whereIn('four', [0, 2, 4, 6, 8])->toArray();
        }
        return $newArray;
    }

    /**
     * 排除一些数
     *
     * @param  Array $array 源数据
     * @param  Array $options 排除的数字数组
     *
     * @return Array
     */
    public function computeExcludeNumber($array, $options)
    {
        $newArray = [];
        foreach ($array as $item) {
            if (!str_contains($item['str'], explode(',', $options))) {
                array_push($newArray, $item);
            }
        }
        return $newArray;
    }

    /**
     * 包含一些数
     *
     * @param  Array $array 源数据
     * @param  Array $options 包含的数字数组
     *
     * @return Array
     */
    public function computeIncludeNumber($array, $options)
    {
        $newArray = [];
        foreach ($array as $item) {
            if (str_contains($item['str'], explode(',', $options))) {
                array_push($newArray, $item);
            }
        }
        return $newArray;
    }

    /**
     * 定位排除
     *
     * @param  Array $array 源数据
     * @param  Array $options 定位排除数组
     *
     * @return Array
     */
    public function computeLocationExcludeNumber($array, $options)
    {
        $where = [];
        $collect = collect($array);
        if (isset($options['one']) && $options['one']) {
            $collect = $collect->whereNotIn('one', explode(',', $options['one']));
        }
        if (isset($options['two']) && $options['two']) {
            $collect = $collect->whereNotIn('two', explode(',', $options['two']));
        }
        if (isset($options['three']) && $options['three']) {
            $collect = $collect->whereNotIn('three', explode(',', $options['three']));
        }
        if (isset($options['four']) && $options['four']) {
            $collect = $collect->whereNotIn('four', explode(',', $options['four']));
        }

        return $collect->all();
    }

    /**
     * 定位包含
     *
     * @param  Array $array 源数据
     * @param  Array $options 定位包含的数字数组
     *
     * @return Array
     */
    public function computeLocationIncludeNumber($array, $options)
    {
        $where = [];
        $collect = collect($array);
        if (isset($options['one']) && $options['one']) {
            $collect = $collect->whereIn('one', explode(',', $options['one']));
        }
        if (isset($options['two']) && $options['two']) {

            $collect = $collect->whereIn('two', explode(',', $options['two']));
        }
        if (isset($options['three']) && $options['three']) {
            $collect = $collect->whereIn('three', explode(',', $options['three']));
        }
        if (isset($options['four']) && $options['four']) {
            $collect = $collect->whereIn('four', explode(',', $options['four']));
        }

        return $collect->all();
    }

    /**
     * 二数合
     *
     * @param  Array $array 源数据
     * @param  Array $options 二数合的数字数组
     *
     * @return Array
     */
    public function computeTwoSum($array, $options)
    {
        $newArray = [];
        foreach ($array as $item) {
            if ($this->isTwoSum($item['str'], $options)) {
                array_push($newArray, $item);
            }
        }
        return $newArray;
    }

    /**
     * 三数数合
     *
     * @param  Array $array 源数据
     * @param  Array $options 三数数合的数字数组
     *
     * @return Array
     */
    public function computeThreeSum($array, $options)
    {
        $newArray = [];
        foreach ($array as $item) {
            if ($this->isThreeSum($item['str'], $options)) {
                array_push($newArray, $item);
            }
        }
        return $newArray;
    }

    /**
     * 四数数合
     *
     * @param  Array $array 源数据
     * @param  Array $options 四数数合的数字数组
     *
     * @return Array
     */
    public function computeFourSum($array, $options)
    {
        $newArray = [];
        foreach ($array as $item) {
            if ($this->isFourSum($item['str'], $options)) {
                array_push($newArray, $item);
            }
        }
        return $newArray;
    }

    /**
     * 是否重数
     *
     * @param  Array $array 源数据
     * @param  Array $options 四数数合的数字数组
     *
     * @return Array
     */
    public function computeDuplication($array, $options)
    {
        $newArray = [];
        foreach ($array as $item) {
            $tmp = array_count_values(str_split($item['str']));
            if ($options == 2 && max($tmp) > 1) {
                array_push($newArray, $item);
            };
            if ($options == 1 && max($tmp) == 1) {
                array_push($newArray, $item);
            };
        }
        return $newArray;
    }

    function isTwoSum($arr, $option)
    {

        if (isset($option['location']) && $option['location']) {
            $array = explode(',', $option['location']);
            $sum = $arr[$array[0]] + $arr[$array[1]];
            if (in_array($sum % 10, $option['sum'])) {
                return true;
            } else {
                return false;
            }
        }

        $one = $arr[0] + $arr[1];
        if (in_array($one % 10, explode(',', $option['sum']))) {
            return true;
        }
        $two = $arr[0] + $arr[2];
        if (in_array($two % 10, explode(',', $option['sum']))) {
            return true;
        }
        $three = $arr[0] + $arr[3];
        if (in_array($three % 10, explode(',', $option['sum']))) {
            return true;
        }
        $four = $arr[1] + $arr[2];
        if (in_array($four % 10, explode(',', $option['sum']))) {
            return true;
        }
        $five = $arr[1] + $arr[3];
        if (in_array($five % 10, explode(',', $option['sum']))) {
            return true;
        }
        $six = $arr[2] + $arr[3];
        if (in_array($six % 10, explode(',', $option['sum']))) {
            return true;
        }
        return false;
    }

    function isThreeSum($arr, $option)
    {

        if (isset($option['location']) && $option['location']) {
            $array = explode(',', $option['location']);
            $sum = $arr[$array[0]] + $arr[$array[1]] + $arr[$array[2]];
            if (in_array($sum % 10, explode(',', $option['sum']))) {
                return true;
            } else {
                return false;
            }
        }

        $one = $arr[0] + $arr[1] + $arr[2];
        if (in_array($one % 10, explode(',', $option['sum']))) {
            return true;
        }
        $two = $arr[0] + $arr[1] + $arr[3];
        if (in_array($two % 10, explode(',', $option['sum']))) {
            return true;
        }
        $three = $arr[0] + $arr[2] + $arr[3];
        if (in_array($three % 10, explode(',', $option['sum']))) {
            return true;
        }
        $four = $arr[1] + $arr[2] + $arr[3];
        if (in_array($four % 10, explode(',', $option['sum']))) {
            return true;
        }
        return false;
    }

    function isFourSum($arr, $option)
    {
        $one = $arr[0] + $arr[1] + $arr[2] + $arr[3];
        if (in_array($one, explode(',', $option['sum']))) {
            return true;
        }
        return false;
    }
}
