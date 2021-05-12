<?php
	/*
	Permet de changer ou de prolonger l'orientation de l'usager sélectionné.
	Les POST sont: 
	-$numeroUsager: correspond au numéro de l'usager sélectionné
	-$renouveler: permet de vérifier si une orientation différente que "Autre" a été selectionné. /!\ Cela n'indique pas de quelle formation il s'agit /!\
	-$numOrientation permet de savoir de quelle orientation il s'agit.	
	*/
	$numeroUsager = $_POST['dateExp'];
	$renouveler = $_POST['inscrire'];
	$today = date("y-m-d");
	$numOrientation =  $_POST['numOrientation'];
	include 'connexionBDD.php';
	connexionBDD();

	if($renouveler == 0)
	{
		$dateFinale = date('Y-m-d',strtotime($today. ' + 90 days'));
	}
	else
	{			
		$requete = mysql_query("SELECT dateExpirationOrientation, numeroOrientation
								FROM Usager
								WHERE idUsager = $numeroUsager ");				
		$resultat = mysql_fetch_object($requete);
		$dateUsager = $resultat->dateExpirationOrientation;

		$dateFinale = date('Y-m-d',strtotime($dateUsager. ' + 90 days'));		
				
	}
	$requete = mysql_query("UPDATE Usager
							SET dateExpirationOrientation='$dateFinale', numeroOrientation='$numOrientation'
							WHERE idUsager = $numeroUsager ");	
	echo ($dateFinale);
	
?>