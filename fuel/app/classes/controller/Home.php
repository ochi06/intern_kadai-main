<?php

namespace Controller;

class Home extends Base
{
    public function action_index()
    {
        // ログインチェック
        $this->requireLogin();

        // プロジェクト一覧取得
        $projects = \Model_Project::findByUserId($this->current_user['id']);

        // ビューを返す
        return \View::forge('home/index', array(
            'user' => $this->current_user,
            'projects' => $projects,
            'error' => \Session::get_flash('error'),
        ));
    }
}