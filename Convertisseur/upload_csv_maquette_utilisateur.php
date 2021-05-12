
<?php
/*
 ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(0);
*/

$base = mysql_connect ('Localhost', 'astre', 'stuGvh5ibMP9IGzr'); // On se connecte a la base
mysql_select_db ('Interface_Astre', $base) ; // On travaille dans Interface Astre

include_once("Fonction_tableau_vide.php"); // On appelle fonction qui teste si un tableau est vide ou pas
include_once("Telechargement.php"); // On appelle fonction qui permet de télécharger le dossier zip contenant les fichiers xml
include_once("Validations_xml.php"); // On appelle fonction qui permet d'afficher les erreurs du fichier xml s'il y en a
include_once("controleCSV.php");



date_default_timezone_set('Europe/Paris');


function convertCsvToXmlFile($input_file) // Fonction qui permet de convertir le tableau excel/csv en xml, de le corriger puis de le télécharger
{
	$requete = mysql_query("INSERT INTO `Interface_Astre`.`Action` (`idaction`, `date`, `numtyp`, `numutilisateur`, `reussite`, `retour`, `libelle`, `erreur`, `nomfichierbase`) 
								VALUES (NULL, '', '1', '".$_SESSION['id']."', '1', '', '', '','');") OR die(mysql_error());
	$idAction = mysql_insert_id();
	
	$convertir = true;
	$utilisateur = $_SESSION['login'];
	
	$retour=convertCsvToXmlFileR($input_file, $convertir, $idAction); // On appelle la fonction de conversion
	
	//on recupere les retours
	$erreur_traitement = $retour[0];
	$erreur_message = $retour[1];
	$doc = $retour[2];
	$nomFichier = $retour[3];
	$e = $retour[5];
	$numeroAjouterBDD = $retour[6];
	
	
	if (!$erreur_message)
	{			
		setlocale(LC_TIME, "fr_FR");
		$nomfic = '/FC'.'_'.strftime("%d%m%y_%H%M%S").'.XML';
		
		$strxml = $doc->saveXML();  // On sauvegarde le doc en XML
		$handle = fopen('/var/www/html/tmp'.$nomfic, "w"); // On crée fichier en fonction du nombre de ligne dans le fichier csv et de la date que l'on place dans le dossier de l'utilisateur de la session
		if($handle)
		{		
			fwrite($handle, $strxml); // On écrit dans le fichier le fichier xml
			fclose($handle);
			libxml_use_internal_errors(true);
			$xml = new DOMDocument();
			
			$xml->load('tmp'.$nomfic);

			if (!$xml->schemaValidate('Commandev1.xsd'))  // Fonction que contrôle les possibles erreurs dans les fichiers xml, si erreur:
			{
				
				$erreur_message .= "Voici les erreurs de type et de syntaxe dans le fichier xml " .$nomfic. ": </br>";

				$listeErreurs=libxml_get_errors();
				
				foreach ($listeErreurs as &$erreur) {
					$erreur_message .= " - ligne ".$erreur->line;
					switch ($erreur->level) 
					{
						case LIBXML_ERR_WARNING:
							$niveau = "Warning $erreur->code ";
							break;
						 case LIBXML_ERR_ERROR:
							$niveau = "Error $erreur->code ";
							break;
						case LIBXML_ERR_FATAL:
							$niveau = "Fatal Error $erreur->code ";
							break;
					}
					$erreur_message .= ". Niveau d'erreur: ".$niveau."</br>";
				}
				
			}
			else // Si pas d'erreurs: 
			{
				
				// Requete permettant de selectionner le lien de depot
				$requete = mysql_query("SELECT pathdepot
										FROM LoginMdp
										WHERE idutilisateur = '".$_SESSION['id']."';") 
										OR die('Erreur de la requête MySQL'); 
				$resultat = mysql_fetch_object($requete);

				$fichierLocal = $resultat->pathdepot.$nomfic;
				rename('tmp'.$nomfic, $resultat->pathdepot.$nomfic); //Enregistre dans le fichier utilisateur	
				
				
				//Requete permettant de selectionner les bons parametres dans la bdd.
				$requete = mysql_query("SELECT host, port, identifiant, motDePasse, fichierDistant
										FROM Parametres
										WHERE idParametre=1;"); 
										
				$resultat = mysql_fetch_object($requete);
				
				//Tous ces paramètres sont ceux qui permettent de se connecter au répertoire distant
				$host = $resultat->host;          
				$port = $resultat->port;                       
				$username = $resultat->identifiant; 
				$password = $resultat->motDePasse;  
				$fichierDistant =$resultat->fichierDistant.$nomfic;
						
				$connection = @ssh2_connect($host, $port); //On se connecte donc au serveur distant			
				$auth = @ssh2_auth_password($connection, $username, $password); //Connexion et authentification au serveur sftp
				$sftp = @ssh2_sftp($connection);  //Initialise le sous-système SFTP
				$sftpStream = @fopen('ssh2.sftp://'.$sftp.$fichierDistant, 'w');//On ouvre le serveur distant

				try { //Si il y a des problèmes dans l'envoi ou dans la connexion, retourne une erreur

					if (!$sftpStream) 
					{
						throw new Exception("Impossible d'ouvrir le fichier distant, veuillez r&eacuteessayer </br>");
						
					}
					
					$data_to_send = @file_get_contents($fichierLocal);
					
					if ($data_to_send === false) 
					{
						throw new Exception("Impossible d'ouvrir le fichier local, veuillez r&eacuteessayer </br>");
					}
					
					if (@fwrite($sftpStream, $data_to_send) === false) 
					{
						throw new Exception("Impossible d'envoyer le fichier local, veuillez r&eacuteessayer");
					}
					
					fclose($sftpStream);
									
				} catch (Exception $e) 
				 {
					error_log('Exception: ' . $e->getMessage());
					$erreur_message.=  $e->getMessage();

				 }

				$handle = @fopen('/var/www/html/tmp'.$nomfic, "w"); // On crée fichier en fonction du nombre de ligne dans le fichier csv et de la date que l'on place dans le dossier de l'utilisateur de la session
				if($handle)
				{
					fwrite($handle, $strxml); // On écrit dans le fichier le fichier xml
					fclose($handle);
				}
				else
				{
					$erreur_message.="Impossible d'ouvrir le fichier, veuillez réessayer plus tard.";
				}
			}
		}
		else
		{
			$erreur_message.="Impossible d'ouvrir le fichier, veuillez réessayer plus tard.";
		}
	}	
	if($erreur_message) //Si il y a une erreur, ajoute à la table Action une conversion ratée et affiche l'erreur dans le tableau de bord
	{
		$nomFichierErreur = "/erreur_".strftime("%d%m%y_%H%M%S").".txt";
		// Requete permettant de selectionner le lien de depot
		$requete = mysql_query("SELECT pathdepot
								FROM LoginMdp
								WHERE idutilisateur = '".$_SESSION['id']."';") 
								OR die('Erreur de la requête MySQL'); 
		$resultat = mysql_fetch_object($requete);
		$fichierLocal = $resultat->pathdepot.$nomFichierErreur;

		//On ouvre le fichier erreur afin de le remplir
		$log = @fopen($fichierLocal,"c+b");
		echo '<div class="container text-center" >
				<div class="d-flex p-2 justify-content-center">	
					<p class="text-dark">'
					.$erreur_message.
					'Fichier XML non g&eacuten&eacuter&eacute</p>
				</div>
			  </div>';
		if($log)
		{

			fwrite($log,$erreur_message);
			
			$lienStocke = 'erreur/'.$nomFichierErreur; //Le chemin vers l'erreur
			
			/*
			Cette requête nous permet de mettre à jour les actions de type conversion en indiquant:
			-La date du jour.
			-le nom du fichier erreur puisque nous sommes dans le cas d'une erreur.
			-le bool reussite que l'on passe à 0 puisque c'est une erreur.	
			-le nom du fichier de base.
			*/
			$requete = mysql_query("UPDATE `Action` 
									SET `date`= '".date("Y-m-d G:i:s")."', 
										`erreur` = '".$nomFichierErreur."',
										`nomfichierbase` = '".$nomFichier."',
										`reussite` = '0'
									WHERE `Action`.`idaction`='".$idAction."';") OR die(mysql_error());
			
			//On supprime toutes les lignes traitées en rapport avec cette action puisque c'est une erreur
			$supprimer=mysql_query("DELETE FROM `LigneTraitee`
									WHERE numeroAction = '".$idAction."'");
		}
		else
		{
			echo '<div class="container text-center" >
					<div class="d-flex p-2 justify-content-center">	
						<p class="text-dark">'
						.$erreur_message.
						'Fichier XML non g&eacuten&eacuter&eacute et fichier erreur non g&eacuten&eacuter&eacute &eacutegalement car il y a eu une erreur.</p>
					</div>
				  </div>'; 
		}

		
	}
	else
	{
		/*
		Cette requête nous permet de mettre à jour les actions de type conversion en indiquant:
		-La date du jour.
		-le nom du nouveau fichier puisque nous sommes dans le cas d'une réussite.
		-le nom du fichier de base.	
		*/
		$requete = mysql_query("UPDATE `Action` 
								SET `date`= '".date("Y-m-d G:i:s")."', 
									`libelle` = '".$nomfic."',
									`nomfichierbase` = '".$nomFichier."'							
								WHERE `Action`.`idaction`='".$idAction."';") OR die(mysql_error());

		
		echo '<div class="container text-center" >
				<div class="d-flex p-2 justify-content-center">
				<p class="text-dark">Les donn&eacutees rentr&eacutees pour g&eacuten&eacuterer le fichier xml sont correctes et ce fichier '.$nomfic.' a &eacutet&eacute plac&eacute dans votre r&eacutepertoire '.$utilisateur.'. </br> Il y a '.$e.' lignes qui ont &eacutet&eacute trait&eacutees.</p>
				</div>
			  </div>
			  </br>';
	}
	mysql_close();
	//On vide le fichier temporaire puisque celui-ci ne sert que pendant la conversion
	array_map('unlink', glob("tmp/".'*')); //Vide le fichier temporaire
}

 
?>
