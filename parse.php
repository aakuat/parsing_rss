<?php
require('simple_html_dom.php');

$con=mysqli_connect("localhost","root","", "parse");
mysqli_set_charset($con, 'utf8');
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$NURKZ_url="https://www.nur.kz/rss/all.rss";
$NURKZ_xml=simplexml_load_file($NURKZ_url);

$Zakon_url="http://st.zakon.kz/rss/rss_all.xml";
$Zakon_xml=simplexml_load_file($Zakon_url);

$rt_url="https://russian.rt.com/rss";
$rt_xml=simplexml_load_file($rt_url);


foreach ($NURKZ_xml->channel->item as $row){ //Nur-KZ parse
	$title = $row->title;
	$origin1= "abcd"; //origin var
	$link = $row->link; 
	$desc = $row->description;
	$pubDate = $row->pubDate;
	
//dom parse origin
		$html=file_get_html("$link");
		foreach($html->find("article.formatted-body") as $origin){
			$origin1= $origin; //original text
		}

	//echo $pubDate;
		
//$sql="INSERT INTO nur_kz (source, title, description, pub_date, link, origin)
//	  VALUES ('nur-kz', '$title','$desc','$pubDate','$link','".mysqli_real_escape_string($con, $origin1)."')";
$sql="INSERT INTO nur_kz (source, title, description, pub_date, link, origin)
SELECT 'Nur-kz', '$title','$desc','$pubDate','$link','".mysqli_real_escape_string($con, $origin1)."'
FROM DUAL
WHERE NOT EXISTS(
    SELECT 1
    FROM nur_kz
    WHERE title = '$title' AND description = '$desc'
)
LIMIT 1;";

$result=mysqli_query($con, $sql);

if(!$result){
	echo "mysql error";
}else{
	echo "";
	}
} //end of Nur-KZ parse

		foreach ($Zakon_xml->channel->item as $row){ //Zakon parse
		$title = $row->title;
		$origin1= "abcd"; //origin var
		$link = $row->link; 
		$desc = $row->description;
		$pubDate = $row->pubDate;
			
		//dom parse origin
				$html=file_get_html("$link");
				foreach($html->find("div.fullnews") as $origin){
					$origin1= $origin; //original text
				}

			//echo $pubDate;
				
		// $sql="INSERT INTO nur_kz (source, title, description, pub_date, link, origin)
		// 	  VALUES ('Zakon', '$title','$desc','$pubDate','$link','".mysqli_real_escape_string($con, $origin1)."')";

		$sql="INSERT INTO nur_kz (source, title, description, pub_date, link, origin)
		SELECT 'Zakon', '$title','$desc','$pubDate','$link','".mysqli_real_escape_string($con, $origin1)."'
		FROM DUAL
		WHERE NOT EXISTS(
		    SELECT 1
		    FROM nur_kz
		    WHERE title = '$title' AND description = '$desc'
		)
		LIMIT 1;";

		$result=mysqli_query($con, $sql);

		if(!$result){
			echo "mysql error";
		}else{
			echo "";
			}
		} //end of Zakon parse

				foreach ($rt_xml->channel->item as $row){ //rt parse
				$title = $row->title;
				$origin1= "abcd"; //origin var
				$link = $row->link; 
				$desc = $row->description;
				$pubDate = $row->pubDate;


				//dom parse origin

						$html=file_get_html("$link");

						foreach($html->find("div.article__text_article-page") as $origin){
							$origin1= $origin; //original text
						}

					//echo $pubDate;

				$sql="INSERT INTO nur_kz (source, title, description, pub_date, link, origin)
				SELECT 'rt', '$title','$desc','$pubDate','$link','".mysqli_real_escape_string($con, $origin1)."'
				FROM DUAL
				WHERE NOT EXISTS(
				    SELECT 1
				    FROM nur_kz
				    WHERE title = '$title' AND description = '$desc'
				)
				LIMIT 1;";

				$result=mysqli_query($con, $sql);

				if(!$result){
					echo "rt error";
				}else{
					echo "";
					}
				} //end of rt parse





?>

<!DOCTYPE html>
<html>
<head>
	<title>Page with pagination</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="bootstrap.min.css">
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
							<td><?php echo $row['id'];?></td>
							<td><?php echo $row['source']; ?></td>
							<td><a href="view.php?id=<?php echo $row['id'];?>" style="text-decoration:none;"><?php echo $row['title'];?></a></td>
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
</html>>