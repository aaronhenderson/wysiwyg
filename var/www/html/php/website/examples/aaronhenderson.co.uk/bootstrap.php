<?php
/**
 * Project specific auto loading
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param   string $class The fully-qualified class name.
 * @return  void
 * @url     https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'AaronHenderson\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});


// error reporting off as we dont show errors in user land
error_reporting(1);

session_start();

chdir(__DIR__);

// Try and route our request
try {

    // detect uri being requested
    $uri = trim($_SERVER['REQUEST_URI'], '/');
    $uri = str_ireplace(
        'php/website/examples/aaronhenderson.co.uk',
        '',
        $uri
    );
    $uri = trim($uri, '/');
    /**
    $uri = substr(
        $uri,
        strpos($uri,'php/website/examples/aaronhenderson.co.uk'),
        strlen('php/website/examples/aaronhenderson.co.uk')
    );
    **/

    if (strpos($uri, '?') !== false) {
        $uri = explode('?', $uri);
        $uri = $uri[0];
    }
    //echo $uri; exit;
    switch ($uri) {

        // blog page
        case 'blog':
            $blog_page = new \AaronHenderson\Blog\Page();
            echo $blog_page->html();
            break;

        case 'experience':
            echo \AaronHenderson\Template::fetch('experience.html', array(
                'page_title' => 'Work and Experience - AaronHenderson.co.uk',
                'page_heading' => 'Aaron Henderson',
                'page_subheading' => 'Design, Develop &amp; Deliver',
            ));
            break;

        // default home page
        case '':
            $page = new \AaronHenderson\ContactRequest\PageWithWordGame();
            echo $page->html();
            break;

        // default to a 404
        default:
            header('Content-type: text/html', true, 404);
            echo \AaronHenderson\Template::fetch('errors/404.html');
            break;
    }

} catch (\Exception $exception) {

    // mail any exceptions
    @mail('monitoring@aaronhenderson.co.uk', 'Exception caught', $exception->getMessage());

    // display a user friendly error page
    header('Content-type: text/html', true, 500);
    echo \AaronHenderson\Template::fetch('errors/500.html');
}


