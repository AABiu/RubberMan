<?php

namespace App\Presenters;

use App\Transformers\FormInfoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class FormInfoPresenter.
 *
 * @package namespace App\Presenters;
 */
class FormInfoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new FormInfoTransformer();
    }
}
