<?php
namespace AaronHenderson\ContactRequest;

class Validator
{

    /**
     * Holds an array of errors linked to the most recent validate request
     *
     * @var array
     */
    private $errors = array();

    /**
     * True if the most recent request to validate a Entity passed
     *
     * @var bool
     */
    private $passed = false;


    /**
     * Validate a Entity
     *
     * @param Entity $contact_request
     * @return $this
     */
    public function validate(Entity $contact_request)
    {
        $this->errors = array();

        if (!is_string($contact_request->getName()) || strlen($contact_request->getName()) < 3) {
            $this->errors[] = 'The provided name must be at least 3 characters long to be processed.';
        }

        if (filter_var($contact_request->getEmailAddress(), FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'A valid email address must be provided.';
        }

        if (!is_string($contact_request->getMessage()) || strlen($contact_request->getMessage()) < 10) {
            $this->errors[] = 'Messages must be at least 10 characters long to be processed.';
        }

        $this->passed = empty($this->errors);

        return $this;
    }

    /**
     * returns true if request passed validation
     *
     * @return bool
     */
    public function passed()
    {
        return $this->passed;
    }

    /**
     * Return validation errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }
}