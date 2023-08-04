<?php
require_once 'app/init.php';
if(isset($_GET['t'])) {
    $userToken = $_GET['t'];
    $tokenObject = $token->isValidForgotPassword($userToken);
    if($tokenObject) {
        ?>
        <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
            <input type="password" name="password">
            <input type="text" name="token" value="<?= $tokenObject->token;?>">
            <br><br>
            <input type="submit" name="reset_password" value="Reset Password">
    </form>
    <?php
        } else {
            die("Your token expired!");
        }
    }
    else if(isset($_POST['reset_password'])) {
        $password = $_POST['password'];
        $tokenObject = $token->isValidForgotPassword($_POST['token']);
        if($tokenObject) {
            $userId = $tokenObject->user_id;
            $result = User::updatePassword($password, $userId);
            if($result) {
                $result = $token->deleteForgotPasswordToken($userId);
                // echo "Password Updated!";
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Password Updated</title>
                </head>
                <body>
                    <h1>Your Password has been Updated</h1>
                    <p>Plase <a href="sign-in.php">click here </a> to login</p>
                </body>
                </html>
                <?php
            } else {
                die("Please try after some time!");
            }
        }
    } else {
        die("How the hell you reached here?");
    }
?>