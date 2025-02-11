<?php
namespace AaronHenderson\Blog;

class Post
{

    /**
     * @var integer
     */
    private $post_id;


    /**
     * @var integer
     */
    private $post_author_id;


    /**
     * @var string
     */
    private $post_author_name;


    /**
     * @var string
     */
    private $post_title;


    /**
     * @var string
     */
    private $post_body;


    /**
     * @var integer
     */
    private $post_visible;


    /**
     * @var string
     */
    private $post_timestamp;


    /**
     * Post constructor.
     *
     * @param int $post_id
     * @param int $post_author_id
     * @param int $post_author_name
     * @param string $post_title
     * @param string $post_body
     * @param int $post_visible
     * @param string $post_timestamp
     */
    public function __construct($post_id, $post_author_id, $post_author_name, $post_title, $post_body, $post_visible, $post_timestamp)
    {
        $this->post_id = $post_id;
        $this->post_author_id = $post_author_id;
        $this->post_author_name = $post_author_name;
        $this->post_title = $post_title;
        $this->post_body = $post_body;
        $this->post_visible = $post_visible;
        $this->post_timestamp = $post_timestamp;
    }


    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }


    /**
     * @return int
     */
    public function getPostAuthorId()
    {
        return $this->post_author_id;
    }


    /**
     * @return string
     */
    public function getPostAuthorName()
    {
        return $this->post_author_name;
    }


    /**
     * @return string
     */
    public function getPostTitle()
    {
        return $this->post_title;
    }


    /**
     * @return string
     */
    public function getPostBody()
    {
        return $this->post_body;
    }


    /**
     * @return int
     */
    public function getPostVisible()
    {
        return $this->post_visible;
    }


    /**
     * @return string
     */
    public function getPostTimestamp()
    {
        return $this->post_timestamp;
    }
}