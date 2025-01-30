<?php

/**
 * A simple accessor to the mysqli interface / domain
 *
 * @param string $name
 * @return mysqli
 */
function mysqli_db(){
    global $conn;

    if(isset($conn) && true === $conn instanceof \mysqli) {
        return $conn;
    }
    $host= $_SERVER['HTTP_HOST'] === 'localhost' ? 'localhost' : '57.129.74.25';

    $conn = new \mysqli($host, 'zyx_apache', 'swcj0621', 'zyx_httpd', 3306)
    or die ('Could not connect to the database server' . mysqli_connect_error());

    return $conn;
}



/**
 * Return user with given email otherwise return false
 *
 * @param $email
 * @return array|bool
 */
function get_user_with_id($user_id)
{
    $user = false;
    $query = "SELECT user_id, 
                     user_handle, 
                     user_email, 
                     user_password_hash,
                     user_enabled, 
                     user_confirmed
              FROM user_account WHERE user_id = ?";
    if ($stmt = mysqli_db()->prepare($query)) {

        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result(
            $user_id,
            $user_handle,
            $user_email,
            $user_password_hash,
            $user_enabled,
            $user_confirmed
        );
        while ($stmt->fetch()) {
            $user = array(
                $user_id,
                $user_handle,
                $user_email,
                $user_password_hash,
                $user_enabled,
                $user_confirmed
            );
        }
        $stmt->close();
    }

    return $user;
}

/**
 * Return user with given email otherwise return false
 *
 * @param $email
 * @return array|bool
 */
function get_user_with_email($email)
{
    $email = encrypt_email_address($email);
    $user = false;
    $query = "SELECT user_id, 
                     user_handle, 
                     user_email, 
                     user_password_hash,
                     user_enabled, 
                     user_confirmed
              FROM user_account WHERE LOWER(user_email) = LOWER(?)";
    if ($stmt = mysqli_db()->prepare($query)) {

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result(
            $user_id,
            $user_handle,
            $user_email,
            $user_password_hash,
            $user_enabled,
            $user_confirmed
        );
        while ($stmt->fetch()) {
            $user = array(
                $user_id,
                $user_handle,
                $user_email,
                $user_password_hash,
                $user_enabled,
                $user_confirmed
            );
        }
        $stmt->close();
    }

    return $user;
}

/**
 * Return user with given handle otherwise return false
 *
 * @param $email
 * @return array|bool
 */
function get_user_with_handle($username)
{
    $user = false;
    $query = "SELECT user_id, 
                     user_handle, 
                     user_email, 
                     user_password_hash,
                     user_enabled, 
                     user_confirmed
              FROM user_account WHERE LOWER(user_handle) = LOWER(?)";
    if ($stmt = mysqli_db()->prepare($query)) {

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result(
            $user_id,
            $user_handle,
            $user_email,
            $user_password_hash,
            $user_enabled,
            $user_confirmed
        );
        while ($stmt->fetch()) {
            $user = array(
                $user_id,
                $user_handle,
                $user_email,
                $user_password_hash,
                $user_enabled,
                $user_confirmed
            );
        }
        $stmt->close();
    }

    return $user;
}
/**
 * Return user with given handle otherwise return false
 *
 * @param $email
 * @return array|bool
 */
function get_users()
{
    $users = false;
    $query = "SELECT user_id, 
                     user_handle, 
                     user_email, 
                     user_password_hash,
                     user_enabled, 
                     user_confirmed
              FROM user_account";
    if ($stmt = mysqli_db()->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result(
            $user_id,
            $user_handle,
            $user_email,
            $user_password_hash,
            $user_enabled,
            $user_confirmed
        );
        while ($stmt->fetch()) {
            $users[] = array(
                $user_id,
                $user_handle,
                $user_email,
                $user_password_hash,
                $user_enabled,
                $user_confirmed
            );
        }
        $stmt->close();
    }

    return $users;
}

/**
 * Create a user record otherwise return false
 *
 * @param $user_handle
 * @param $user_email
 * @param $user_password_hash
 * @param int $user_enabled
 * @param int $user_confirmed
 * @return bool
 */
