<?php

namespace App\Validation;

use App\Constants\Services;
use App\Library\Util;
use Phalcon\Di;
use Phalcon\Validation\Validator\PresenceOf;
use PhalconUtils\Validation\RequestValidation;
use PhalconUtils\Validation\Validators\InlineValidator;
use PhalconUtils\Validation\Validators\Model;

/**
 * Class CommonValidations
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Validation
 */
trait CommonValidations
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param bool $cancelOnFail
     */
    public function addIsObjectValidation($field, $cancelOnFail = true)
    {
        if (!is_null($this->getValue($field))) {
            $this->add($field, new InlineValidator([
                'function' => function () use ($field) {
                    return is_object($this->getValue($field));
                },
                'message' => sprintf(ValidationMessages::PARAMETER_MUST_BE_AN_OBJECT, $field),
                'cancelOnFail' => $cancelOnFail
            ]));
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param bool $cancelOnFail
     */
    public function addIsArrayValidation($field, $cancelOnFail = true)
    {
        if (!is_null($this->getValue($field))) {
            $this->add($field, new InlineValidator([
                'function' => function () use ($field) {
                    return is_array($this->getValue($field));
                },
                'message' => sprintf(ValidationMessages::PARAMETER_MUST_BE_AN_ARRAY, $field),
                'cancelOnFail' => $cancelOnFail
            ]));
        }
    }

    /**
     * Validate bulk model fields
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $modelClass
     * @param string $bulkField
     */
    public function validateBulkModelField($field, $modelClass, $bulkField)
    {
        $this->validateValidity(
            $field,
            function ($uniqueAdvertIds) use ($modelClass) {
                $uniqueAdverts = $modelClass::query()->inWhere('id', $uniqueAdvertIds)->execute();
                return count($uniqueAdvertIds) == $uniqueAdverts->count();
            },
            $bulkField
        );
    }

    /**
     * Validate validity of field
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $validateFunction
     * @param string $bulkField
     */
    public function validateValidity($field, $validateFunction, $bulkField)
    {
        if (is_array($this->getValue($bulkField))) {
            $uniqueElements = Util::getUniqueColumnElements($this->getValue($bulkField), $field);
            if (!empty($uniqueElements)) {
                $this->add($bulkField, new InlineValidator([
                    'function' => function () use ($uniqueElements, $validateFunction) {
                        return call_user_func($validateFunction, $uniqueElements);
                    },
                    'message' => sprintf(ValidationMessages::PARAMETER_CONTAINS_INVALID_FIELD, $field),
                    'cancelOnFail' => true
                ]));
            }
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $requiredElements
     */
    public function validateArrayElementsHasFields($field, array $requiredElements)
    {
        $fieldValue = $this->getValue($field);
        if (!is_array($fieldValue)) {
            return;
        }

        $this->add($field, new InlineValidator([
            'function' => function () use ($fieldValue, $requiredElements) {
                foreach ($fieldValue as $element) {
                    foreach ($requiredElements as $requiredElement) {
                        $validation = new RequestValidation($element);
                        $validation->add($requiredElement, new PresenceOf(['allowEmpty' => false]));
                        if (!$validation->validate()) {
                            return false;
                        }
                    }
                }
                return true;
            },
            'message' => sprintf(ValidationMessages::INCORRECT_ELEMENT_STRUCTURE, $field),
            'cancelOnFail' => true
        ]));
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $requiredElements
     */
    public function validateArrayElements($field, array $requiredElements)
    {
        $fieldValue = $this->getValue($field);
        if (!is_array($fieldValue)) {
            return;
        }

        $this->add($field, new InlineValidator([
            'function' => function () use ($fieldValue, $requiredElements) {
                foreach ($fieldValue as $element) {
                    foreach ($requiredElements as $requiredElement => $validations) {
                        $requestValidation = new RequestValidation($element);
                        foreach ($validations as $validation) {
                            $requestValidation->add($requiredElement, $validation);
                        }
                        if (!$requestValidation->validate()) {
                            Di::getDefault()->get(Services::LOGGER)->debug($requestValidation->getMessages());
                            return false;
                        }
                    }
                }
                return true;
            },
            'message' => sprintf(ValidationMessages::INCORRECT_ELEMENT_STRUCTURE, $field),
            'cancelOnFail' => true
        ]));
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     */
    public function isNotEmpty($field)
    {
        $this->add($field, new InlineValidator([
            'function' => function () use ($field) {
                $value = $this->getValue($field);
                if (is_array($value) && $value) {
                    return true;
                }

                if (is_object($value)) {
                    $value = (array)$value;
                    return ($value);
                }

                return !empty($this->getValue($field));
            }
        ]));
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param bool $cancelOnFail
     */
    public function validateBooleanValue($field, $cancelOnFail = true)
    {
        $this->add($field, new InlineValidator([
            'function' => function () use ($field) {
                return in_array($this->getValue($field), [0, 1, '0', '1', true, false], true);
            },
            'message' => sprintf(ValidationMessages::PARAMETER_MUST_BE_BOOLEAN, $field),
            'cancelOnFail' => $cancelOnFail
        ]));
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $model
     * @param string $column
     */
    public function validateDataField($field, $model, $column = 'key')
    {
        $this->add($field, new Model([
            'model' => $model,
            'conditions' => $column.' = :key:',
            'bind' => ['key' => $this->getValue($field)],
            'message' => sprintf(ValidationMessages::INVALID_PARAMETER_SUPPLIED, $field)
        ]));
    }
}
