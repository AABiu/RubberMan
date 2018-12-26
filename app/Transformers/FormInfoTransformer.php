<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\FormInfo;

/**
 * Class FormInfoTransformer.
 *
 * @package namespace App\Transformers;
 */
class FormInfoTransformer extends TransformerAbstract
{
    /**
     * Transform the FormInfo entity.
     *
     * @param \App\Entities\FormInfo $model
     *
     * @return array
     */
    public function transform(FormInfo $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
