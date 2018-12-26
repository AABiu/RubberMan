<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class DataOrigin.
 *
 * @package namespace App\Entities;
 */
class DataOrigin extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'one',
        'two',
        'three',
        'four',
        'str'
    ];

    const EXCLUDE_NUM = 1;//排除
    const INCLUDE_NUM = 2;//包含
    const LOCATION_EXCLUDE_NUM = 3;//定位排除
    const LOCATION_INCLUDE_NUM = 4;//定位包含
    const TWO_SUM = 5;//二数合
    const THREE_SUM = 6;//三数合
    const EXCLUDE_HISTORY = 7;//不含历史数据

}
