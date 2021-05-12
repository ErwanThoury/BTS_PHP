<?php
	$pereMere = $_POST['nomPereMere'];
	$nomEnfantAjout = $_POST['nomAjoute'];
	$prenomEnfantAjout = $_POST['prenomAjoute'];
	$anneeDeNaissanceAjout = $_POST['anneeAjoute'];
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("INSERT INTO `GestionFluxESI`.`Enfant` (`idEnfant`, `nomEnfant`, `prenomEnfant`, `numeroParent`, `anneeDeNaissance`)
							VALUES (NULL,'$nomEnfantAjout','$prenomEnfantAjout','$pereMere', '$anneeDeNaissanceAjout')");
							
?>


