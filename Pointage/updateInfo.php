<?php
	/*
	Permet de mettre à jour les infos d'un usager.
	Les POST sont alors semblable à ceux de la fonction "setUsager".
	
	*/
	$idModifie = $_POST['idModifie'];
	$carte = $_POST['carteRS'];
	$nomModifie = $_POST['nomModifie'];
	$prenomModifie = $_POST['prenomModifie'];
	$anneeDeNaissanceModifiee = $_POST['anneeDeNaissanceModifiee'];
	$nationaliteModifiee = $_POST['nationaliteModifiee'];	
	if($nationaliteModifiee == "UE")
	{
		$nationaliteModifiee = "2";
	}
	if($nationaliteModifiee == "Francais" || $nationaliteModifiee == "Francaise" || $nationaliteModifiee == "Français"|| $nationaliteModifiee == "Française" )
	{
		$nationaliteModifiee = "1";
	}
	if($nationaliteModifiee == "Autre")
	{
		$nationaliteModifiee = "3";
	}
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("UPDATE Usager 
							SET nom = '$nomModifie', prenom = '$prenomModifie', anneeDeNaissance = '$anneeDeNaissanceModifiee', nationalite = '$nationaliteModifiee', numeroCarte='$carte'
							WHERE idUsager = $idModifie ");				
	$resultat = mysql_fetch_object($requete);
	echo json_encode($resultat);
	
?>