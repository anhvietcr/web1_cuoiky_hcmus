<!DOCTYPE html>
<html lang="vn">
<head>
    <title>Chào mừng bạn đến với website | F5 Team - Web 1 - HCMUS</title>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="asset/style.css">
	<link rel="stylesheet" type="text/css" href="plugins/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
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