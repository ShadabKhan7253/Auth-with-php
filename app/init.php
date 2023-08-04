<?php

session_start();

$app = __DIR__;
require_once("$app/helper_functions/utility.php");
require_once("$app/classes/Hash.class.php");
require_once("$app/classes/Mail.class.php");
require_once("$app/classes/Auth.class.php");
require_once("$app/classes/ErrorHandler.class.php");
require_once("$app/classes/Validator.class.php");
require_once("$app/classes/Database.class.php");
require_once("$app/classes/Token.class.php");
require_once("$app/classes/User.class.php");

$database = new Database();
$token = new Token($database);
User::$database = $database;
$validator = new Validator($database);
User::build();
$token->build();
?>