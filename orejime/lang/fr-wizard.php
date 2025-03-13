<?php
	if(!defined('PLX_ROOT')) exit; 
	/**
	* Plugin 			orejime
	*
	* @CMS required		PluXml 
	* @page				-wizard.php
	* @version			1.0
	* @date				2025-03-10
	* @author 			G-Cyrillus
	**/		
	
	# pas d'affichage dans un autre plugin !	
	if(isset($_GET['p'])&& $_GET['p'] !== 'orejime' ) {goto end;}
	
	# on charge la class du plugin pour y accéder
	$plxMotor = plxMotor::getInstance();
	$plxPlugin = $plxMotor->plxPlugins->getInstance( 'orejime'); 
	
	# On vide la valeur de session qui affiche le Wizard maintenant qu'il est visible.
	if (isset($_SESSION['justactivatedorejime'])) {unset($_SESSION['justactivatedorejime']);}
	
	# initialisation des variables propres à chaque lanque 
	$langs = array();
	
	# initialisation des variables communes à chaque langue	
	$var = array();
	

	
	#affichage
	?>
	<link rel="stylesheet" href="<?= PLX_PLUGINS ?>orejime/css/wizard.css" media="all" />
	<input id="closeWizard" type="checkbox">
	<div class="wizard">	
	<div class="container">	
	<div class='title-wizard'>
	<h2><?= $plxPlugin->aInfos['title']?><br><?= $plxPlugin->aInfos['version']?></h2>
	<img src="<?php echo PLX_PLUGINS. 'orejime'?>/icon.png">
	<div><q> Made in <?= $plxPlugin->aInfos['author']?> </q></div>
	</div>
	<p></p>
	
	<div id="tab-status">
	<span class="tab active">1</span>
	</div>		
	<form action="parametres_plugin.php?p=<?php echo 'orejime' ?>"  method="post">
	<div role="tab-list">		
	<div role="tabpanel" id="tab1" class="tabpanel">
	<h2>Bienvenue dans l’extension <b style="font-family:cursive;color:crimson;font-variant:small-caps;font-size:2em;vertical-align:-.5rem;display:inline-block;"><?= $plxPlugin->aInfos['title']?></b></h2>
	<p>Cette extension embarque le gestionnaire de cookies "<a href="https://boscop.fr/gestionnaire-cookies-orejime/" target="_blank">orejime V.3.0.0</a>".</p>
	<p>Facilement configurable, accessible et conforme au RGPD.</p>
	</div>	
	<div role="tabpanel" id="tab2" class="tabpanel hidden title">
	<h2>Configuration</h2>
	<p style="color:orange">Etapes primordiales à réaliser:</p>
	<ul><li>type et groupe de cookie</li><li>Politique de Confidentialité</li><li>traduction ou correction des textes.</li></ul>
	<!-- Ci-dessous , valide le passage à une autre page si d'autre champs required existe dans le formulaire -->
	<!-- <input type="hidden"  class="form-input" value="keepGoing"> -->
	</div>		
	<div role="tabpanel" id="tab3" class="tabpanel hidden">
	<h2>type</h2>
	<p>Créez un type par groupe de cookie en indiquant un nom type, le groupe ou le rattacher, un titre et une courte description</p>
	<h2>"Politique de confidentialité"</h2>
	<p>Editez votre page, son titre, son URL et sa position au menu </p>
	<h2>Langues</h2>
	<p>Modifiez ou créez la traduction dans votre langue.</p>
	<!-- Ci-dessous , valide le passage à une autre page si d'autre champs required existe dans le formulaire -->
	<!-- <input type="hidden"  class="form-input" value="keepGoing"> -->
	</div>		
	<div role="tabpanel" id="tab4" class="tabpanel hidden title">
	<h2>Administration</h2>
	<p style="color:orange">lorsque la configuration est prete:</p>
	<ul><li>integrez vos script facilement</li><li>moderez les contenus externes</li><li>boutons optionnels</li></ul>
	<!-- Ci-dessous , valide le passage à une autre page si d'autre champs required existe dans le formulaire -->
	<!-- <input type="hidden"  class="form-input" value="keepGoing"> -->
	</div>			
	<div role="tabpanel" id="tab5" class="tabpanel hidden">
	<h2>Intégrations</h2>
	<p>copiez/collez votre script ou contenu HTML dans la premiere boite d'édition, selectionné un groupe puis le type de contenu</p>
	<p>Enregistrer les script ou copiez/collez le code generer pour vos conntenus externes</p>
	<h2>enregistrés</h2>
	<p>Visualisez les script déjà enregistré et injectés dans vos pages.</p>
	<h2>Boutons optionnels</h2>
	<p>Proposez au visiteur un bouton, Annuler, configurer aprés configuration</p>
	<!-- Ci-dessous , valide le passage à une autre page si d'autre champs required existe dans le formulaire -->
	<!-- <input type="hidden"  class="form-input" value="keepGoing"> -->
	</div>		
	<div role="tabpanel" id="tabEnd" class="tabpanel hidden title">
	<h2>The End</h2>
	<p>Bonnes Aventures.</p>
	<!-- Ci-dessous , valide le passage à une autre page si d'autre champs required existe dans le formulaire -->
	<!-- <input type="hidden"  class="form-input" value="keepGoing"> -->
	</div>		
	<div class="pagination">
	<a class="btn hidden" id="prev"><?php $plxPlugin->lang('L_PREVIOUS') ?></a>
	<a class="btn" id="next"><?php $plxPlugin->lang('L_NEXT') ?></a>
	<?php echo plxToken::getTokenPostMethod().PHP_EOL ?>
	<button class="btn btn-submit hidden"  id="submit"><?php $plxPlugin->lang('L_CLOSE') ?></button>
	</div>
	</div>		
	</form>			
	<p class="idConfig">
	<?php
	if(file_exists(PLX_PLUGINS. 'orejime/admin.php')) {echo ' 
	<a href="/core/admin/plugin.php?p= orejime">Page d\'administration  orejime </a>';}
	if(file_exists(PLX_PLUGINS. 'orejime/config.php')) {echo '
 	<a href="/core/admin/parametres_plugin.php?p=orejime">Page de configuration  orejime</a>';}
	?>
	<label for="closeWizard"> <?php $plxPlugin->lang('L_CLOSE') ?> </label>
	</p>	
	</div>	
	<script src="<?= PLX_PLUGINS ?>orejime/js/wizard.js"></script>
	</div>
	<?php end: // FIN! ?>				
	