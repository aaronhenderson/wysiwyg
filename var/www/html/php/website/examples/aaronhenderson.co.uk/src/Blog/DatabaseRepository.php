<?php
namespace AaronHenderson\Blog;

use mysqli;

class DatabaseRepository
{

    /**
     *
     * @var mysqli
     */
    private $mysqli;


    /**
     * DatabaseRepository constructor.
     */
    public function __construct()
    {
        $this->mysqli = new mysqli('localhost', 'test', '', 'aaron');
    }


    /**
     * Insert a blog post to the database
     *
     * @param Post $post
     * @return bool
     */
    public function insertPost(Post $post)
    {
        $saved = false;

        $sql = "INSERT INTO posts (post_author_id, post_title, post_body)
                VALUES (?, ?, ?)";

        if ($stmt = $this->mysqli->prepare($sql)) {

            $stmt->bind_param(
                'iss',
                $post->getPostAuthorId(),
                $post->getPostTitle(),
                $post->getPostBody()
            );

            if ($stmt->execute()) {
                $saved = true;
            }

            $stmt->close();
        }

        return $saved;
    }


    /**
     * Insert a comment to the database
     *
     * @param PostComment $comment
     * @return bool
     */
    public function insertComment(PostComment $comment)
    {
        $saved = false;

        $sql = "INSERT INTO post_comments (post_id, post_comment_body, post_comment_visible)
                VALUES (?, ?, ?)";

        if ($stmt = $this->mysqli->prepare($sql)) {

            $stmt->bind_param(
                'isi',
                $comment->getPostId(),
                $comment->getPostCommentBody(),
                $comment->getPostCommentVisible()
            );

            if ($stmt->execute()) {
                $saved = true;
            }

            $stmt->close();
        }

        return $saved;
    }


    /**
     * Retrieve posts from the database
     *
     * @return Post[]
     */
    public function posts()
    {
        $posts = array();

        $sql = "SELECT p.post_id,
                       p.post_author_id,
                       a.author_name,
                       p.post_title,
                       p.post_body,
                       p.post_visible, 
                       p.post_timestamp
                FROM posts AS p
                LEFT JOIN authors AS a ON p.post_author_id = a.author_id ";

        if ($stmt = $this->mysqli->prepare($sql)) {
            $stmt->bind_result(
                $post_id,
                $post_author_id,
                $post_author_name,
                $post_title,
                $post_body,
                $post_visible,
                $post_timestamp
            );

            $stmt->execute();

            while ($stmt->fetch()) {

                $posts[] = new Post(
                    $post_id,
                    $post_author_id,
                    $post_author_name,
                    $post_title,
                    $post_body,
                    $post_visible,
                    $post_timestamp
                );

            }
            $stmt->close();
        }

        return $posts;
    }


    /**
     * Get comments linked to a post
     *
     * @param integer $post_id
     * @return PostComment[]
     */
    public function comments($post_id)
    {
        $post_comments = array();

        $sql = "SELECT post_comment_id,
                       post_id,
                       post_comment_body, 
                       post_comment_visible,
                       post_comment_timestamp
                FROM post_comments 
                WHERE post_id = ?";

        if ($stmt = $this->mysqli->prepare($sql)) {

            $stmt->bind_param('i', $post_id);

            $stmt->bind_result(
                $comment_id,
                $post_id,
                $comment_body,
                $comment_visible,
                $comment_timestamp
            );

            $stmt->execute();

            while ($stmt->fetch()) {
                $post_comments[] = new PostComment(
                    $comment_id,
                    $post_id,
                    $comment_body,
                    $comment_visible,
                    $comment_timestamp
                );
            }

            $stmt->close();
        }

        return $post_comments;
    }
}