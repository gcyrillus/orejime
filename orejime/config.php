<?php
	if(!defined('PLX_ROOT')) exit;
	/**
		* Plugin 			orejime
		*
		* @CMS required		PluXml 
		* @page				config.php
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
	$skip=array();
	
	# Chargement du fichier lang actif de orejime
	$translations=$plxPlugin->translations[$plxAdmin->aConf['default_lang']];
	
	if(!empty($_POST) && isset($_POST['submit'])) {		
		#multilingue
		$plxPlugin->setParam('mnuDisplay', $_POST['mnuDisplay'], 'numeric');
		$plxPlugin->setParam('mnuPos', $_POST['mnuPos'], 'numeric');
		$plxPlugin->setParam('template', $_POST['template'], 'string');
		$plxPlugin->setParam('url', plxUtils::title2url($_POST['url']), 'string');
		foreach($aLangs as $lang) {
			$plxPlugin->setParam('mnuName_'.$lang, $_POST['mnuName_'.$lang], 'string');
			$plxPlugin->setParam('privacyName'.$lang, $_POST['privacyName'.$lang], 'cdata');
		}
		
		# maj params
		$purposesSetup= array('purposes'=>array());
		foreach($_POST['idCookie'] as $id) {
			if(!empty($_POST['title-'.$id])){
				
				
				$plxPlugin->setParam('id-'.$id, $_POST['id-'.$id], 'string');
				$plxPlugin->setParam('purposes-'.$id	, $_POST['purposes-'.$id]	, 'numeric');
				$plxPlugin->setParam('title-'.$id		, $_POST['title-'.$id]		, 'string');
				$plxPlugin->setParam('isMandatory-'.$id	, $_POST['isMandatory-'.$id], 'string');
				$plxPlugin->setParam('description-'.$id	, $_POST['description-'.$id], 'string');
				if($plxPlugin->getParam('group-'.plxUtils::urlify($_POST['id-'.$id]))=='')
						$plxPlugin->setParam('group-'.plxUtils::urlify($_POST['id-'.$id]),'','string');
					
					
					
					
			}
		}
		
		$plxPlugin->saveParams();	
		
		
			# mise à jour des tableaux cookieTypes et cookieGroup
			#RAZ 
			$plxPlugin->cookieTypes=array();
			$plxPlugin->cookieGroup = array();
			$plxPlugin->cookieGroup['0']= $plxPlugin->getLang('L_MAIN_GROUP');
			foreach  ($plxPlugin->getParams() as $k => $v) {
				if (strpos($k, "id-")===0) {
					$k=substr($k, 3, 3);
					$plxPlugin->cookieTypes[$k]['id'] = $plxPlugin->getParam('id-'.$k);
					$plxPlugin->cookieTypes[$k]['purposes'] = $plxPlugin->getParam('purposes-'.$k);
					if($plxPlugin->getParam('purposes-'.$k) !='0') $plxPlugin->cookieTypes[$plxPlugin->getParam('purposes-'.$k)]['subGroup'][]= $k;
					$plxPlugin->cookieTypes[$k]['title'] = $plxPlugin->getParam('title-'.$k);
					if($plxPlugin->getParam('isMandatory-'.$k) !="false")$plxPlugin->cookieTypes[$k]['isMandatory'] = $plxPlugin->getParam('isMandatory-'.$k);
					$plxPlugin->cookieTypes[$k]['description'] = $plxPlugin->getParam('description-'.$k);
					if($plxPlugin->getParam('purposes-'.$k) =='0')$plxPlugin->cookieGroup[$k]=$plxPlugin->getParam('id-'.$k);	
					$plxPlugin->purposes[$plxPlugin->getParam('id-'.$k)]=$plxPlugin->getParam('id-'.$k);
				}
			}
			
			
			# mise à jour configuration cookies group orejime
			$skip=array();
			$i=0;
			foreach($plxPlugin->cookieTypes as $id => $values) {
				if(in_array($id,$skip)) continue;			
				
				$purposesSetup['purposes'][$i]=array(
					'id'			=>	$values['id'],
					'title'			=>	$values['title'],
					'description'	=>	$values['description']);
					
				if(isset($values['isMandatory']) && $values['isMandatory'] == 'true') {
					$purposesSetup['purposes'][$i]['isMandatory']=$values['isMandatory'];
				}
				
				if(isset($values['subGroup'])){
					$subGroupType=array();
					$subi=0;
					foreach($values['subGroup'] as $subId ) {
						$subGroupType[$subi]= array(
							'id'			=>$plxPlugin->cookieTypes[$subId]['id'],
							'title'			=>$plxPlugin->cookieTypes[$subId]['title'],
							'description'	=>$plxPlugin->cookieTypes[$subId]['description']);
						if(isset($plxPlugin->cookieTypes[$subId]['isMandatory']) && $plxPlugin->cookieTypes[$subId]['isMandatory'] == 'true') {
							$subGroupType[$subi]['isMandatory']= $plxPlugin->cookieTypes[$subId]['isMandatory'];
						}
						$skip[] = $subId;
						$subi++;
					}
					$purposesSetup['purposes'][$i]['purposes']= $subGroupType;
				}
				
				$i++;
				
			}
					

		$plxPlugin->saveJsonDatas($plxPlugin->orejimeInitPath.'purpose.json', $purposesSetup);
		

		header("Location: parametres_plugin.php?p=".basename(__DIR__));
		exit;
	}
	
	if(!empty($_POST) && isset($_POST['submitLang'])) {
		echo 'Langues';
		foreach($_POST['banner'] as $key => $value){
			$translations['banner'][$key]=$value;
		}
		foreach($_POST['modal'] as $key => $value){
			$translations['modal'][$key]=$value;
		}
		foreach($_POST['contextual'] as $key => $value){
			$translations['contextual'][$key]=$value;
		}
		foreach($_POST['purpose'] as $key => $value){
			$translations['purpose'][$key]=$value;
		}
		foreach($_POST['misc'] as $key => $value){
			$translations['misc'][$key]=$value;
		}
        $plxPlugin->saveJsonDatas($plxPlugin->updateTranslationPath.$plxAdmin->aConf['default_lang'].'.json' , $translations);
		header("Location: parametres_plugin.php?p=".basename(__DIR__));
		exit;
	}	
	$nextId= count($plxPlugin->cookieTypes)+1 ;
	# initialisation des variables propres à chaque lanque
	$langs = array();
	foreach($aLangs as $lang) {
		# chargement de chaque fichier de langue
		$langs[$lang] = $plxPlugin->loadLang(PLX_PLUGINS.'orejime/lang/'.$lang.'.php');
		$var[$lang]['mnuName'] =  $plxPlugin->getParam('mnuName_'.$lang)=='' ? $plxPlugin->getLang('L_DEFAULT_MENU_NAME') : $plxPlugin->getParam('mnuName_'.$lang);
		$var[$lang]['privacyName'] =  $plxPlugin->getParam('privacyName'.$lang)=='' ? $plxPlugin->getLang('L_DEFAULT_PRIVACY_TEXTS') : $plxPlugin->getParam('privacyName'.$lang);
	}
	# initialisation des variables page statique
	$var['mnuDisplay'] =  $plxPlugin->getParam('mnuDisplay')=='' ? 1 : $plxPlugin->getParam('mnuDisplay');
	$var['mnuPos'] =  $plxPlugin->getParam('mnuPos')=='' ? 2 : $plxPlugin->getParam('mnuPos');
	$var['template'] = $plxPlugin->getParam('template')=='' ? 'static.php' : $plxPlugin->getParam('template');
	$var['url'] = $plxPlugin->getParam('url')=='' ? strtolower(basename(__DIR__)) : $plxPlugin->getParam('url');
	
	//$var['purposes'] = $plxPlugin->getParam('purposes')=='' ? '' : $plxPlugin->getParam('purposes');
	
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
	
	
	
	# affichage du wizard à la demande
	if(isset($_GET['wizard'])) {$_SESSION['justactivated'.basename(__DIR__)] = true;}
	# fermeture session wizard
	if (isset($_SESSION['justactivated'.basename(__DIR__)])) {
		unset($_SESSION['justactivated'.basename(__DIR__)]);
		$plxPlugin->wizard();
	}
	
?>
<link rel="stylesheet" href="<?php echo PLX_PLUGINS."orejime/css/tabs.css" ?>" media="all" />
<p>Embarque le gestionnaire de consentement aux cookies "Orejime" avec une interface de configuration. 
Le plugin Orejime intègre vos script à vos pages sans que vous ayez besoin d'éditer votre thème. Préconfigurer, une aide et un wizard d'aide à la configuration est disponible. Ce plugin est multilingue et compatible avec le plugin "plxMyMultiLingue".</p>	
<h2><?php $plxPlugin->lang("L_CONFIG") ?></h2>
<a href="plugin.php?p=<?= basename(__DIR__) ?>"> Admin </a>   <a href="parametres_plugin.php?p=<?= basename(__DIR__) ?>&wizard" class="aWizard"><img src="<?= PLX_PLUGINS.basename(__DIR__)?>/img/wizard.png" style="height:2em;vertical-align:middle" alt="Wizard"> Wizard</a>
<div id="tabContainer">
	<form action="parametres_plugin.php?p=<?= basename(__DIR__) ?>" method="post" >
		<div class="tabs">
			<ul>
				<li id="tabHeader_Param"><?php $plxPlugin->lang('L_COOKIES_OBJECT') ?></li>
				<li id="tabHeader_main"><?php $plxPlugin->lang('L_PARAMS_PRIVACY_PAGE') ?></li>
				<?php
					foreach($aLangs as $lang) {
						echo '<li id="tabHeader_'.$lang.'">Orejime '.strtoupper($lang).'</li>';
						echo '<li id="tabHeader_privacy'.$lang.'">'. $plxPlugin->getLang('L_PRIVACY_POLICY') .' '.strtoupper($lang).'</li>';
					} ?>
			</ul>
		</div>
		<div class="tabscontent">
			<div class="tabpage" id="tabpage_Param">	
				<fieldset><legend><?= $plxPlugin->getLang('L_PARAMS_COOKIES_OBJECTS') ?></legend>
					<table id="orejime_table">
						<thead>
							<tr>
								<td colspan="2"></td>
								<th><?= $plxPlugin->getLang('L_TYPE_COOKIE') ?></th>
								<th><?= $plxPlugin->getLang('L_GROUP_COOKIE') ?></th>
								<th><?= $plxPlugin->getLang('L_TITLE') ?></th>
								<th><?= $plxPlugin->getLang('L_IS_MANDATORY') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($plxPlugin->cookieTypes as $id => $val ) : 
								# on ne traite pas les sous groupes ici.
								if(in_array($id,$skip)) continue;
							?>
							<tr>
								<td colspan="2">N° <?= $id ?><input type="hidden" name="idCookie[]" value="<?= $id ?>"> </td>
								<td><input type="text" name="id-<?= $id ?>" value="<?= $val['id'] ?>" placeholder="nom de groupe, objectif"></td>
								<td>
									<?php plxUtils::printSelect('purposes-'.$id,$plxPlugin->cookieGroup, $val['purposes']);?>	
									
								</td>
								<td><input type="text" name="title-<?= $id ?>" value="<?= $val['title'] ?>" placeholder"titre"></td>
								<td>
									
									<?php plxUtils::printSelect('isMandatory-'.$id,array("true"=>$plxPlugin->getLang('L_NOT_MANDATORY'),"false"=>$plxPlugin->getLang('L_MANDATORY')), $val['isMandatory']);?>	
									
								</td>
							</tr>
							<tr>
								<th>Description</th>
								<td colspan="5"><textarea name="description-<?= $id ?>" rows="1" style="width:100%" placeholder="Description texte courte"><?= $val['description'] ?></textarea></td>
							</tr>
						</tr>
						<?php 
							# traitement des sous groupes
						if(isset($val['subGroup'])): ?>
						<?php foreach($val['subGroup'] as $subId => $subVal ) : ?>
						<tr class="subGroup">
							<td colspan="2">N° <?= $subVal ?><input type="hidden" name="idCookie[]" value="<?= $subVal ?>"> </td>
							<td><input type="text" name="id-<?= $subVal ?>" value="<?= $plxPlugin->cookieTypes[$subVal]['id'] ?>" placeholder="nom de groupe, objectif"></td>
							<td>
								<?php plxUtils::printSelect('purposes-'.$subVal,$plxPlugin->cookieGroup, $plxPlugin->cookieTypes[$subVal]['purposes']);?>	
								
							</td>
							<td><input type="text" name="title-<?= $subVal?>" value="<?= $plxPlugin->cookieTypes[$subVal]['title'] ?>" placeholder"titre"></td>
							<td>
								
								<?php plxUtils::printSelect('isMandatory-'.$subVal,array("true"=>$plxPlugin->getLang('L_NOT_MANDATORY'),"false"=>$plxPlugin->getLang('L_MANDATORY')), $plxPlugin->cookieTypes[$subVal]['isMandatory']);?>	
								
							</td>
						</tr>
						<tr class="subGroup">
							<th>Description</th>
							<td colspan="5"><textarea name="description-<?= $subVal ?>" rows="1" style="width:100%" placeholder="Description texte courte"><?= $plxPlugin->cookieTypes[$subVal]['description'] ?></textarea></td>
						</tr>
					</tr>
					<?php $skip[] = $subVal;?>
					<?php endforeach; ?>
					
					<?php endif; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2">N° <?= $nextId ?><input type="hidden" name="idCookie[]" value="<?= $nextId ?>"></td>
						<td><input type="text" name="id-<?= $nextId ?>" value="" placeholder="nom de groupe, objectif"></td>
						<td>
							<?php 
							plxUtils::printSelect('purposes-'.$nextId,$plxPlugin->cookieGroup, '0');
							
							?>
						</td>
						<td><input type="text" name="title-<?= $nextId ?>" value="" placeholder"titre"></td>
						<td>
							<select name="isMandatory-<?= $nextId ?>">
								<option value="true">Non Requis</option>
								<option value="false">Requis</option>
								<select>
								</td>
							</tr>
							<tr>
								<th>Description</th>
								<td colspan="5"><textarea name="description-<?= $nextId ?>" rows="3" style="width:100%" placeholder="Description texte courte"></textarea></td>
							</tr>
						</tfoot>
					</table>
				</fieldset>
			</div>
			
			<div class="tabpage" id="tabpage_main">
				<fieldset class="auto-col-230">
					<p>
						<label for="id_url"><?php $plxPlugin->lang('L_PARAM_URL') ?>&nbsp;:</label>
						<?php plxUtils::printInput('url',$var['url'],'text','20-20') ?>
					</p>
					<p>
						<label for="id_mnuDisplay"><?php echo $plxPlugin->lang('L_MENU_DISPLAY') ?>&nbsp;:</label>
						<?php plxUtils::printSelect('mnuDisplay',array('1'=>L_YES,'0'=>L_NO),$var['mnuDisplay']); ?>
					</p>
					<p>
						<label for="id_mnuPos"><?php $plxPlugin->lang('L_MENU_POS') ?>&nbsp;:</label>
						<?php plxUtils::printInput('mnuPos',$var['mnuPos'],'text','2-5') ?>
					</p>
					<p>
						<label for="id_template"><?php $plxPlugin->lang('L_TEMPLATE') ?>&nbsp;:</label>
						<?php plxUtils::printSelect('template', $aTemplates, $var['template']) ?>
					</p>	
				</fieldset>
			</div>
			<?php foreach($aLangs as $lang) : ?>
			<div class="tabpage" id="tabpage_<?php echo $lang ?>">
				<fieldset style="display:contents">
					<p class="in-action-bar" style="z-index:1000000">
						<?php echo plxToken::getTokenPostMethod() ?><br>
						<input type="submit" name="submitLang" value="<?= $plxPlugin->getLang('L_UPDATE_TRANSLATION') ?>" class="button blue"/>
					</p>
				</fieldset>
				<fieldset class="langs">
					<h3><?= $plxPlugin->getLang('L_TRANSLATION').' ('.$lang.')'; ?></h3>
					
					
					
					<?php 
						
						foreach($translations as $box => $values) {
							$output = '<fieldset><legend>';
							switch($box) {
								case 'warning':
								$legendText = null;
								break;
								case 'banner':
								$legendText= 'L_BANNER';
								$iptName= 'banner';
								break;
								case 'modal':
								$legendText= 'L_MODAL';
								$iptName= 'modal';
								break;
								case 'contextual':
								$legendText= 'L_CONTEXTUAL';
								$iptName= 'contextual';
								break;
								case 'purpose':
								$legendText= 'L_PURPOSE';
								$iptName= 'purpose';
								break;
								
								case 'misc':
								$legendText= 'L_MISC';
								$iptName= 'misc';
								break;
							}
							if(!isset($legendText)){
								$output .= '⚠️⚠️⚠️⚠️⚠️⚠️</legend>';
								$output .='<p class="alert orange">'.$values['warning'].'</p>';
							}
							else {
								$output .= $plxPlugin->getLang($legendText).'</legend>'.PHP_EOL;
								foreach($values as $label => $texts) {
									$output .= '
									<p>
									<label for="'.$box.'-'.$label.'">'.$label.'</label>
									<textarea id="'.$box.'-'.$label.'" name="'.$iptName.'['.$label.']">'.$texts.'</textarea>
									</p>	
									';
								}
							}
							$output .= '</fieldset>';
							echo $output;
						}
						
					?>
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
				</fieldset>
				
			</div>
			<div class="tabpage" id="tabpage_privacy<?=$lang?>">
				
				<fieldset>
					
					<p>
						<label for="id_mnuName_<?php echo $lang ?>"><?php $plxPlugin->lang('L_MENU_TITLE') ?>&nbsp;:</label>
						<?php plxUtils::printInput('mnuName_'.$lang,$var[$lang]['mnuName'],'text','20-30') ?>
					</p>					
					<p>
						<label for="id_privacyName<?php echo $lang ?>"><?php $plxPlugin->lang('L_PRIVACY_POLICY_TEXT') ?> (<?=$lang?>)&nbsp;:</label>
						<?php plxUtils::printArea('privacyName'.$lang, $var[$lang]['privacyName'],'',20) ?>
					</p>
				</fieldset>
				
			</div>
			<?php endforeach; ?>
		</div>
		<fieldset>
			<p class="in-action-bar">
				<?php echo plxToken::getTokenPostMethod() ?><br>
				<input type="submit" name="submit" value="<?= $plxPlugin->getLang('L_SAVE') ?>"/>
			</p>
		</fieldset>
	</form>
</div>
<script type="text/javascript" src="<?php echo PLX_PLUGINS."orejime/js/tabs.js" ?>"></script>



<style>
	#orejime_table  {
	margin:1em 5em;		
	border-collapse:separate;
	border-spacing:0;	
	}
	#orejime_table input,
	#orejime_table select {
	width: 100%;
	}
	#orejime_table th,#orejime_table td{
	padding:0.25em;
	}
	#orejime_table thead th {
	
	}
	#orejime_table tbody  tr>* {
	border:solid 1px;
	}
	#orejime_table tbody tr:nth-child(2n)>*  {
	border-bottom:solid;
	}
	#orejime_table tbody tr:not(.subGroup):nth-child(2n - 1)>*  {
	padding-top:1.5em;
	background:linear-gradient(to bottom, white .9em, black .9em, transparent 1.05em);
	clip-path: polygon(0 1em, 100% 1em, 100% 100%, 0 100%);
	}
	#orejime_table td:first-child{
	text-align:center;
	}
	#orejime_table select[name^="isMandatory"] {
	color:white;
	background:tomato;
	border-color:gray
	}
	#orejime_table select[name^="isMandatory"]:has([value="true"][selected]),
	#orejime_table select[name^="isMandatory"] option[value="true"]{
	color:darkgreen;
	border-color:currentColor;
	background:lightgreen;
	}
	#orejime_table select, input,textarea{
	border:solid 1px;
	border-radius:5px;
	}
	#orejime_table tfoot tr:first-child>*{
	font-weight:bolder;
	padding-top:2em;
	}
	
	#orejime_table .subGroup {
	border:none;
	clip-path: polygon(1em 0, 110% 0, 110% 110%, 1em 110%);
	}
	#orejime_table .subGroup >*:first-child{
	clip-path: polygon(1em 0, 100% 0, 100% 100%, 1em 100%);
	padding-inline-start: 1.5em;
	border-left:none;
	background:linear-gradient(to right, white 1em, black 1em, transparent 1.1em)
	}
	.langs p {
	display:contents;
	}
	.langs fieldset {
	display:grid;
	grid-template-columns:auto 1fr;
	gap:1em .25em;
	}
	.langs label {
	text-align:end;
	margin-block:auto;
	font-weight:bolder
	}
	.langs label::after{
	content:':';
	}
	.auto-col-230 ,
	.auto-col-230  p{
	display:grid;
	grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
	gap:0.5em;
	}
	.tabpage {
	clear:both;
	}
	.tabs {
	height:auto;
	}
	.tabs ul {
	margin: 0;
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: .35em 0;
	}
	.tabActiveHeader {
	order:1
	}
	fieldset h3 {
	text-align:center;
	margin:0 1em;
	}
	input.button.blue {
	padding-block:0;
	}
</style>
<textarea style="display:none" data-css="orejime-Env">
	
	--orejime-space-m: 1.4em;
	--orejime-shadow: 0.1em 0.2em 0.4em rgba(var(--orejime-color-shadow), 0.25),
    0.2em 0.6em 1.5em rgba(var(--orejime-color-shadow), 0.2);
	--orejime-font-size-small: 0.8rem;
	--orejime-radius: calc(var(--orejime-space-m) / 4);
	--orejime-space-xs: calc(var(--orejime-space-m) / 4);
	--orejime-banner-max-width: 45ch;
	--orejime-space-l: calc(2 * var(--orejime-space-m));
	--orejime-font-family: sans-serif;
	--orejime-modal-max-width: 65ch;
	--orejime-space-s: calc(var(--orejime-space-m) / 2);
	--orejime-color-shadow: 0, 0, 0;
	--orejime-color-subdued: #666;
	--orejime-color-text: #222;
	--orejime-color-background: #fff;
	--orejime-color-backdrop: #00000080;
	--orejime-color-on-interactive: #fff;
	--orejime-color-interactive: royalblue;
	all: unset;
</textarea>