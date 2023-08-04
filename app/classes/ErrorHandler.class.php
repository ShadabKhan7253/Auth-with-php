<?php

class ErrorHandler 
{
    protected $errorBag = array();

    public function addError(string $key, string $errorMessage)
    {
        $this->errorBag[$key][] = $errorMessage;
    }
    public function hasErrors(): bool
    {
        return count($this->errorBag);
    }
    public function has($key): bool
    {
        return isset($this->errorBag[$key]);
    }
    public function all($key = null): mixed
    {
        return isset($this->errorBag[$key]) ? $this->errorBag[$key] : $this->errorBag;
    }
    public function first($key) : string|bool
    {
        return isset($this->errorBag[$key]) ? $this->errorBag[$key][0] : false;
    }
}