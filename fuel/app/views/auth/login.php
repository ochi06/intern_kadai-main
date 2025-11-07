<?php ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .form-group{
            margin-bottom: 15px;
        }
        label{
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"]{
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffe6e6;
            border-radius: 4px;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>ログイン</h1>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="<?php echo Uri::create('auth/login'); ?>">
        <?php echo \Form::csrf(); ?>
        <div class="form-group">
            <label for="mail_address">メールアドレス:</label>
            <input type="email" id="mail_address" name="mail_address" required>
        </div>
        <div class="form-group">
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">ログイン</button>
    </form>


    <div class="link">
        <a href="<?php echo Uri::create('auth/register'); ?>">新規登録</a>
    </div>
</body>
</html>