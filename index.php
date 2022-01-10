<?php
    // connect to database
    $conn = new PDO('mysql:host=localhost;dbname=mylessons;charset=utf8','root','');

    $message = "";
    $error = "";
    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $designation = $_POST['designation'];

        if(empty($_POST['name'])){
            $error = "<label class='text-danger'>Ener name</label>";
        }else if(empty($_POST['gender'])){
            $error = "<label class='text-danger'>Select gender</label>";
        }else if(empty($_POST['designation'])){
            $error = "<label class='text-danger'>Enter designation</label>";
        }else{
            if(file_exists('employee_data.json')){
                // get json data from json file
                $current_data = file_get_contents('employee_data.json');
                // convert json data to PHP array
                $current_data_array = json_decode($current_data, true, 512, 1);
                // set new data into an array
                $new_data_array = array(
                    'name'=> $_POST['name'],
                    'gender' => $_POST['gender'],
                    'designation' => $_POST['designation']
                );
                // append new data array to current data array
                $current_data_array[] = $new_data_array;
                // convert current array back to json
                $updated_data = json_encode($current_data_array);

                // store data to database table
                $sql_query = "INSERT INTO employee(name, gender, designation) VALUES('$name', '$gender','$designation')";
                if($conn->query($sql_query)){
                    if(file_put_contents('employee_data.json',$updated_data)){
                        $message = "<label class='text-success'>Data appended successfully...</label>";
                    }else{
                        $error = "<label class='text-danger'>Error appending data</label>";
                    }
                }else{
                    $error = "<label class='text-danger'>MySQL error</label>";
                }
            }else{
                $error = "<label class='text-danger'>Employee data not exists</label>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Append Data to JSON</title>
</head>
<body>
    <div class="bg-primary text-light p-4 mb-3">
        <h3 class="text-center">Append Data to JSON File & Database Using PHP</h3>
    </div>
    <div class="container" style="width: 500px;">
        <?php if(!empty($message)):?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><?php echo $message; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(!empty($error)):?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><?php echo $error; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
            <br>
            <label>Gender</label>
            <select name="gender" id="" class="form-select" required>
                <option value="">--select--</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
                <option value="N/A">N/A</option>
            </select>
            <br>
            <label>Designation</label>
            <input type="text" name="designation" class="form-control" required>
            <br>
            <button type="submit" class="btn btn-lg btn-primary" name="submit">Append</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>