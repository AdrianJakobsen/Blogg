<?php
class User {
    
    public static function assertLoggedIn() {
        if(!self::isLoggedIn()) {
            header("Location: ".config("wwwroot")."/admin/login.php?return_to=".rawurlencode($_SERVER["REQUEST_URI"]));
            die();
        }
    }
    
    public static function isLoggedIn() {
        return isset($_SESSION['username']);
    }
    
    public static function getUser() {
        if(self::isLoggedIn()) {
            $stmt = db()->prepare('SELECT * FROM users WHERE username=?');
            $stmt->execute([$_SESSION['username']]);
            return $stmt->fetch();
        } else {
            return null;
        }
    }
        
    public static function checkUserLogin($username, $password) {
        
        $stmt = db()->prepare('SELECT * FROM users WHERE username=? AND password=PASSWORD(?)');
        $stmt->execute([$username, $password]);
        
        return $stmt->fetch();
    }
    
    public static function doLogin($username, $password) {
        $user = self::checkUserLogin($username, $password);
        if($user) {
            $_SESSION['username'] = $user['username'];
            return true;
        } else {
            return false;
        }
    }
    
}