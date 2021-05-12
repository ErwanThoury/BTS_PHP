<?php
	/*
	Permet juste de récupérer les infos liées au numero du POST $nomRecherche
	*/

	$nomRecherche = $_POST['idUsager'];
	$centre = $_POST['centre'];
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("SELECT libelle, date, idRdv, presenceRDV
							FROM RendezVous INNER JOIN TypePresence on numeroTypePresence = idTypePresence
							WHERE numeroUsager = $nomRecherche
								AND numeroCentre = $centre");				
	$lesRDV=[];
	while($resultat = mysql_fetch_object($requete))
	{
		$lesRDV[]=$resultat;
	}
	echo json_encode($lesRDV);
	
?>