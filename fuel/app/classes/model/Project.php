<?php

namespace Model;

use \Fuel\Core\DB;

class Project
{
    public static function create($user_id, $project_name, $description = '')
    {
        return DB::insert('projects')
            ->set(array(
                'user_id' => $user_id,
                'project_name' => $project_name,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s'),
            ))
            ->execute();
    }

    public static function findByUserId($user_id)
    {
        $query = DB::query("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC", array($user_id));
        $result = $query->execute()->as_array();
        
        return $result;
    }

    public static function findById($id)
    {
        $query = DB::query("SELECT * FROM projects WHERE id = ?", array($id));
        $result = $query->execute()->current();
        
        return $result;
    }
}