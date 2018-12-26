<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\WechatSession;

/**
 * Class WechatSessionTransformer.
 *
 * @package namespace App\Transformers;
 */
class WechatSessionTransformer extends TransformerAbstract
{
    /**
     * Transform the WechatSession entity.
     *
     * @param \App\Entities\WechatSession $model
     *
     * @return array
     */
    public function transform(WechatSession $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
