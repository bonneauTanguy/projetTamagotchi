<?php      
    include('connection.php');  
    $username = $_POST['user'];   
      
        //to prevent from mysqli injection  
        $username = stripcslashes($username);   
        $username = mysqli_real_escape_string($con, $username);  
 
      
        $sql = "select *from users where username = '$username'";  
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);  
          
        if($count == 1){  
            echo "<h1><center> Login successful </center></h1>";  
        }  
        else{  
            echo "<h1> Login failed. Invalid username or password.</h1>";  
        }     
?>  