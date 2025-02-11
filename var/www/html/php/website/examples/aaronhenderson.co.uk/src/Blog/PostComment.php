<?php
namespace AaronHenderson\Blog;

class PostComment
{

    /**
     * @var integer
     */
    private $post_comment_id;

    /**
     * @var integer
     */
    private $post_id;

    /**
     * @var string
     */
    private $post_comment_body;

    /**
     * @var integer
     */
    private $post_comment_visible;

    /**
     * @var
     */
    private $post_comment_timestamp;

    /**
     * PostComment constructor.
     *
     * @param int $post_comment_id
     * @param int $post_id
     * @param string $post_comment_body
     * @param int $post_comment_visible
     * @param $post_comment_timestamp
     */
    public function __construct($post_comment_id, $post_id, $post_comment_body, $post_comment_visible, $post_comment_timestamp)
    {
        $this->post_comment_id = $post_comment_id;
        $this->post_id = $post_id;
        $this->post_comment_body = $post_comment_body;
        $this->post_comment_visible = $post_comment_visible;
        $this->post_comment_timestamp = $post_comment_timestamp;
    }

    /**
     * @return int
     */
    public function getPostCommentId()
    {
        return $this->post_comment_id;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @return string
     */
    public function getPostCommentBody()
    {
        return $this->post_comment_body;
    }

    /**
     * @return int
     */
    public function getPostCommentVisible()
    {
        return $this->post_comment_visible;
    }

    /**
     * @return mixed
     */
    public function getPostCommentTimestamp()
    {
        return $this->post_comment_timestamp;
    }
}