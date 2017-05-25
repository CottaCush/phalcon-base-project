<?php

namespace App\Transformer;

use Phalcon\Mvc\Model;
use PhalconRest\Transformers\Transformer;

/**
 * Class ModelTransformer
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Transformer
 */
class ModelTransformer extends Transformer
{
    public function transform(Model $model)
    {
        return $model->toArray();
    }
}
