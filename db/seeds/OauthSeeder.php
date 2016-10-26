<?php

use Phinx\Seed\AbstractSeed;

/**
 * Class OauthSeeder
 * @author Adeyemi Olaoye <yemexx1@gmail.com>
 */
class OauthSeeder extends AbstractSeed
{
    public function run()
    {
        $client_id = getenv('CLIENT_ID');
        $client_secret = getenv('CLIENT_SECRET');

        if (!$client_id || !$client_secret) {
            print 'Please supply CLIENT_ID and CLIENT_SECRET as environment vars';
            exit;
        }

        if ($this->fetchRow('SELECT client_id FROM oauth_clients WHERE client_id = "' . $client_id . '"')) {
            print 'Client ID already exists';
            return true;
        }

        $this->insert('oauth_clients', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => 'http://app.com',
            'grant_types' => null,
            'scope' => null,
            'user_id' => null
        ]);

        return false;
    }
}