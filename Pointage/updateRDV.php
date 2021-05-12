<?php
	/*
	Permet de mettre à jour les infos d'un usager.
	Les POST sont alors semblable à ceux de la fonction "setUsager".
	
	*/
	$idRdv = $_POST['idRdv'];
	$presence = $_POST['presence'];
	include 'connexionBDD.php';
	connexionBDD();
	$requete = mysql_query("UPDATE RendezVous 
							SET presenceRDV = '$presence' 
							WHERE idRdv = $idRdv ");				
	$resultat = mysql_fetch_object($requete);
	echo json_encode($resultat);
	
?>