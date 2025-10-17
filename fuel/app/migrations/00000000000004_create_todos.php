<?php

namespace Fuel\Migrations;
use Fuel\Core\DBUtil;
use Fuel\Core\DB;

class Create_todos
{
	public function up()
	{
		DBUtil::create_table('todos', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'project_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'title' => array('constraint' => 30, 'type' => 'varchar', 'null' => false),
			'description' => array('constraint' => 100, 'type' => 'varchar', 'null' => true),
			'start_date' => array('type' => 'date', 'null' => false),
			'end_date' => array('type' => 'date', 'null' => false),
			'started_at' => array('type' => 'date', 'null' => true),
			'ended_at' => array('type' => 'date', 'null' => true),
			'is_completed' => array('type' => 'boolean', 'null' => false, 'default' => 0),
			'created_at' => array('type' => 'datetime', 'null' => false),
			'updated_at' => array('type' => 'datetime', 'null' => true)
		), array('id'));

		DBUtil::create_index('todos', 'project_id');
		DB::query('
			ALTER TABLE `todos` 
			ADD CONSTRAINT `fk_todos_projects`
			FOREIGN KEY (`project_id`)
			REFERENCES `projects` (`id`)
			ON UPDATE CASCADE
			ON DELETE CASCADE;
		')->execute();
	}

	public function down()
	{
		DBUtil::drop_foreign_key('todos', 'fk_todos_projects');
		DBUtil::drop_table('todos');
	}
}