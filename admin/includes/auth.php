<?php
/**
 * التحقق من جلسة المدير
 * يتم تضمين هذا الملف في كل صفحات لوحة التحكم
 */

session_start();

// التحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// التحقق وإعادة التوجيه إذا لم يكن مسجل الدخول
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// الحصول على بيانات المدير الحالي
function getCurrentAdmin() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'] ?? '',
        'full_name' => $_SESSION['admin_full_name'] ?? '',
        'email' => $_SESSION['admin_email'] ?? ''
    ];
}

// تسجيل الخروج
function logout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// إنشاء رسالة تنبيه
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

// عرض رسالة التنبيه
function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// حماية CSRF
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}