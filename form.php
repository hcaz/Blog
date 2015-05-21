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
				foreach($tags as $tag){
					
				}
				die;
				$conn->query("INSERT INTO `POSTS` (`TITLE`, `TYPE`, `CONTENT`, `CATEGORY`, `USER`, `PINNED`) VALUES ('".$_POST['title']."', '".$_POST['type']."', '".$_POST['content']."', '".$_POST['category']."', '".$USER['ID']."', '".$_POST['pinned']."')");
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: /?messageS=Posted!");
			}
		}
	}else{
		header("Location: /?messageE=Not found.");
	}
?>