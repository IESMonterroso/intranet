<?php

@session_start();

/**


CSRF-Tokens Lib for https://github.com/IESMonterroso/intranet


**/


function generateToken($length = 10) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    
}


function outputToken() {

    $token = generateToken();
    
    $_SESSION['csrf'] = $token;
    
    $output = '<input type="hidden" name="csrf" value="' . $token . '">';
    
    return $output;

}

function checkToken()  {

    if(@isset($_POST['csrf'])) {
    
        if($_POST['csrf'] == $_SESSION['csrf']) {
        
            return 1; // ALLOW REQUESTS: CORRECT CSRF TOKEN
        
        } else {
        
            return 0; // BLOCK REQUESTS: WRONG CSRF TOKEN
        
        }
    
    } else if(@isset($_GET['csrf'])) {
    
        if($_GET['csrf'] == $_SESSION['csrf']) {
        
            return 1; // ALLOW REQUESTS: CORRECT CSRF TOKEN
        
        } else {
        
            return 0; // BLOCK REQUESTS: WRONG CSRF TOKEN
        
        }
    
    } else {
    
        return 0; // BLOCK REQUESTS: NO CSRF TOKEN SPECIFIED
    
    }

}
