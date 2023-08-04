<?php

require_once("./app/init.php");

// $data = $database->table("users")
//             ->insert(['email'=>'abc@gmail.com', 'password'=>'abc@1234']);

// $data = $database->table("users")
//                 ->where('email','%v%','like')
//                 ->get();    

// $data = $database->table("users")
//                 ->where('email','%h%','like')
//                 ->update(['password' => 'shadab123']);

// $data = $database->table("users")
//                 ->count();

// $data = $database->table("users")
//                 ->where('email','%dy%','like')
//                 ->delete();   

// $data = $database->table("users")
//                 ->get();  

// dd($data);

if(isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // dd(isset($_POST['remember']));
    // we have use isset because $_POST['remember'] give string 'on' if it is check otherwise give error as undeclare variable
    $rememberMe = isset($_POST['remember']) ? true : false;

    if(Auth::signin($username,$password))
    {
        if($rememberMe) {
            // dd(Auth::user());
            // object(stdClass)#7 (4) { ["id"]=> string(1) "8" ["email"]=> string(19) "shaddy123@gmail.com" ["username"]=> string(6) "Shaddy" ["password"]=> string(60) "$2y$10$sfSnenZoPP12ypwRj5wn0udaDfvBrAiAFK.I/FYxLGaJ9Y2y.MKM6" }
            $userToken = $token->createRememberMeToken(Auth::user()->id);
            // dd($userToken);
            // array(4) { ["user_id"]=> int(8) ["token"]=> string(64) "a47a95863626c58fa6009c6569c0bda95bc3d85ccb3bd13419c827226cd7f665" ["expires_at"]=> string(19) "2023-04-04 00:33:29" ["type"]=> int(0) }
            setcookie("rememberme",$userToken->token,time() +
            Token::$REMEMBER_ME_EXPIRY_TIME_IN_SECS);
        }
        redirect('secured-page.php');
    }
    else 
    {
        echo "Username/password not match...";
    }
}

if(secure($token,false)) {
    redirect('secured-page.php');
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
    <h1>Sign In</h1>
        <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST" style = back>
            <div>
                <label for="username">Username</label>
                <input type="username" name="username" id="username"/>
            </div>
            <br>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password"/>
            </div>
            <br>
            <div>
                <label for="remember">Remember Me</label>
                <input type="checkbox" name="remember" id="remember"/>
            </div>
            <br>
            <div>
                <input type="submit" name="signin" value="Sign In" />
            </div>
        </form>
    </div>
</body>
</html>