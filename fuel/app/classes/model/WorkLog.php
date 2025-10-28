<?php





class Model_WorkLog
{
    public static function create($project_id, $record_date, $duration_minutes, $description = '')
    {
        return \DB::insert('work_logs')
            ->set(array(
                'project_id' => $project_id,
                'record_date' => $record_date,
                'duration_minutes' => $duration_minutes,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s'),
            ))
            ->execute();
    }

    public static function findByProjectId($project_id)
    {
        $result = \DB::select()
            ->from('work_logs')
            ->where('project_id', '=', $project_id)
            ->order_by('record_date', 'desc')
            ->execute()
            ->as_array();
        
        return $result;
    }

    public static function findById($id)
    {
        $result = \DB::select()
            ->from('work_logs')
            ->where('id', '=', $id)
            ->execute()
            ->current();
        
        return $result;
    }

    public static function update($id, $data)
    {
        // updated_atを自動設定
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return \DB::update('work_logs')
            ->set($data)
            ->where('id', '=', $id)
            ->execute();
    }

    public static function delete($id)
    {
        return \DB::delete('work_logs')
            ->where('id', '=', $id)
            ->execute();
    }
}
