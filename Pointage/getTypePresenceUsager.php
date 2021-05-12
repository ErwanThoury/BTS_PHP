<?php
	/*
	Permet juste de récupérer les infos liées au numero du POST $nomRecherche
	*/

	$nomRecherche = $_POST['idUsager'];
	$centre = $_POST['centre'];
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("SELECT libelle, dateHeurePointage, idPresence
							FROM Presence INNER JOIN TypePresence on numeroTypePresence = idTypePresence
							WHERE numeroUsager = $nomRecherche
								AND numeroCentre = $centre
							ORDER BY dateHeurePointage DESC");				
	$lesRDV=[];
	while($resultat = mysql_fetch_object($requete))
	{
		$lesRDV[]=$resultat;
		if($resultat->libelle == "Maraude")
		{
			
		}
	}
	echo json_encode($lesRDV);
	
?>