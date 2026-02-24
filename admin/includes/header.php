<?php
/**
 * هيدر لوحة التحكم
 */
require_once __DIR__ . '/auth.php';
requireLogin();

$currentAdmin = getCurrentAdmin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'لوحة التحكم'; ?> | من أجلك للهدايا</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    
    <link rel="icon" type="image/png" href="../public/favicon.png">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <?php include __DIR__ . '/sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                
                <div class="topbar-title">
                    <h1><?php echo $pageTitle ?? 'لوحة التحكم'; ?></h1>
                </div>
                
                <div class="topbar-actions">
                    <a href="../index.php" target="_blank" class="topbar-btn" title="عرض الموقع">
                        <i class="fa-solid fa-external-link"></i>
                    </a>
                    
                    <div class="admin-dropdown">
                        <button class="admin-profile-btn">
                            <span class="admin-avatar">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <span class="admin-name"><?php echo htmlspecialchars($currentAdmin['full_name'] ?? $currentAdmin['username']); ?></span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="logout.php" class="dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                تسجيل الخروج
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <?php $flash = getFlashMessage(); if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <i class="fa-solid fa-<?php echo $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($flash['message']); ?>
                    <button class="alert-close">&times;</button>
                </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <div class="admin-content">