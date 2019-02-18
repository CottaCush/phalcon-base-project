<?php

namespace App\Tests\Api;

use Phalcon\Http\Response\StatusCode;

/**
 * Class VersionCest
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package App\Tests\Api
 */
class VersionCest
{
    public function getAppVersion(\ApiTester $I)
    {
        $I->wantTo('Get App Version - SUCCESS CASE');
        $I->sendGET('/');
        $I->seeResponseCodeIs(StatusCode::OK);
    }
}
