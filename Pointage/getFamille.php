<?php
	/*
	Permet juste de récupérer les infos liées au numero du POST $nomRecherche
	*/

	$nomRecherche = $_POST['nomRecherche'];

	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("SELECT idEnfant, nomEnfant, prenomEnfant, anneeDeNaissance
							FROM Enfant
							WHERE numeroParent=$nomRecherche");
	
	while($resultat = mysql_fetch_object($requete))
	{
		$enfant[]=$resultat;
	}								
		
	if($enfant)
	{
		echo json_encode($enfant);	
	}
	else
	{
		echo "0";
	}
	
?>