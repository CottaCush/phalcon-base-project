<?php

use App\Constants\ResponseMessages;

/**
 * Class CommonTests
 * @author Adeyemi Olaoye <yemexx1@gmail.com>
 */
class CommonTests
{
    /**
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param ApiTester $I
     */
    public static function assertSuccessResponse(ApiTester $I)
    {
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'success']);
    }

    /**
     * Test response JSON data content
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param ApiTester $I
     * @param array $schema
     */
    public static function testResponseSchema(ApiTester $I, array $schema)
    {
        foreach ($schema as $key) {
            $I->canSeeResponseJsonMatchesJsonPath('$.data.' . $key);
        }
    }

    /**
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param $schema
     * @param string $prefix
     * @return array
     */
    public static function prefixSchema($schema, $prefix = '')
    {
        if (!empty($schema)) {
            $prefixedSchema = [];
            foreach ($schema as $key => $column) {
                $prefixedSchema[] = $prefix . '.' . $column;
            }
            return $prefixedSchema;
        } else {
            return $schema;
        }
    }

    /**
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param ApiTester $I
     * @param null $responseCode
     */
    public static function assertErrorResponse(ApiTester $I, $responseCode = null)
    {
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'error']);
        if (!is_null($responseCode)) {
            $I->seeResponseCodeIs($responseCode);
        }
    }

    /**
     * Test error response parameters
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param ApiTester $I
     * @param $code
     * @param null $message
     */
    public static function testErrorResponseParams(ApiTester $I, $code, $message = null)
    {
        self::testErrorResponseCode($I, $code);

        if (!is_null($message)) {
            self::testErrorResponseMessage($I, ResponseMessages::getMessageFromCode($code));
        }
    }


    /**
     * Test error response code
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param ApiTester $I
     * @param $code
     */
    public static function testErrorResponseCode(ApiTester $I, $code)
    {
        $I->seeResponseContainsJson(['code' => $code]);
    }

    /**
     * Test error response message
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param ApiTester $I
     * @param $message
     */
    public static function testErrorResponseMessage(ApiTester $I, $message)
    {
        $I->seeResponseContainsJson(['message' => $message]);
    }

    /**
     * generate email address
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @return string
     */
    public static function generateEmailAddress()
    {
        return uniqid() . '@mailinator.com';
    }

    /**
     * get oauth client id
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @return string
     */
    public static function getOAuthClientId()
    {
        return getenv('CLIENT_ID');
    }

    /**
     * get oauth client secret
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @return string
     */
    public static function getOAuthClientSecret()
    {
        return getenv('CLIENT_SECRET');
    }
}