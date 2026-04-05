<?php
/**
 * فوتر لوحة التحكم
 */
$adminJsPath = __DIR__ . '/../assets/js/admin.js';
$adminJs = is_readable($adminJsPath) ? file_get_contents($adminJsPath) : '';
?>
            </div><!-- .admin-content -->
        </main>
    </div><!-- .admin-wrapper -->
    
    <?php if ($adminJs !== ''): ?>
        <script><?php echo $adminJs; ?></script>
    <?php else: ?>
        <script src="../public/js/admin.js"></script>
    <?php endif; ?>
</body>
</html>
