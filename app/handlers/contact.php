<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name    = trim($_POST['name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$email   = trim($_POST['email'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

// التحقق من الحقول الأساسية
if ($name === '' || $phone === '' || $message === '') {
    echo "يرجى تعبئة الاسم، رقم الجوال، وتفاصيل الطلب.";
    exit;
}

// حماية من الأكواد
$name    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$phone   = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$phone = preg_replace('/[^\d+\-\s]/', '', $phone);

$email   = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$service = htmlspecialchars($service, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
$phone = trim($_POST['phone'] ?? '');
if ($phone === '') {
    echo "يرجى تعبئة رقم الجوال.";
    exit;
}


// بريد متجر من أجلك
$to      = "mousa.mc13@gmail.com"; 

// عنوان الرسالة
$subject = "طلب جديد من متجر من أجلك للهدايا";

// نص الرسالة
$body    = 
"تم استلام طلب جديد من متجر من أجلك:\n\n" .
"الاسم: {$name}\n" .
"رقم الجوال: {$phone}\n" .
"البريد الإلكتروني: " . ($email ?: "غير مذكور") . "\n" .
"نوع الخدمة المطلوبة: " . ($service ?: "غير محدد") . "\n\n" .
"تفاصيل الطلب:\n{$message}\n\n" .
"وقت الإرسال: " . date("Y-m-d H:i:s");

// الهيدر
$headers = "From: mousa.mc13@gmail.com\r\n";
if ($email !== '') {
    $headers .= "Reply-To: {$email}\r\n";
}

// إرسال البريد
if (@mail($to, $subject, $body, $headers)) {
    echo "شكرًا لتواصلك مع متجر من أجلك. تم استلام طلبك وسنقوم بالتواصل معك قريبًا.";
} else {
    echo "تم استلام طلبك، ولكن لم يتمكن النظام من إرسال رسالة بريد. يرجى التحقق من إعدادات الخادم.";
}
