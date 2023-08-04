<?php
function dd($data) {
    die(var_dump($data));
}
function redirect($url)
{
    return header("Location: $url");
}

function secure($token,$checkAuthUser = true) 
{
    if(isset($_COOKIE['rememberme'])) {
        $userToken = $token->isValidRememberMe($_COOKIE['rememberme']);
        if($userToken) {
            $userId = $userToken->user_id;
            $user = User::find($userId);
            Auth::setLoggedInUser($user);

            return true;
        }
    }

    if($checkAuthUser && !Auth::user()) {
        // die("403: Access Forbidden!");
        abort(403);
    }
    return false;
}

function getCurrentTimeInMillis()
{
    return round(microtime(true)*1000);
}

function abort($statusCode = 200)
{
    http_response_code(403);
    exit();
}
?>