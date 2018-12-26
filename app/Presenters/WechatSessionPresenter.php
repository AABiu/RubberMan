<?php

namespace App\Presenters;

use App\Transformers\WechatSessionTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class WechatSessionPresenter.
 *
 * @package namespace App\Presenters;
 */
class WechatSessionPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new WechatSessionTransformer();
    }
}
