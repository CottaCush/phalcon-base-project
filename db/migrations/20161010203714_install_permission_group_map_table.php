<?php

use Phinx\Migration\AbstractMigration;

class InstallPermissionGroupMapTable extends AbstractMigration
{
    public function up()
    {
        $this->table('permission_group_map')
            ->addColumn('permission', 'string', ['length' => 50, 'null' => false])
            ->addColumn('group', 'string', ['length' => 50, 'null' => false])
            ->addIndex(
                ['group', 'permission'],
                ['name' => 'k_permission_group_map_permission_group', 'unique' => true]
            )
            ->addForeignKey('permission', 'permissions', 'key', ['constraint' => 'fk_pgm_permissions_permission_key'])
            ->addForeignKey('group', 'permission_groups', 'key', ['constraint' => 'fk_pgm_permission_groups_group_key'])
            ->create();
    }

    public function down()
    {
        $this->dropTable('permission_group_map');
    }
}
