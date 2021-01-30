
<?php 

class Employee {
    
    /**
     * Employees Unique ID
     * @var int $id
     */
    public $id;

    /**
     * Employees Surname
     * @var string $surname
     */
    public $surname;

    /**
     * Hashed als salted password
     * @var string $password
     */
    public $password;

    public function __construct($id, $surname, $password){
        
        $this->surname = $surname;
        $this->password = $password;
        $this->id = $id;
    }

    public function getInfo(){
        return "ID: $this->id, Surname: $this->surname, Password: $this->password";
    }
}