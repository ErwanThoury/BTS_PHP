<?php
	/*
	Permet de récupérer la liste des noms qui commencent par le POST $debutLib
	*/
	$debutLib = $_POST['debutLib'];
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("select * from Usager where nom like '$debutLib%' and idUsager != '0' order by nom asc, prenom asc");				
	$lesNoms = [];
	while($resultat = mysql_fetch_object($requete)) //On affiche les données dans le tableau.
	{ 			
		$lesNoms[]=$resultat;
		
	}	
	echo json_encode($lesNoms);
	
?>