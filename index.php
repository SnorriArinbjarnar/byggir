<?php 
    include('./DataService.php');

    $service = new DataService('hello');
    $data = $service->getListOfMachinesAndUsage();
    $employees = $service->getAllEmployees();
    $unassignedMachines = $service->getAllUnassignedMachines();
   
    $name = '';
    $machines = array();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!empty($_POST['employeeForm'])){
            $name = $_POST['name'];
            $id = $service->getEmployeeIdFromName($name);
            $machines = $service->getAllEmployeeMachines($id);
        }
        if(!empty($_POST['getEmpMachines'])){
            $id = $_POST['empMachines'];
            $empName = $service->getEmployeeById($id);
            $name = $empName->surname;
            $machines = $service->getAllEmployeeMachines($id);
        }
        if(!empty($_POST['assignForm'])){
            $selectedMachine = $_POST['assignMachine'];
            $selectedUser = $_POST['assignEmployee'];
            $getSelectedUser = $service->getEmployeeById($selectedUser);
            $getSelectedMachine = $service->getMachineById($selectedMachine);
            $getSelectedMachine->set_service($service);

            $getSelectedMachine->checkout($getSelectedUser);

        }
        if(!empty($_POST['createMach'])){
            $machine_title = $_POST['title'];
            $machine = new Machine(NULL, $machine_title);
            $service->insert_record('Machine', $machine);
        }
        if(!empty($_POST['createEmp'])){
            $emp_surname = $_POST['surname'];
            $emp_passwd = $_POST['password'];
            $emp = new Employee(NULL, $emp_surname, $emp_passwd);
            $service->insert_record('Employee', $emp);
        }
        if(!empty($_POST['returnMachine'])){
            $machinePOST = $_POST['machineid'];
            $getMachine = $service->getMachineById($machinePOST);
            $getMachine->set_service($service);
            $getMachine->back_to_warehouse();
 
        }
    }
     
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .machines {
            margin-bottom: 10px;
        }

        .input-group {
            padding: 10px;
        }

        .create-container {
            display: flex;
        }

        input {
            display: block;
            padding: 5px;
        }

        .create-mach, .create-emp {
            padding: 10px;
        }

        .create-emp input {
            margin-right: 10px;
        }

        .create-mach input[type=submit] {
            margin-top: 35px;
        }

        #getEmpMachines {
            margin-top: 10px;
            width: 300px;
        }

        #assigned-machine {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
   <h1>Playground</h1>
   <div class="container">
        <div class="assign-machine">
        <h4>Assign Machine to Employee</h4>
            <form action="<?php echo $_SERVER["PHP_SELF"] ;?>" method="post">
                <label for="assignEmployee">Choose a Employer:</label>
                <select name="assignEmployee" id="">
                    <?php 
                    foreach($employees as $employee){
                    ?>
                        <option value="<?php echo $employee['ID'] ?>"><?php echo $employee['Surname'] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <br><br>
                <label for="cars">Choose a Machine:</label>
                <select name="assignMachine" id="">
                    <?php 
                    foreach($unassignedMachines as $uMachine){
                    ?>
                        <option value="<?php echo $uMachine['ID'] ?>"><?php echo $uMachine['Title'] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <br><br>
                <input type="submit" name="assignForm">
            </form>
        </div>
        <div class="machines">
        <h4>Machines that are in use</h4>
            <table>
                <tr>
                    <th>Machine</th>
                    <th>Using</th>
                </tr>
                <?php 
                    foreach($data as $item){
                        ?>
                        <tr>
                            <td><?php echo $item['Title'] ?></td>
                            <td><?php echo $item['Surname'] ?></td>
                        </tr>
                        <?php
                    }
                ?>
                
            </table>
        </div>
        <div class="employees">
            <h4>Get Machines loaned to a particular Employee</h4>
            <form action="<?php echo $_SERVER["PHP_SELF"] ;?>" method="post">
                <label for="empMachines">Choose a Employer:</label>
                <select name="empMachines" id="">
                    <?php 
                    foreach($employees as $employee){
                    ?>
                        <option value="<?php echo $employee['ID'] ?>"><?php echo $employee['Surname'] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <input type="submit" value="Get Machines" name="getEmpMachines" id="getEmpMachines">
            </form>
            <table>
                <tr>
                    <th>Machines assigned to <?php echo $name ?></th>
                </tr>
                <?php 
                    foreach($machines as $machine){
                        ?>
                        <tr>
                            <td id="assigned-machine">
                            <?php echo $machine->title ?>
                            <form action="<?php echo $_SERVER["PHP_SELF"] ;?>" method="POST">
                                    <input type="hidden" name="machineid" id="bar" value="<?php echo $machine->id; ?>" > 
                                    <input type="submit" name="returnMachine" value="Return Machine">
                                </form>
                            </td>
                            
                        </tr>
                        <?php
                    }
                ?>
            </table>
        </div>
       
   </div>
   <div class="create-container">
           <div class="create-emp">
                <h4>Create An employee</h4>
                <form action="" method="POST">
                    <label for="surname">Surname</label>
                    <input type="text" name="surname">
                    <label for="password">Password</label>
                    <input type="password" name="password">
                    <input type="submit" name="createEmp" value="Create Employee">
                </form>
           </div>
           <div class="create-mach">
                <h4>Create A Machine</h4>
                <form action="" method="POST">
                    <label for="title">Title</label>
                    <input type="text" name="title">
                    <input type="submit" name="createMach" value="Create Machine">
                </form>
           </div>
        </div>

</body>
</html>