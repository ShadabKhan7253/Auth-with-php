<?php
require_once 'app/init.php';

if(isset($_POST['submit']))
{
    $email = $_POST['email'];
    $user = User::findByEmail($email);
    if($user)
    {
        $userToken = $token->createForgotPasswordToken($user->id);
        // dd($userToken);
        if($userToken)
        {
            $mail = Mail::getMailer('noreply@fulltimepasss.com');
            $mail->addAddress($email);
            $mail->subject = 'Password Recovery';
            $mail->Body = <<<MAIL_BODY
            <p>Use the below link to reset your passowrd</p>
            <p><a href="http://localhost:9999/reset-password.php?t={$userToken->token}">Click Here</a></p>
            MAIL_BODY;

            if($mail->send()) {
                die("Mail has been sent! Please check your inbox!");
            } else {
                echo "There is some issue with the server! PLease try again in some time";
            }
        }
        else
        {
            echo "There is some issue with the server! PLease try again in some time";
        }
    } 
    else 
    {
        echo("No such user found!");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div style="margin:40px auto; width:600px;">
    <h1>Forget Password</h1>
    <p>We will help to reset it!</p>
        <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email"/>
            </div>
            <br>
            <div>
                <input type="submit" value="submit" name="submit">
            <div>
        </form>
    </div>
</body>
</html>