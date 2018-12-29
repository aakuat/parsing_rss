<?php
$con=mysqli_connect("localhost","root","", "parse");
mysqli_set_charset($con, 'utf8');
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Post</title>
	<meta charset="utf-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
</head>
<body style="background-color:grey;">

<?php 
		if(isset($_GET['id'])){
			$query=mysqli_query($con, "select * from nur_kz where id='".$_GET['id']."'");
			$row=mysqli_fetch_assoc($query);
			$title=$row['title'];
			$body=$row['origin'];
		}

?>

<!--post_view-->
<div style="margin:0 auto; width:900px;background-color:white; height:auto; min-height:100vh;">
	<h2><?php echo $title; ?></h2>
	</br>
	<div><?php echo $body; ?></div>
</div>

</body>
</html>>