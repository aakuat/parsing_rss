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

$count_nur=1;
$count_zakon=1;
$count_rt=1;

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
	echo "nurKZ".$count_nur."</br>";
	}
	$count_nur++;
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
			echo "Zakon".$count_zakon."</br>";
			}
			$count_zakon++;
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
					echo "RT".$count_rt."</br>";
					}
					$count_rt++;
				} //end of rt parse

?>