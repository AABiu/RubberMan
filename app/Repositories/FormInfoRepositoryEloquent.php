<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FormInfoRepository;
use App\Entities\FormInfo;
use App\Validators\FormInfoValidator;

/**
 * Class FormInfoRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FormInfoRepositoryEloquent extends BaseRepository implements FormInfoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FormInfo::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return FormInfoValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    public function presenter()
    {
        return "App\\Presenters\\FormInfoPresenter";
    }

    public function useModel(callable $callback)
    {
        $this->model = $callback($this->model);
        return $this;
    }
}
