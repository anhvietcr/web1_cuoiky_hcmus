<!-- MEMBER -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();

// Form Request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $user = new UserController();
    $message = $user->confirm($_GET);
    
    if ($message == 1)
    { 
       header('Location: login.php');
    }
    else
    {
       header('Location: register.php');
    }
}

?>

