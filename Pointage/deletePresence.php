<?php
	/*
	Permet de mettre à jour les infos d'un usager.
	Les POST sont alors semblable à ceux de la fonction "setUsager".
	
	*/
	$idPresence = $_POST['idPresence'];

	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("DELETE FROM Presence
							WHERE idPresence = $idPresence ");				
	$resultat = mysql_fetch_object($requete);
	echo json_encode($resultat);
	
?>