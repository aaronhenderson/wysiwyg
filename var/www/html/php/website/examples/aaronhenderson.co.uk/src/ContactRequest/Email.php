<?php
namespace AaronHenderson\ContactRequest;

class Email {

    /**
     * Email address to receive contact request submissions
     */
    const RECIPIENT_EMAIL = 'submissions@aaronhenderson.co.uk';


    /**
     * A subject for the contact request email
     */
    const EMAIL_SUBJECT = 'Contact Request Received';


    /**
     * True or false if contact request was submitted / sent
     *
     * @var bool
     */
    private $request_sent = false;


    /**
     * The contact Request
     *
     * @var Entity
     */
    private $contact_request;


    /**
     * Email constructor.
     *
     * @param Entity $request
     */
    public function __construct(Entity $request)
    {
        $this->contact_request = $request;
    }

    /**
     * Return headers for sending email
     *
     * @return string
     */
    private function getEmailHeaders()
    {
        $headers  = 'From: <webserver@aaronhenderson.co.uk>' . "\r\n";
        $headers .= 'Reply-To: <' . $this->contact_request->getEmailAddress() . '>' . "\r\n";

        return $headers;
    }

    /**
     * Returns the body of the contact request email to send
     *
     * @return string
     */
    private function getEmailBody() {

        $email_body  = 'Contact Request Received' . "\r\n";
        $email_body .= 'At: ' .$this->contact_request->getTimestamp() . "\r\n";
        $email_body .= 'IP: ' . $this->contact_request->getIpAddress() . "\r\n";
        $email_body .= 'Name: ' . $this->contact_request->getName() . "\r\n";
        $email_body .= 'Email: ' . $this->contact_request->getEmailAddress() . "\r\n";
        $email_body .= 'Message: ' . $this->contact_request->getMessage() . "\r\n";

        return $email_body;
    }


    /**
     * Return true or false if the request was emailed
     *
     * @return bool
     */
    public function send()
    {
        if($this->request_sent === false)
        {
            $this->request_sent = mail(
                self::RECIPIENT_EMAIL,
                self::EMAIL_SUBJECT,
                $this->getEmailBody(),
                $this->getEmailheaders()
            );
        }

        return $this->request_sent;
    }

}