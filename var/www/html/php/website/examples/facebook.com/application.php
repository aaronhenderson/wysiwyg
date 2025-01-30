<?php
define('SCRIPT_START', microtime(true));

// one of these two statements does nothing;  both alert the developer..
ini_set('session.name', 'datr');
session_name('datr');

// we do not give a monkeys about the spl yet; these are functions
require_once 'functions/account.php';
require_once 'functions/authentication.php';
require_once 'functions/cryptography.php';
require_once 'functions/database.php';
require_once 'functions/math.php';
require_once 'functions/templating.php';
require_once 'functions/time.php';

/*
var_dump(
    encrypt_email_address('ello@henda.u'),
    encrypt_email_address('ello@henda.u'),
    decrypt_email_address(
        'e1671797c52e15f763380b45e841ec322db95e8e1a9267b7a1188556b2013b332db95e8e1a9267b7a1188556b2013b33d95679752134a2d9eb61dbd7b91c4bcc518ed29525738cebdac49c49e60ea9d32510c39011c5be704182423e3a695e91e1671797c52e15f763380b45e841ec327b8b965ad4bca0e41ab51de7b31363a18277e0910d750195b448797616e091ad0cc175b9c0f1b6a831c399e2697726615058f1af8388633f609cadb75a75dc9d7b774effe4a349c6dd82ad4f4f21d34c'
    )
);
*/
// var_dump(session_status() === PHP_SESSION_NONE);

try {

    if(logged_in() && isset($_GET['edit_post'])){
        $post = get_user_post($_GET['edit_post']);
        if($post === false) {
            $alerting = 'The post you are trying to edit does not exist.';
        } elseif ($_SESSION['user'][0] !== $post['author_id']){
            $alerting = 'You cannot edit posts that are not your own.';
        }

        if(isset($alerting)){
            unset($post);
        }
    }

    if(logged_in() && isset($_GET['delete_post'])){
        $post = get_user_post($_GET['delete_post']);
        if($post === false) {
            $alerting = 'The post you tried to delete does not exist.';
        } elseif ($_SESSION['user'][0] !== $post['author_id']){
            $alerting = 'You cannot delete posts that are not your own.';
        } else if(delete_user_post($_GET['delete_post'])) {
            $alerting = 'Your post was deleted.';
        } else {
            $alerting = 'There was a problem trying to delete your post.';
        }
        unset($post);
    }
    if(logged_in() === true && isset($_GET['arbitrary_posting'], $_POST['input__arbitrary'])) {

        if(empty($_POST['input__arbitrary'])){
            $alerting = 'Your post cannot be empty.';
        } else if(publish_post(
            $_SESSION['user'][0],
            $_SESSION['user'][0],
            $_POST['input__arbitrary']
        )){
            $alerting = 'Your post has just been published.';
        } else {
            $alerting = 'Your post could not be published.';
        }
    }

    if(isset($_GET['sign_in'], $_POST['input__email'], $_POST['input__password'])) {

        $user = get_user_with_email($_POST['input__email']);

        if($user === false){
            $alerting = 'No user account with that email.';
        }
        else {
            if($user[3] !== md5(sha1($_POST['input__password']))) {
                $alerting = 'Password does not match.';
                //var_dump($user);
            } else {

                if(session_status() === PHP_SESSION_NONE){
                    session_start();
                }
                $_SESSION['user'] = $user;
                $_SESSION['session'] = array(
                    date('h:i:s d-m-Y') . ' from ' . $_SERVER['REMOTE_ADDR'],
                    $_SERVER['HTTP_USER_AGENT']
                );
                $alerting = 'You are now logged in as ' . $user[1] . '.';
            }
        }
    }

    if(isset($_POST['input__username'], $_POST['input__email'], $_POST['input__password'], $_POST['input__confirm'])){

        if(strlen($_POST['input__username']) < 5) {
            $alerting = 'Username too short. Must be 5 characters or more.';
        } else if(false === ctype_alpha($_POST['input__username'])) {
            $alerting = 'Your username may only contain letters and numbers (no special characters or spaces).';
        } else if(filter_var($_POST['input__email'], FILTER_VALIDATE_EMAIL) === false){
            $alerting = 'The provided email address could not be validated.';
        } else if(strlen($_POST['input__password']) < 6){
            $alerting = 'Passwords must be 6 characters or more.';
        } else if($_POST['input__password'] !== $_POST['input__confirm']) {
            $alerting = 'The password confirmation does not match.';
        } else if(get_user_with_email($_POST['input__email']) !== false){
            $alerting = 'An account already exists with the provided email address.';
        } else {
            $created = create_user_account(
                $_POST['input__username'],
                strtolower($_POST['input__email']),
                md5(sha1($_POST['input__password'])),
                1,
                1
            );
            //var_dump($created); exit;

            if($created !== true){
                $alerting = 'There was a problem creating your user account.';
            } else {
                $alerting = 'You may now sign in with the credentials you provided.';
            }
        }

    }

} catch (Exception $exception){
    echo $exception->getMessage();
}

