<!DOCTYPE html>
<html lang="vn">
<head>
    <title>Chào mừng bạn đến với website | 1660765 - Web 1 - HCMUS</title>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="asset/style.css">
	<link rel="stylesheet" type="text/css" href="plugins/bootstrap/css/bootstrap.css">
</head>
<body>
<div class="container-fluid">
<!-- DIRECTION -->
<?php
    if (isset($_COOKIE['login'])) {
        header('Location: dashboard.php');
    } else {
        header('Location: login.php');
    }
?>
</div>
</body>
</html>