<?php
session_start();
session_unset();
session_destroy();

if (isset($_COOKIE['userID'])) {
    setcookie('userID', '', time() - 3600, '/');
}
if (isset($_COOKIE['userName'])) {
    setcookie('userName', '', time() - 3600, '/');
}

// Chuyển hướng bằng JavaScript
echo "<script>window.location.href = '/Hoc_PHP/AppPetShop/index.php';</script>";
exit();
?>
