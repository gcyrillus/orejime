# orejime pour PluXml

Plugin embarquant le gestionnaire de coolie "orejime" v3.0.0 
Le gestionnaire de cookie "orejime" est 
Orejime en version 3.0.0 est chargé via jsdlvr
**Le plugin ne modifie pas orejime**, en tant qu'interface, il aide à la mise en oeuvre de cet outil en balisant vos scripts et contenus externes, puis en initialisant orejime coté visiteurs, en fonction de votre configuration.

Le plugin vous permet depuis l'administration de votre site PluXml de configurer:

* Les familles/types de traceurs ou contenus externes par Famille ou rattachés à une famille en rédigeant une description pour informer le visiteur.
* Les options **requis**(obligatoire) et **non requis**(demandant un consentement de l'internaute).
* Votre page de "Politique de confidentialité" (celle ci est genéré par le plugin, il y manquera vos textes.)
* Modifier ou créer les traductions existantes ou manquantes (par défaut la version anglaise devrait s'afficher)
* Un bouton **Reset** aprés que le visiteur a validé ses choix.
* Un bouton **Configurer** aprés que le visiteur a validé ses choix.

 
Vous n'avez pas à toucher aux fichiers du thème, les traceurs sont automatiquement injectés dans la partie `<head>` de votre page.
Vos script ou codes HTML n'ont pas à être modifiés, il suffit de les copier/coller. Ils seront encapsulés dans une balise `<template>` par le plugin du même nom.
Le script d'initialisation est automatiquement injecté en fin de page en fonction de votre configuration si vous avez au moins une famille configurée.
