<?php
namespace AaronHenderson\Blog;

use AaronHenderson\Template;

class Page
{

    /**
     * An array of page alerts
     *
     * @var array
     */
    private $alerts = array();


    /**
     * Page constructor.
     */
    public function __construct()
    {

        if (isset($_POST['inputCommentBody'], $_POST['inputPostID'])) {
            // is data valid
            $post_comment = $_POST['inputCommentBody'];

            // flag for determining if post is valid
            $post_validation_passed = true;

            // check post not empty
            if (empty($post_comment) === true) {
                $post_validation_passed = false;
            }

            // check post does not contain bad words
            $bad_words = array(
                'badger',
                'frog'
            );

            foreach ($bad_words as $bad_word) {
                if (stripos($post_comment, $bad_word) !== false) {
                    $post_validation_passed = false;

                    $alerts['danger'][] = 'Please do not use bad word: ' . $bad_word;

                }
            }

            // is post data valid?
            if ($post_validation_passed === true) {

                $blog_post = new PostComment(
                    '',
                    $_POST['inputPostID'],
                    $_POST['inputCommentBody'],
                    '',
                    ''
                );

                $blog_repo = new DatabaseRepository();

                if ($blog_repo->insertComment($blog_post)) {
                    $alerts['success'][] = 'Comment was posted.';
                } else {
                    $alerts['danger'][] = 'Unable to post comment.';
                }
            }

        }

        if (isset($alerts)) {

            $this->alerts = $alerts;
        }
    }


    /**
     *
     *
     * @return string
     * @throws Exception
     */
    public function html()
    {
        $alert_html = '';

        foreach ($this->alerts as $type => $messages) {

            $alert_html .= '<p class="alert alert-' . $type . '">';
            foreach ($messages as $message) {
                $alert_html .= $message . '<br>';
            }
            $alert_html .= '</p>';
        }

        $context = array(
            'page_title' => 'Aaron Henderson\'s Blog - AaronHenderson.co.uk',

            'page_heading' => 'Aaron Henderson\'s <br>Blog Roll',

            'alert_html' => $alert_html,

            'blog_posts_html' => $this->blogPostsHtml()
        );

        return Template::fetch('blog.html', $context);
    }


    /**
     * @return string
     * @throws Exception
     */
    private function blogPostsHtml()
    {
        $blog_posts_html = '';

        $repository = new DatabaseRepository();

        $posts = $repository->posts();
        if (empty($posts)) {
            $blog_posts_html = '<p class="text-center">The blog roll is empty!</p>';
        } else {
            foreach ($posts as $post) {
                $blog_posts_html .= Template::fetch('blog/article.html', array(
                    'article_heading' => $post->getPostTitle(),
                    'article_body' => nl2br($post->getPostBody()),
                    'article_author_name' => $post->getPostAuthorName(),
                    'article_timestamp' => $post->getPostTimestamp(),

                    'article_comments' => $this->blogPostCommentsHtml($post->getPostId()),

                    'comment_form' => Template::fetch(
                        'blog/comment-form.html',
                        array(
                            'post_id' => $post->getPostId()
                        )
                    )
                ));
            }
        }

        return $blog_posts_html;
    }


    private function blogPostCommentsHtml($post_id)
    {

        $blog_repo = new DatabaseRepository();
        $post_comments = $blog_repo->comments($post_id);


        if (empty($post_comments) === true) {
            $article_comments_html = '<h4>Comments</h4>';
            $article_comments_html .= '<p>There are no comments to display.</p>';
        } else {
            $article_comments_html = '<h4>Comments (' . count($post_comments) . ')</h4>';
            foreach ($post_comments as $comment) {

                $article_comments_html .= Template::fetch('blog/comment.html', array(
                    'comment_body' => $comment->getPostCommentBody(),
                    'comment_timestamp' => $comment->getPostCommentTimestamp()
                ));
            }
        }

        return $article_comments_html;
    }
}