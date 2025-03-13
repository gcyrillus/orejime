<?php if(!defined('PLX_ROOT')) exit; ?>
<?php 
		# récupération d'une instance de plxMotor
	$plxMotor = plxMotor::getInstance();
	$plxPlugin = $plxMotor->plxPlugins->getInstance(basename(__DIR__));
	echo  $plxPlugin->getParam('privacyName'.$this->defaultLang(false));
?>