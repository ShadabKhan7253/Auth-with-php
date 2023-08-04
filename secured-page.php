<?php
require_once("./app/init.php");
secure($token);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secured Page</title>
</head>
<body>
    <h1>Welcome <?= Auth::user()->username; ?></h1>
    <p>This is secured page, you cannot acccess without login</p>
</body>
</html>