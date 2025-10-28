<?php

namespace Model;

use \Fuel\Core\DB;

class User
{
    public static function create($user_name, $mail_address, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        return DB::insert('users')
            ->set(array(
                'user_name' => $user_name,
                'mail_address' => $mail_address,
                'password' => $hashed_password,
                'created_at' => date('Y-m-d H:i:s'),
            ))
            ->execute();
    }

    public static function findByEmail($mail_address)
    {
        $query = DB::query("SELECT * FROM users WHERE mail_address = ?", array($mail_address));
        $result = $query->execute()->current();
        
        return $result;
    }

    public static function findById($id)
    {
        $query = DB::query("SELECT * FROM users WHERE id = ?", array($id));
        $result = $query->execute()->current();
        
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
        return DB::update('users')
            ->set($data)
            ->where('id', '=', $id)
            ->execute();
    }

    public static function delete($id)
    {
        return DB::delete('users')
            ->where('id', '=', $id)
            ->execute();
    }
}