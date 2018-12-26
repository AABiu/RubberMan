<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Fortune;

/**
 * Class FortuneTransformer.
 *
 * @package namespace App\Transformers;
 */
class FortuneTransformer extends TransformerAbstract
{
    /**
     * Transform the Fortune entity.
     *
     * @param \App\Entities\Fortune $model
     *
     * @return array
     */
    public function transform(Fortune $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
