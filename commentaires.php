<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8"/>
		<title>LylieFlex</title>
		<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
		<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
		<link rel="stylesheet" href="css/index.css"/>
	</head>

	<body>
		<div id="bloc_page">        
       

					<header>
		<h1><a href ="index.html">Bienvenue sur LylieFlex</a></h1>

		<nav id="menu">
			<ul id="nav">
				<li><a href="index.html">Accueil</a></li>
				<li><a href="#">Visage</a>
					<ul>
						<li><a href="#">Par catégories ➔</a>
							<ul>
								<li><a href="visage_products/categories/cremes_de_jour.html">Crèmes de jour</a></li>
								<li><a href="visage_products/categories/contour_des_yeux.html">Contour des yeux</a></li>
								<li><a href="visage_products/categories/serums.html">Sérums</a></li>
								<li><a href="visage_products/categories/demaquillants_nettoyants.html">Démaquillants et nettoyants</a></li>
								<li><a href="visage_products/categories/masques_et_exfoliants.html">Masques et Exfoliants</a></li>
								<li><a href="visage_products/categories/soins_multifonction.html">Soins multifonction</a></li>
								<li><a href="visage_products/categories/protections_solaires.html">Protections solaires</a></li>
							</ul>	
						</li>

						<li><a href="#">Par Besoin ➔</a>
							<ul>
								<li><a href="visage_products/besoin/hydratation_et_nutrition.html">Hydratation et Nutrition</a></li>
								<li><a href="visage_products/besoin/lift_et_fermete.html">Lift et fermeté</a></li>
								<li><a href="visage_products/besoin/rides_et_ridules.html">Rides et ridules</a></li>
								<li><a href="visage_products/besoin/anti_age.html">Anti-Age</a></li>
								<li><a href="visage_products/besoin/eclat.html">Eclat</a></li>
								<li><a href="visage_products/besoin/regard_fatigue.html">Regard fatigué</a></li>
								<li><a href="visage_products/besoin/parfaire_et_unifier.html">Parfaire et unifier</a></li>	
							</ul>
						</li>
					</ul> 
				</li>
			
				<li><a href="#">Yeux</a>
					<ul>
						<li><a href="yeux_products/regard_fatigue.html">Regard fatigué</a></li>
						<li><a href="yeux_products/rides_et_ridules.html">Rides et ridules</a></li>
						<li><a href="yeux_products/eclat.html">Eclat</a></li>
						<li><a href="yeux_products/fortifiant_cils_sourcils.html">Fortifiant cils/sourcils</a></li>
					</ul>
				</li> 

				<li><a href="#">Make-up</a>
					<ul>
						<li><a href="#">Par Catégories ➔</a>
						<ul>
							<li><a href="make_up_products/categories/bases_de_maquillage.html">Bases de maquillage</a></li>
							<li><a href="make_up_products/categories/fluides_de_teint.html">Fluides de teint</a></li>
							<li><a href="make_up_products/categories/demaquillants.html">Démaquillants</a></li>
						</ul></li>

						<li><a href="#">Par Besoin ➔</a>
							<ul>
								<li><a href="make_up_products/besoin/lisser.html">Lisser</a></li>
								<li><a href="make_up_products/besoin/unifier_le_teint.html">Unifier</a></li>
								<li><a href="make_up_products/besoin/parfaire_et_embellir.html">Parfaire et embellir</a></li>
							</ul>
						</li>
					</ul>
				</li>

				<li><a href="#">Corps</a>
					<ul>
						<li><a href="corps_products/soin_corps.html">Soins corps</a></li>
						<li><a href="corps_products/solaires.html">Solaires</a></li>
					</ul>
				</li>

				<li><a href="contact.html">Contact</a></li>
				<li><a href="commentaires.php">Commentaires</a></li>
			</ul>
		</nav>

		</header>
		<section class="commentaires">
				<?php include("gestioncommentaires.php"); ?>
			<!--  début du bloc commentaires, version 1.0  (06 novembre 2009)-->
<script language="JavaScript" type="text/javascript">
    var sujet='general'; // les dix premiers caractères serviront d'identification de la liste des commentaires
    var adressebase='';  // base d'adressage pour le fichier PHP de gestion des commentaires
</script>
<div id="laisseruncommentaire">
    <form name="commentaire_bouton" id="commentaire_form_A" action="gestioncommentaires.php" method="post" onsubmit="return false;" style="display: Block">
        <input type="button" value="laisser un commentaire" onclick="Affiche_form_B()">
    </form>
    <form name="commentaire_saisie" id="commentaire_form_B" action="gestioncommentaires.php" method="post" onsubmit="return false;" style="display: None">
		<fieldset>
        <legend>Vous pouvez saisir un commentaire ...</legend>
			Votre nom (<i><small>ou pseudo</small></i>):<br><input name="nom" type="text" size="20"><br>
			Saisissez le commentaire que vous voulez ajouter:<BR>
			<textarea name="commentaire" cols="60" rows="4" wrap="soft"></textarea>
			<input name="annuler" type="button" value="Annuler" onclick="Annuler()"><br>
			<input name="envoyermodifier" type="button" value="Envoyer" onclick="Envoyer_modifier()">&nbsp;&nbsp;
			<input name="terminer" type="button" value="Terminer" onclick="Terminer()" style="display: None">
        </fieldset>
    </form>
    <div id="liste_des_messages">&nbsp;</div>
</div>
<script language="JavaScript" type="text/javascript" src="commentaires.js"></script>
<!-- fin du bloc commentaires -->

</section>
<!--fin balise bloc_page -->
		</div>

<script>
  AOS.init();
</script>


	</body>
	</html>