<?php

/**
 * @return bool
 */
function logged_in(){
    if(session_status() === PHP_SESSION_NONE)
        session_start();

    return isset($_SESSION['user']);
}
