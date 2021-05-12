<?php
	/*
	Permet juste de récupérer la liste des nationalités
	*/

	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("select * from Nationalite");				
	while($resultat = mysql_fetch_object($requete)) //On affiche les données dans le tableau.
	{ 			
		$lesNations[]=$resultat;
		
	}	
	echo json_encode($lesNations);
	
?>