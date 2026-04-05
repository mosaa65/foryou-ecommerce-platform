<?php
/**
 * صفحة تسجيل دخول المدير
 * متجر "من أجلك" للهدايا
 */

session_start();

// إذا كان مسجل الدخول، توجيه للداشبورد
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../app/config/database.php';

$error = '';
$username = '';

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'يرجى إدخال اسم المستخدم وكلمة المرور';
    } else {
        $db = getDB();
        
        if ($db) {
            try {
                $stmt = $db->prepare("
                    SELECT id, username, email, password_hash, full_name, is_active 
                    FROM admin_users 
                    WHERE username = ? OR email = ?
                    LIMIT 1
                ");
                $stmt->execute([$username, $username]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    if ($user['is_active']) {
                        // تسجيل الدخول ناجح
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_username'] = $user['username'];
                        $_SESSION['admin_email'] = $user['email'];
                        $_SESSION['admin_full_name'] = $user['full_name'];
                        
                        // تحديث آخر تسجيل دخول
                        $updateStmt = $db->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
                        $updateStmt->execute([$user['id']]);
                        
                        header('Location: index.php');
                        exit;
                    } else {
                        $error = 'هذا الحساب معطل. تواصل مع المسؤول.';
                    }
                } else {
                    $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
                }
            } catch (PDOException $e) {
                $error = 'حدث خطأ في الاتصال بقاعدة البيانات';
                error_log("Login Error: " . $e->getMessage());
            }
        } else {
            $error = 'تعذر الاتصال بقاعدة البيانات';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | لوحة تحكم من أجلك</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #7c3aed;
            --primary-dark: #6d28d9;
            --primary-light: #a855f7;
            --bg-dark: #0f1115;
            --bg-card: #1a1d24;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #2d3748;
            --error: #ef4444;
            --success: #22c55e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: 
                radial-gradient(ellipse at top right, rgba(124, 58, 237, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at bottom left, rgba(168, 85, 247, 0.1) 0%, transparent 50%);
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .login-logo img {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            margin-bottom: 16px;
            box-shadow: 0 8px 32px rgba(124, 58, 237, 0.3);
        }
        
        .login-logo h1 {
            color: var(--text);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .login-logo p {
            color: var(--text-muted);
            font-size: 15px;
        }
        
        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px 32px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .form-input {
            width: 100%;
            padding: 14px 18px;
            font-size: 15px;
            font-family: inherit;
            background: var(--bg-dark);
            border: 2px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15);
        }
        
        .form-input::placeholder {
            color: var(--text-muted);
        }
        
        .input-icon-wrapper {
            position: relative;
        }
        
        .input-icon-wrapper .form-input {
            padding-right: 48px;
        }
        
        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--error);
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            font-size: 16px;
            font-weight: 700;
            font-family: inherit;
            color: white;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: var(--text-muted);
            font-size: 13px;
        }
        
        .login-footer a {
            color: var(--primary-light);
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="../public/favicon.png" alt="من أجلك">
            <h1>لوحة التحكم</h1>
            <p>متجر من أجلك للهدايا</p>
        </div>
        
        <div class="login-card">
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">اسم المستخدم أو البريد الإلكتروني</label>
                    <div class="input-icon-wrapper">
                        <input 
                            type="text" 
                            name="username" 
                            class="form-input" 
                            placeholder="أدخل اسم المستخدم"
                            value="<?php echo htmlspecialchars($username); ?>"
                            required
                            autofocus
                        >
                        <i class="fa-solid fa-user input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">كلمة المرور</label>
                    <div class="input-icon-wrapper">
                        <input 
                            type="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="أدخل كلمة المرور"
                            required
                        >
                        <i class="fa-solid fa-lock input-icon"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    تسجيل الدخول
                </button>
            </form>
        </div>
        
        <div class="login-footer">
            <a href="../index.php">← العودة للموقع</a>
        </div>
    </div>
</body>
</html>
