<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Correction des fichiers xml g&eacuten&eacuter&eacutes</title>
    </head>
    <body>
			<?php include("entete.php"); ?>
			<section>
			</br>
			</br>
			</br>
			</br>
			<h1 Class="text-center" > V&eacuterification de la validit&eacute d'un fichier .csv </h1>
					<div class="container text-center" >
						<div class="d-flex p-2 justify-content-center">


<?php
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);

include_once("upload_csv_maquette_ligne.php"); // On inlue le code de conversion et de téléchargement du fichier xml
$dossier_csv = 'upload_csv/';
$fichier = basename($_FILES['conversion']['name']);
$taille_maxi = 10000000;
$taille = filesize($_FILES['conversion']['tmp_name']);
$extensions = array('.csv');
$extension = strrchr($_FILES['conversion']['name'], '.'); 
//Début des vérifications de sécurité...
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
     $erreur = 'Vous devez uploader un fichier de type csv';
}
if($taille>$taille_maxi)
{
     $erreur = 'Le fichier est trop gros...';
}
if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
{
     //On formate le nom du fichier ici... 
     $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜİàáâãäåçèéêëìíîïğòóôõöùúûüıÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     move_uploaded_file($_FILES['conversion']['tmp_name'], $dossier_csv.$fichier); //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     convertCsvToXmlFile($dossier_csv.$fichier); // On utilise le code pour convertir le fichier .csv 
	 	
}
else
{
	echo '</br>';

	echo '<p class="text-center">' .$erreur. '</p>';

}
array_map('unlink', glob("upload_csv/".'*')); //Vide le fichier temporaire
?>
    </body>
</html>
