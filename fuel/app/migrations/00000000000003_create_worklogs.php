<?php

namespace Fuel\Migrations;
use Fuel\Core\DBUtil;
use Fuel\Core\DB;

class Create_worklogs
{
	public function up()
	{
		DBUtil::create_table('worklogs', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'project_id' =>  array('constraint' => 11, 'type' => 'int', 'null' => false, 'unsigned' => true),
			'record_date' => array('type' => 'date', 'null' => false),
			'description' => array('constraint' => 100, 'type' => 'varchar', 'null' => true),
			'duration_minutes' => array('constraint' => 3, 'type' => 'int', 'null' => false),
			'created_at' => array('type' => 'datetime', 'null' => false),
			'updated_at' => array('type' => 'datetime', 'null' => true)

		), array('id'));

		DBUtil::create_index('worklogs', 'project_id', 'idx_worklogs_project_id'); 

		// Raw SQLクエリの実行
		DB::query('
			ALTER TABLE `worklogs` 
			ADD CONSTRAINT `fk_worklogs_project_id`
			FOREIGN KEY (`project_id`)
			REFERENCES `projects` (`id`)
			ON UPDATE CASCADE
			ON DELETE CASCADE;
		')->execute();
	}

	public function down()
	{
		DBUtil::drop_foreign_key('worklogs', 'fk_worklogs_project_id');
		DBUtil::drop_table('worklogs');
	}
}