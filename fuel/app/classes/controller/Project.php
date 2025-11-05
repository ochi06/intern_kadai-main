<?php

class Controller_Project extends Controller_Base
{
    public function action_index($project_id = null)
    {
        // ログインチェック
        $this->requireLogin();

        // プロジェクトID必須
        if (!$project_id)
        {
            \Session::set_flash('error', 'プロジェクトが指定されていません');
            \Response::redirect('home/index');
        }

        // プロジェクト取得
        $project = \Model_Project::findById($project_id);

        // プロジェクトの存在チェック＆所有者チェック
        if (!$project || $project['user_id'] != $this->current_user['id'])
        {
            \Session::set_flash('error', 'プロジェクトが見つかりません');
            \Response::redirect('home/index');
        }

        // 未完了TODOリスト取得
        $todos = \Model_Todo::findByProjectId($project_id);
        $incomplete_todos = array_filter($todos, function($todo) {
            return $todo['is_completed'] == 0;
        });

        // 全プロジェクト一覧取得（サイドバー用）
        $all_projects = \Model_Project::findByUserId($this->current_user['id']);

        // 今日の作業時間取得
        $today = date('Y-m-d');
        $work_logs = \Model_WorkLog::findByProjectId($project_id);
        $today_duration = 0;
        foreach ($work_logs as $log)
        {
            if ($log['record_date'] == $today)
            {
                $today_duration += $log['duration_minutes'];
            }
        }

        // カレンダー用データ
        // 年月を取得（URLパラメータから、デフォルトは今月）
        $year = \Input::get('year', date('Y'));
        $month = \Input::get('month', date('n'));
        
        // その月の最初の日と最後の日
        $first_day = "$year-$month-01";
        $last_day = date('Y-m-t', strtotime($first_day));
        
        // デバッグ
        \Log::info("Calendar date range: $first_day to $last_day");
        
        // カレンダーに渡す情報
        $calendar_todos = \Model_Todo::findByProjectIdAndMonth($project_id, $first_day, $last_day);
        
        // デバッグ
        \Log::info("Calendar todos count: " . count($calendar_todos));
        foreach ($todos as $todo) {
            \Log::info("TODO: {$todo['title']} ({$todo['started_at']} - {$todo['ended_at']})");
        }
        
        $days_in_month = date('t', strtotime($first_day));
        $first_day_of_week = date('w', strtotime($first_day));


        // モード判定
        $mode = \Input::get('mode', 'create'); // create, update, delete

        // ビューにデータを渡す
        return \View::forge('project/index', array(
            'user' => $this->current_user,
            'project' => $project,
            'todos' => array_values($incomplete_todos),
            'all_todos' => $todos, // 更新用（全TODO）
            'all_projects' => $all_projects,
            'today_duration' => $today_duration,
            'mode' => $mode,
            'year' => $year,  // カレンダー用
            'month' => $month,  // カレンダー用
            'calendar_todos' => $calendar_todos,  // カレンダー用TODO
            'work_logs' => $work_logs,  // カレンダー用作業時間
            'error' => \Session::get_flash('error'),
            'success' => \Session::get_flash('success'),
        ));
    }

    // プロジェクト新規作成（Ajaxリクエスト）
    public function action_create()
    {
        $this->requireLogin();

        if (\Input::method() === 'POST')
        {
            // JSON形式のリクエストボディを取得
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $project_name = isset($data['project_name']) ? $data['project_name'] : '';
            $description = isset($data['description']) ? $data['description'] : '';

            if (empty($project_name))
            {
                return $this->response(array(
                    'success' => false,
                    'error' => 'プロジェクト名を入力してください'
                ));
            }

            $result = \Model_Project::create(
                $this->current_user['id'],
                $project_name,
                $description
            );

            if ($result)
            {
                $project_id = $result[0];
                return $this->response(array(
                    'success' => true,
                    'message' => 'プロジェクトを作成しました',
                    'project_id' => $project_id
                ));
            }
            else
            {
                return $this->response(array(
                    'success' => false,
                    'error' => 'プロジェクトの作成に失敗しました'
                ));
            }
        }
    }

    // プロジェクト削除（Ajaxリクエスト）
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
            \Model_Project::delete($project_id);
            return $this->response(array(
                'success' => true,
                'message' => 'プロジェクトを削除しました'
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
}