<?php

namespace App\Presenters;

use App\Transformers\FortuneTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class FortunePresenter.
 *
 * @package namespace App\Presenters;
 */
class FortunePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new FortuneTransformer();
    }
}
