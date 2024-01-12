=== Colissimo Officiel : Méthodes de livraison pour WooCommerce ===
Contributors: iscpcolissimo
Tags: shipping, colissimo, woocommerce
Requires at least: 4.7
Tested up to: 6.0
Stable tag: 1.7.5
Requires PHP: 5.6.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Ce plugin permet d'utiliser les méthodes de livraison Colissimo dans WooCommerce

== Description ==

> #### Requirements
> [WooCommerce (Testé jusqu’à 6.5.1)](https://wordpress.org/plugins/woocommerce/)

Ce plugin permet :
* L’intégration de l’affichage des points retrait sur le site marchand
* La génération et l’impression des étiquettes depuis le B.O. WooCommerce Colissimo
* Le suivi des expéditions aux destinataires

= Caractéristiques : =

Colissimo Officiel regroupe plusieurs fonctionnalités essentielles dans un seul plugin.

Celui-ci permet :
 
* EN FRONT OFFICE :
    - L’affichage en responsive design des points de retrait
    - Le suivi de commande depuis le site marchand
    - La simplification du process retour dont la possibilité d’effectuer le retour en boite aux lettres
* EN BACK OFFICE :
    - L’envoi de colis vers la France, l’Outre Mer et l’international
	- L’édition d’étiquettes depuis le back office marchand
	- La génération d’un bordereau de dépôt
	- Le suivi des commandes 

= Bénéfices pour le e-commerçant : =

Le plugin Colissimo-Officiel est une solution complète & gratuite qui vous permettra de gagner du temps au quotidien dans le traitement de vos commandes et le suivi de vos expéditions. Vous pourrez facilement développer vos ventes à l’export en proposant les services innovants de la gamme Colissimo.
En cas de besoin, vous pourrez vous appuyer sur le support technique Colissimo .

= Bénéfices pour le e-acheteur : =

Colissimo facilite également la vie du destinataire en lui proposant le plus large éventail de solutions de livraison (en France et à l’international).
L’e-acheteur peut suivre sur son espace client le parcours de son colis et effectuer les retours depuis ce même espace s’il le souhaite.

== Screenshots ==
1. Onglet commandes Colissimo
2. Onglet commandes WooCommerce
3. Paramétrage du plugin
4. Point de retrait Colissimo avec widget
5. Prévisualisation des frais de livraison
6. Associer les transporteurs à des zones

== Changelog ==

## 1.7.5

CORRECTIFS

* Le déploiement de la précédente version contenait trop de fichiers, ils ont été retirés


## 1.7.4

FONCTIONNALITÉS

* Vous pouvez maintenant changer le type du colis lors de la génération manuelle d'une étiquette
* Il est maintenant possible d'assurer le retour d'un colis
* Une nouvelle option gratuite d'affichage de la carte des points de retrait est disponible
* Une option a été ajoutée pour ajouter ou non la facture dans les étiquettes téléchargées individuellement

AMÉLIORATIONS

* L'option d'ID de compte parent a été temporairement retirée, le temps que la fonctionnalité soit déployée pour tous les services
* Il n'est plus nécessaire de rentrer des conditions de prix si vous souhaitez uniquement vous baser sur le poids du panier (et inversement)
* Le montant payé pour l'envoi en DDP est à présent enregistré en base de données
* Un indicateur a été ajouté lorsque l'étiquette a déjà été imprimée
* Dans le cas où le client cliquerait sur le lien de suivi dans la première minute où l'étiquette a été générée, une erreur était affichée. Il est maintenant redirigé sur son listing de commandes
* Le nom de la méthode en points de retrait a été uniformisée dans les traductions françaises
* Un message explicatif a été ajouté lors d'une tentative de génération d'étiquette à l'étranger pour une livraison gratuite
* Le design de la carte des points de retrait a été amélioré pour la version mobile et PC

CORRECTIFS

* La génération d'étiquette de retour pour la Réunion a été corrigée
* Correction d'une erreur lors de l'achat pour les produits variants sans classes d'expédition
* Les mots de passe rentrés dans la configuration peuvent à présent contenir un %
* La popup contenant la carte des points de retrait et sa croix s'affichent mieux sur mobile
* Il n'était plus possible de supprimer complètement les valeurs des options de restriction des coupons, c'est corrigé
* La génération d'étiquette n'est plus bloquée lorsque l'envoi est gratuit et qu'il se fait pour une destination ayant besoin d'une déclaration de douanes


## 1.7.3

AMÉLIORATIONS

* Amélioration de certains messages d'erreur pour qu'ils soient plus explicites

CORRECTIFS

* Correction d'un message d'erreur lorsque l'adresse d'origine n'est pas définie


## 1.7.2

FONCTIONNALITÉS

* L'envoi en point de retrait a été activé pour l'Italie et l'Irlande
* Vous pouvez à présent spécifier le nom, le prénom, l'adresse email, le numéro de mobile et de fixe de votre entreprise pour la génération des étiquettes
* Vous pouvez maintenant personnaliser le montant de l'assurance lors de la génération d'une étiquette depuis une commande
* Deux nouvelles options sont disponibles sur la grille tarifaire des méthodes d'expédition pour exporter/importer les prix et conditions
* Une nouvelle option vous permet d'appliquer une réduction sur l'expédition basée sur le nombre de produits dans le panier
* L'envoi en multi-colis est maintenant disponible pour un envoi France => Outre-Mer

AMÉLIORATIONS

* La compatibilité avec la dernière version de PHP a été ajoutée, la compatibilité avec PHP 5.6 a dû être retirée
* Vous pouvez à présent entrer des quantités décimales pour les produits (vente de produit au mètre par exemple)
* Le format de date utilisé sur le listing Colissimo est maintenant le même que sur les listings WooCommerce
* Les pièces jointes sont maintenant ajoutées à l'email de suivi si d'autres extensions en ajoutent
* Des restrictions ont été appliquées pour l'envoi DDP au Royaume-Uni : autorisé uniquement en envoi commercial entre 160€ et 1050€
* La gestion des commandes WooCommerce Subscriptions a été revue
* Le bouton de validation de panier n'est plus désactivé si aucun point de retrait n'est sélectionné. Un message s'affiche à la place
* La colonne de statut de livraison a été fusionnée avec la colonne de gestion des étiquettes, pour que chaque étiquette ait son statut
* Lorsqu'une erreur survient lors de la génération d'une étiquette, elle est sauvegardée et affichée sur le listing Colissimo jusqu'à la prochaine génération d'étiquette
* L'option d'envoi DPD a été séparée pour l'Autriche, l'Italie, l'Allemagne et le Luxembourg

CORRECTIFS

* Les produits gratuits sont maintenant gérés lorsqu'une déclaration de douanes est nécessaire
* Les numéros Belges au format 00324XXX fonctionnent de nouveau lors de la génération d'étiquettes
* La date pour le retour en boite aux lettres était affichée avec un jour de décalage dans le passé, c'est corrigé
* Le lien de suivi du site est maintenant compatible avec toutes les variantes de format permalink de WordPress


## 1.7.1

FONCTIONNALITÉS

* De nouvelles actions sont disponibles sur le listing des commandes pour expédier des commandes existantes avec Colissimo
* Une option a été ajoutée dans les réglages pour activer la sélection automatique du point de retrait le plus proche de l'adresse lorsque cette méthode d'expédition est sélectionnée
* Vous pouvez à présent définir une adresse de retour différente de l'adresse depuis laquelle les colis sont expédiés
* Vous pouvez à présent personnaliser le port et le protocole utilisés pour l'impression thermique des étiquettes
* L'option de gratuité d'une méthode d'expédition selon les classes d'expéditions des produits a désormais deux modes : soit "au moins un produit" soit "tous les produits" du panier
* Un numéro de contact Colissimo a été ajouté dans les réglages en cas de besoin

AMÉLIORATIONS

* Le numéro de téléphone fourni est à présent vérifié en amont lors d'une livraison en point de retrait pour la France ou la Belgique
* Un message informatif a été ajouté lorsque le montant de l'assurance est plafonné
* L'assurance est à présent disponible pour toutes les destinations sauf le Soudan du Sud et la partie Néerlandaise de Saint-Martin

CORRECTIFS

* Une incompatibilité avec d'autres plugins utilisant Google Maps était possible en utilisant le webservice de la carte des points de retrait. Il est désormais possible de ne pas renseigner la clé d'API GMaps pour régler le problème
* La compatibilité avec le plugin WooCommerce GLS a été ajoutée


## 1.7.0

CORRECTIFS

* Correction sur l'utilisation de l'assurance des colis lors de l'envoi Expert International


## 1.6.9

FONCTIONNALITÉS

* 10 nouvelles destinations ont été ajoutées
* L'envoi avec signature est désormais disponible pour Saint-Martin

AMÉLIORATIONS

* Lorque vous passez une commande en complète alors que le colis n'est pas considéré comme livré côté Colissimo, la commande ne re-passe plus automatiquement à un autre statut

CORRECTIFS

* Une erreur a été gérée dans un cas particulier lors de la mise à jour automatique des statuts de livraison
* Une erreur a été corrigée sur le listing Colissimo lorsqu'une des commandes est corrompue dans WooCommerce


## 1.6.8

FONCTIONNALITÉS

* L'envoi avec signature et expert est désormais disponible en DDP (paiement des frais de douane à l'avance) pour certaines destinations
* Lors de la première installation, les tarifs Colissimo sont ajoutés par défaut aux différentes méthodes d'envoi
* Vous pouvez à présent télécharger, imprimer ou supprimer un bordereau de livraison depuis le listing Colissimo
* Il y a à présent une nouvelle action pour générer l'étiquette d'une commande en particulier depuis le listing Colissimo
* De nouveaux droits d'accès ont été ajoutés pour les différentes fonctionnalités du plugin. Elles sont par défaut ajoutées aux rôles Administrator et Shop Manager (paramétrables avec User Role Editor)
* Dans les options des méthodes d'envoi, il est maintenant possible d'exclure des produits avec des classes d'expédition spécifiques plus facilement
* Dans les options des méthodes d'envoi, il est maintenant possible de rendre l'envoi gratuit si l'un des produits a une classe d'expédition spécifique
* Une nouvelle action a été ajoutée sur le listing des commandes pour générer une étiquette aller