if(isset($_GET['sign_out'])) {

    if(session_status() === PHP_SESSION_NONE)
        session_start();

    session_destroy();
    $alerting = 'You are now logged out.';
}

switch (true){
    case isset($_GET['error_document']):

        echo php_template('partials/private_header.html');
        if(isset($alerting)): echo '<p class="alerting">' . $alerting . '</p>'; endif;
        echo '<article>';
        echo '<h2>Page could not be found</h2>';
        echo '<p>';
        echo 'The resource that was requested could not be found. We did try to look for you. ';
        echo 'If you believe this to be an error you may submit a request for revision by using your pineal gland.';
        echo '</p>';
        echo '</article>';
        echo template('private_footer');
        break;

    case isset($_GET['user_handle']):
        $user = get_user_with_handle($_GET['user_handle']);
        $_SESSION['session'] [3] = date('h:i:s d-m-Y') . ' from ' . $_SERVER['REMOTE_ADDR'];
        echo template('private_header');
        if(isset($alerting)): echo '<p class="alerting">' . $alerting . '</p>'; endif;
        echo template('private_navigation');
        echo template('private_footer');
        break;

    case isset($_GET['terms_of_service']):
        echo template('public_header');
        if(isset($alerting)): echo '<p class="alerting">' . $alerting . '</p>'; endif;
        echo template('terms_and_agreement');
        echo template('public_footer');
        break;

    case isset($_GET['privacy_statement']):
        echo template('public_header');
        if(isset($alerting)): echo '<p class="alerting">' . $alerting . '</p>'; endif;
        echo template('privacy_statement');
        echo template('public_footer');
        break;

    case logged_in() && !isset($_GET['user_handle']):
        //$_SESSION['users'] = get_users();
        $_SESSION['user']['timeline'] = get_user_posts($_SESSION['user'][0]);
        $_SESSION['session'] [3] = date('h:i:s d-m-Y') . ' from ' . $_SERVER['REMOTE_ADDR'];

        echo template('private_header');
        echo '<p class="alerting">';
        if(isset($alerting)): echo $alerting; endif;
        echo '</p>';
        echo template('private_navigation');
        echo php_template('templates/homepage/timeline.html', array('post' => (isset($post)) ? $post : null));
        echo template('private_footer');
        break;

    case logged_in() === false && !isset($_GET['user_handle']):

        switch (true){

            case isset($_GET['sign_up']):
                echo template('public_header');
                if(isset($alerting)): echo '<p class="alerting">' . $alerting . '</p>'; endif;
                echo template('public_signup');
                echo template('public_footer');
                break;

            default:
                echo template('public_header');
                if(isset($alerting)): echo '<p class="alerting">' . $alerting . '</p>'; endif;

                echo php_template('partials/public_signin.html');
                echo template('public_footer');
                break;
        }
        break;
}



if($_SERVER['REMOTE_ADDR'] === '127.0.0.1'){
    echo '<pre style="margin:24px; max-width: 100%; word-wrap: break-word;';
    echo '            overflow-x: scroll; border-top: solid 1px #000; padding-top:12px;">';
    var_dump(array($_SESSION, what_is_my_ipvseven()));
    echo '</pre>';
}