<?php

class Controller_Todo extends Controller_Base
{
    // TODO新規作成（Ajaxリクエスト）
    public function action_create($project_id = null)
    {
        $this->requireLogin();

        if (!$project_id)
        {
            return $this->response(array(
                'success' => false,
                'message' => 'プロジェクトが指定されていません'
            ));
        }

        $project = \Model_Project::findById($project_id);
        if (!$project || $project['user_id'] != $this->current_user['id'])
        {
            return $this->response(array(
                'success' => false,
                'message' => 'プロジェクトが見つかりません'
            ));
        }

        if (\Input::method() === 'POST')
        {
            $title = \Input::post('title');
            $description = \Input::post('description', '');
            $started_at = \Input::post('started_at', null);
            $ended_at = \Input::post('ended_at', null);

            if (empty($title))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => 'タイトルを入力してください'
                ));
            }

            \Model_Todo::create($project_id, $title, $description, $started_at, $ended_at);
            
            return $this->response(array(
                'success' => true,
                'message' => 'TODOを作成しました'
            ));
        }
    }

    // TODO更新
    public function action_update($project_id = null)
    {
        $this->requireLogin();

        if (!$project_id)
        {
            \Session::set_flash('error', 'プロジェクトが指定されていません');
            \Response::redirect('home/index');
        }

        if (\Input::method() === 'POST')
        {
            $todo_id = \Input::post('todo_id');
            $title = \Input::post('title');
            $description = \Input::post('description', '');
            $started_at = \Input::post('started_at', null);
            $ended_at = \Input::post('ended_at', null);
            $is_completed = \Input::post('is_completed', 0);

            if (empty($todo_id) || empty($title))
            {
                \Session::set_flash('error', 'TODOとタイトルを選択してください');
            }
            else
            {
                \Model_Todo::update($todo_id, array(
                    'title' => $title,
                    'description' => $description,
                    'started_at' => $started_at,
                    'ended_at' => $ended_at,
                    'is_completed' => $is_completed,
                ));
                \Session::set_flash('success', 'TODOを更新しました');
            }
        }

        \Response::redirect('project/index/' . $project_id . '?mode=update');
    }

    // TODO削除（Ajaxリクエスト）
    public function action_delete($project_id = null)
    {
        $this->requireLogin();

        if (!$project_id)
        {
            return $this->response(array(
                'success' => false,
                'message' => 'プロジェクトが指定されていません'
            ));
        }

        if (\Input::method() === 'POST')
        {
            $todo_ids = \Input::post('todo_ids', array());

            if (empty($todo_ids))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '削除するTODOを選択してください'
                ));
            }

            foreach ($todo_ids as $todo_id)
            {
                \Model_Todo::delete($todo_id);
            }
            
            return $this->response(array(
                'success' => true,
                'message' => count($todo_ids) . '件のTODOを削除しました'
            ));
        }
    }

    // TODO完了/未完了切り替え（Ajax用）
    public function action_toggle($todo_id = null)
    {
        $this->requireLogin();

        if (!$todo_id)
        {
            return $this->response(array('success' => false, 'message' => 'TODOが指定されていません'));
        }

        $todo = \Model_Todo::findById($todo_id);
        if (!$todo)
        {
            return $this->response(array('success' => false, 'message' => 'TODOが見つかりません'));
        }

        $new_status = $todo['is_completed'] ? 0 : 1;
        \Model_Todo::update($todo_id, array('is_completed' => $new_status));

        return $this->response(array('success' => true, 'is_completed' => $new_status));
    }

    // JSONレスポンスを返すヘルパーメソッド
    protected function response($data)
    {
        return \Response::forge(json_encode($data), 200, array(
            'Content-Type' => 'application/json'
        ));
    }
}