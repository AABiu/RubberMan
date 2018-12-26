<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\WechatSessionRepository;
use App\Entities\WechatSession;
use App\Validators\WechatSessionValidator;

/**
 * Class WechatSessionRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class WechatSessionRepositoryEloquent extends BaseRepository implements WechatSessionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WechatSession::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return WechatSessionValidator::class;
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
        return "App\\Presenters\\WechatSessionPresenter";
    }

    public function useModel(callable $callback)
    {
        $this->model = $callback($this->model);
        return $this;
    }
    
}
