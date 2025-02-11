<?php
namespace AaronHenderson\ContactRequest;

use AaronHenderson\CSRF;
use AaronHenderson\Template;

class Page
{

    /**
     * The template for our contact page
     */
    const TEMPLATE = 'contact.html';


    /**
     * Page context replacements
     *
     * @var array
     */
    private $page_context = array();


    /**
     * Page constructor.
     *
     * @param array $page_context
     * @throws \Exception
     */
    public function __construct($page_context = array())
    {
        $csrf = new CSRF();


        // Add replacements to context and any additional context data from constructor
        $this->page_context = array_merge(
            array(
                'page_title' => 'Aaron Henderson, Web Developer - AaronHenderson.co.uk',

                'page_heading' => 'Aaron Henderson',

                'page_subheading' => 'Design, Develop &amp; Deliver',


                'input_name' => $this->input('inputName'),

                'input_email' => $this->input('inputEmail'),

                'input_message' => $this->input('inputMessage'),


                'xsrf_token' => $csrf->getToken(), // md5($_SERVER['REMOTE_ADDR'] . self::TEMPLATE),

                'form_alert' => '',


                'footer_section' => ''
            ),
            $page_context
        );

        // Try process a request if post data submitted and xsrf token is valid
        if (!empty($_POST) && isset($_POST['xsrfCheck']) && $csrf->validateToken($_POST['xsrfCheck'])){

            if ($this->input('inputSpecies') == '-1') {
                $this->appendAlert('Sorry, Robots are not currently permitted to use this form.');
            } else {
                $this->request();
            }

        }
    }


    /**
     * Process a contact form submission
     */
    private function request()
    {
        $request = new Entity(
            $this->input('inputName'),
            $this->input('inputEmail'),
            $this->input('inputMessage')
        );

        $validator = new Validator();

        $validator->validate($request);

        foreach ($validator->errors() as $alert) {
            $this->appendAlert($alert);
        }

        if ($validator->passed()) {
            $email = new Email($request);
            if ($email->send()) {
                $this->appendAlert('Your message was sent.', 'success');
            } else {
                // there was a problem sending mail
                $this->appendAlert('There was a problem sending your message.');
            }
        }
    }


    /**
     * Append a form alert to page contect
     *
     * @param $message
     * @param string $type
     */
    private function appendAlert($message, $type = 'danger')
    {
        $this->page_context['form_alert'] .= $this->alert($message, $type);
    }


    /**
     * Return an alert as HTML
     *
     * @param $message
     * @param string $type
     * @return string
     */
    protected function alert($message, $type = 'danger')
    {
        return '<div class="alert alert-' . $type . '">' . $message . '</div>';
    }


    /**
     * Load html from template and replace contextual data before returning contents as a string
     *
     * @return string
     * @throws Exception
     */
    public function html()
    {
        return Template::fetch(self::TEMPLATE, $this->page_context);
    }


    /**
     * Return input data from a post request
     *
     * @param $key
     * @param null $default
     * @return null
     */
    private function input($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
}