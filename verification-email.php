<?php
require_once('./app/init.php');
if(isset($_GET['t'])) {
    $msg = $_GET['t'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
    </head>
    <body>
        <h2><?= $msg; ?></h2>
    </body>
    </html>
<?php
}
?>