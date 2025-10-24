<?php
return array(
	'_root_'  => 'auth/login',     // ログイン画面をホームに設定
	'_404_'   => 'welcome/404',    // The main 404 route
	
	// 認証関連
	'auth/(:action)' => 'auth/$1',
	
	// ホーム・プロジェクト・TODO関連（後で追加）
	'home/(:action)' => 'home/$1',
	'project/(:action)' => 'project/$1',
	'todo/(:action)' => 'todo/$1',
);
