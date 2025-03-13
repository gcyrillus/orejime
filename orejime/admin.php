<?php
	if(!defined('PLX_ROOT')) exit;
	/**
		* Plugin 			orejime
		*
		* @CMS required		PluXml 
		* @page				admin.php
		* @version			1.0
		* @date				2025-03-10
		* @author 			G-Cyrillus
		░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
		░       ░░  ░░░░░░░  ░░░░  ░  ░░░░  ░░      ░░       ░░░      ░░  ░░░░░░░        ░░      ░░░░░   ░░░  ░        ░        ░
		▒  ▒▒▒▒  ▒  ▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒  ▒▒  ▒▒  ▒▒▒▒  ▒  ▒▒▒▒  ▒  ▒▒▒▒  ▒  ▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒▒▒▒▒▒▒▒▒    ▒▒  ▒  ▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒
		▓       ▓▓  ▓▓▓▓▓▓▓  ▓▓▓▓  ▓▓▓    ▓▓▓  ▓▓▓▓  ▓       ▓▓  ▓▓▓▓  ▓  ▓▓▓▓▓▓▓▓▓▓  ▓▓▓▓▓      ▓▓▓▓▓  ▓  ▓  ▓      ▓▓▓▓▓▓  ▓▓▓▓
		█  ███████  ███████  ████  ██  ██  ██  ████  █  ███████  ████  █  ██████████  ██████████  ████  ██    █  ██████████  ████
		█  ███████        ██      ██  ████  ██      ██  ████████      ██        █        ██      ██  █  ███   █        ████  ████
		█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
	**/	
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
	
	# Liste des langues disponibles et prises en charge par le plugin
	$aLangs = array($plxAdmin->aConf['default_lang']);	
	
	if(!empty($_POST)) {
	
		if(isset($_POST['submit']))$plxPlugin->setParam('buttons', $_POST['buttons'],'numeric');
		
		if(isset($_POST['submitTPL']) && !empty($_POST['markup'])) {
			$id=$_POST['newId'];			
			$plxPlugin->setParam('cookieTPL-'.$id, $_POST['markup'], 'cdata');
			if($plxPlugin->getParam('group-'.plxUtils::urlify($_POST['purpose']))=='')
					$plxPlugin->setParam('group-'.plxUtils::urlify($_POST['purpose']),'cookieTPL-'.$id,'string');
			else
					$plxPlugin->setParam('group-'.plxUtils::urlify($_POST['purpose']),$plxPlugin->getParam('group-'.plxUtils::urlify($_POST['purpose'])).',cookieTPL-'.$id,'string');	
			$plxPlugin->saveParams();			
			header("Location: plugin.php?p=".basename(__DIR__));
			exit;
		}
		/*newId
			new-item
			diet_type
		purpose*/
		
		
		
		
		$plxPlugin->saveParams();
		
		header("Location: plugin.php?p=".basename(__DIR__));
		exit;
	}
	# init vars / remove unecessary
	
	
	# initialisation des variables propres à chaque lanque
	$langs = array();
	foreach($aLangs as $lang) {
		# chargement de chaque fichier de langue
		$langs[$lang] = $plxPlugin->loadLang(PLX_PLUGINS.'orejime/lang/'.$lang.'.php');
		$var[$lang]['mnuName'] =  $plxPlugin->getParam('mnuName_'.$lang)=='' ? $plxPlugin->getLang('L_DEFAULT_MENU_NAME') : $plxPlugin->getParam('mnuName_'.$lang);
	}
	# init static page var
	# initialisation des variables page statique
	$var['mnuDisplay'] =  $plxPlugin->getParam('mnuDisplay')=='' ? 1 : $plxPlugin->getParam('mnuDisplay');
	$var['mnuPos'] =  $plxPlugin->getParam('mnuPos')=='' ? 2 : $plxPlugin->getParam('mnuPos');
	$var['template'] = $plxPlugin->getParam('template')=='' ? 'static.php' : $plxPlugin->getParam('template');
	$var['url'] = $plxPlugin->getParam('url')=='' ? strtolower(basename(__DIR__)) : $plxPlugin->getParam('url');
	
	$var['buttons']= $plxPlugin->getParam('buttons')=='' ? 0 : $plxPlugin->getParam('buttons');
	
	# On récupère les templates des pages statiques
	$glob = plxGlob::getInstance(PLX_ROOT . $plxAdmin->aConf['racine_themes'] . $plxAdmin->aConf['style'], false, true, '#^^static(?:-[\\w-]+)?\\.php$#');
	if (!empty($glob->aFiles)) {
		$aTemplates = array();
		foreach($glob->aFiles as $v)
		$aTemplates[$v] = basename($v, '.php');
		} else {
		$aTemplates = array('' => L_NONE1);
	}
	/* end template */
	
	
	# maj selects 
	$plxPlugin->purposes = array_merge(array('0'=>L_FOR_SELECTION), $plxPlugin->purposes);
	
	$nextTplId= count($plxPlugin->cookieTPL)+1 ;
	
