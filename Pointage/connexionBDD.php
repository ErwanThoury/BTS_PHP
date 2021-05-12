<?php
function connexionBDD()
{
	$base = mysql_connect ('Localhost', 'gestionFlux', 'NlrRofwekJdSiuvi') or die(); // On se connecte a la base
	mysql_select_db ('GestionFluxESI', $base) or die(); // On travaille dans Interface Astre
} 
?>