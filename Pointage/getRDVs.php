<?php
	$dateSel = $_POST["dateSel"];
	$presence = $_POST["presence"];
	$centre = $_POST['centre'];
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("SELECT idRdv, nom, prenom
							FROM RendezVous INNER JOIN Usager 
								ON numeroUsager = idUsager
							WHERE date ='$dateSel' 
								AND numeroTypePresence = '$presence' 
								AND numeroCentre = $centre;");				
	$lesRDV=[];
	while($resultat = mysql_fetch_object($requete))
	{
		$lesRDV[]=$resultat;
	}
	echo json_encode($lesRDV);
	
?>