<?php
	if($_POST['source']=="post"){
		if($_POST['title']==""){
			header("Location: /?messageE=Please enter a title.");
		}else{
			if($_POST['content']==""){
				header("Location: /?messageE=Please enter some content.");
			}else{
				if($_POST['pinned']==""){$_POST['pinned']="0";}
				$_POST['slug'] = str_replace(" ","_",strtolower($_POST['title']));
				$tags = explode(",",$_POST['tags']);
				$conn->query("INSERT INTO `POSTS` (`TITLE`, `SLUG`, `TYPE`, `CONTENT`, `CATEGORY`, `USER`, `PINNED`) VALUES ('".$_POST['title']."', '".$_POST['slug']."', '".$_POST['type']."', '".$_POST['content']."', '".$_POST['category']."', '".$USER['ID']."', '".$_POST['pinned']."')");
				$postid = mysqli_insert_id($conn);
				foreach($tags as $tag){
					$tagslug = str_replace(" ","_",strtolower($tag));
					$result = $conn->query("SELECT `ID` FROM `TAGS` WHERE `SLUG` = '".$tagslug."';");
					if($result->num_rows==1){
						$row = $result->fetch_assoc();
						$tagid = $row['ID'];
					}else{
						$conn->query("INSERT INTO `TAGS` (`SLUG`, `NAME`) VALUES ('".$tagslug."', '".$tag."');");
						$tagid = mysqli_insert_id($conn);
					}
					$conn->query("INSERT INTO `TAG_LINK` (`POST`, `TAG`) VALUES ('".$postid."', '".$tagid."');");
				}
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: /?messageS=Posted!");
			}
		}
	}elseif($_POST['source']=="search"){
		header("Location: /search/".$_POST['search']);
	}else{
		header("Location: /?messageE=Not found.");
	}
?>