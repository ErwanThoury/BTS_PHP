<?php
	/*
	Permet juste de récupérer la liste des RDV
	*/
	$centre=$_POST['centre'];
	
	include 'connexionBDD.php';
	connexionBDD();
	if($centre == 1)
	{
		$requete = mysql_query("select * from TypePresence where rdv = '1' and disponibiliteCentre != '2'  ");	
	}
	else
	{
		$requete = mysql_query("select * from TypePresence where rdv = '1' and disponibiliteCentre != '1'  ");	
	}
	
	while($resultat = mysql_fetch_object($requete)) //On affiche les données dans le tableau.
	{ 			
		$lesTypes[]=$resultat;
		
	}	
	echo json_encode($lesTypes);
	
?>