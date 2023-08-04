<?php
require_once('./app/init.php');
// secure($token);
$userId = -1;
if(isset($_POST['signup']))
{
    // dd($_POST); //array(4) { ["username"]=> string(8) "Shaddy12" ["email"]=> string(12) "We@gmail.com" ["password"]=> string(3) "123" ["signup"]=> string(6) "Submit" }
    $validator->check($_POST, [
        'email' => [
            'required'=>true,
            'email'=>true,
            'minlength'=>5,
            'maxlength'=>255,
            'unique'=>'users'
        ],
        'username' => [
            'required'=>true,
            'maxlength'=>20,
            'unique'=>'users'
        ],
        'password' => [
            'required'=>true,
            'minlength'=>0,
            'password'=>true
        ],
    ]);

    if(!$validator->fails()) {
        $userToken = $token->createVerificationEmailToken($userId);
        if($userToken) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $mail = Mail::getMailer('noreply@fulltimepasss.com');
            $mail->addAddress($_POST['email']);
            $mail->Subject = 'Email Verification';
            $mail->Body = <<<MAIL_BODY
            <p>Plaese click on the below link to verify your email</p>
            
            <p><a href="http://localhost:9999/signup.php?aParam[]=$userToken->token&aParam[]=$username&aParam[]=$password&aParam[]=$email">Click Here</a></p>
            MAIL_BODY;

            if($mail->send()) {
                // die("Mail has been sent! Please check your inbox!");
                die(" <center><h3>Email Verificaction</h3><p>We've send you a mail to {$email} for verification<p></center>");
            } else {
                echo "There is some issue with the server! PLease try again in some time";
            }
            // die("<h3>We have send you a verification link on your email <a href='#'>$email</a></h3>");
        }
    }
} 
else if(isset($_GET['aParam'])) {
    // dd($_GET['aParam']);
    setcookie('rememberme',"",time() - 3600);
    $userToken = $_GET['aParam'][0];
    $tokenObject = $token->isValidVerificationEmail($userToken);
    // dd($tokenObject);
    if($tokenObject) {

        $username = $_GET['aParam'][1];
        $password = $_GET['aParam'][2];
        $email = $_GET['aParam'][3];
        User::create ([
            'username' => $username,
            'email'=> $email,
            'password'=> $password
        ]);
        Auth::signin($username,$password);
        $result = $token->deleteVerificationEmailToken($userId);
        redirect("secured-page.php");
    } else {
        $msg = "Your token expired!!!!!!!";
        redirect("verification-email.php?t={$msg}");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <div style="margin:40px auto; width:600px;">
        <h1>Sign Up</h1>
        <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST" >
            <div>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" value="<?=$database->old($_POST,'username');?>">
                <span><?= $validator->errors()->has('username') ? $validator->errors()->first('username'): ' '; ?></span>
            </div>
            <div>
                <label for="email">Email:</label><br>
                <input type="text" id="email" name="email" value="<?=$database->old($_POST,'email');?>">
                <span><?= $validator->errors()->has('email') ? $validator->errors()->first('email'): ' '; ?></span>
            </div>
            <div>
                <label for="password">Password:</label><br>
                <input type="text" id="password" name="password" value="<?=$database->old($_POST,'password');?>">
                <span><?= $validator->errors()->has('password') ? $validator->errors()->first('password'): ' ';?></span>
            </div>
            <br>
            <div>
                <input type="submit" name="signup">
            </div>
        </form>
    </div>
</body>
</html>