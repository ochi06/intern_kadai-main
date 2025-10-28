<?php
namespace Fuel\Migrations;
use Fuel\Core\DBUtil;

class Create_users
{
	public function up()
	{
		DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'null' => false, 'auto_increment' => true, 'unsigned' => true),
			'user_name' => array('constraint' => 10, 'type' => 'varchar', 'null' => false),
			'mail_address' => array('constraint' => 50, 'type' => 'varchar', 'null' => false),
			'password' => array('constraint' => 20, 'type' => 'varchar', 'null' => false),
			'created_at' => array('type' => 'datetime', 'null' => false),
			'updated_at' => array('type' => 'datetime', 'null' => false),

		), array('id'));
	}

	public function down()
	{
		DBUtil::drop_table('users');
	}
}