function create_user_account($user_handle, $user_email, $user_password_hash, $user_enabled = 1, $user_confirmed = 0)
{
    $user_email = encrypt_email_address($user_email);
    $user = false;
    $user_created_from = $_SERVER['REMOTE_ADDR'];

    $query = "INSERT INTO user_account (
                  user_handle,
                  user_email, 
                  user_password_hash, 
                  user_enabled, 
                  user_confirmed, 
                  user_created_from,
                  user_created_timestamp
              ) 
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
    if ($stmt = mysqli_db()->prepare($query)) {
        $stmt->bind_param('sssiis',
            $user_handle,
            $user_email,
            $user_password_hash,
            $user_enabled,
            $user_confirmed,
            $user_created_from
        );
        $stmt->execute();
        $user = $stmt->insert_id > 0;
        $stmt->close();
    }

    return $user;
}

/**
 * Create a user timeline entry
 * @return bool
 */
function publish_post($user_id, $recip_id, $message = '')
{
    $post = false;
    $post_created_from = $_SERVER['REMOTE_ADDR'];

    $query = "INSERT INTO user_timeline (
                  user_timeline_user_id, 
                  user_timeline_author_id, 
                  user_timeline_message_body, 
                  user_timeline_posted_at,
                  user_timeline_posted_from
              ) 
              VALUES (?, ?, ?, NOW(), ?)";
    if ($stmt = mysqli_db()->prepare($query)) {
        $stmt->bind_param('iiss',
            $user_id, $recip_id, $message, $post_created_from
        );
        $stmt->execute();
        $post = $stmt->insert_id > 0;
        $stmt->close();
    }

    return $post;
}

function get_user_posts($user_id)
{
    $timeline = array();
    $query = "SELECT ut.user_timeline_id,
                     ut.user_timeline_user_id, 
                     ut.user_timeline_author_id, 
                     ut.user_timeline_message_body, 
                     ut.user_timeline_posted_at,
                     ut.user_timeline_posted_from,
                     author.user_handle
              FROM user_timeline AS ut
              LEFT JOIN user_account AS author ON author.user_id = ut.user_timeline_author_id
              WHERE ut.user_timeline_user_id = ?
              ORDER BY ut.user_timeline_posted_at DESC";
    if ($stmt = mysqli_db()->prepare($query)) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result(
            $user_timeline_id,
            $user_timeline_user_id,
            $user_timeline_author_id,
            $user_timeline_message_body,
            $user_timeline_posted_at,
            $user_timeline_posted_from,

            $author_handle
        );
        while ($stmt->fetch()) {
            $timeline[] = array(
                'id' => $user_timeline_id,
                'user_id' => $user_timeline_user_id,
                'author_id' => $user_timeline_author_id,
                'message_body' => $user_timeline_message_body,
                'published_time' => $user_timeline_posted_at,
                'author_handle' => $author_handle
            );
        }
        $stmt->close();
    }

    return $timeline;
}

function get_user_post($post_id)
{
    $timeline = false;
    $query = "SELECT ut.user_timeline_id,
                     ut.user_timeline_user_id, 
                     ut.user_timeline_author_id, 
                     ut.user_timeline_message_body, 
                     ut.user_timeline_posted_at,
                     ut.user_timeline_posted_from,
                     author.user_handle
              FROM user_timeline AS ut
              LEFT JOIN user_account AS author ON author.user_id = ut.user_timeline_author_id
              WHERE ut.user_timeline_id = ?
              ORDER BY ut.user_timeline_posted_at DESC";
    if ($stmt = mysqli_db()->prepare($query)) {
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        $stmt->bind_result(
            $user_timeline_id,
            $user_timeline_user_id,
            $user_timeline_author_id,
            $user_timeline_message_body,
            $user_timeline_posted_at,
            $user_timeline_posted_from,

            $author_handle
        );
        while ($stmt->fetch()) {
            $timeline = array(
                'id' => $user_timeline_id,
                'user_id' => $user_timeline_user_id,
                'author_id' => $user_timeline_author_id,
                'message_body' => $user_timeline_message_body,
                'published_time' => $user_timeline_posted_at,
                'author_handle' => $author_handle
            );
        }
        $stmt->close();
    }

    return $timeline;
}

function delete_user_post($post_id){

    $deleted = false;
    $query = "DELETE FROM user_timeline WHERE user_timeline_id = ?";
    if ($stmt = mysqli_db()->prepare($query)) {
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        $deleted = $stmt->affected_rows > 0;
        $stmt->close();
    }

    return $deleted;
}