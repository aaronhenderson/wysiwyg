<?php
namespace AaronHenderson\ContactRequest;

class Entity
{

    /**
     * The name of person submitting request
     *
     * @var string
     */
    private $name;


    /**
     * The email of person submitting request
     *
     * @var string
     */
    private $email_address;


    /**
     * The message being submitted
     *
     * @var string
     */
    private $message;


    /**
     * The IP address where request is being made from
     *
     * @var string
     */
    private $ip_address;


    /**
     * The time of request
     *
     * @var string
     */
    private $timestamp;


    /**
     * Entity constructor.
     *
     * @param string $name
     * @param string $email_address
     * @param string $message
     * @param null|string $ip_address
     * @param null|string $timestamp
     */
    public function __construct($name, $email_address, $message, $ip_address = null, $timestamp = null)
    {
        $this->name = $name;
        $this->email_address = $email_address;
        $this->message = $message;

        // optional parameters with defaults
        $this->ip_address = isset($ip_address) ? $ip_address : $_SERVER['REMOTE_ADDR'];
        $this->timestamp = isset($timestamp) ? $timestamp : date('Y-m-d H:i:s', time());
    }


    /**
     * Return the name of the person submitting the request
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Return the email of the person submitting the request
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }


    /**
     * Return the message being submitted
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * Return the IP address where the request is being made from
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }


    /**
     * Return the time request was made
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

}