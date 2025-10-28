<?php

class Model_User
{
    public static function create($user_name, $mail_address, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        return \DB::insert('users')
            ->set(array(
                'user_name' => $user_name,
                'mail_address' => $mail_address,
                'password' => $hashed_password,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ))
            ->execute();
    }

    public static function findByEmail($mail_address)
    {
        $result = \DB::select()
            ->from('users')
            ->where('mail_address', '=', $mail_address)
            ->execute()
            ->current();
        
        return $result;
    }

    public static function findById($id)
    {
        $result = \DB::select()
            ->from('users')
            ->where('id', '=', $id)
            ->execute()
            ->current();
        
        return $result;
    }

    public static function verifyPassword($mail_address, $password)
    {
        $user = self::findByEmail($mail_address);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public static function update($id, $data)
    {
        return \DB::update('users')
            ->set($data)
            ->where('id', '=', $id)
            ->execute();
    }

    public static function delete($id)
    {
        return \DB::delete('users')
            ->where('id', '=', $id)
            ->execute();
    }
}