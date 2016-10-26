<?php

use Phinx\Migration\AbstractMigration;

class InstallAccountsTable extends AbstractMigration
{

    public function up()
    {
        $this->table('accounts')
            ->addColumn('business_owner_id', 'string', ['length' => 100, 'null' => false])
            ->addColumn('social_platform', 'string', ['length' => 50, 'null' => false])
            ->addColumn('is_active', 'boolean', ['default' => 1, 'null' => false])
            ->addColumn('social_account_id', 'string', ['length' => 100, 'null' => false])
            ->addColumn('access_token', 'string', ['length' => 200, 'null' => false])
            ->addColumn('permission_group', 'string', ['length' => 50, 'null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('updated_at', 'datetime', ['default' => null])
            ->addIndex('business_owner_id', ['name' => 'k_accounts_business_owner_id'])
            ->addIndex(['business_owner_id', 'social_platform'], ['unique' => true, 'name' => 'k_accounts_boi_sp'])
            ->addForeignKey(
                'social_platform',
                'social_platforms',
                'key',
                ['constraint' => 'fk_accounts_social_platforms_social_platform_key']
            )
            ->addForeignKey(
                'permission_group',
                'permission_groups',
                'key',
                ['constraint' => 'fk_accounts_permission_groups_group_key']
            )
            ->create();
    }

    public function down()
    {
        $this->dropTable('accounts');
    }
}
