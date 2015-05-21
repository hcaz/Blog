<?php
	require("config.php");
	require_once getcwd().'/Twig/Autoloader.php';
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem(getcwd().'/themes/'.$GLOBAL['theme']);
	$twig = new Twig_Environment($loader, array('debug' => true));
	$twig->addExtension(new Twig_Extension_Debug());
	$twig->getExtension('core')->setTimezone('Europe/London');
	if($sub==null && $slug==null){
		$template = $twig->loadTemplate('home.htm');
		echo $template->render(
			array(
				'global' => $GLOBAL,
				'sub' => $sub,
				'slug' => $slug,
				'get' => array(
					'1'=>$_GET['gethandle1'],
					'2'=>$_GET['gethandle2'],
					'3'=>$_GET['gethandle3'],
					'4'=>$_GET['gethandle4']
				)
			)
		);
	}else{
		$template = $twig->loadTemplate('error.htm');
		echo $template->render(
			array(
				'global' => $GLOBAL,
				'sub' => $sub,
				'slug' => $slug,
				'message' => "Page not found"
			)
		);
	}
?>