<?php

class Controller_WorkLog extends Controller_Base
{
    // 作業ログ記録
    public function action_create($project_id = null)
    {
        $this->requireLogin();

        if (!$project_id)
        {
            \Session::set_flash('error', 'プロジェクトが指定されていません');
            \Response::redirect('home/index');
        }

        // プロジェクトの所有者チェック
        $project = \Model_Project::findById($project_id);
        if (!$project || $project['user_id'] != $this->current_user['id'])
        {
            \Session::set_flash('error', 'プロジェクトが見つかりません');
            \Response::redirect('home/index');
        }

        if (\Input::method() === 'POST')
        {
            $duration = \Input::post('duration');
            $todo_id = \Input::post('todo_id', null);
            $record_date = date('Y-m-d'); // 今日の日付

            if (empty($duration) || $duration <= 0)
            {
                \Session::set_flash('error', '作業時間を正しく入力してください');
            }
            else
            {
                // TODOが選択されている場合、存在チェック
                if ($todo_id)
                {
                    $todo = \Model_Todo::findById($todo_id);
                    if (!$todo || $todo['project_id'] != $project_id)
                    {
                        \Session::set_flash('error', '選択されたTODOが見つかりません');
                        \Response::redirect('project/index/' . $project_id);
                    }
                }

                // 作業ログ記録
                \Model_WorkLog::create($project_id, $todo_id, $record_date, $duration);
                \Session::set_flash('success', '作業時間を記録しました');
            }
        }

        \Response::redirect('project/index/' . $project_id);
    }
}