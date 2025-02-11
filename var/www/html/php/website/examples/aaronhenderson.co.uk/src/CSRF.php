<?php

namespace AaronHenderson;

class CSRF
{

    public function getToken()
    {
        $token = bin2hex(random_bytes(4));

        $tokens = isset($_SESSION['csrf_tokens']) && is_array($_SESSION['csrf_tokens']) ? $_SESSION['csrf_tokens'] : array();

        array_push($tokens, $token);

        $_SESSION['csrf_tokens'] = $tokens;

        return $token;
    }

    public function validateToken($token)
    {
        $tokens = (array) $_SESSION['csrf_tokens'];

        if (!in_array($token, $tokens)) {
            // Invalid token!
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Invalid CSRF token', true, 400);
            throw new \Exception($_SERVER['SERVER_PROTOCOL'] . ' 400 Invalid CSRF token', 400);
        }

        return true;
    }
}