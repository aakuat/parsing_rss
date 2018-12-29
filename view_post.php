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
	<link rel="stylesheet" href="bootstrap.min.css">
</head>
<body>

<?php 
		if(isset($_GET['id'])){
			$query=mysqli_query($con, "select * from nur_kz where id='".$_GET['id']."'");
			$row=mysqli_fetch_assoc($query);
			$title=$row['title'];
			$body=$row['origin'];
		}

?>

<!--post_view-->
<div style="margin:0 auto;">
	<h2><?php echo $title; ?></h2>
	</br>
	<div><?php echo $body; ?></div>
</div>

</body>
</html>>