<?php
require_once 'app/init.php';
secure($token);
$user = Auth::user();
Auth::signout();
$token->deleteRememberMeToken($user->id);
setcookie('rememberme',"",time() - 3600);

redirect("sign-in.php");
?>