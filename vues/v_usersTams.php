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
            <td><?php //drink(); ?> 70</td>
            <td><?php //eat(); ?>70</td>
            <td><?php //bedtime(); ?>70</td>
            <td><?php //enjoy();?>70</td>
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