?>
<link rel="stylesheet" href="<?php echo PLX_PLUGINS."orejime/css/tabs.css" ?>" media="all" />
<p>Embarque le gestionnaire de consentement aux cookies "Orejime" avec une interface de configuration. 
Le plugin Orejime intègre vos script à vos pages sans que vous ayez besoin d'éditer votre thème. Préconfigurer, une aide et un wizard d'aide à la configuration est disponible. Ce plugin est multilingue et compatible avec le plugin "plxMyMultiLingue".</p>	
<h2><?php $plxPlugin->lang("L_ADMIN") ?></h2>
<a href="parametres_plugin.php?p=<?= basename(__DIR__) ?>"> Config </a>  <a href="parametres_plugin.php?p=<?= basename(__DIR__) ?>&wizard" class="aWizard"><img src="<?= PLX_PLUGINS.basename(__DIR__)?>/img/wizard.png" style="height:2em;vertical-align:middle" alt="Wizard"> Wizard</a>
<div id="tabContainer">
	<form action="plugin.php?p=<?= basename(__DIR__) ?>" method="post" >
		<h3><?php $plxPlugin->lang('L_ADD_DIET') ?></h3>
		<div class="tabs">
			<ul>
				<li id="tabHeader_newParam"><?php $plxPlugin->lang('L_ADD_NEW') ?></li>
				<li id="tabHeader_records"><?php $plxPlugin->lang('L_DIETS') ?></li>
				<li id="tabHeader_button"><?= L_CONFIG_VIEW_FIELD ?></li>
			</div>
			<div class="tabscontent">
				<div class="tabpage" id="tabpage_newParam">
					<fieldset class="auto-col-2"><legend><?= $plxPlugin->getLang('L_ADD_DIET')?></legend>
						<div>
							<input type="hidden" name="newId" value="<?= $nextTplId ?>">
							<label for="id_newItem"><?= $plxPlugin->getLang('L_ADD_NEW')?></label>
							<?php plxUtils::printArea('newItem','','',6) ?>
						</div>
						<div>
							<span><label for="id_newItem"><?= $plxPlugin->getLang('L_ADD_MARKUP')?></label><b id="copy" title="copy HTML">&squ;</b></span>
							<?php plxUtils::printArea('markup','','',6) ?>
						</div>
						<p>
							<label for="id_purpose"><?= $plxPlugin->getLang('L_SCRIPT')?></label>
							<?php plxUtils::printSelect('purpose',$plxPlugin->purposes, '');?>
						</p>
						<p>
							<label for="id_diet_type"><?= $plxPlugin->getLang('L_ADD_DIET_TYPE')?></label>
							<?php plxUtils::printSelect('diet_type',array("X"=>L_FOR_SELECTION, "1"=> $plxPlugin->getLang('L_ADD_COOKIE_SCRIPT'),"2"=> $plxPlugin->getLang('L_ADD_FRONT_HTML')), '');?>
						</p>
					</fieldset>
					
					<fieldset>
						<p class="in-action-bar">
							<?php echo plxToken::getTokenPostMethod() ?><br>
							<input type="submit" class="button blue" name="submitTPL" value="<?= $plxPlugin->getLang('L_SAVE_TPL') ?>"/>
						</p>
					</fieldset>
				</div>
				
				<div class="tabpage" id="tabpage_records">
				
				<?php
				foreach($plxPlugin->cookieTPL as $tpl =>$val ) {
					echo'
					<textarea readonly rows="6" style="width:100%">'.$val.'</textarea>';
				
				}
				
				?>
					
				</div>

				<div class="tabpage" id="tabpage_button">
				<fieldset>
					<p>
						<label for="id_buttons"><?php $plxPlugin->lang('L_ADD_BUTTONS') ?></label>
						<?php plxUtils::printSelect('buttons',array(
						"0" => L_CONFIG_VIEW_FIELD ,
						"1"=> $plxPlugin->getLang('L_RESET'),
						"2" => $plxPlugin->getLang('L_CONF'),
						"3" =>  $plxPlugin->getLang('L_CONF_RESET')),
						$var['buttons']); ?>
					</p>
					</fieldset>				
					<fieldset>
						<p class="in-action-bar">
							<?php echo plxToken::getTokenPostMethod() ?><br>
							<input type="submit" name="submit" value="<?= $plxPlugin->getLang('L_SAVE') ?>"/>
						</p>
					</fieldset>
				</div>
			</div>
		</form>
	</div>
	<script type="text/javascript" src="<?php echo PLX_PLUGINS."orejime/js/tabs.js" ?>"></script>
	<style>
		fieldset label {
		display:inline;
		line-height:4;
		margin-bottom:auto;
		}
		fieldset label::after {
		content:':'
		}
		.spanAllColl {
		grid-column:1/-1;
		}
		.auto-col-2 {
		display:grid;
		grid-template-columns:repeat(4,1fr);
		gap:0.5em;
		}
		.auto-col-2 div {
		grid-column: span 2;
		}
		#copy {
		cursor:pointer;
		font-size:2em;
		padding:.25em;
		text-shadow:.1em .1em gray
		}
		.button.blue {
		padding-block:0;
		}
	</style>
	<script>
		const purpose = document.querySelector("#id_purpose");
		const diet_type = document.querySelector("#id_diet_type");
		const htmlArea = document.querySelector("#id_newItem");
		const markupArea = document.querySelector("#id_markup");
		const close = "</template>";
		purpose.addEventListener("change", function () {
			let htmlCode = htmlArea.value;
			console.log(htmlCode)
			let purposeId = this.value;
			let dataContexte = "";
			let open = `<template data-purpose="${purposeId}" ${dataContexte}>`;
			let tpl = `${open}\n${htmlCode}\n${close}`;
			if(purposeId==0) {
				markupArea.value=' selectionnez d\'abord un groupe !';
				diet_type.value="X";
			}
			else  {
				markupArea.value = tpl;
				markupArea.focus();
			}
		});
		diet_type.addEventListener("change", function () {
			let htmlCode = htmlArea.value;
			let purposeId = purpose.value;
			let dataContexte = "";
			if(this.value==2) dataContexte = "data-contextual";
			let open = `<template data-purpose="${purposeId}" ${dataContexte}>`;
			let tpl = `${open}\n${htmlCode}\n${close}`;
			if(purposeId==0){
				markupArea.value=' selectionnez d\'abord un groupe !';
				this.value="X"
			} 
			else  {
				markupArea.value = tpl;
				markupArea.focus();
			}
		});
		
		// gestion de la copie 
		let copybtn = document.querySelector("#copy");
		copybtn.style.display = "none";
		copybtn.addEventListener("click", function () {
			markupArea.select();
			if (
			document.queryCommandSupported &&
			document.queryCommandSupported("copy")
			) {
				let copie = document.execCommand("copy");
				alert("Copier");
			}
		});
		markupArea.addEventListener("focus", function () {
			if (this.value != "") copybtn.style.display = "inline";
			else copybtn.style.display = "none";
		});
	</script>		