<?php

namespace Fuel\Migrations;
use Fuel\Core\DBUtil;
use Fuel\Core\DB;

class Create_projects
{
	public function up()
	{
		DBUtil::create_table('projects', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'null' => false, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int', 'null' => false, 'unsigned' => true),
			'project_name' => array('constraint' => 30, 'type' => 'varchar', 'null' => false),
			'description' => array('constraint' => 100, 'type' => 'varchar', 'null' => true),
			'created_at' => array('type' => 'datetime', 'null' => false),
			'updated_at' => array('type' => 'datetime', 'null' => true)
		), array('id'));

		DBUtil::create_index('projects', 'user_id', 'idx_projects_user_id'); 

        // Raw SQLクエリの実行
        DB::query('
            ALTER TABLE `projects` 
            ADD CONSTRAINT `fk_projects_user_id`
            FOREIGN KEY (`user_id`)
            REFERENCES `users` (`id`)
            ON UPDATE CASCADE
            ON DELETE CASCADE;
        ')->execute();
	}

	

	public function down()
	{
		DBUtil::drop_foreign_key('projects', 'fk_projects_user_id');
		DBUtil::drop_table('projects');
	}
}