<?php

namespace App\Presenters;

use App\Transformers\DataOriginTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class DataOriginPresenter.
 *
 * @package namespace App\Presenters;
 */
class DataOriginPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new DataOriginTransformer();
    }
}
