<?php

class Controller_Todo extends Controller_Base
{
    // TODO新規作成（Ajaxリクエスト）
    public function action_create($project_id = null)
    {
        $this->requireLogin();

        // プロジェクトIDの型チェック
        $project_id = (int)$project_id;
        if ($project_id <= 0)
        {
            return $this->response(array(
                'success' => false,
                'message' => '無効なプロジェクトIDです'
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
            // 入力値の取得と型チェック
            $title = trim(\Input::post('title'));
            $description = trim(\Input::post('description', ''));
            $started_at = \Input::post('started_at', null);
            $ended_at = \Input::post('ended_at', null);

            // バリデーション
            if (empty($title))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => 'タイトルを入力してください'
                ));
            }

            if (strlen($title) > 200)
            {
                return $this->response(array(
                    'success' => false,
                    'message' => 'タイトルは200文字以内で入力してください'
                ));
            }

            if (strlen($description) > 1000)
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '説明は1000文字以内で入力してください'
                ));
            }

            // 日付形式の検証
            if ($started_at && !$this->isValidDate($started_at))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '開始日の形式が正しくありません'
                ));
            }

            if ($ended_at && !$this->isValidDate($ended_at))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '終了日の形式が正しくありません'
                ));
            }

            try {
                \Model_Todo::create($project_id, $title, $description, $started_at, $ended_at);
                
                return $this->response(array(
                    'success' => true,
                    'message' => 'TODOを作成しました'
                ));
            } catch (\Exception $e) {
                \Log::error('Todo creation failed: ' . $e->getMessage());
                return $this->response(array(
                    'success' => false,
                    'message' => 'TODOの作成に失敗しました'
                ));
            }
        }
    }

    // TODO更新（Ajaxリクエスト）
    public function action_update($project_id = null)
    {
        $this->requireLogin();

        // プロジェクトIDの型チェック
        $project_id = (int)$project_id;
        if ($project_id <= 0)
        {
            return $this->response(array(
                'success' => false,
                'message' => '無効なプロジェクトIDです'
            ));
        }

        if (\Input::method() === 'POST')
        {
            // 入力値の取得と型チェック
            $todo_id = (int)\Input::post('todo_id');
            $title = trim(\Input::post('title'));
            $description = trim(\Input::post('description', ''));
            $started_at = \Input::post('started_at', null);
            $ended_at = \Input::post('ended_at', null);
            $is_completed = (int)\Input::post('is_completed', 0);

            // バリデーション
            if ($todo_id <= 0)
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '無効なTODO IDです'
                ));
            }

            if (empty($title))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => 'タイトルを入力してください'
                ));
            }

            if (strlen($title) > 200)
            {
                return $this->response(array(
                    'success' => false,
                    'message' => 'タイトルは200文字以内で入力してください'
                ));
            }

            if (strlen($description) > 1000)
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '説明は1000文字以内で入力してください'
                ));
            }

            // 日付形式の検証
            if ($started_at && !$this->isValidDate($started_at))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '開始日の形式が正しくありません'
                ));
            }

            if ($ended_at && !$this->isValidDate($ended_at))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '終了日の形式が正しくありません'
                ));
            }

            try {
                \Model_Todo::update($todo_id, array(
                    'title' => $title,
                    'description' => $description,
                    'started_at' => $started_at,
                    'ended_at' => $ended_at,
                    'is_completed' => $is_completed ? 1 : 0,
                ));

                return $this->response(array(
                    'success' => true,
                    'message' => 'TODOを更新しました'
                ));
            } catch (\Exception $e) {
                \Log::error('Todo update failed: ' . $e->getMessage());
                return $this->response(array(
                    'success' => false,
                    'message' => 'TODOの更新に失敗しました'
                ));
            }
        }
    }

    // TODO削除（Ajaxリクエスト）
    public function action_delete($project_id = null)
    {
        $this->requireLogin();

        // プロジェクトIDの型チェック
        $project_id = (int)$project_id;
        if ($project_id <= 0)
        {
            return $this->response(array(
                'success' => false,
                'message' => '無効なプロジェクトIDです'
            ));
        }

        if (\Input::method() === 'POST')
        {
            $todo_ids = \Input::post('todo_ids', array());

            // 配列チェック
            if (!is_array($todo_ids) || empty($todo_ids))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '削除するTODOを選択してください'
                ));
            }

            // 全ての要素を整数に変換
            $safe_todo_ids = array_map('intval', $todo_ids);
            $safe_todo_ids = array_filter($safe_todo_ids, function($id) {
                return $id > 0;
            });

            if (empty($safe_todo_ids))
            {
                return $this->response(array(
                    'success' => false,
                    'message' => '有効なTODO IDがありません'
                ));
            }

            try {
                foreach ($safe_todo_ids as $todo_id)
                {
                    \Model_Todo::delete($todo_id);
                }
                
                return $this->response(array(
                    'success' => true,
                    'message' => count($safe_todo_ids) . '件のTODOを削除しました'
                ));
            } catch (\Exception $e) {
                \Log::error('Todo deletion failed: ' . $e->getMessage());
                return $this->response(array(
                    'success' => false,
                    'message' => 'TODOの削除に失敗しました'
                ));
            }
        }
    }

    // TODO完了/未完了切り替え（Ajax用）
    public function action_toggle($todo_id = null)
    {
        $this->requireLogin();

        try {
            // 入力の型検証
            $todo_id = (int)$todo_id;
            
            if (!$todo_id || $todo_id <= 0)
            {
                \Log::warning('Invalid todo_id in toggle: ' . var_export($todo_id, true));
                return $this->response(array('success' => false, 'message' => 'TODOが指定されていません'));
            }

            $todo = \Model_Todo::findById($todo_id);
            if (!$todo)
            {
                \Log::warning('Todo not found in toggle: ' . $todo_id);
                return $this->response(array('success' => false, 'message' => 'TODOが見つかりません'));
            }

            $new_status = $todo['is_completed'] ? 0 : 1;
            \Model_Todo::update($todo_id, array('is_completed' => $new_status));

            return $this->response(array('success' => true, 'is_completed' => $new_status));
        }
        catch (\Exception $e)
        {
            \Log::error('Toggle failed: ' . $e->getMessage());
            return $this->response(array(
                'success' => false,
                'message' => 'TODOの更新に失敗しました'
            ));
        }
    }

    // JSONレスポンスを返すヘルパーメソッド
    protected function response($data)
    {
        return \Response::forge(json_encode($data), 200, array(
            'Content-Type' => 'application/json'
        ));
    }

    // 日付形式のバリデーション
    protected function isValidDate($date)
    {
        if (empty($date)) {
            return true;
        }
        
        // Y-m-d 形式の検証
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }
        
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}