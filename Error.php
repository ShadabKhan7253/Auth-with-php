<?php
require_once('./app/init.php');
if(isset($_GET['t'])) {
    $msg = $_GET['t'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap"
        rel="stylesheet"
        />
        <title>404</title>
        <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Roboto;
            background-color: #eee;
            color: #999;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .error-body {
            margin-top: 8rem;
        }
        .material-icons {
            font-size: 10rem;
            text-align: center;
        }
        .emoji {
            text-align: center;
        }
        .center {
            text-align: center;
        }
        .error-code {
            font-size: 5rem;
            font-weight: 500;
        }
        .error-messgae {
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .error-info {
            font-size: 1.2rem;
        }
        </style>
    </head>
    <body>
        <div class="container">
        <div class="error-body">
            <p class="emoji center"><i class="material-icons">sentiment_very_dissatisfied</i></p>
            <h1 class="error-code center"><?= $msg; ?></h1>
            <p class="error-messgae center">Page not found</p>
            <p class="error-info center">
            The page you are looking for doesn't exist or an other error occured.
            </p>
        </div>
        </div>
    </body>
    </html>

    
<?php
}
?>