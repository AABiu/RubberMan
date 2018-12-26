<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FortuneRepository;
use App\Entities\Fortune;
use App\Validators\FortuneValidator;

/**
 * Class FortuneRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FortuneRepositoryEloquent extends BaseRepository implements FortuneRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Fortune::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return FortuneValidator::class;
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
        return "App\\Presenters\\FortunePresenter";
    }

    public function useModel(callable $callback)
    {
        $this->model = $callback($this->model);
        return $this;
    }
    
}
