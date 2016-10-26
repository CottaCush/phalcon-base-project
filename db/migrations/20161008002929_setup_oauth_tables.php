<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class SetupOauthTables extends AbstractMigration
{
    const TABLE_OAUTH_CLIENTS = 'oauth_clients';
    const TABLE_OAUTH_ACCESS_TOKENS = 'oauth_access_tokens';
    const TABLE_OAUTH_AUTHORIZATION_CODES = 'oauth_authorization_codes';
    const TABLE_OAUTH_REFRESH_TOKENS = 'oauth_refresh_tokens';
    const TABLE_OAUTH_USERS = 'oauth_users';
    const TABLE_OAUTH_SCOPES = 'oauth_scopes';
    const TABLE_OAUTH_JWT = 'oauth_jwt';

    public function up()
    {
        $this->table(self::TABLE_OAUTH_CLIENTS, ['id' => false, 'primary_key' => 'client_id'])
            ->addColumn('client_id', 'string', ['length' => 80, 'null' => false])
            ->addColumn('client_secret', 'string', ['length' => 80, 'null' => true])
            ->addColumn('redirect_uri', 'string', ['length' => 2000, 'null' => false])
            ->addColumn('grant_types', 'string', ['length' => 80, 'null' => true])
            ->addColumn('scope', 'string', ['length' => 100, 'null' => true])
            ->addColumn('user_id', 'string', ['length' => 80, 'null' => true])->create();

        $this->table(self::TABLE_OAUTH_ACCESS_TOKENS, ['id' => false, 'primary_key' => 'access_token'])
            ->addColumn('access_token', 'string', ['length' => 40, 'null' => false])
            ->addColumn('client_id', 'string', ['length' => 80, 'null' => false])
            ->addColumn('user_id', 'string', ['length' => 255, 'null' => true])
            ->addColumn('expires', 'timestamp', ['null' => false])
            ->addColumn('scope', 'string', ['length' => 2000, 'null' => true])->create();


        $this->table(self::TABLE_OAUTH_AUTHORIZATION_CODES, ['id' => false, 'primary_key' => 'authorization_code'])
            ->addColumn('authorization_code', 'string', ['length' => 40, 'null' => false])
            ->addColumn('client_id', 'string', ['length' => 80, 'null' => false])
            ->addColumn('user_id', 'string', ['length' => 255, 'null' => true])
            ->addColumn('redirect_uri', 'string', ['length' => 2000, 'null' => true])
            ->addColumn('expires', 'timestamp', ['null' => false])
            ->addColumn('scope', 'string', ['length' => 2000, 'null' => true])->create();

        $this->table(self::TABLE_OAUTH_REFRESH_TOKENS, ['id' => false, 'primary_key' => 'refresh_token'])
            ->addColumn('refresh_token', 'string', ['length' => 40, 'null' => false])
            ->addColumn('client_id', 'string', ['length' => 80, 'null' => false])
            ->addColumn('user_id', 'string', ['length' => 255, 'null' => true])
            ->addColumn('expires', 'timestamp', ['null' => false])
            ->addColumn('scope', 'string', ['length' => 2000, 'null' => true])->create();

        $this->table(self::TABLE_OAUTH_USERS, ['id' => false, 'primary_key' => 'username'])
            ->addColumn('username', 'string', ['length' => 255, 'null' => false])
            ->addColumn('password', 'string', ['length' => 2000, 'null' => true])
            ->addColumn('first_name', 'string', ['length' => 255, 'null' => true])
            ->addColumn('last_name', 'string', ['length' => 255, 'null' => true])->create();

        $this->table(self::TABLE_OAUTH_SCOPES, ['id' => false])
            ->addColumn('scope', 'text')
            ->addColumn('is_default', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => true])
            ->create();

        $this->table(self::TABLE_OAUTH_JWT, ['id' => false, 'primary_key' => 'client_id'])
            ->addColumn('client_id', 'string', ['length' => 80, 'null' => false])
            ->addColumn('subject', 'string', ['length' => 80, 'null' => true])
            ->addColumn('public_key', 'string', ['length' => 2000, 'null' => true])->create();
    }

    public function down()
    {

        $this->dropTable(self::TABLE_OAUTH_JWT);
        $this->dropTable(self::TABLE_OAUTH_SCOPES);
        $this->dropTable(self::TABLE_OAUTH_USERS);
        $this->dropTable(self::TABLE_OAUTH_REFRESH_TOKENS);
        $this->dropTable(self::TABLE_OAUTH_AUTHORIZATION_CODES);
        $this->dropTable(self::TABLE_OAUTH_ACCESS_TOKENS);
        $this->dropTable(self::TABLE_OAUTH_CLIENTS);
    }
}
