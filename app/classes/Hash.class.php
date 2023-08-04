<?php
class Hash 
{
    static public function make(string $plaintext): string
    {
        return password_hash($plaintext, PASSWORD_BCRYPT);
    }

    static public function verify(string $password,string $hashedPassword): bool
    {
        return password_verify($password,$hashedPassword);
    }

    static public function generateRandomToken(int $userId=0)
    {
        return hash('sha256',"$userId" . getCurrentTimeInMillis() . strrev("$userId") . rand());
    }
}