<?php
	$base = mysql_connect ('Localhost', 'astre', 'stuGvh5ibMP9IGzr'); // On se connecte a la base
	mysql_select_db ('Interface_Astre', $base) ; // On travaille dans Interface Astre
	
	/**
	* Toutes ces fonctions permettent de supprimer une action de la bdd puis  
	* redirige tout de suite vers la page d'où provient le GET
	*/
	
	if(isset($_GET["supp"]))
	{
		$requete = mysql_query('DELETE FROM LigneTraitee WHERE numeroAction ="'.$_GET["supp"].'"') 					 
	        					OR die('Erreur de la requête MySQL'); 
		$requete = mysql_query('DELETE FROM Action WHERE idaction ="'.$_GET["supp"].'"') 					 
	        					OR die('Erreur de la requête MySQL'); 
		header ('location: lienTDB.php');
	}
	
	if(isset($_GET["suppRet"]))
	{
		$requete = mysql_query('DELETE FROM LigneTraitee WHERE numeroAction ="'.$_GET["supp"].'"') 					 
	        					OR die('Erreur de la requête MySQL'); 
		$requete = mysql_query('DELETE FROM Action WHERE idaction ="'.$_GET["suppRet"].'"') 					 
	        					OR die('Erreur de la requête MySQL');
		header ('location: lienTDBRetour.php');
	}
	
	if(isset($_GET["suppCon"]))
	{
		$requete = mysql_query('DELETE FROM LigneTraitee WHERE numeroAction ="'.$_GET["supp"].'"') 					 
	        					OR die('Erreur de la requête MySQL'); 
		$requete = mysql_query('DELETE FROM Action WHERE idaction ="'.$_GET["suppCon"].'"') 					 
	        					OR die('Erreur de la requête MySQL');
		header ('location: lienTDBConnexion.php');
	}
	
	if(isset($_GET["suppConv"]))
	{
		$requete = mysql_query('DELETE FROM LigneTraitee WHERE numeroAction ="'.$_GET["supp"].'"') 					 
	        					OR die('Erreur de la requête MySQL'); 
		$requete = mysql_query('DELETE FROM Action WHERE idaction ="'.$_GET["suppConv"].'"') 					 
	        					OR die('Erreur de la requête MySQL');
		header ('location: lienTDBConversion.php');
	}
	
?>