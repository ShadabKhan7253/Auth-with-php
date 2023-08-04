<?php

class User {
    static public Database $database;
    static protected string $table="users";

    protected $attb = array();
    public function __set($name, $value)
    {
        $this->attb[$name] = $value;
    }
    public function __get($name)
    {
        return $this->attb[$name] ?? null;
    }
    public static function build()
    {
        $sql = "CREATE TABLE IF NOT EXISTS ". static::$table ." 
        (id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, email VARCHAR(255) NOT NULL UNIQUE, 
        username VARCHAR(20) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL)";
        self::$database->query($sql);
    }

    static public function create(mixed $data): mixed
    {
        // dd($data); // array(3) { ["username"]=> string(2) "az" ["email"]=> string(11) "a@gmail.com" ["password"]=> string(3) "123" }
        if(!isset($data['password'])) {
            return false;
        }
        $data['password'] = Hash::make($data['password']);
        return self::$database->table(self::$table)->insert($data);
    }

    public function save(): bool
    {
        return $this->create($this->attb);
    }

    public static function updatePassword(string $newPassword,int $userId)
    {
        return self::$database->table(self::$table)->where('id',$userId)->update(['password'=>Hash::make($newPassword)]);
    }

    public static function findByUsername(string $username)
    {
        return self::$database->table(self::$table)
                            ->where('username',$username)
                            ->first();
    }

    public static function findByEmail(string $email)
    {
        return self::$database->table(self::$table)
                            ->where('email',$email)
                            ->first();
    }

    public static function find(int $userId)
    {
        return self::$database->table(self::$table)
        ->where('id',$userId)
        ->first();
    }
}

?>