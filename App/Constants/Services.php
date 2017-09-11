<?php

namespace App\Constants;

use PhalconUtils\Constants\Services as BaseServices;

/**
 * Class Services
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library\Constants
 */
class Services extends BaseServices
{

    const PAPERTRAIL_LOGGER = 'papertrailLogger';
    const FILE_LOGGER = 'fileLogger';
}
