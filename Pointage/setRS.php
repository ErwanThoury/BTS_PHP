<?php

	$date = $_POST['date'];
	$idUsager = $_POST['idUsager'];
	$txtRS = $_POST['txtCarte'];
	$dateD = $_POST['dateDelivrance'];
	include 'connexionBDD.php';
	connexionBDD();

	$dateFor = explode("-", $dateD);
	$dateFormate = $dateFor[2]."-".$dateFor[1]."-".$dateFor[0];
	
	$requete = mysql_query("UPDATE Usager
							SET dateExpirationCarte='$date', numeroCarte = '$txtRS', dateDelivrance = '$dateFormate'
							WHERE idUsager = $idUsager ");	
	echo ($dateFinale);
	
?>