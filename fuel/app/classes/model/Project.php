<?php

class Model_Project
{
    public static function create($user_id, $project_name, $description = '')
    {
        return \DB::insert('projects')
            ->set(array(
                'user_id' => $user_id,
                'project_name' => $project_name,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ))
            ->execute();
    }

    public static function findByUserId($user_id)
    {
        $result = \DB::select()
            ->from('projects')
            ->where('user_id', '=', $user_id)
            ->order_by('created_at', 'desc')
            ->execute()
            ->as_array();
        
        return $result;
    }

    public static function findById($id)
    {
        $result = \DB::select()
            ->from('projects')
            ->where('id', '=', $id)
            ->execute()
            ->current();
        
        return $result;
    }

    public static function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return \DB::update('projects')
        ->set($data)
        ->where('id', '=', $id)
        ->execute();
    }

    public static function delete($id)
    {
        return \DB::delete('projects')
            ->where('id', '=', $id)
            ->execute();
    }
}
