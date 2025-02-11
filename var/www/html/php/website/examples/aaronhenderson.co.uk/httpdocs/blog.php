<?php
// dependencies
//error_reporting(0);
require_once '../src/Template.php';

require_once '../src/Blog/DatabaseRepository.php';
require_once '../src/Blog/Post.php';
require_once '../src/Blog/PostComment.php';
require_once '../src/Blog/Page.php';

$blog_page = new \AaronHenderson\Blog\Page();

echo $blog_page->html();