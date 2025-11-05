<?php





class Model_Todo
{
    public static function create($project_id, $title, $description, $started_at, $ended_at)
    {
        return \DB::insert('todos')
            ->set(array(
                'project_id' => $project_id,
                'title' => $title,
                'description' => $description,
                'started_at' => $started_at,
                'ended_at' => $ended_at,
                'is_completed' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ))
            ->execute();
    }

    public static function findByProjectId($project_id)
    {
        $result = \DB::select()
            ->from('todos')
            ->where('project_id', '=', $project_id)
            ->order_by('created_at', 'desc')
            ->execute()
            ->as_array();
        
        return $result;
    }

    public static function findByProjectIdAndMonth($project_id, $first_day, $last_day)
    {
        $result = \DB::select()
            ->from('todos')
            ->where('project_id', '=', $project_id)  // プロジェクトIDでフィルタ
            ->where_open()  // OR条件グループの開始
                ->where_open()
                    ->where('started_at', '>=', $first_day)
                    ->where('started_at', '<=', $last_day)
                ->where_close()
                ->or_where_open()
                    ->where('ended_at', '>=', $first_day)
                    ->where('ended_at', '<=', $last_day)
                ->where_close()
                ->or_where_open()
                    ->where('started_at', '<', $first_day)
                    ->where('ended_at', '>', $last_day)
                ->where_close()
            ->where_close()  // OR条件グループの終了
            ->order_by('created_at', 'desc')
            ->execute()
            ->as_array();

        return $result;
    }

    public static function findById($id)
    {
        $result = \DB::select()
            ->from('todos')
            ->where('id', '=', $id)
            ->execute()
            ->current();
        
        return $result;
    }

    public static function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return \DB::update('todos')
            ->set($data)
            ->where('id', '=', $id)
            ->execute();
    }

    public static function delete($id)
    {
        return \DB::delete('todos')
            ->where('id', '=', $id)
            ->execute();
    }
}
