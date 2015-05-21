<?php
	require("config.php");
	require_once getcwd().'/Twig/Autoloader.php';
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem(getcwd().'/themes/'.$GLOBAL['THEME']);
	$twig = new Twig_Environment($loader, array('debug' => true));
	$twig->addExtension(new Twig_Extension_Debug());
	$twig->getExtension('core')->setTimezone('Europe/London');
	if($sub==null && $slug==null){
		$template = $twig->loadTemplate('home.htm');
		$result = $conn->query("SELECT * FROM `POSTS` WHERE `STATE` = '1' ORDER BY `ID` DESC;");
		while($row = $result->fetch_assoc()) {
			$POSTS[$row['ID']] = $row;
			$result2 = $conn->query("SELECT `TAGS`.`SLUG`, `TAGS`.`NAME` FROM `TAG_LINK` INNER JOIN `TAGS` on `TAG_LINK`.`TAG` = `TAGS`.`ID` WHERE `POST` = '".$row['ID']."';");
			while($row2 = $result2->fetch_assoc()) {
				if($row2['NAME']!=""){
					$POSTS[$row['ID']]['TAGS'][$row2['SLUG']] = $row2['NAME'];
				}
			}
		}
		$USER['DETAILS']['PERMS'] = json_decode($USER['DETAILS']['PERMS'],true);
		echo $template->render(
			array(
				'global' => $GLOBAL,
				'user' => $USER,
				'posts' => $POSTS,
				'sub' => $sub,
				'slug' => $slug
			)
		);
	}elseif($sub=="post" && $slug!=""){
		$template = $twig->loadTemplate('post.htm');
		$result = $conn->query("SELECT * FROM `POSTS` WHERE `STATE` = '1' AND `SLUG` = '".$slug."';");
		while($row = $result->fetch_assoc()) {
			$POSTS[$row['ID']] = $row;
			$result2 = $conn->query("SELECT `TAGS`.`SLUG`, `TAGS`.`NAME` FROM `TAG_LINK` INNER JOIN `TAGS` on `TAG_LINK`.`TAG` = `TAGS`.`ID` WHERE `POST` = '".$row['ID']."';");
			while($row2 = $result2->fetch_assoc()) {
				if($row2['NAME']!=""){
					$POSTS[$row['ID']]['TAGS'][$row2['SLUG']] = $row2['NAME'];
				}
			}
		}
		$USER['DETAILS']['PERMS'] = json_decode($USER['DETAILS']['PERMS'],true);
		echo $template->render(
			array(
				'global' => $GLOBAL,
				'user' => $USER,
				'posts' => $POSTS,
				'sub' => $sub,
				'slug' => $slug
			)
		);
	}elseif($sub=="tag" && $slug!=""){
		$template = $twig->loadTemplate('home.htm');
		$result = $conn->query("SELECT `POSTS`.`ID`, `POSTS`.`TITLE`, `POSTS`.`SLUG`, `POSTS`.`TYPE`, `POSTS`.`CONTENT`, `POSTS`.`CATEGORY`, `POSTS`.`USER`, `POSTS`.`PINNED`, `POSTS`.`PINNED`, `POSTS`.`STATE`, `POSTS`.`DATE` FROM `TAGS` INNER JOIN `TAG_LINK` ON `TAGS`.`ID` = `TAG_LINK`.`TAG` INNER JOIN `POSTS` ON `TAG_LINK`.`POST` = `POSTS`.`ID` WHERE `TAGS`.`SLUG` = '".$slug."';");
		while($row = $result->fetch_assoc()) {
			$POSTS[$row['ID']] = $row;
			$result2 = $conn->query("SELECT `TAGS`.`SLUG`, `TAGS`.`NAME` FROM `TAG_LINK` INNER JOIN `TAGS` on `TAG_LINK`.`TAG` = `TAGS`.`ID` WHERE `POST` = '".$row['ID']."';");
			while($row2 = $result2->fetch_assoc()) {
				if($row2['NAME']!=""){
					$POSTS[$row['ID']]['TAGS'][$row2['SLUG']] = $row2['NAME'];
				}
			}
		}
		$USER['DETAILS']['PERMS'] = json_decode($USER['DETAILS']['PERMS'],true);
		echo $template->render(
			array(
				'global' => $GLOBAL,
				'user' => $USER,
				'posts' => $POSTS,
				'sub' => $sub,
				'slug' => $slug
			)
		);
	}elseif($sub=="search" && $slug!=""){
		$template = $twig->loadTemplate('home.htm');
		$result = $conn->query("SELECT * FROM `POSTS` WHERE `STATE` = '1' AND `TITLE` LIKE '%".$slug."%' OR `CONTENT` LIKE '%".$slug."%';");
		while($row = $result->fetch_assoc()) {
			$POSTS[$row['ID']] = $row;
			$result2 = $conn->query("SELECT `TAGS`.`SLUG`, `TAGS`.`NAME` FROM `TAG_LINK` INNER JOIN `TAGS` on `TAG_LINK`.`TAG` = `TAGS`.`ID` WHERE `POST` = '".$row['ID']."';");
			while($row2 = $result2->fetch_assoc()) {
				if($row2['NAME']!=""){
					$POSTS[$row['ID']]['TAGS'][$row2['SLUG']] = $row2['NAME'];
				}
			}
		}
		$USER['DETAILS']['PERMS'] = json_decode($USER['DETAILS']['PERMS'],true);
		echo $template->render(
			array(
				'global' => $GLOBAL,
				'user' => $USER,
				'posts' => $POSTS,
				'sub' => $sub,
				'slug' => $slug
			)
		);
	}elseif($sub=="system" && $slug=="form"){
		require("form.php");
	}else{
		$template = $twig->loadTemplate('error.htm');
		echo $template->render(
			array(
				'global' => $GLOBAL,
				'user' => $USER,
				'sub' => $sub,
				'slug' => $slug,
				'message' => "Page not found"
			)
		);
	}
?>