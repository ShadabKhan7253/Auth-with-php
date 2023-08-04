<?php
define("USER_KEY","logged_in_user");

class Auth 
{
    static public function signin($username,$password): bool
    {
        $dbUser = User::findByUsername($username);
        // dd($dbUser); 
        // object(stdClass)#8 (4) { ["id"]=> string(1) "8" ["email"]=> string(19) "shaddy123@gmail.com" ["username"]=> string(6) "Shaddy" ["password"]=> string(60) "$2y$10$sfSnenZoPP12ypwRj5wn0udaDfvBrAiAFK.I/FYxLGaJ9Y2y.MKM6" }
        if($dbUser) {
            $hashedPassword = $dbUser->password;
            if(Hash::verify($password,$hashedPassword)) 
            {
                self::setLoggedInUser($dbUser);
                return true;
            }
        }
        return false;
    }

    static public function setLoggedInUser($dbUser)
    {
        $_SESSION[USER_KEY] = $dbUser;
    }

    static public function user()
    {
        if(isset($_SESSION[USER_KEY])) {
            return $_SESSION[USER_KEY];
        }
        return null;
    }

    static public function signout()
    {
        unset($_SESSION[USER_KEY]);
    }

}
?>