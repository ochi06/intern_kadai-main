<?php





class Model_Todo
{
    public static function create($project_id, $title, $description, $start_date, $end_date)
    {
        return \DB::insert('todos')
            ->set(array(
                'project_id' => $project_id,
                'title' => $title,
                'description' => $description,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'is_completed' => 0,
                'created_at' => date('Y-m-d H:i:s'),
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
        // updated_atを自動設定
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
