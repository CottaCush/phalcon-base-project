<?php

namespace App\Tests\Api;

use ApiTester;
use CommonTests;

/**
 * Class OAuthCest
 * @author Adeyemi Olaoye <yemexx1@gmail.com>
 */
class OAuthCest
{

    public function testGetAccessToken(ApiTester $I)
    {
        $I->wantTo('OAUTH: Get Access Token - SUCCESS CASE');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_secret' => CommonTests::getOAuthClientSecret(),
            'client_id' => CommonTests::getOAuthClientId()
        ]);

        CommonTests::assertSuccessResponse($I);
        $I->canSeeResponseJsonMatchesJsonPath('$.data.access_token');

    }
}
