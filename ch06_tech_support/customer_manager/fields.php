<?php


// creates a Field object for each form field
class Field
{
    // flag to indicate if any of the field has an error
    private $hasError = FALSE;

    // the value of the field
    private  $value;

    // constructor
    public function __construct(
        private $name,
        private $message = '',
        private $required = TRUE,
    ) {
    }

    // getters and setters
    public function getName()
    {
        return $this->name;
    }
    public function getMessage()
    {
        return $this->message;
    }
    public function hasError()
    {
        return $this->hasError;
    }
    public function isRequired()
    {
        return $this->required;
    }
    public function isEmpty()
    {
        return empty($this->value);
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setErrorMessage($message)
    {
        $this->message = $message;
        $this->hasError = TRUE;
    }
    public function clearErrorMessage()
    {
        $this->message = '';
        $this->hasError = FALSE;
    }

    public function getHTML()
    {
        $message = htmlspecialchars($this->message);
        if ($this->hasError()) {
            return '<span class="error">' . $message . '</span>';
        } else {
            return '<span>' . $message . '</span>';
        }
    }
}


// creates a Fields array to hold all the Field objects
class Fields
{
    private $fields = [];

    public function addField($name, $message = '', $required = TRUE)
    {
        $field = new Field($name, $message, $required);
        $this->fields[$field->getName()] = $field;
    }

    public function getField($name)
    {
        return $this->fields[$name];
    }

    public function hasErrors()
    {
        foreach ($this->fields as $field) {
            if ($field->hasError()) {
                return TRUE;
            }
        }
        return FALSE;
    }
}