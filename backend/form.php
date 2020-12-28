<!DOCTYPE HTML>
<html>
<head>
<title>Test-backend</title>
</head>
<body>
		<form method="post" action="form.php">
		<input type="radio" name="rss" value="1">komputer świat
		
		<input type="radio" name="rss" value="2">rmf24

		<input type="submit" value="send">
	</form>
</body>
</html>
<?php

header('Content-Type: text/html; charset=utf-8');
set_time_limit(90);
if (!ini_get('date.timezone')) {
	date_default_timezone_set('Europe/Prague');
}

require_once 'vendor/dg/rss-php/src/Feed.php';
define('__ROOT__', dirname(dirname(__FILE__)));
require_once 'config.php';

include('vendor/dg/simplehtmldom//simple_html_dom.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	switch ($_POST['rss']) 
	{
		
		case 1:
		writeLog(0,"start","logs/logs_ks.txt");
		$html = file_get_html('http://www.komputerswiat.pl/rss-feeds/komputer-swiat-feed.aspx');
		//$links=array();
		//$articles=array();

		
		foreach($html->find('.mediumNewsBox a') as $e) 
		{	
			$links[]= $e->href;
		}
		//print_r($links);
		$i=0;
		foreach($links as $link)
		{
			
			$html = file_get_html($link);
			foreach($html->find('.mainTitle') as $e)
			$articles[$i]['title']=$e->outertext;
			foreach($html->find('.datePublished') as $e)
			$articles[$i]['datePublished']=$e->datetime;
			foreach($html->find('.lead') as $e)
			$articles[$i]['contents']=$e->outertext;
			$i++;
		}
		//print_r($articles);
		$mMysqli=mysqli_connect(DB_HOST, DB_USER, DB_PASWORD, DB_DATABASE);
		$mMysqli->query('SET NAMES utf8');
		$sql = 'INSERT INTO articles_ks (date_publication,date_add,title,contents) VALUES ';
		$limit=0;
		foreach ($articles as $article) 
		{		
			$query1='SELECT title from articles_ks where title="'.mysql_real_escape_string(strip_tags($article['title'])).'" and date_publication="'.date("Y/m/d H:i:s", strtotime($article['datePublished'])).'"'; 
			$rez=mysqli_query($mMysqli,$query1);
			$k=mysqli_fetch_array($rez,MYSQLI_ASSOC);
			
			if(is_null($k['title']))
			{
			   if($limit<5)
				{
					$sql.='("'.date("Y/m/d H:i:s", strtotime($article['datePublished'])).'","'.date("Y-m-d H:i:s").'","'.mysql_real_escape_string(strip_tags($article['title'])).'","'.mysql_real_escape_string(strip_tags($article['contents'])).'" ) ,';
					
					$limit++;
				}
		

			}
					
		}
		$sql=substr($sql, 0, -1);
		if(mysqli_query($mMysqli,$sql))
		{
			echo "Records inserted successfully.";
		} else
		{
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($mMysqli);
		}
		
		$insert=$mMysqli->insert_id;
		$row_cnt;
		if ($result = $mMysqli->query("SELECT id FROM articles_ks ")) 
		{
			$row_cnt = $result->num_rows;
		}
		for($i=$insert;$i<=$row_cnt;$i++)
		{
			writeLog($i,"write","logs/logs_ks.txt");
		}
		$mMysqli->close();
		writeLog(0,"end","logs/logs_ks.txt");
		break;
		case 2:
		 
		writeLog(0,"start","logs/logs_rmf24.txt");
		$rss = Feed::loadRss('http://www.rmf24.pl/sport/feed');
		$mMysqli=mysqli_connect(DB_HOST, DB_USER, DB_PASWORD, DB_DATABASE);
		$mMysqli->query('SET NAMES utf8');
		$sql = 'INSERT INTO articles_rmf24 (date_publication,date_add,title,contents,guid) VALUES ';
		$limit=0;
		foreach ($rss->item as $item) 
		{		
			$query1='SELECT guid from articles_rmf24 where guid="'.$item->guid.'"'; 
			$rez=mysqli_query($mMysqli,$query1);
			$k=mysqli_fetch_array($rez,MYSQLI_ASSOC);
			
			if(is_null($k['guid']))
			{
			   if($limit<5)
				{
					$sql.='("'.date("Y/m/d H:i:s", strtotime($item->pubDate)).'","'.date("Y-m-d H:i:s").'","'.mysql_real_escape_string(strip_tags($item->title)).'","'.mysql_real_escape_string(strip_tags($item->description)).'","'.$item->guid.'" ) ,';
					
					$limit++;
				}
		

			}
				
		}
		$sql=substr($sql, 0, -1);
		if(mysqli_query($mMysqli,$sql))
		{
			echo "Records inserted successfully.";
		} 
		else
		{
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($mMysqli);
		}
		$insert=$mMysqli->insert_id;
		$row_cnt;
		if ($result = $mMysqli->query("SELECT id FROM articles_rmf24 ")) 
		{
			$row_cnt = $result->num_rows;
		}
		for($i=$insert;$i<=$row_cnt;$i++)
		{
			writeLog($i,"write","logs/logs_rmf24.txt");
		}
		$mMysqli->close();
		writeLog(0,"end","logs/logs_rmf24.txt");
		break;
		
	}
	
}	
 function writeLog($index,$flag,$file )
{
	$dane;
	if($flag=="write")
	{
		$dane = "Dodane indeks: ".$index."\n"."Data: ".date("Y-m-d H:i:s")."\n";
    }
	if($flag=="start")
	{
		$dane = "Rozpoczecie wykonywania skryptu: ".date("Y-m-d H:i:s")."\n";	
	}
	if($flag=="end")
	{
		$dane = "Zakończenie wykonywania skryptu: ".date("Y-m-d H:i:s")."\n";	
	}
				
				$fp = fopen($file, "a");
				flock($fp, 2);
				fwrite($fp, $dane);
				flock($fp, 3);
				fclose($fp); 
}		 	
?>