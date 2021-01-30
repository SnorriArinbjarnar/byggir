<?php 


class Machine {
    /**
     * Machines unique id
     * @var int $id
     */
    public $id;

    /**
     * Machines title
     * @var string $title
     */
    public $title;
    public $emp_id;
    public $service;

    function __construct($id, $title){
        $this->id = $id;
        $this->title = $title;
        
    }
    /**
     * assigning a service to this model.  The way I models the solution
     * this model needed connection to database, I know it is bad practice
     * to give the model direct connection to the database so I instead
     * passed in the service.  My idea here was like Depenceny Injection but I am
     * not sure how it is implemented in PHP.
     */
    function set_service($service){
        $this->service = $service;
    }

    public function get_data(){
        return "ID: $this->id, \n Title:$this->title \n";
    }
    /**
     * Assigns the machine to the given employee (checks the machine out)
     * @param Employee $employee the employee who wants to check out the machine
     */
    public function checkout(Employee $employee) : void 
    {
        $this->service->assignMachineToEmployee($employee->id, $this->id);
    }

    /**
     * Indicates that no employee has taken the machine with them
     * and that the employee put the machine back to the warehouse
     */
    public function back_to_warehouse() : void 
    {
        $this->service->returnMachine($this->id);
    }
}