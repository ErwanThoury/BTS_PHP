<?php
	/*
	Permet juste de récupérer les infos liées au numero du POST $nomRecherche
	*/

	$nomRecherche = $_POST['nomRecherche'];
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("select nom, prenom, anneeDeNaissance, numeroOrientation, numeroCarte, dateExpirationCarte,dateDelivrance, nationalite,dateExpirationOrientation,dateCreation, Nationalite.libelle AS nationaliteLibelle from Usager inner join Nationalite on nationalite = idNationalite where idUsager = $nomRecherche ");				
	$resultat = mysql_fetch_object($requete);
	echo json_encode($resultat);
	
?>