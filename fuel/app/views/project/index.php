<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($project['project_name'], ENT_QUOTES, 'UTF-8'); ?> - TODOアプリ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
        }
        
        /* ヘッダー */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
        }
        .header h1 {
            font-size: 24px;
        }
        .home-btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .home-btn:hover {
            background-color: #218838;
        }
        
        /* カレンダーエリア（後回し） */
        .calendar-area {
            padding: 20px;
            background-color: #f8f9fa;
            text-align: center;
            color: #999;
        }
        
        /* メインエリア */
        .main-area {
            display: grid;
            grid-template-columns: 2fr 1.5fr 1.5fr;
            gap: 20px;
            padding: 20px;
            min-height: 500px;
        }
        
        /* 共通スタイル */
        .section {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: white;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        
        /* 左：TODOリスト */
        .todo-list {
            list-style: none;
        }
        .todo-item {
            padding: 10px;
            margin-bottom: 8px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .todo-item:hover {
            background-color: #e9ecef;
        }
        .todo-checkbox {
            width: 18px;
            height: 18px;
        }
        .todo-title {
            font-weight: bold;
            flex: 1;
        }
        .todo-dates {
            font-size: 12px;
            color: #666;
        }
        .no-todos {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        /* 中央：作業時間 */
        .work-time-display {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        /* 右：プロジェクト管理 */
        .project-list {
            list-style: none;
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 15px;
        }
        .project-item {
            padding: 8px;
            margin-bottom: 5px;
            background-color: #f8f9fa;
            border-radius: 4px;
            cursor: pointer;
        }
        .project-item:hover {
            background-color: #e9ecef;
        }
        .project-item.active {
            background-color: #007bff;
            color: white;
        }
        .project-item a {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn-group .btn {
            flex: 1;
        }
        
        /* モーダル */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        .modal-footer {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .modal-footer .btn {
            flex: 1;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        /* メッセージ */
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* モード切り替えボタン */
        .mode-buttons {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }
        .mode-btn {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            cursor: pointer;
            border-radius: 4px;
        }
        .mode-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <!-- ヘッダー -->
    <div class="header">
        <h1><?php echo htmlspecialchars($project['project_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <a href="<?php echo Uri::create('home/index'); ?>" class="home-btn">ホームへ</a>
    </div>

    <!-- カレンダーエリア（後回し） -->
    <div class="calendar-area">
        カレンダーエリア（後で実装）
    </div>

    <!-- メッセージ -->
    <?php if (isset($success)): ?>
        <div style="padding: 0 20px;">
            <div class="message success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div style="padding: 0 20px;">
            <div class="message error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    <?php endif; ?>

    <!-- メインエリア -->
    <div class="main-area">
        <!-- 左：TODOリスト -->
        <div class="section">
            <div class="section-title">TODOリスト（未完了）</div>
            
            <?php if (empty($todos)): ?>
                <div class="no-todos">
                    未完了のTODOはありません
                </div>
            <?php else: ?>
                <ul class="todo-list" id="todoList">
                    <?php foreach ($todos as $todo): ?>
                        <li class="todo-item">
                            <input type="checkbox" class="todo-checkbox delete-checkbox" value="<?php echo $todo['id']; ?>" style="display: none;">
                            <div style="flex: 1;">
                                <div class="todo-title"><?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <?php if ($todo['started_at'] || $todo['ended_at']): ?>
                                    <div class="todo-dates">
                                        <?php if ($todo['started_at']): ?>
                                            開始: <?php echo date('Y/m/d', strtotime($todo['started_at'])); ?>
                                        <?php endif; ?>
                                        <?php if ($todo['ended_at']): ?>
                                            / 終了: <?php echo date('Y/m/d', strtotime($todo['ended_at'])); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- 中央：作業時間記録 -->
        <div class="section">
            <div class="section-title">作業時間</div>
            <div class="work-time-display">
                今日: <?php echo $today_duration; ?> 分
            </div>
            
            <form method="POST" action="<?php echo Uri::create('worklog/create/' . $project['id']); ?>">
                <div class="form-group">
                    <label>作業時間（分）</label>
                    <input type="number" name="duration" min="1" required>
                </div>
                <div class="form-group">
                    <label>TODO選択</label>
                    <select name="todo_id">
                        <option value="">選択してください</option>
                        <?php foreach ($all_todos as $todo): ?>
                            <option value="<?php echo $todo['id']; ?>">
                                <?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">記録</button>
            </form>
        </div>

        <!-- 右：プロジェクト管理 -->
        <div class="section">
            <div class="section-title">プロジェクト一覧</div>
            
            <ul class="project-list">
                <?php foreach ($all_projects as $proj): ?>
                    <li class="project-item <?php echo ($proj['id'] == $project['id']) ? 'active' : ''; ?>">
                        <a href="<?php echo Uri::create('project/index/' . $proj['id']); ?>">
                            <?php echo htmlspecialchars($proj['project_name'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="btn-group">
                <button class="btn btn-success" onclick="openProjectCreateModal()">新規作成</button>
                <button class="btn btn-danger" onclick="openProjectDeleteModal()">削除</button>
            </div>

            <div class="section-title" style="margin-top: 20px;">TODO操作</div>
            <div class="mode-buttons">
                <button class="mode-btn <?php echo ($mode == 'create') ? 'active' : ''; ?>" onclick="switchMode('create')">新規作成</button>
                <button class="mode-btn <?php echo ($mode == 'update') ? 'active' : ''; ?>" onclick="switchMode('update')">更新</button>
                <button class="mode-btn <?php echo ($mode == 'delete') ? 'active' : ''; ?>" onclick="switchMode('delete')">削除</button>
            </div>

            <!-- TODO新規作成ボタン -->
            <div id="createModeArea" style="<?php echo ($mode == 'create') ? '' : 'display:none;'; ?>">
                <button class="btn btn-success" style="margin-top: 10px;" onclick="openTodoCreateModal()">TODO作成</button>
            </div>

            <!-- TODO削除ボタン -->
            <div id="deleteModeArea" style="<?php echo ($mode == 'delete') ? '' : 'display:none;'; ?>">
                <button class="btn btn-danger" style="margin-top: 10px;" onclick="openTodoDeleteModal()">選択したTODOを削除</button>
            </div>

            <!-- TODO更新エリア -->
            <div id="updateModeArea" style="<?php echo ($mode == 'update') ? '' : 'display:none;'; ?>">
                <form method="POST" action="<?php echo Uri::create('todo/update/' . $project['id']); ?>" style="margin-top: 10px;">
                    <div class="form-group">
                        <label for="updateTodoSelect">TODO選択 <span style="color: red;">*</span></label>
                        <select name="todo_id" id="updateTodoSelect" required onchange="loadTodoData(this.value)">
                            <option value="">選択してください</option>
                            <?php foreach ($all_todos as $todo): ?>
                                <option value="<?php echo $todo['id']; ?>" 
                                        data-title="<?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-description="<?php echo htmlspecialchars($todo['description'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-started="<?php echo $todo['started_at']; ?>"
                                        data-ended="<?php echo $todo['ended_at']; ?>"
                                        data-completed="<?php echo $todo['is_completed']; ?>">
                                    <?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8'); ?>
                                    <?php echo $todo['is_completed'] ? '（完了）' : '（未完了）'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="updateTitle">タイトル <span style="color: red;">*</span></label>
                        <input type="text" name="title" id="updateTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="updateDescription">説明</label>
                        <input type="text" name="description" id="updateDescription">
                    </div>
                    <div class="form-group">
                        <label for="updateStartedAt">開始日</label>
                        <input type="date" name="started_at" id="updateStartedAt">
                    </div>
                    <div class="form-group">
                        <label for="updateEndedAt">終了日</label>
                        <input type="date" name="ended_at" id="updateEndedAt">
                    </div>
                    <div class="form-group">
                        <label for="updateCompleted">
                            <input type="checkbox" name="is_completed" id="updateCompleted" value="1">
                            完了
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">更新</button>
                </form>
            </div>
        </div>
    </div>

    <!-- モーダル：プロジェクト新規作成 -->
    <div id="projectCreateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">新規プロジェクト作成</div>
            <form id="projectCreateForm">
                <div class="form-group">
                    <label>プロジェクト名 <span style="color: red;">*</span></label>
                    <input type="text" name="project_name" required>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <input type="text" name="description">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('projectCreateModal')">キャンセル</button>
                    <button type="submit" class="btn btn-success">作成</button>
                </div>
            </form>
        </div>
    </div>

    <!-- モーダル：プロジェクト削除確認 -->
    <div id="projectDeleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">プロジェクト削除確認</div>
            <p>本当にこのプロジェクトを削除しますか？</p>
            <p><strong><?php echo htmlspecialchars($project['project_name'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <p style="color: red; font-size: 14px;">※ 関連するTODOと作業ログもすべて削除されます</p>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('projectDeleteModal')">キャンセル</button>
                <button type="button" class="btn btn-danger" onclick="deleteProject()">削除</button>
            </div>
        </div>
    </div>

    <!-- モーダル：TODO新規作成 -->
    <div id="todoCreateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">TODO新規作成</div>
            <form id="todoCreateForm">
                <div class="form-group">
                    <label for="todoTitle">タイトル <span style="color: red;">*</span></label>
                    <input type="text" id="todoTitle" name="title" required>
                </div>
                <div class="form-group">
                    <label for="todoDescription">説明</label>
                    <input type="text" id="todoDescription" name="description">
                </div>
                <div class="form-group">
                    <label for="todoStartedAt">開始日</label>
                    <input type="date" id="todoStartedAt" name="started_at">
                </div>
                <div class="form-group">
                    <label for="todoEndedAt">終了日</label>
                    <input type="date" id="todoEndedAt" name="ended_at">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('todoCreateModal')">キャンセル</button>
                    <button type="submit" class="btn btn-success">作成</button>
                </div>
            </form>
        </div>
    </div>

    <!-- モーダル：TODO削除確認 -->
    <div id="todoDeleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">TODO削除確認</div>
            <p id="todoDeleteMessage">選択したTODOを削除しますか？</p>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('todoDeleteModal')">キャンセル</button>
                <button type="button" class="btn btn-danger" onclick="deleteTodos()">削除</button>
            </div>
        </div>
    </div>

    <script>
        const projectId = <?php echo $project['id']; ?>;

        // モーダル開閉
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
        }
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // モード切り替え
        function switchMode(mode) {
            window.location.href = '<?php echo Uri::create('project/index/' . $project['id']); ?>?mode=' + mode;
        }

        // プロジェクト新規作成
        function openProjectCreateModal() {
            openModal('projectCreateModal');
        }
        document.getElementById('projectCreateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('<?php echo Uri::create('project/create'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?php echo Uri::create('project/index/'); ?>' + data.project_id;
                } else {
                    alert(data.message);
                }
            });
        });

        // プロジェクト削除
        function openProjectDeleteModal() {
            openModal('projectDeleteModal');
        }
        function deleteProject() {
            fetch('<?php echo Uri::create('project/delete/' . $project['id']); ?>', {
                method: 'POST'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?php echo Uri::create('home/index'); ?>';
                } else {
                    alert(data.message);
                }
            });
        }

        // TODO新規作成
        function openTodoCreateModal() {
            openModal('todoCreateModal');
        }
        document.getElementById('todoCreateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('<?php echo Uri::create('todo/create/' . $project['id']); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                console.log('Response status:', res.status);
                console.log('Response headers:', res.headers.get('content-type'));
                return res.text(); // まずテキストとして取得
            })
            .then(text => {
                console.log('Response text:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message);
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    console.error('Response was:', text);
                    alert('エラーが発生しました。コンソールを確認してください。');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('通信エラーが発生しました');
            });
        });

        // TODO削除
        function openTodoDeleteModal() {
            const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('削除するTODOを選択してください');
                return;
            }
            document.getElementById('todoDeleteMessage').textContent = checkboxes.length + '件のTODOを削除しますか？';
            openModal('todoDeleteModal');
        }
        function deleteTodos() {
            const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
            const todoIds = Array.from(checkboxes).map(cb => cb.value);
            
            const formData = new FormData();
            todoIds.forEach(id => formData.append('todo_ids[]', id));
            
            fetch('<?php echo Uri::create('todo/delete/' . $project['id']); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        // 削除モード時のチェックボックス表示切り替え
        function updateCheckboxVisibility() {
            const mode = new URLSearchParams(window.location.search).get('mode') || 'create';
            const checkboxes = document.querySelectorAll('.delete-checkbox');
            checkboxes.forEach(cb => {
                cb.style.display = (mode === 'delete') ? 'block' : 'none';
            });
            
            document.getElementById('createModeArea').style.display = (mode === 'create') ? 'block' : 'none';
            document.getElementById('deleteModeArea').style.display = (mode === 'delete') ? 'block' : 'none';
            
            // 更新モードエリアの表示切り替え
            const updateArea = document.getElementById('updateModeArea');
            if (updateArea) {
                updateArea.style.display = (mode === 'update') ? 'block' : 'none';
            }
        }
        updateCheckboxVisibility();

        // TODO更新時のデータロード
        function loadTodoData(todoId) {
            const select = document.getElementById('updateTodoSelect');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                document.getElementById('updateTitle').value = option.getAttribute('data-title');
                document.getElementById('updateDescription').value = option.getAttribute('data-description');
                document.getElementById('updateStartedAt').value = option.getAttribute('data-started');
                document.getElementById('updateEndedAt').value = option.getAttribute('data-ended');
                document.getElementById('updateCompleted').checked = option.getAttribute('data-completed') == '1';
            }
        }
    </script>
</body>
</html>