<?php
/**
 * 本番環境用のconfig設定
 * セキュリティのため、エラー詳細を非表示にする
 */

return array(
    // エラー表示を無効化（本番環境）
    'errors' => array(
        'continue_on' => array(),
        'throttle' => 10,
        'notice' => false,
    ),
    
    // プロファイリングを無効化
    'profiling' => false,
    
    // キャッシュを有効化
    'caching' => true,
    
    // ログレベルを警告以上に設定
    'log_threshold' => \Fuel::L_WARNING,
);
