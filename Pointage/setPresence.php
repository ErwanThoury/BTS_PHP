<?php
	/*
	Permet d'enregistrer la présence d'un usager dans la BDD.
	Les POST $nomAjoute, $centre et $numeroPresence sont les infos à rentrer dans la table Presence
	*/
	$today = date("y-m-d G:i:s");
	$nomAjoute = $_POST['nomAjoute'];
	$centre=$_POST['centre'];
	$numeroPresence=$_POST['typePresence'];
	include 'connexionBDD.php';
	connexionBDD();
	
	if($nomAjoute>0){
		$requete = mysql_query("INSERT INTO `GestionFluxESI`.`Presence` (`idPresence`,`Presence`.`numeroCentre`, `numeroUsager`, `numeroTypePresence`,`dateHeurePointage`) 
								VALUES (NULL,'$centre', '$nomAjoute','$numeroPresence', '$today');");				
		$requete = mysql_query("SELECT count(*) AS 'nombreAccueil' 
								FROM Presence
								WHERE DATE(dateHeurePointage) = DATE(NOW()) and numeroTypePresence=$numeroPresence and numeroCentre = '$centre'");				
		$resultat = mysql_fetch_object($requete);
		echo json_encode($resultat);
	}
?>