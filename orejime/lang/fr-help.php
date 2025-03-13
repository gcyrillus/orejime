<div>
	<style>
		h1,::marker{;color:deepskyblue;}
		h3, ol> ::marker{font-weight:bold;font-size:1.5em;}
		ol ol ::marker{font-size:initial;color:gray}
		ul li {list-style-type: "\1F44D ";}
		h2,h3{color:hotpink;}
		ul{list-style-type:square}
		table {border-collapse:separate;border-spacing:.25em;} 
		td:not(:first-child) {white-space:revert;border:solid 1px;border-radius:5px;}
	</style>
	<h1>Aide du plugin orejime</h1>
	<h2 id="config">La configuration</h2>
	<ol>
		<li>
			<p>Aller à l'onglet <b>"Paramètrage Orejime"</b> 
				pour parametrer au mieux les familles de Cookies ou données de session utilisées,
			il faut prendre en compte ces informations et conseils suivant:</p>
			<ul>
				<li><p>Il y a 2 options pour le consentement du visiteur.</p></li>
				<li><p>Les cookies et données de session au bon fonctionement 
				du site devraient être indiquer, même si elles sont obligatoires.</p> 
				<h3>Créez une premiere famille ex:</h3>
				<table style="width:80%;table-layout:fixed;white-space:wrap;;"> 
					<tr>
						<td style="width:6em"></td>
						<th>type</th>
						<th>Groupe</th>
						<th>Titre</th>
						<th>Consentement</th>
					</tr>
					<tr>
						<td>N° X</td>
						<td>nécessaire</td>
						<td>Groupe Principale</td>
						<td>Cookies Techniques</td>
						<td style="color:green;">Non Requis</td>
					</tr>
					<tr>
						<td>Description</td>
						<td colspan="4">Ces cookies nécessaires au bon fonctionnement du site 
							ne requiert pas votre consentement. Ils servent à valider les formulaires 
						et à conserver vos identifiants de connexion si vous êtes membre.</td>
					</tr>
				</table>
				</li>
				<li>
					<p>Tous les contenus provenant de sites externes .</p>
					<h3>Créez une seconde famille</h3>
					<p>Ces contenus peuvent être regroupée dans le type "contextuel".</p>
					<p>Ex:</p>
					<table style="width:80%;table-layout:fixed;white-space:wrap;;"> 
						<tr>
							<td style="width:6em"></td>
							<th>type</th>
							<th>Groupe</th>
							<th>Titre</th>
							<th>Consentement</th>
						</tr>
						<tr>
							<td>N° X</td>
							<td><b>Contextuels</b></td>
							<td>Groupe Principale</td>
							<td>Contenus Externes</td>
							<td style="color:red;">Requis</td>
						</tr>
						<tr>
							<td>Description</td>
							<td colspan="4">Contenus contextuels depuis un site tiers. Les sites tiers peuvent suivre votre navigation 
							et déposer leurs cookies. Une autorisation est requise.</td>
						</tr>
					</table>
					<p>des type comme des vidéos youtube, des widget de réseaux sociaux , Google font,  des iframes... Peuvent être 
					classés individuellement et rattachés a un groupe principal</p>
					<p>Ex:</p>
					<table style="padding-left:3em;width:80%;table-layout:fixed;white-space:wrap;;"> 
						<tr>
							<td style="width:6em"></td>
							<th>type</th>
							<th>Groupe</th>
							<th>Titre</th>
							<th>Consentement</th>
						</tr>
						<tr>
							<td>N° X</td>
							<td>Youtube</td>
							<td><b style="color:red;">Contextuels</b></td>
							<td>Vidéos Youtube</td>
							<td style="color:red;">Requis</td>
						</tr>
						<tr>
							<td>Description</td>
							<td colspan="4">Afficher et lire des vidéos en provenance de Youtube.
							Ce site collecte des données. Une autorisation est requise.</td>
						</tr>
					</table>
					
					
				</li>
				<li>
					<h3>Et ainsi de suite</h3> 
					<p>Pour chaque type de collecte de données sur votre site que 
					vous pouvez classé en Groupe principale ou le rattacher à un autre.</p>
				</li>
			</ul>
			
		</li>
		<li>
			<p>L'onglet <b>"Paramètres page statique"</b></p>
			<p>Cette onglet correspond à la page statique du plugin qui vous 
			servira à afficher vore "Politique de confidentialité.</p>
			<p>Les options de configuration sont:</p>
			<ol>
				<li>Nom de la page dans l'URL</li>
				<li>Affichage au menu des pages staiques</li>
				<li>Position dans le menu</li>
				<li>Le template à utiliser pour l'affichage</li>
			</ol>
		</li>
		<li>
			<p>L'onglet <b>"Orejime FR"</b> (ou la langue de votre site</p>
			<p>Cette onglet vous permet de modifier les textes affichés par 
			"Orejime" ou de creer votre traduction si votre langue est manquante.</p>
		</li>
		<li>
			<p>L'onglet <b>"Politique de Confidentialité"</b> </p>
			<p>Cette onglet vous permet de</p>
			<ol>
				<li>donner le titre de la page et son nom dans le menu.</li>
				<li>Editer le contenu de cette page</li>
			</ol>
		</li>
		
	</ol>
	
	<h2 id="admin">L'Administration</h2>
	<ol>
		<li>
		<p>L'onglet <b>"Nouveau"</b></p>
		<p>Cet onglet vous permet d'inserer vos script dans votre site automatiquement</p>
		<p>Inserer dans la premiere zone de texte éditable le script.</p>
		<p>Associer le à un groupe puis selectionner le type de contenu</p>
		<div style="border:solid 1px;border-radius:5px;margin:1em auto;padding:1em;background:ivory;width:400px;text-wrap:balance;"><p><b style="color:hotpink">Pour un contenu externe</b> à afficher dans un article 
		ou une page statique, copier/coller le code à l'endroit de son affichage.</p>
		<p>L'attribut <b style="color:orange">"data-contextual"</b> <b style="color:hotpink">doit-être inscrit dans le code genéré</b>, 
		copier/coller simplement ce code sans l'enregistrer.</p>
		</div>
		</li>
		<li>
		<p>L'onglet <b>"enregistré(s)"</b> affiche les scripts générés et injectés dans vos pages</li>
		</li>
		<li>
		<p>L'onglet <b>"Options d'affichage"</b> permet aux choix d'afficher les boutons, 
		Annuler et Configurer les cookie aprés que le visiteur ai enregistré ses choix.</p>
		</li>
	</ol>
	<p>Hook du widget >aucun</p>
</div>																															