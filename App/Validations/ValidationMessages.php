<?php

namespace App\Validation;

/**
 * Class ValidationMessages
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Validation
 */
class ValidationMessages
{
    const INVALID_PARAMETER_SUPPLIED = 'Invalid %s supplied';
    const PARAMETER_NOT_FOUND = '%s not found';
    const PARAMETER_MUST_BE_AN_OBJECT = '%s must be an object';
    const PARAMETER_MUST_BE_AN_ARRAY = '%s must be an array';
    const PARAMETER_CONTAINS_INVALID_FIELD = '%s contains invalid field';
    const PARAMETER_MUST_BE_BOOLEAN = '%s must be boolean';
    const PARAMETER_IS_REQUIRED = '%s is required';
    const MERCHANT_ALREADY_HAS_MEETING_SCHEDULED_FOR_THIS_TIME = 'Merchant already has meeting scheduled for this time';
}
