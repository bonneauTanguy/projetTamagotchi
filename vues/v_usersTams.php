<html>
<div>
 <body>
  <link href="../style/style.css" rel="stylesheet" type="text/css">
    <img src="../style/images/logo.png">

    <table>
    <thead>
        <tr>
            <th scope="col"> <?php echo $name ?>NAME</th> 

            <th scope="col"> Soif </th>

            <th scope="col"> Faim </th>

            <th scope="col"> Dormir </th>

            <th scope="col"> Jouer </th>
            
            <th scope="col"> Action </th>

        </tr>
    </thead>
        <tr>
            <td>TAmTAM</td>
            <td><?php require_once ("./bdd/Database.class.php"); //drink(); ?> 70</td> 
            <td><?php require_once ("./bdd/Database.class.php"); //eat(); ?>70</td>
            <td><?php require_once ("./bdd/Database.class.php"); //bedtime(); ?>70</td>
            <td><?php require_once ("./bdd/Database.class.php"); //enjoy();?>70</td>
            <td><input class='myclass' type='button' value='Delete'/></td>
            

        </tr>
    <tfoot>
        <tr>
        <th scope="col"></th> 

        <th scope="col"><input class='myclass' type='button' value='Soif'/></th>

        <th scope="col"><input class='myclass' type='button' value='Faim'/></th>

        <th scope="col"><input class='myclass' type='button' value='Dormir'/></th>

        <th scope="col"><input class='myclass' type='button' value='Jouer'/></th>

        <th scope="col"></th>

        </tr>
        </tfoot>
</table>

</body>    
</div>
</html>

<?php
/*
* here we try to call the create account function We have followed your example 
* with migrate and some examples on the internet but unfortunately it does not work
*/
?>