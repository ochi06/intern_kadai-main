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

        /* モーダルのスタイル */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 30px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }

        .modal-header h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 20px;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .modal-footer {
            margin-top: 25px;
            text-align: right;
        }

        .modal-footer button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        .btn-submit {
            background-color: #007bff;
            color: white;
        }

        .btn-submit:hover {
            background-color: #0056b3;
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
        <button id="createProjectBtn" class="create-btn">新規プロジェクト作成</button>

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

    <!-- プロジェクト作成モーダル -->
    <div id="createProjectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>新規プロジェクト作成</h2>
            </div>
            <form id="createProjectForm">
                <div class="form-group">
                    <label for="project_name">プロジェクト名 <span style="color: red;">*</span></label>
                    <input type="text" id="project_name" name="project_name" required>
                </div>
                <div class="form-group">
                    <label for="description">説明</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="cancelBtn">キャンセル</button>
                    <button type="submit" class="btn-submit">作成</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // モーダル要素の取得
        const modal = document.getElementById('createProjectModal');
        const btn = document.getElementById('createProjectBtn');
        const span = document.getElementsByClassName('close')[0];
        const cancelBtn = document.getElementById('cancelBtn');
        const form = document.getElementById('createProjectForm');

        // モーダルを開く
        btn.onclick = function() {
            modal.style.display = 'block';
        }

        // モーダルを閉じる
        span.onclick = function() {
            modal.style.display = 'none';
            form.reset();
        }

        cancelBtn.onclick = function() {
            modal.style.display = 'none';
            form.reset();
        }

        // モーダル外をクリックで閉じる
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                form.reset();
            }
        }

        // フォーム送信処理
        form.onsubmit = async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const data = {
                project_name: formData.get('project_name'),
                description: formData.get('description')
            };

            try {
                const response = await fetch('<?php echo Uri::create('project/create'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    // 成功したらページをリロード
                    alert('プロジェクトを作成しました');
                    location.reload();
                } else {
                    // エラー表示
                    alert('エラー: ' + (result.error || '作成に失敗しました'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('通信エラーが発生しました');
            }
        }
    </script>
</body>
</html>

