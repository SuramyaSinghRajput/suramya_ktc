

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #55917f;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            width: 800px;
            height: 500px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .left {
            flex: 1;
            background-color: #143b69;
            color: #fff;
            padding: 170px 40px;
            position: relative;
        }

        .left h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .left p {
            font-size: 16px;
            opacity: 0.9;
        }

        .right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form {
            width: 100%;
            max-width: 300px;
        }

        .login-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .login-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .options a {
            color: #6f42c1;
            text-decoration: none;
        }

        .login-form button {
            width: 100%;
            padding: 12px;
            background: #143b69;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-form button:hover {
            background: #55917f;
        }

        .signup {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .signup a {
            color: #6f42c1;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <h2>Welcome back!</h2>
            <p>You can sign in to access with your existing account.</p>
        </div>
        <div class="right">
            <form class="login-form" action="<?php echo e(url('admin/auth/login')); ?>" method="POST">
                <h2 style="color:#f7cd46;">Krishna Trading Co.</h2>
                <?php echo csrf_field(); ?>
                <h2>Sign In</h2>
                <input type="text" placeholder="Email" name="email" required />
                <input type="password" placeholder="Password" name="password" required />
                <div class="options">
                    <label><input type="checkbox" style="width:unset;" /> Remember me</label>
                </div>
                <button type="submit">Sign In</button>
            </form>
            <?php if(session('error')): ?>
                <p style="color: red;"><?php echo e(session('error')); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\wamp64\www\KTC\resources\views/admin/pages/auth/login.blade.php ENDPATH**/ ?>