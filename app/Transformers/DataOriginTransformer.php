<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\DataOrigin;

/**
 * Class DataOriginTransformer.
 *
 * @package namespace App\Transformers;
 */
class DataOriginTransformer extends TransformerAbstract
{
    /**
     * Transform the DataOrigin entity.
     *
     * @param \App\Entities\DataOrigin $model
     *
     * @return array
     */
    public function transform(DataOrigin $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
