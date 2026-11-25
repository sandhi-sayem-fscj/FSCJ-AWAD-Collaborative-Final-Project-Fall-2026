<?php

// class to validate form fields
class Validate
{
    // array to hold all the form Field objects
    private $fields;

    public function __construct()
    {
        $this->fields = new Fields();
    }

    public function getFields()
    {
        return $this->fields;
    }

    // validate generic text field and return the Field object
    public function text($name, $value, $min = 1, $max = 50){
        // get Field object and set the value
        $field = $this->fields->getField($name);
        $field->setValue($value);

        // validate the value and set/clear error message
        if($field->isRequired() && $field->isEmpty()){
            $field->setErrorMessage('Required.');
        } else if(strlen($value) < $min && !$field->isEmpty()) {
            $field->setErrorMessage("Too short. Value must be within $min to $max characters.");
        } else if(strlen($value) > $max){
            $field->setErrorMessage("Too long. Value must be within $min to $max characters.");
        } else {
            $field->clearErrorMessage();
        }

        return $field;
    }

    // validate a field with generic pattern and set/clear error message
    public function pattern(&$field, $pattern, $message){

        $match = preg_match($pattern, $field->getValue());
        if($match === FALSE){
            $field->setErrorMessage('Error testing field.');
        } else if ($match != 1){
            $field->setErrorMessage($message);
        } else {
            $field->clearErrorMessage();
        }
        return $field;
    }

    public function postalCode($name, $value){
        // text field check
        $this->text($name, $value, 1, 20);
    }

    public function phone($name, $value){
        // text field check
        $message = 'Use (999) 999-9999 format.';
        $field = $this->text($name, $value, 1, 14);

        if($field->hasError()){
            $field->setErrorMessage($message);
            return;
        } else {
            $pattern = '/^\(\d{3}\) \d{3}-\d{4}$/';
            $this->pattern($field, $pattern, $message);
        }
    }

    public function email($name, $value){
        // text field check
        $field = $this->text($name, $value);

        // validate email format
        if (!$field->hasError() && !$field->isEmpty()) {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $field->clearErrorMessage();
            } else {
                $field->setErrorMessage("Invalid email address.");
            }
        }
    }

    public function password($name, $value){
        // text field check
        $this->text($name, $value, 6, 20);
    }
}