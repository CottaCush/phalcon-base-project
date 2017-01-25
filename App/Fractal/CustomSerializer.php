<?php

namespace App\Fractal;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class CustomSerializer
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Fractal
 */
class CustomSerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        if ($resourceKey == null) {
            return $data;
        }

        return [$resourceKey ?: 'data' => $data];
    }

    public function item($resourceKey, array $data)
    {
        if ($resourceKey == null) {
            return $data;
        }

        return [$resourceKey ?: 'data' => $data];
    }

    public function null()
    {
        return null;
    }
}
