<?php
// pass pe true
// fail pe false

class Validator 
{
    protected Database $database;
    protected ErrorHandler $errorHandler;

    protected $rules = ['required','minlength','maxlength','unique','email','password'];
    protected $messages = [
        'required' => 'The :field field is required',
        'minlength' => 'The :field field must be a minimum of :satisfier character', 
        'maxlength' => 'The :field field must be a maximum of :satisfier character', 
        'email' => 'That is not a valid email address',
        'unique' => 'That :field is already taken',
        'password' => 'Password must have atleast one number'
    ];

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->errorHandler = new ErrorHandler();
    }

    public function check(mixed $data, mixed $rules)
    {
        foreach($data as $item => $valueToBeValidted) {
            if(in_array($item,array_keys($rules))) {
                $this->validate($item,$valueToBeValidted,$rules[$item]);
            }
        }
        return $this->errorHandler->hasErrors();
    }

    public function fails() 
    {
        return $this->errorHandler->hasErrors();
    }
    public function errors() 
    {
        return $this->errorHandler;
    }

    private function validate($field, $value, $rules)
    {
        foreach($rules as $rule=> $satisfier) {
            if(in_array($rule, $this->rules)) {
                if(!call_user_func_array([$this,$rule], [$field,$value,$satisfier])) {
                    // error hai
                    $this->errorHandler->addError($field,str_replace([':field',':satisfier'],
                    [$field,$satisfier],$this->messages[$rule]));
                }
            }
        }
    }

    protected function required($field,$value,$satisfier) 
    {
        return !empty(trim($value));
    }
    protected function minlength($field,$value,$satisfier) 
    {
        return mb_strlen($value) >= $satisfier;
    }
    protected function maxlength($field,$value,$satisfier) 
    {
        return mb_strlen($value) <= $satisfier;
    }
    protected function email($field,$value,$satisfier) 
    {
        return filter_var($value,FILTER_VALIDATE_EMAIL);
    }
    protected function password($field,$value,$satisfier) 
    {
        return true;
    }
    protected function unique($field,$value,$satisfier) 
    {
        return !$this->database->table($satisfier)->exists([$field=>$value]);
    }
}

