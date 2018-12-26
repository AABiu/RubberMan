<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DataOriginRepository;
use App\Entities\DataOrigin;
use App\Validators\DataOriginValidator;

/**
 * Class DataOriginRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class DataOriginRepositoryEloquent extends BaseRepository implements DataOriginRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DataOrigin::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return DataOriginValidator::class;
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
        return "App\\Presenters\\DataOriginPresenter";
    }

    public function useModel(callable $callback)
    {
        $this->model = $callback($this->model);
        return $this;
    }
}
