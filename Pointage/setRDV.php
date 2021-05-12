<?php
	$numeroUsager = $_POST['idUsager'];
	$numeroPrestation = $_POST['prestation'];
	$date =  $_POST['date'];
	$today = date("y-m-d G:i:s");
	$centre=$_POST['centre'];
	include 'connexionBDD.php';
	connexionBDD();
	if($numeroUsager != 0)
	{
		$requete = mysql_query("INSERT INTO `GestionFluxESI`.`RendezVous` (`idRdv`, `numeroUsager`, `numeroTypePresence`, `date`, `presenceRDV`, `numeroCentre`) 
								VALUES (NULL, '$numeroUsager', '$numeroPrestation', '$date', '0', '$centre');");
		$requete = mysql_query("INSERT INTO `GestionFluxESI`.`Presence` (`idPresence`, `numeroCentre`, `numeroUsager`, `numeroTypePresence`, `dateHeurePointage`) 
								VALUES (NULL, '$centre', '$numeroUsager', '16', '$today');");
	}

	echo $centre;
?>