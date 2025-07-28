<?php
require_once 'config/class.user.php';

$auth_user = new USER();
$auth_user->logout(); 

header('Location: index.php');
exit;

?>