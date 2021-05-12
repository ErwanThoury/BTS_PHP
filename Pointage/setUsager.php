<?php
	/*
	Permet d'insérer un usager dans la BDD. Les POST sont alors les infos de l'usager
	*/
	$numOrientation =  $_POST['numOrientation'];
	$today = date("y-m-d");
	$creation = $today;
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$anneeDeNaissance = $_POST['anneeDeNaissance'];
	$nationalite = $_POST['nationalite'];
	$sexe = $_POST['sexe'];
	if($numOrientation != 0)
	{
		$today = date('Y-m-d',strtotime($today. ' + 90 days'));
	}
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("INSERT INTO `GestionFluxESI`.`Usager` (`idUsager`, `nom`, `prenom`, `C`, `anneeDeNaissance`, `nationalite`, `dateCreation`, `numeroOrientation`,`dateExpirationOrientation`) 
							VALUES (NULL, '$nom', '$prenom', '$sexe', '$anneeDeNaissance', '$nationalite','$creation','$numOrientation','$today');");				
	
?>