AMÉLIORATIONS

* La configuration a été découpée en plusieurs sections pour simplifier sa lisibilité
* La carte des points de retrait à été mise à jour pour mieux gérer les petits écrans
* Le numéro de suivi et l'adresse de livraison ont été ajoutés à l'email de suivi
* L'option "Toujours gratuit" a été déplacée dans les options des méthodes d'envoi
* un onglet a été ajouté au bandeau de gestion d'étiquettes pour expliquer la mise en place de la collecte Colissimo
* Le chargement des scripts du plugin a été optimisé, pour ne charger que les scripts nécessaires et améliorer le temps de chargement des pages
* La déclaration de douane a été ajoutée pour certaines destinations (Ceuta, Las Palmas, Melilla, Santa Cruz de Tenerife, Mont Athos, Helgoland, Büsingen, Campione d'Italia et Livigno)

CORRECTIFS

* L'affichage de la carte des points de retrait pouvait bloquer dans le cas où le mot de passe du compte Colissimo comportait le caractère "%", c'est corrigé
* L'option d'exclusion de méthodes d'envoi sur les codes promo fonctionne à nouveau lorsque le code promo rend l'expédition gratuite
* Le prix ajouté dans la déclaration de douane est à présent en hors taxes
* Un poids minimum de 10g par produit était forcé à la génération de l'étiquette pour éviter des erreurs du commerçant, il passe à 1g pour gérer les produits très légers
* La suppression d'un produit du store n'affiche plus d'erreur lors de la génération de l'étiquette, mais un message expliquant le problème
* L'impression des étiquettes de plusieurs commandes depuis le listing Colissimo n'était pas fait dans le bon ordre dans certains cas, c'est maintenant corrigé
* Correction du champ pour réordonner le listing Colissimo
* Correction des factures non centrées dans certains cas lors de l'impression


= 1.6.7 =

FONCTIONNALITÉS

* Un nouveau bouton a été ajouté dans la configuration pour vérifier l'état des services Colissimo
* Une option a été ajoutée pour permettre l'envoi via un partenaire postal pour l'Allemagne, l'Autriche, L'Italie et le Luxembourg
* Une option a été ajoutée à la génération d'une étiquette pour spécifier si le colis est classic ou hors normes (non machinable)
* Il est à présent possible de générer des étiquettes avec un utilisateur avancé
* Une action de masse a été ajoutée pour supprimer les étiquettes
* Une option vous permet de choisir si les prix d'envoi sont calculés avant ou après l'application des coupons
* Il est maintenant possible de choisir d'assurer le colis depuis la génération d'une étiquette
* Une option a été ajoutée vous permettant d'offrir la possibilité à vos clients de télécharger une étiquette de retour depuis leur compte

AMÉLIORATIONS

* Ajout d'un hook permettant la surcharge des options de changement de statut de commande
* Un hook a été ajouté vous permettant de modifier le poids du panier (si vous ajoutez un cadeau dans le colis par exemple)
* Une option "Aucune méthode" a été ajoutée dans le filtre par méthode d'envoi des coupons pour permettre l'annulation de toute condition
* Une nouvelle option vous permet de choisir si vous souhaitez que la facture soit ajoutée au zip téléchargé depuis le téléchargement des étiquettes
* La vérification sur le numéro de téléphone lors d'une commande en point relais est moins restrictive
* Une nouvelle option vous permet de spécifier un pays par défaut pour les produits n'en ayant pas
* l'affichage des grilles tarifaires a été amélioré

CORRECTIFS

* Il est à présent possible de proposer plusieurs fois la même méthode d'envoi Colissimo, avec un nom et des prix différents
* Tous les produits du panier doivent respecter le filtre sur les classes d'envoi
* Le champ contenu additionel est à présent bien inclu dans l'email de suivi
* L'impression des étiquettes fonctionne à nouveau pour Firefox et Safari
* Si plusieurs étiquettes ont déjà été générées pour un colis, la quantité des produits par défaut passe à 0 et n'est plus négative
* Le calcul des méthodes d'envoi a été corrigé pour les deux zones de Saint Martin


= 1.6.6 =

CORRECTIFS

* Correction de l'impression thermique
* Correction des changements de statuts de livraison


= 1.6.5 =

FONCTIONNALITÉS

* Une nouvelle option dans la configuration vous permet de choisir quel statut une commande prend lorsque le colis a été livré
* Dans l'interface de création d'un coupon, vous pouvez désormais rendre incompatibles certaines méthodes de livraison pour le coupon
* Une option a été ajoutée pour permettre l'import d'étiquettes pour des commandes, lorsque les étiquettes ont été générées ailleurs que depuis votre site
* Il est maintenant possible de filtrer les commandes dont l'étiquette a été imprimée/n'a pas été imprimée sur le listing Colissimo

AMÉLIORATIONS

* Lors d'une commande en point relais, le type du point relais est maintenant affiché sur la page d'édition d'une commande
* L'extension est maintenant compatible avec WooCommerce lorsque cette extension est installée en tant que must-use plugin

CORRECTIFS

* Un message a été corrigé lorsqu'une erreur survient lors de l'affichage des points relais dans certains cas
* Une erreur avec le système de cache du site a été corrigée lors de la mise à jour des statuts de livraison sur le listing Colissimo


= 1.6.4 =

FONCTIONNALITÉS

* L'envoi de documents douaniers pour la Guyanne Française est à présent possible après la génération d'une étiquette
* Une nouvelle option est disponible pour appliquer un statut spécial à la commande lors d'une expédition partielle
* Une nouvelle option vous permet d'afficher ou non le logo Colissimo près des méthodes de livraison lors de l'achat
* La chronologie des événements pour la livraison d'un colis est désormais affichée sur la page de suivi du colis
* Un nouveau bouton a été ajouté sur le listing Colissimo pour éditer un bordereau de fin de journée, regroupant toutes les commandes dont l'étiquette a été générée sans bordereau

AMÉLIORATIONS

* Les libellés des factures générées sont à présent traduits.
* Un lien est désormais mis à disposition aux clients pour suivre leurs colis en front-office
* Un bouton de contact est à présent disponible dans la configuration pour nous joindre en cas de questions
* Lorsque les logs d'activité sont activés, le fichier de logs peut se remplir très rapidement. Seules les 10.000 dernières lignes sont maintenant affichées lors de la consultation
* Les bordereaux sont désormais consultables lors de l'édition d'une commande
* Si plusieurs étiquettes ont été générées pour la même commande (une commande à plusieurs colis), un bordereau généré sera lié aux étiquettes n'ayant pas encore de bordereau


= 1.6.3 =

FONCTIONNALITÉS

* Il est désormais possible de définir une adresse d'entrepôt différente de l'adresse du magasin
* Le nom des méthodes de livraison Colssimo sont devenues traduisibles par une extension de traduction

AMÉLIORATIONS

* Le montant maximal de l'assurance a été mis à jour. Il est maintenant de 5000€, à l'exception de la livraison en point relais, où il est de 1000€. Pour plus d'informations sur l'assurance Colissimo, merci de vous référer à votre contrat Colissimo.
* Certains scripts en back-office étaient appelés sur des pages où ils n'étaient pas nécessaires

CORRECTIFS

* Sur le listing des commandes Colissimo, la pagination ne fonctionnait pas avec la recherche
* Sur le tunnel de commande, le prix des méthodes de livraison se basait sur le total TTC hors coupons de réduction. Désormais, le prix des méthodes de livraison est calculé sur le total TTC en prenant en compte les coupons de réduction
* Lors de la mise à jour des status de livraison, on pouvait avoir une erreur qui bloquait la mise à jour des status.
* Si WooCommerce est configuré en grammes, la génération d'étiquette depuis le bandeau Colissimo pouvait prendre en compte un mauvais poids
* Lors du téléchargement d'une étiquette de livraison, une erreur pouvait apparaitre
* Sur le tunnel de commande, si on avait seulement des produits virtuels dans le panier, on pouvait ne pas pouvoir soumettre la commande


= 1.6.2 =

AMÉLIORATIONS

* Ajout d'un hook pour l'utilisation ou non de l'assurance Colissimo

CORRECTIFS

* Dans le bloc Colissimo, les valeurs des champs désactivés pouvaient ne pas être pris en compte lors de la génération d'une étiquette de livraison


= 1.6.1 =

CORRECTIFS

* Dans le bloc Colissimo, le poids en grammes est désormais bien pris en compte comme étant en gramme, et non plus en kilogrammes
* Lors d'une commande en point relais, si une deuxième ligne d'adresse était définie dans l'adresse de facturation, cette ligne se retrouvait dans l'adresse du point relais


= 1.6 =

FONCTIONNALITÉS

* Ajout d'un bloc Colissimo sur la page d'édition d'une commande en back-office. Ce bloc liste les étiquettes liées à la commande avec les options d'impression, de téléchargement et d'impression. Ce bloc propose aussi la création d'une étiquette de livraison et/ou d'une étiquette de retour avec la possibilité de choisir quels produits seront dans le colis
* Livraison depuis Monaco : les méthodes de livraison Colissimo sont désormais disponibles pour un magasin situé à Monaco
* L'envoi du mail de suivi Colissimo peut désormais être déclenché par la génération du bordereau
* Ajout d'une option permettant d'inclure ou non les commentaires de la commande sur l'étiquette de livraison
* Sur le listing des commandes WooCommerce, le nombre de commandes liées à un statut Colissimo est désormais affiché

AMÉLIORATIONS

* Compatibilité PHP8
* Modification du processus de mise à jour des statuts de livraison pour prendre en compte un nombre important de commande
* Ajout d'un champ "N°TVA" dans la configuration pour la livraison en direction du Royaume-Uni, destination pour laquelle il est désormais nécessaire d'avoir cette donnée
* Ajout de champs "EORI" et "EORI Royaume-Uni" pour la livraison vers les destinations soumises à une déclaration douanière CN23
* Suppression des caractères spéciaux sur les commentaires de commande ajoutés sur l'étiquette de livraison pour éviter les erreurs à la génération
* Lors de la mise à jour des statuts de livraison, ce sont désormais les statuts des commandes non livreés des 90 derniers jours qui sont mis à jour, contre 15 jours auparavant
* Utilisation de la version 2 du webservice d'affranchissement
* Ajout de nouveaux statuts de livraison
* Dans le tableau de définition des prix des méthodes de livraison Colissimo, tous les champs peuvent désormais avoir 4 décimales

CORRECTIFS

* Les clients dont la commande est a un statut Colissimo peuvent désormais laisser un avis sur les produits de la commande
* La référence de la commande est de nouveau présente sur l'étiquette de livraison
* Correctif à la génération d'un formulaire CN23 si un produit du colis pèse moins de 10 grammes
* Le nom de l'entreprise est de nouveau présent dans l'adresse de livraison pour les commandes en point relais


= 1.5 =

FONCTIONNALITÉS

* Il est désormais possible de définir un nom de méthode de livraison différent lorsque celle ci est gratuite
* Les coupons de réductions offrant la livraison gratuite sont désormais appliqués sur les méthodes de livraison Colissimo
* Les destinations Andorre et Monaco sont ajoutées

AMÉLIORATIONS

* La Google Maps en mode webservice est désormais 100% traduite en français
* Ajout de hooks sur la requête de génération des étiquettes
* Sur le tunnel de commande, l'adresse du point relais n'est plus gérée par la partie "Livrer à une autre adresse"
* Brexit : à partir du 31/12, la livraison en point relais pour le Royaume-Uni ne sera plus disponible et un formulaire CN23 sera généré avec les étiquettes de livraison
* L'envoi du mail de suivi et du mail contenant l'étiquette de retour sont désormais conditionnés à leur activation dans la configuration WooCommerce (Réglages -> Emails)

CORRECTIFS

* Correction de l'affichage des messages d'informations pouvant se retrouver dans les requêtes Ajax
* Correction d'une erreur sur les statuts de commandes Colissimo avec WooCommerce Subscription
* Les statuts des commandes était modifié à la génération du bordereau, alors que l'option était désactivée
* La prise en compte du poids du colis se faisait uniquement en kilogrammes
* Correctifs divers

= 1.4.2 =

CORRECTIFS

* Le fichier de traduction français wc_colissimo-fr_fr.po ne s'était pas mis à jour dans la version 1.4.1

= 1.4.1 =

AMÉLIORATIONS

* Les statuts de livraisons sont désormais traduits
* Ajout du fichier de langue .pot

CORRECTIFS

* Un conflit pouvait se poser avec d'autres plugins lors de la génération d'une étiquette ou d'un bordereau de livraison au format PDF

= 1.4 =

FONCTIONNALITÉS

* Il est désormais possible d'appliquer une tranche de prix de livraison à plusieurs classes de livraison

* Une option a été ajoutée pour prendre en compte le poids de l'emballage lors de la génération de l'étiquette de livraison

* Le lien dans l'email de suivi de la livraison peut désormais rediriger soit vers la page de suivi non authentifiée du site, soit vers la page de suivi La Poste

AMÉLIORATIONS

* Sur la page de suivi non authentifiée, la colonne "Localisation" a été retirée car elle contenait des informations inexactes

* Dans les emails Colissimo, il manquait la traduction sur certaines chaînes de caractères. Les emails sont maintenant tous bien traduits

* Sur le listing des commandes Colissimo, la recherche fonctionnait mal. Désormais, il est possible de rechercher sur toutes les informations du listing

CORRECTIFS

* Correction d'un bug pouvant empêcher la génération en masse des bordereaux ou des étiquettes de livraison

= 1.3.4 =

AMÉLIORATIONS

* La partie française de St Martin est désormais éligible à la livraison dans la zone "DOM1"

* Dans le tunnel de commande, la vérification de la présence d'un numéro de téléphone pour une livraison en point retrait se fait désormais aussi sur le numéro de téléphone de l'adresse de livraison, en plus de celle de facturation

CORRECTIFS

* Une erreur pouvait se passer si un plugin chargeait la librairie TCPDF, que nous chargeons aussi

* Il pouvait arriver que la pop-up de choix des points de retrait s'ouvre deux fois

* Résolution d'un bug sur la page commande WooCommerce en back-office si un pays n'était pas défini dans la commande

* Les tables en base de données contenant les étiquettes de livraison pouvaient ne pas se créer à l'installation du plugin

* Si une commande était en brouillon, une erreur se produisait sur le listing des commandes Colissimo

= 1.3.3 =

AMÉLIORATIONS

* Les commandes dont la livraison est Colissimo apparaissent désormais dans les rapport WooCommerce

* Ajout d'une notification invitant l'installation du plugin 'Enable JQuery Migrate Helper' sur WordPress 5.5 pour corriger les potentielles erreurs rencontrées depuis cette mise à jour de WordPress

CORRECTIFS

* Sur le listing des commandes Colissimo, le statut de la commande pouvait être mal affiché

* Sur le checkout, un message d'erreur pouvait s'afficher à la place du bouton pour valider la commande

= 1.3.2 =

AMÉLIORATIONS

* L'appel au web-service qui affiche le widget de sélection du point relais a reçu une mise à jour pour être compatible avec les identifiants contenant des caractères spéciaux

CORRECTIFS

* Résolution d'un problème qui pouvait ne pas afficher les méthodes de livraison sur le tunnel de commande sur un multisite

* Résolution d'un problème à l'installation qui pouvait tenter de faire la migration des étiquettes de livraison même si cela n'était pas nécessaire

= 1.3.1 =

CORRECTIFS

* Résolution d'un bug pouvant affecter l'affichage des filtres sur le listing des commandes Colissimo

= 1.3 =

FONCTIONNALITÉS

* L'interface de listing des commandes Colissimo a été revu :
  - Un nouveau système de filtre est disponible permettant, notamment, de faire une sélection multiple des filtres
  - Les étiquettes allers et retours sont désormais conservées, même à la génération d'autres étiquettes pour une même commande
  - La liste déroulante d'actions sur les étiquettes est remplacée par des icones correspondantes à chaque action
  
* Il est désormais possible d'expédier depuis l'Outre Mer pour les territoires suivants : 
  - Saint-Barthélemy
  - Guyane Française
  - Guadeloupe
  - Martinique
  - Saint-Pierre-et-Miquelon
  - Réunion
  - Mayotte
  
AMÉLIORATIONS

* Il pouvait arriver que le calcul du prix des méthodes de livraisons se basaient sur le prix HT du panier. Désormais, ce calcul se base toujours sur le prix TTC du panier

* En front-office, le lien pour que le client puisse choisir son point de retrait dans le cas échéant est désormais un bouton

* Dans l'interface de listing des commandes Colissimo, la colonne de statut de la commande est désormais présente

* Dans l'interface de listing des commandes Colissimo, le choix des filtres est sauvegardé automatiquement pour les retrouver d'une consultation du listing à une autre

* L'option d'activation des logs est désormais binaire : activé ou désactivé

* Tous les appels et réponses des différents web-services sont désormais logués si l'option est activée

CORRECTIFS

* Résolution d'un problème qui pouvait faire apparaître l'affichage du retour boite aux lettres de manière intempestive

* Résolution d'un problème pouvant passer une commande en "En attente de paiement" après la génération de l'étiquette aller

= 1.2 =

FONCTIONNALITÉS

* Possibilité d'affecter à un transporteur Colissimo une commande passée avec un autre transporteur et qui n'est pas encore expédiée, depuis la page commande WooCommerce

AMÉLIORATIONS

* Compatibilité avec WooCommerce 4

* Le prix des méthodes de livraison se base maintenant sur un prix panier minimum, un prix panier maximum, un poids panier minimum, un poids panier maximum et une classe de livraison 

* Lors du passage de commande, le client est désormais bloqué s'il n'a pas défini un numéro de téléphone portable pour une livraison en point de retrait (France et Belgique uniquement)

* La deuxième ligne d'adresse est désormais présente sur les étiquettes de livraison à destination de la Belgique et de la Suisse

CORRECTIFS

* Résolution d'un problème qui pouvait empêcher l'utilisation du plugin après sa mise à jour

* Résolution d'un problème empêchant la personnalisation de la police d'écriture lors de l'utilisation du widget de choix de point de retrait 

* Résolution d'un problème qui pouvait provoquer des incohérences d'affichage sur les pages du site

= 1.1 =

FONCTIONNALITÉS

* Prise en charge de étiquettes au format ZPL et DPL : 
  - Possibilité de générer les étiquettes de livraison au format ZPL ou DPL
  - Possibilité d'imprimer les étiquettes de livraison au format ZPL ou DPL directement sur une imprimante thermique via USB ou Ethernet.

* Impression en masse des étiquettes de livraisons de plusieurs commandes depuis le listing des commandes Colissimo

* Il est désormais possible de trier les commandes du listing Colissimo selon :
  - Le nom du client
  - L'adresse de livraison
  - Le pays de livraison
  - La méthode de livraison
  - Le statut de la livraison
  - Le numéro de bordereau

* Il est désormais possible de filtrer les commandes du listing Colissimo selon :
  - Le pays de livraison
  - La méthode de livraison
  - Le statut de livraison
  - Les étiquettes générées ("Aller", "Retour", "Aller et Retour" et "Étiquette non générée")

* Le nombre de commandes affichées par page sur le listing Colissimo est paramétrable via l'option de WordPress "Options de l'écran"

AMÉLIORATIONS

* Ajout de la référence de la commande sur l'étiquette de livraison

* Les prix des méthodes de livraisons se basent désormais sur le prix TTC

* Lors de l'impression d'une étiquette de livraison, la facture n'est plus présente 

CORRECTIFS

* Résolution d'un problème qui pouvait se poser au moment de la sauvegarde des prix des méthodes de livraison, s'il y avait la présence de nombres décimaux

* Résolution d'un problème qui faisait que le prix de la commande pris en compte pour le calcul du prix de la méthode de livraison n'incluait pas les réductions liées à des coupons 

* Résolution d'un problème qui pouvait rendre l'ouverture de la pop-up de choix du point relais impossible pour le client

* Résolution de la prise en charge du multisite

* Résolution d'un problème qui pouvait rendre le lien de suivi non-fonctionnel

* Résolution d'un problème qui pouvait empêcher la sélection d'un point relais si un autre avait été choisi précédemment

* Résolution d'un problème qui pouvait empêcher la génération du formulaire CN23
