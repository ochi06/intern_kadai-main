<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ホーム - TODOアプリ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .logout-btn {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .welcome {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .create-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        .create-btn:hover {
            background-color: #218838;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        .project-list {
            list-style: none;
            padding: 0;
        }
        .project-item {
            margin-bottom: 10px;
        }
        .project-link {
            display: block;
            padding: 15px;
            background-color: #f8f9fa;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            border-left: 4px solid #007bff;
            transition: background-color 0.2s;
        }
        .project-link:hover {
            background-color: #e9ecef;
        }
        .project-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .project-description {
            color: #666;
            font-size: 14px;
        }
        .no-projects {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .error {
            color: red;
            padding: 10px;
            background-color: #ffe6e6;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- ヘッダー -->
    <div class="header">
        <h1>ホーム</h1>
        <a href="<?php echo Uri::create('auth/logout'); ?>" class="logout-btn">ログアウト</a>
    </div>

    <!-- メインコンテンツ -->
    <div class="container">
        <!-- ようこそメッセージ -->
        <div class="welcome">
            ようこそ、<?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?>さん
        </div>

        <!-- エラー表示 -->
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <!-- 新規プロジェクト作成ボタン -->
        <a href="<?php echo Uri::create('project/create'); ?>" class="create-btn">新規プロジェクト作成</a>

        <!-- プロジェクト一覧 -->
        <div class="section-title">プロジェクト一覧</div>

        <?php if (empty($projects)): ?>
            <div class="no-projects">
                <p>プロジェクトがまだありません。</p>
                <p>上のボタンから新しいプロジェクトを作成してください。</p>
            </div>
        <?php else: ?>
            <ul class="project-list">
                <?php foreach ($projects as $project): ?>
                    <li class="project-item">
                        <a href="<?php echo Uri::create('project/index/' . $project['id']); ?>" class="project-link">
                            <div class="project-name">
                                <?php echo htmlspecialchars($project['project_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <?php if (!empty($project['description'])): ?>
                                <div class="project-description">
                                    <?php echo htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>

