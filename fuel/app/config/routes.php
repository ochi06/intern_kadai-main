<?php
return array(
	'_root_'  => 'auth/login',     // デフォルトはログイン画面
	'_404_'   => 'welcome/404',    // The main 404 route
	
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
