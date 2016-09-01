<?php 
	if(!defined('PLX_ROOT')) exit; 

	$plxShow = plxShow::getInstance();

	$plxPlugin = $plxShow->plxMotor->plxPlugins->getInstance('Page');

	echo $plxPlugin->getParam('texte');
?>