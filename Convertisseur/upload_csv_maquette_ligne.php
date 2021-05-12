 
<?php
/* ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL); */

include_once("Fonction_tableau_vide.php");
include_once("Telechargement.php");
include_once("Validations_xml.php"); 
include_once("controleCSV.php");

$base = mysql_connect ('Localhost', 'astre', 'stuGvh5ibMP9IGzr');
mysql_select_db ('Interface_Astre', $base) ;

date_default_timezone_set('Europe/Paris');

function convertCsvToXmlFile($input_file) 
{
	
	$convertir = false;
	$idAction = 0;
	$retour=convertCsvToXmlFileR($input_file, $convertir, $idAction);// On appelle la fonction de conversion
	
	//on recupere les retours
	
	$erreur_traitement = $retour[0];
	$erreur_message = $retour[1];
	$doc = $retour[2];
	$nomFichier = $retour[3];
	$date = $retour[4];
	$e = $retour[5];
				
	if ($erreur_traitement)
	{			
		echo '<div class="container text-center" >
				<div class="d-flex p-2 justify-content-center">	
					<p class="text-dark">'
					.$erreur_message.
					'</p>
				</div>
			</div>'; 
	}
	else
	{
		$strxml = $doc->saveXML();  // On sauvegarde le doc en XML
		$handle = fopen('/var/www/html/tmp/FC'.$e.'_'.$date, "w"); // On crée fichier en fonction du nombre de ligne dans le fichier csv et de la date que l'on place dans le dossier de l'utilisateur de la session		
		fwrite($handle, $strxml); // On écrit dans le fichier le fichier xml
		fclose($handle);
		libxml_use_internal_errors(true);
		$xml = new DOMDocument();
		$xml->load('tmp/FC'.$e.'_'.$date);

		if (!$xml->schemaValidate('Commandev1.xsd'))  // Fonction que contrôle les possibles erreurs dans les fichiers xml, si erreur:
		{
			echo '<div class="container text-center" >
					<div class="d-flex p-2 justify-content-center">';
			echo '<p class="text-dark">Voici les erreurs de type et de syntaxe dans le fichier xml num&eacutero ' .$e. ':</p>';
			libxml_display_errors();
			echo '	</div>
				  </div>';
			echo '</br>';		
		}
		else // Si pas d'erreurs: 
		{
			echo '<div class="container text-center" >
					<div class="d-flex p-2 justify-content-center">';
			echo '<p class="text-dark">Les donn&eacutees rentr&eacutees sont correctes. </br> Il y a ' .$e. ' lignes qui ont &eacutet&eacute v&eacuterifi&eacutees.</p>';
			echo '	</div>
				  </div>';
			echo '</br>';
		}
		
	}
	
	$e++;
	echo '</br>';
	array_map('unlink', glob("tmp/".'*')); //Vide le fichier temporaire
	
}

 
?>


