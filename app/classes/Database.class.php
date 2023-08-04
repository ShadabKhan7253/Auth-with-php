<?php
class Database {

    protected PDO $pdo;
    protected $dataBag = array();
    protected string $table;
    protected string $where;
    // protected bool debug = false;

    public function __construct($host = 'localhost' , $db = 'auth' , $port=3306, 
    $username='ShadabKhan',$password='Khan123@',$fetchMode = PDO::FETCH_OBJ)  // PDO::FETCH_ASSOC
    {
        $this->pdo = new PDO("mysql:host={$host};port={$port};dbname={$db}",$username,$password);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , $fetchMode);
        $this->where = "1";
    }

    /**
     * Used to hit DDL Statements
     */
    public function query($sql) 
    {
        return $this->pdo->query($sql);
    }

    /**
     * Use this to fire Raw Select Queries
     */
    public function rawQueryExecutor($sql)
    {
        return $this->query($sql)->fetchAll();
    }

    public function table($table) 
    {
        $this->table = $table;
        // dd($this);
        // object(Database)#1 (4) { ["pdo":protected]=> object(PDO)#2 (0) { } ["dataBag":protected]=> array(0)
        //  { } ["table":protected]=> string(5) "users" ["where":protected]=> string(1) "1" }
        return $this;
    }

    public function where(string $field, string $value , string $operator="=")
    {
        $this->dataBag[$field] = $value;
        // dd($this->dataBag); // array(1) { ["email"]=> string(3) "%h%" }
        if($this->where === "1") {
            $this->where = "$field $operator :$field";
            // dd($this);
            // object(Database)#1 (4) { ["pdo":protected]=> object(PDO)#2 (0) { } 
            // ["dataBag":protected]=> array(1) { ["email"]=> string(3) "%h%" } ["table":protected]=> string(5) 
            // "users" ["where":protected]=> string(17) "email like :email" }
        } else {
            $this->where =  $this->where . " AND $field $operator :$field";
        }
        return $this;
    }

    public function get($columns = "*")
    {
        $sql = $this->prepareSQLQuery($columns);
        $ps = $this->pdo->prepare($sql);
        $ps->execute($this->dataBag);
        // dd($ps->execute($this->dataBag)); // bool(true)
        // return $ps->fetchAll(PDO::FETCH_NUM); // array(1) { [0]=> array(2) { [0]=> string(16) "shadab@gmail.com" [1]=> string(3) "z12" } }
        return $ps->fetchAll();
    }

    public function first($columns = "*") 
    {
        $sql = $this->prepareSQLQuery($columns);
        $sql = $sql . " LIMIT 0, 1";
        $ps = $this->pdo->prepare($sql);
        $ps->execute($this->dataBag);
        $data = $ps->fetchAll();
        $this->resetFields();
        return !empty($data) ? $data[0] : null;
    }

    public function count() 
    {
        $as = "count";
        $sql = "SELECT count(*) as $as FROM ($this->table) WHERE ($this->where)";
        $ps = $this->pdo->prepare($sql);
        $ps->execute($this->dataBag);
        return $ps->fetchAll()[0]->$as;
    }
    
    public function insert($data) 
    {
        // dd($data); //array(2) { ["email"]=> string(16) "shaddy@gmail.com" ["password"]=> string(8) "zzz@1234" }
        $keys = array_keys($data); // array(2) { [0]=> string(5) "email" [1]=> string(8) "password" }

        $fields = "`" . implode("`, `", $keys) . "`";
        // dd($fields); // string(19) "`email`, `password`"
        $placeholder = ":" . implode(", :", $keys);
        // dd($placeholder); // string(17) ":email, :password"

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES($placeholder)";
        // dd($sql); // string(62) "INSERT INTO users `email`, `password` VALUES :email, :password"
        $ps = $this->pdo->prepare($sql);
        // dd($ps);
        $this->resetFields();
        return $ps->execute($data);
    }

    private function prepareSQLQuery($columns) 
    {
        // dd($this->where); // string(17) "email like :email"
        // dd($columns); // *
        return "SELECT $columns FROM {$this->table} WHERE {$this->where}";
    }
    public function update(array $data) 
    {
        // dd($data); // array(1) { ["password"]=> string(3) "123" }
        $updationString = "";
        foreach($data as $key => $value) {
            $updationString = $updationString . " $key = :$key,";
            // dd($updationString); // string(22) " password = :password,"
        } 
        $updationString = rtrim($updationString, ",");
        // dd($updationString); // string(21) " password = :password"
        $sql = "UPDATE {$this->table} SET {$updationString} WHERE {$this->where}";
        // dd($sql); // string(62) "UPDATE users SET password = :password WHERE email like :email"
        $dataWithConditionalParams = array_merge($data,$this->dataBag);
        // dd($dataWithConditionalParams); // array(2) { ["password"]=> string(3) "123" ["email"]=> string(3) "%h%" }
        $ps = $this->pdo->prepare($sql);
        $this->resetFields();
        return $ps->execute($dataWithConditionalParams);
    }

    public function delete()
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->where}";
        $dataParams = $this->dataBag;
        $ps = $this->pdo->prepare($sql);
        $this->resetFields();
        return $ps->execute($dataParams);
    }
    public function exists(mixed $data) 
    {
        foreach($data as $key => $value)
        {
            $this->where($key,$value);
        }
        return $this->count() >=1 ? true : false;
    }

    private function resetFields() 
    {
        $this->table = "";
        $this->where = "1";
        $this->dataBag = array();
    }
    public function old($collection,$key,$defaultValue="") {
        return trim(isset($collection[$key]) ? $collection[$key] : $defaultValue);
    }

}

?>

<!-- Prepares an SQL statement to be executed by the PDOStatement::execute() method. 
The statement template can contain zero or more named (:name) or question mark (?) 
parameter markers for which real values will be substituted when the statement is executed. 
Both named and question mark parameter markers cannot be used within the same statement template; 
only one or the other parameter style. Use these parameters to bind any user-input, do not include 
the user-input directly in the query. -->