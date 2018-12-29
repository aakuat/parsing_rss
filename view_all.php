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
	<title>Page with pagination</title>
	<meta charset="utf-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>  

</head>
<body>

<?php 
		//taking feedData to view
	$table_data_query=mysqli_query($con, "select * from nur_kz");
	$data_numRows=mysqli_num_rows($table_data_query);
	
	$perPage=10;
	$offset=0;
	$num_of_Page=ceil($data_numRows/$perPage);
	$current_page='';

	if(isset($_GET['page'])){
		$current_page=$_GET['page'];
		$offset=($perPage*$current_page)-$perPage;
	}

	$table_data_query_perPage=mysqli_query($con, "select * from nur_kz LIMIT " .$perPage. " OFFSET " .$offset);


?>

<!--Content view-->
<section>
	<div class="container justify-content-center">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<h2 class="text-center">RSS Feed</h2>
				<table class="table table-bordered">
					<thead>
						<tr>
							<td>№</td>
							<td>Source</td>
							<td>Title</td>
							<td>Description</td>
							<td>Date of publication</td>
						</tr>
					</thead>
					<tbody>
					<?php while($row=$table_data_query_perPage->fetch_assoc()):?>
						<tr>
							<td><a href="#" class="hover" id="<?php echo $row["id"]; ?>"><?php echo $row["id"]; ?></a></td>
							<td><?php echo $row['source']; ?></td>
							<td><a href="view_post.php?id=<?php echo $row['id'];?>" style="text-decoration:none;"><?php echo $row['title'];?></a></td>
							<td><?php echo $row['description'];?></td>
							<td><?php echo $row['pub_date'] ?></td>
						</tr>
					<?php endwhile; ?>
					</tbody>
				</table>

<!--Pagination start-->

	<nav aria-label="Page navigation example">
	 	<ul class="pagination">
	 	<!--Previous-->
		    <li class="page-item <?php if($current_page==1 || $current_page==''){echo 'disabled';} ?>">
		    <a class="page-link" href="<?php  
		    if($current_page==1 || $current_page==''){
		    	echo '#';
		    }else{
		    	echo '?page='.($current_page - 1);
		    }
		    ?>">Previous</a></li>

		<!--Numbers of pages-->
		    <?php for($p=1; $p<=$num_of_Page; $p++): ?>
		    	<li class="page-item <?php 
		    		if($current_page==$p){
		    			echo 'active';
		    		}elseif($current_page=='' && $p==1){
		    			echo 'active';
		    		}
		    						?>"><a class="page-link" href="?page=<?php echo $p;?>"><?php echo $p; ?></a></li>
		   	<?php endfor ?>
		<!--Next-->
		    <li class="page-item <?php if($current_page==$num_of_Page){
		    	echo 'disabled';
		    } ?>"><a class="page-link" href="<?php  
		    if($current_page==$num_of_Page){
		    	echo '#';
		    }elseif($current_page==''){
		    	echo '?page=2';
		    }else{
		    	echo '?page='.($current_page + 1);
		    }
		    ?>">Next</a></li>
	  	</ul>
		</nav>
<!--Pagination end-->
			</div>
		</div>
	</div>
</section>	
</body>
</html>


<script>  
      $(document).ready(function(){  
           $('.hover').popover({  
                title:fetchData,  
                html:true,  
                placement:'right'  
           });  
           function fetchData(){  
                var fetch_data = '';  
                var element = $(this);  
                var id = element.attr("id");  
                $.ajax({  
                     url:"view_popover.php",  
                     method:"POST",  
                     async:false,  
                     data:{id:id},  
                     success:function(data){  
                          fetch_data = data;  
                     }  
                });  
                return fetch_data;  
           }  
      });  
 </script>

