<?php

namespace Model;

use \Fuel\Core\DB;

class Todo
{
    public static function create($project_id, $title, $description, $start_date, $end_date)
    {
        return DB::insert('todos')
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
        $query = DB::query("SELECT * FROM todos WHERE project_id = ? ORDER BY created_at DESC", array($project_id));
        $result = $query->execute()->as_array();
        
        return $result;
    }

    public static function findById($id)
    {
        $query = DB::query("SELECT * FROM todos WHERE id = ?", array($id));
        $result = $query->execute()->current();
        
        return $result;
    }
}