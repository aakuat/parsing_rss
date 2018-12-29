<?php
$con=mysqli_connect("localhost","root","", "parse");
mysqli_set_charset($con, 'utf8');
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if(isset($_POST["id"]))  
 {  
      $output = '';  
      $query = "SELECT * FROM nur_kz WHERE id='".$_POST['id']."'";  
      $result = mysqli_query($con, $query); 
      while($row = mysqli_fetch_array($result))  
      {  
           $output = '
                <h5>'.$row['title'].'</h5>  
                <p>'.$row['origin'].'</p>  
           ';  
      }  
      echo $output;  
 }  
?>
