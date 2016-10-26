<?php

use Phinx\Migration\AbstractMigration;

class InstallPermissionGroupsTable extends AbstractMigration
{
    public function up()
    {
        $this->table('permission_groups')
            ->addColumn('key', 'string', ['length' => 50, 'null' => false])
            ->addColumn('label', 'string', ['length' => 100, 'null' => false])
            ->addIndex('key', ['unique' => true, 'name' => 'k_permission_groups_key'])
            ->create();
    }

    public function down()
    {
        $this->dropTable('permission_groups');
    }
}
