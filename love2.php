<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: love1.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Hardcoded credentials
    $validUsername = 'user';
    $validPasswordHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password = "password"
    
    if ($_POST['username'] === $validUsername && 
        password_verify($_POST['password'], $validPasswordHash)) {
        $_SESSION["user"] = $validUsername;
        header("Location: love1.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Love Theme Login</title>
    <style>
         body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff99cc, #ff1a66);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255, 0, 102, 0.3);
            width: 300px;
            text-align: center;
        }

        h1 {
            color: #ff1a66;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .heart {
            color: #ff1a66;
            font-size: 24px;
            margin: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ff99cc;
            border-radius: 25px;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #ff1a66;
            box-shadow: 0 0 8px #ff6699;
        }

        button {
            background-color: #ff1a66;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #ff0066;
            transform: scale(1.05);
        }

        .links {
            margin-top: 20px;
        }

        a {
            color: #ff6699;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Love Login ❤️</h1>
        <?php if ($error): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="💌 Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="🔒 Password" required>
            </div>
            <button type="submit">Sweetheart Login</button>
        </form>
    </div>
</body>
</html>