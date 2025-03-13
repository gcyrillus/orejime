<?php if(!defined('PLX_ROOT')) exit;
	/**
		* Plugin 			orejime
		*
		* @CMS required			PluXml 
		*
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
	class orejime extends plxPlugin {
		
		
		
		const BEGIN_CODE = '<?php' . PHP_EOL;
		const END_CODE = PHP_EOL . '?>';
		public $lang = ''; 
		public $cookieTypes =array();
		public $cookieGroup=array();
		public $cookieTPL=array();
		public $defaultTranslationPath;
		public $updateTranslationPath;
		public $orejimeInitPath;
		public $translations = array();
		public $orejimeStyle ='';
		public $purposes=array();
		
		private $url = ''; # parametre de l'url pour accèder à la page static		
		
		
		public function __construct($default_lang) {
			# appel du constructeur de la classe plxPlugin (obligatoire)
			parent::__construct($default_lang);
			
			
			# droits pour accèder à la page admin.php du plugin
			$this->setAdminProfil(PROFIL_ADMIN, PROFIL_MANAGER);
			
			# droits pour accèder à la page config.php du plugin
			$this->setConfigProfil(PROFIL_ADMIN, PROFIL_MANAGER);		
			// url Page static
			$this->url = $this->getParam('url')=='' ? strtolower(basename(__DIR__)) : $this->getParam('url');	
			
			$string101 = $this->getParam('string101') ==''   ?   'string' : $this->getParam('string101') ;
			$CDATA101 = $this->getParam('CDATA101') ==''   ?   '<p>cdata</p>' : $this->getParam('CDATA101') ;
			$numeric101 = $this->getParam('numeric101') ==''   ?   '0' : $this->getParam('numeric101') ;
			
			
			# Declaration des hooks		
			$this->addHook('AdminTopBottom', 'AdminTopBottom');
			$this->addHook('ThemeEndHead', 'ThemeEndHead');
			$this->addHook('plxShowConstruct', 'plxShowConstruct');
			$this->addHook('plxMotorPreChauffageBegin', 'plxMotorPreChauffageBegin');
			$this->addHook('plxShowStaticListEnd', 'plxShowStaticListEnd');
			$this->addHook('plxShowPageTitle', 'plxShowPageTitle');
			$this->addHook('SitemapStatics', 'SitemapStatics');
			$this->addHook('wizard', 'wizard');
			$this->addHook('ThemeEndBody', 'ThemeEndBody');
			

			$this->defaultTranslationPath = PLX_PLUGINS.basename(__DIR__).'/assets/defaultTranslations/';
			$this->updateTranslationPath  = PLX_PLUGINS.basename(__DIR__).'/assets/updateTranslations/';
			$this->orejimeInitPath  = PLX_PLUGINS.basename(__DIR__).'/assets/orejimeInit/';
			
			$file=$default_lang.'.json';
			if(file_exists($this->updateTranslationPath.$file)) {
				$this->translations[$default_lang]= $this->getFileDatas($this->updateTranslationPath.$file);
			}
			elseif(file_exists($this->defaultTranslationPath.$file)){
				$this->translations[$default_lang]= $this->getFileDatas($this->defaultTranslationPath.$file);
			}
			else{ # english if none matches
				loadLang(PLX_CORE.'lang/'.$default_lang.'/core.php');
				loadLang(PLX_CORE.'lang/'.$default_lang.'/admin.php');
				$this->translations[$default_lang]= $this->getFileDatas($this->defaultTranslationPath.'en.json');
				$warning= L_MEDIAS_NO_FILE.' '.sprintf(L_WRITE_NOT_ACCESS , '<b>'.$default_lang.'.json</b>' ).' - '. L_MEDIAS_MODIFY .' & '.L_SAVE_FILE;
				array_unshift( $this->translations[$default_lang],array('warning'=>$warning));
			}
			
			# alimentation des tableaux cookieTypes et cookieGroup et cookieTPL
			$this->cookieGroup['0']= $this->getLang('L_MAIN_GROUP');
			if($this->getParams())
			foreach  ($this->getParams() as $k => $v) {
				if (strpos($k, "id-")===0) {
					$k=substr($k, 3, 3);
					$this->cookieTypes[$k]['id'] = $this->getParam('id-'.$k);
					$this->cookieTypes[$k]['purposes'] = $this->getParam('purposes-'.$k);
					if($this->getParam('purposes-'.$k) !='0') $this->cookieTypes[$this->getParam('purposes-'.$k)]['subGroup'][]= $k;
					$this->cookieTypes[$k]['title'] = $this->getParam('title-'.$k);
					$this->cookieTypes[$k]['isMandatory'] = $this->getParam('isMandatory-'.$k);
					$this->cookieTypes[$k]['description'] = $this->getParam('description-'.$k);
					if($this->getParam('purposes-'.$k) =='0')$this->cookieGroup[$k]=$this->getParam('id-'.$k);	
					$this->purposes[$this->getParam('id-'.$k)]=$this->getParam('id-'.$k);
					
				}
				if (strpos($k, "cookieTPL-")===0) {
					$this->cookieTPL[]=$this->getParam($k);
				}
			}

			
			
			
			
			# des styles custom ??
			$this->orejimeStyle= $this->getParam('orejimeStyle') == ''?'' : $this->getParam('orejimeStyle');
			
		}
		
		# Activation / desactivation
		
		public function OnActivate() {
			# code à executer à l’activation du plugin
			# activation du wizard
			if(!file_exists(PLX_ROOT.PLX_CONFIG_PATH.'plugins/'.basename(__DIR__).'.xml'))
			$_SESSION['justactivated'.basename(__DIR__)] = true;
		}
		
		public function OnDeactivate() {
			# code à executer à la désactivation du plugin
		}	
		
		
		public function ThemeEndHead() {
			#gestion multilingue
			if(defined('PLX_MYMULTILINGUE')) {		
				$plxMML = is_array(PLX_MYMULTILINGUE) ? PLX_MYMULTILINGUE : unserialize(PLX_MYMULTILINGUE);
				$langues = empty($plxMML['langs']) ? array() : explode(',', $plxMML['langs']);
				$string = '';
				foreach($langues as $k=>$v)	{
					$url_lang="";
					if($_SESSION['default_lang'] != $v) $url_lang = $v.'/';
					$string .= 'echo "\\t<link rel=\\"alternate\\" hreflang=\\"'.$v.'\\" href=\\"".$plxMotor->urlRewrite("?'.$url_lang.$this->getParam('url').'")."\" />\\n";';
				}
				echo '<?php if($plxMotor->mode=="'.$this->getParam('url').'") { '.$string.'} ?>';
			}
			
			echo ' 		<link href="'.PLX_PLUGINS.basename(__DIR__).'/css/static.css" rel="stylesheet" type="text/css" />'."\n";
			// ajouter ici vos propre codes (insertion balises link, script , ou autre)
			
			# initialisation de orejime si il y une configuration de groupes
			if(file_exists($this->orejimeInitPath.'purpose.json'))
			echo'
			<script src="https://cdn.jsdelivr.net/npm/orejime@3.0.0/dist/orejime-standard-'.$this->default_lang.'.js"></script>
			<!-- or english if language not avalaible / use the configuration to make a new translation
			<script src="https://cdn.jsdelivr.net/npm/orejime@3.0.0/dist/orejime-standard-en.js"></script> -->
			<link href="https://cdn.jsdelivr.net/npm/orejime@3.0.0/dist/orejime-standard.css" rel="stylesheet" />
';
			if($this->getParams())
			foreach($this->getParams() as $param => $val) {
				if (strpos($param, "group-") === 0 && strpos($param, "group-context") !==0 ) {
					$searchTPL = explode(',',$this->getParam($param));
					foreach($searchTPL as $tpl) {
						echo $this->getParam($tpl).PHP_EOL;
					}
				}
			}
			
		}
		
		/**
			* Méthode qui affiche un message si le plugin n'a pas la langue du site dans sa traduction
			* Ajout gestion du wizard si inclus au plugin
			* @return	stdio
			* @author	Stephane F
		**/
		public function AdminTopBottom() {
			
			echo '<?php
			$file = PLX_PLUGINS."'.$this->plug['name'].'/lang/".$plxAdmin->aConf["default_lang"].".php";
			if(!file_exists($file)) {
			echo "<p class=\\"warning\\">'.basename(__DIR__).'<br />".sprintf("'.$this->getLang('L_LANG_UNAVAILABLE').'", $file)."</p>";
			plxMsg::Display();
			}
			?>';
			
			# affichage du wizard à la demande
			if(isset($_GET['wizard'])) {$_SESSION['justactivated'.basename(__DIR__)] = true;}
			# fermeture session wizard
			if (isset($_SESSION['justactivated'.basename(__DIR__)])) {
				unset($_SESSION['justactivated'.basename(__DIR__)]);
				//$this->wizard();
			}
			
		}
		
		/** 
			* Méthode wizard
			* 
			* Descrition	: Affiche le wizard dans l'administration
			* @author		: G.Cyrille
			* 
		**/
		# insertion du wizard
		public function wizard() {
			# uniquement dans les page d'administration du plugin.
			if(basename(
			$_SERVER['SCRIPT_FILENAME']) 			=='parametres_plugins.php' || 
			basename($_SERVER['SCRIPT_FILENAME']) 	=='parametres_plugin.php' || 
			basename($_SERVER['SCRIPT_FILENAME']) 	=='plugin.php'
			) 	{	
				include(PLX_PLUGINS.__CLASS__.'/lang/'.$this->default_lang.'-wizard.php');
			}
		}
		
		/**
			* Méthode de traitement du hook plxShowConstruct
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowConstruct() {
			
			# infos sur la page statique
			$string  = "if(\$this->plxMotor->mode=='".$this->url."') {";
			$string .= "	\$array = array();";
			$string .= "	\$array[\$this->plxMotor->cible] = array(
			'name'		=> '".$this->getParam('mnuName_'.$this->default_lang)."',
			'menu'		=> '',
			'url'		=>  '".basename(__DIR__)."',
			'readable'	=> 1,
			'active'	=> 1,
			'group'		=> ''
			);";
			$string .= "	\$this->plxMotor->aStats = array_merge(\$this->plxMotor->aStats, \$array);";
			$string .= "}";
			echo "<?php ".$string." ?>";
		}
		
		/**
			* Méthode de traitement du hook plxMotorPreChauffageBegin
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxMotorPreChauffageBegin() {				
			$template = $this->getParam('template')==''?'static.php':$this->getParam('template');
			
			$string = "
			if(\$this->get && preg_match('/^".$this->url."\/?/',\$this->get)) {
			\$this->mode = '".$this->url."';
			\$prefix = str_repeat('../', substr_count(trim(PLX_ROOT.\$this->aConf['racine_statiques'], '/'), '/'));
			\$this->cible = \$prefix.\$this->aConf['racine_plugins'].'".basename(__DIR__)."/static';
			\$this->template = '".$template."';
			return true;
			}
			";
			
			echo "<?php ".$string." ?>";
		}
		
		
		/**
			* Méthode de traitement du hook plxShowStaticListEnd
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowStaticListEnd() {
			
			# ajout du menu pour accèder à la page statique
			if($this->getParam('mnuDisplay')) {
				echo "<?php \$status = \$this->plxMotor->mode=='".$this->url."'?'active':'noactive'; ?>";
				echo "<?php array_splice(\$menus, ".($this->getParam('mnuPos')-1).", 0, '<li class=\"static menu '.\$status.'\" id=\"static-".basename(__DIR__)."\"><a href=\"'.\$this->plxMotor->urlRewrite('?".$this->lang.$this->url."').'\" title=\"".$this->getParam('mnuName_'.$this->default_lang)."\">".$this->getParam('mnuName_'.$this->default_lang)."</a></li>'); ?>";
			}
		}
		
		/**
			* Méthode qui renseigne le titre de la page dans la balise html <title>
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowPageTitle() {
			echo '<?php
			if($this->plxMotor->mode == "'.$this->url.'") {
			$this->plxMotor->plxPlugins->aPlugins["'.basename(__DIR__).'"]->lang("L_PAGE_TITLE");
			return true;
			}
			?>';
		}
		
		/**
			* Méthode qui référence la page statique dans le sitemap
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function SitemapStatics() {
			echo '<?php
			echo "\n";
			echo "\t<url>\n";
			echo "\t\t<loc>".$plxMotor->urlRewrite("?'.$this->lang.$this->url.'")."</loc>\n";
			echo "\t\t<changefreq>monthly</changefreq>\n";
			echo "\t\t<priority>0.8</priority>\n";
			echo "\t</url>\n";
			?>';
		}
		
		
		
		/** 
			* Méthode ThemeEndBody
			* 
			* Descrition	:
			* @author		: TheCrok
			* 
		**/
		public function ThemeEndBody() {
			$plxMotor = plxMotor::getInstance();		
			# initialisation de orejime si il y une configuration de groupes
			if(file_exists($this->orejimeInitPath.'purpose.json')){
				loadLang(PLX_CORE.'/lang/'.$this->default_lang.'/admin.php');
				$options=$translationUpdate='';
				# options d'affichage
				if($this->getParam('buttons') >0) {
				$reset='
					let btnResetConsent = document.createElement("button");
					btnResetConsent.textContent = "'.L_CANCEL.'";
					btnResetConsent.setAttribute("id", "orejimeReset");
					btnResetConsent.setAttribute("type", "button");
					btnResetConsent.setAttribute("class", "orejime-Button");
					btnResetConsent.addEventListener("click", function () {
					  window.orejime.manager.clearConsents();
					});			
					reset.appendChild(btnResetConsent);
				';
				$init='
					let btninitConsent = document.createElement("button");
					btninitConsent.textContent = "'.L_PLUGINS_CONFIG.'";
					btninitConsent.setAttribute("id", "orejimeInit");
					btninitConsent.setAttribute("type", "button");
					btninitConsent.setAttribute("class", "orejime-Button");
					btninitConsent.addEventListener("click", function () {
					  window.orejime.prompt();
					});			
					reset.appendChild(btninitConsent);
				';
				if($this->getParam('buttons') <=2  )
					$reset='';
				if($this->getParam('buttons') <2 )
					$init='';
				$options ='
				window.addEventListener("load", function () {
					let reset = document.createElement("div");
					reset.setAttribute("class","orejime-Env");
					reset.classList.add("orejime-Banner");'.$reset.$init.'
					document.body.appendChild(reset);
				});
				';			
				}				
				if(file_exists($this->updateTranslationPath.$plxMotor->aConf['default_lang'].'.json')){
					$translationUpdate = ','.PHP_EOL.'translations : '.file_get_contents($this->updateTranslationPath.$plxMotor->aConf['default_lang'].'.json');
					echo'<!-- fichier lang chargé: '.$this->updateTranslationPath.$plxMotor->aConf['default_lang'].'.json -->';
				}
				echo '
				<script>
				window.orejimeConfig = {
				'.rtrim(ltrim(file_get_contents($this->orejimeInitPath.'purpose.json'), "{"),"}").'
				,
				"privacyPolicyUrl" : "/index.php?'.$this->url.'"'.$translationUpdate.'
				}
				'.$options.'
				</script>
				<style>
				body:has(.orejime-Root [aria-hidden="false"])  .orejime-Env.orejime-Banner {
					display: none;
				}
				div.orejime-Env.orejime-Banner button {
					pointer-events: auto!important;
					background-image:url(plugins/orejime/icon.png);
					background-size:1.6em 2em;
					background-position:0.2em 50%;
					background-repeat:no-repeat;
					padding:  .25em .5em 0.25em 2em;
					height: auto;
					margin:0 0.25em 
				}
				</style>
				'
				;
			}
			
		}
		
		
		####################################
		######## Fonctions internes ########
		####################################
		
		/**
			* Methode getFileDatas
			*
			* renvoi un tableau vide si le fichier si absent
			*
			* charge et decode json
			*
			* @author		: Gcyrillus
			*
		**/
		public function getFileDatas($file) {
			$file = $file;
			if(!file_exists($file) || strlen(trim(file_get_contents($file)))<1)  { 
				return array();
			}
			$result = json_decode(file_get_contents($file), true);			
			return $result;
		}
		
		
		/**
			* Methode SaveJsonDatas
			*
			* Enregistre les données au format json
			*
			* @author		: Gcyrillus
			*
		**/
		public function saveJsonDatas($file, $datas) {
			file_put_contents($file, json_encode($datas,true|JSON_PRETTY_PRINT) );		
		}
	}	