<?php

class Model_Todo
{
    public static function create($project_id, $title, $description, $started_at, $ended_at)
    {
        // 型の強制と検証
        $project_id = (int)$project_id;
        $title = substr(trim($title), 0, 200);
        $description = substr(trim($description), 0, 1000);
        
        // 日付の検証とnull処理
        if ($started_at && !self::isValidDate($started_at)) {
            throw new \InvalidArgumentException('Invalid started_at date');
        }
        if ($ended_at && !self::isValidDate($ended_at)) {
            throw new \InvalidArgumentException('Invalid ended_at date');
        }
        
        // 空文字列をnullに変換
        $started_at = $started_at ?: null;
        $ended_at = $ended_at ?: null;
        
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
        // 型の強制
        $project_id = (int)$project_id;
        
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
        // 型の強制と検証
        $project_id = (int)$project_id;
        
        // 日付の検証
        if (!self::isValidDate($first_day) || !self::isValidDate($last_day)) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        
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
        // 型の強制
        $id = (int)$id;
        
        $result = \DB::select()
            ->from('todos')
            ->where('id', '=', $id)
            ->execute()
            ->current();
        
        return $result;
    }

    public static function update($id, $data)
    {
        // 型の強制
        $id = (int)$id;
        
        // ホワイトリスト方式: 更新可能なカラムのみ許可
        $allowed_columns = array('title', 'description', 'started_at', 'ended_at', 'is_completed');
        $safe_data = array();
        
        foreach ($allowed_columns as $column) {
            if (array_key_exists($column, $data)) {
                $value = $data[$column];
                
                // カラムごとの検証とサニタイズ
                switch ($column) {
                    case 'title':
                        $safe_data[$column] = substr(trim($value), 0, 200);
                        break;
                    case 'description':
                        $safe_data[$column] = substr(trim($value), 0, 1000);
                        break;
                    case 'started_at':
                    case 'ended_at':
                        if ($value && !self::isValidDate($value)) {
                            throw new \InvalidArgumentException("Invalid date format for {$column}");
                        }
                        $safe_data[$column] = $value ?: null;
                        break;
                    case 'is_completed':
                        $safe_data[$column] = (int)(bool)$value;
                        break;
                }
            }
        }
        
        $safe_data['updated_at'] = date('Y-m-d H:i:s');
        
        return \DB::update('todos')
            ->set($safe_data)
            ->where('id', '=', $id)
            ->execute();
    }

    public static function delete($id)
    {
        // 型の強制
        $id = (int)$id;
        
        return \DB::delete('todos')
            ->where('id', '=', $id)
            ->execute();
    }
    
    /**
     * 日付フォーマットの検証
     */
    private static function isValidDate($date)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }
        
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
