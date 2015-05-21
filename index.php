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
			$row['TAGS'] = array("ONE","TWO","THREE");
			$POSTS[] = $row;
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
	}elseif($slug=="form"){
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