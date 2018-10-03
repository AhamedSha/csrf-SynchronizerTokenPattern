<?php

    session_start();

    function logout() {

        // unset all cookies and sessions belongs to that user
        unset($_COOKIE['csrf_session_cookie']);
        setcookie('csrf_session_cookie', null, -1, '/');
        unset($_SESSION);

        header("location: ./../login.php");
    }

    function generateCSRFToken($session, $length = 32) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $randomString .= $session[rand(0, strlen($session) - 5)];
        
        // store the generated csrf token string in a session variable
        $_SESSION[$session] = $randomString;
        return $randomString;
    }

    if(isset($_POST['logout'])){

        logout();

    } elseif (isset($_POST['csrf_request'])){

        $sessionId = $_POST['csrf_request'];

        if($_SESSION[$sessionId]){

            echo $_SESSION[$sessionId];

        }else {
            echo "null";
        }

    } else if (isset($_POST['verify'])){

        $sessionId = $_COOKIE["csrf_session_cookie"];

        if($_POST['csrf_token'] == $_SESSION[$sessionId]){
            header("location: ./../display/success.php");
        }else {
            header("location: ./../display/error.php");
        }
    }
?>