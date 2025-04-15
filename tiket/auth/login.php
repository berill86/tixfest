<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "tixfest";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
    $data = mysqli_fetch_assoc($query);

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['admin'] = $data['username'];
        header('Location:../admin/dashboard.php');
        exit;
    } else {
        echo "<script>alert('Username atau password salah');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - TixFest</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            background-color: #ffffff;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #FFCC00;
            text-align: center;
        }

        .login-title {
            font-size: 24px;
            color: #0d1b2a;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .subtitle {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        label {
            font-weight: 600;
            color: #0d1b2a;
            font-size: 14px;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #0d1b2a;
            outline: none;
            background-color: #ffffff;
        }

        .btn-login {
            background-color: #FFCC00;
            color: #0d1b2a;
            font-weight: bold;
            font-size: 15px;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #f2bb00;
        }

        .back-link {
            display: block;
            margin-top: 18px;
            color: #6b7280;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .back-link:hover {
            color: #0d1b2a;
        }

        .footer {
            margin-top: 24px;
            font-size: 13px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-title">TixFest Admin</div>
        <div class="subtitle">Login untuk mengelola event</div>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required />
            </div>

            <button type="submit" name="login" class="btn-login">Login</button>
        </form>

        <a class="back-link" href="../index.php">← Kembali ke Beranda</a>

        <div class="footer">© 2025 TixFest. All rights reserved.</div>
    </div>
</body>
</html>
