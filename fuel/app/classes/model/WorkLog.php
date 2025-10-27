<?php

namespace Model;

use \Fuel\Core\DB;

class WorkLog
{
    public static function create($project_id, $record_date, $duration_minutes, $description = '')
    {
        return DB::insert('work_logs')
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
        $query = DB::query("SELECT * FROM work_logs WHERE project_id = ? ORDER BY record_date DESC", array($project_id));
        $result = $query->execute()->as_array();
        
        return $result;
    }

    public static function findById($id)
    {
        $query = DB::query("SELECT * FROM work_logs WHERE id = ?", array($id));
        $result = $query->execute()->current();
        
        return $result;
    }
}