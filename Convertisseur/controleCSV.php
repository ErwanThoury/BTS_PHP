<?php
/*
Parametres:
-$input_file est une variable de type chaîne de caractères désignant le chemin du fichier à convertir
-$convertir est une variable de type bool qui indique si le fichier à vocation à être converti ou juste vérifié
-$idAction est une variable de type entier qui permet d'indiquer quel est le numéro de l'action en cours

Retours: 
-$erreur_traitement est une variable de type bool qui permettra d'indiquer si il y a eu une erreur dans le traitement ou non
-$erreur_message est une variable de type chaîne de caractères qui indique s'il y a une erreur l'erreur en question.  
-$doc est une variable qui correspond au fichier XML que l'on a rempli
-$nomFichier est une varaible de type chaîne de caractères qui permet de retenir le nom de l'ancien fichier
-$date est une variable de type chaîne de caractères qui retourne la date
-$e est une variable de type entier qui retourne le nombre de lignes traitées
-$numeroAjouterBDD est une variable de type table qui contient toutes les informations à update pour les lignes traitées

Cette fonction permet donc de convertir un fichier .csv en .xml.
*/
function convertCsvToXmlFileR($input_file, $convertir, $idAction) // Fonction qui permet de convertir le tableau excel/csv en xml, de le corriger puis de le télécharger
{
	$numeroAjouterBDD=[];
	$cptr = 0;
	$erreur_message="";
	$doc = "";
	$nomFichier = ""; 
	$date = "";
	$e = "";
	// On ouvre le fichier csv pour le lire
	$inputFile  = fopen($input_file, 'rt'); 
	if($inputFile == false)
	{
		$erreur_message = "Le fichier n'a pas pu être chargé, veuillez réessayer ";
		$erreur_traitement = true;
		return array($erreur_traitement, $erreur_message, $doc, $nomFichier, $date, $e, $numeroAjouterBDD);
	}
	$erreur_traitement = false;
	$nomFichier = basename ($input_file , ".csv" ).PHP_EOL; //On récupère nom du fichier de base à convertir
	
	
	// Titre ligne 1
	$titre = fgetcsv($inputFile,2000,";");
	
	// Correspondance balise ligne 2
	$correspondance = fgetcsv($inputFile,2000,";");
	
	// On prend les headers du fichier csv en ligne 3
	$headers = fgetcsv($inputFile,2000,";");
	
	// On vérifie que l'on a bien le bon nombre de colonne dans la maquette excel (pour loyers et charges ici) 
	if (count($headers) != 38) 
    	{
		$erreur_message = "Veuillez charger le bon fichier .csv . Il s'agit du fichier excel avec 38 colonnes sur les loyers et charges. ";
		$erreur_traitement = true;
		return array($erreur_traitement, $erreur_message, $doc, $nomFichier, $date, $e, $numeroAjouterBDD);
		}
	// On sépare les Headers ------------------------- COMMENTER OU DECOMMENTER HEADERS EN FONCTION DE LA MAQUETTE QUE L'ON VEUT UTILISER ---------------------------

	
	$ContexteHeaders = array($headers[0],$headers[1],$headers[2],$headers[3]);
	$DetailHeaders = array($headers[4],$headers[5],$headers[6],$headers[7],$headers[8],$headers[9],$headers[10],$headers[11],$headers[12],$headers[13],$headers[14],$headers[15],$headers[16]);
	$LignesHeaders = array($headers[17], $headers[18],$headers[19],$headers[20],$headers[21]);
	$VentilationsCPLoyers = array($headers[22], $headers[23],$headers[24],$headers[25],$headers[26]);
	$ChargesHeaders = array($headers[27], $headers[28],$headers[29],$headers[30],$headers[31]);
	$VentilationsCPCharges = array($headers[32], $headers[33],$headers[34],$headers[35],$headers[36]);
	$VentilationsAP = array();
	
	// ---------------------------------- ON PEUT AUSSI CREER DE NOVUELLES MAQUETTE EN COMPTANT BIEN LES HEADERS -----------------------------------------------------
	

	// ON VERIFIE QUE LES ENTETES DU FICHIER CSV SONT BONNES ET BIEN PLACEES
	
	foreach($ContexteHeaders as $i => $ContexteHeader) // On lit les balises du bloc Contexte
		{
			
			$place=$i+1;
			switch ($ContexteHeader) {
			
			case "Utilisateur":
				if ($i != 0)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $ContexteHeader . " mal plac&eacutee à la colonne " . $place . ". </br>";	
				}
				
				break;
				
			case "Organisme":
				if ($i != 1)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $ContexteHeader . " mal plac&eacutee à la colonne " + $place . ". </br>";
				}
				
				break;
				
			case "Exercice":
				if ($i != 2)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $ContexteHeader . " mal plac&eacutee à la colonne " . $place . ". </br>";
				}
				
				break;
				
			case "Budget":
				if ($i != 3)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $ContexteHeader . " mal plac&eacutee à la colonne " . $place . ". </br>";
				}
			
				break;
				
			default :  // valeur de balise inconnu donc on génère une erreur
				$erreur_traitement=TRUE;
				$erreur_message .= " Balise " . $ContexteHeader . " inconnue dans le bloc Contexte. </br>";
				break;                  
			}
		}
	
	foreach($DetailHeaders as $j => $DetailHeader) // On lit les balises du bloc DetailCmd
		{
			$place=count($ContexteHeaders)+$j+1;
			
			switch ($DetailHeader) {

			case "CodTypCmd" :
				if ($j != 0)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";			
				}
								
				break;

			case "Objet" :
				if ($j != 1)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "Tiers":
				if ($j != 2)
				{
					$erreur_traitement=TRUE;
					$erreur_message.= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;

			case "CodAdr":
			if ($j != 3)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "SerDem":
				if ($j != 4)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";				}
								
				break;
				
			case "ActeurDem":
				if ($j != 5)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "SerDes":
				if ($j != 6)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "SerGes":
				if ($j != 7)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "TypCmd":
				if ($j != 8)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "TypEngCmd":
				if ($j != 9)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "StaCmd":
				if ($j != 10)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MultiBudg":
				if ($j != 11)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "AdrSerGes":
				if ($j != 12)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $DetailHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
					
				}
				
				break;
			 
			 default :  // valeur de balise inconnu donc on génère une erreur
				$erreur_traitement=TRUE;
				$erreur_message .= " Balise " . $DetailHeader . " inconnue dans le bloc DetailCmd. </br>";
				break;                     
			}
		}
		
	foreach($LignesHeaders as $m => $LigneHeader) // On parcour les headers de lignes
		{
			$place=count($ContexteHeaders)+count($DetailHeaders)+$m+1;
			switch ($LigneHeader) {
			
			case "Designation":
				if ($m != 0)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $LigneHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "Prix":
				if ($m != 1)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $LigneHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntHtLig":
				if ($m != 2)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $LigneHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntTvaLig":
				if ($m != 3)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $LigneHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntTtcLig":
				if ($m != 4)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $LigneHeader . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
				
				break;
			
			default :  // valeur de balise inconnu donc on génère une erreur
				$erreur_traitement=TRUE;
				$erreur_message .= " Balise " . $LigneHeader . " inconnue dans le bloc LignesLoyers. </br>";				
				break;                   
			}
		}
	
	foreach($VentilationsCPLoyers as $a => $VentilationCPLoyer) // On lit les balises du bloc VentilationsCP
		{
			$place=count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+$a+1;
			switch ($VentilationCPLoyer) {
				
			case "LigneCredit":
				if ($a != 0)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";

				}
								
				break;
				
			case "TypeNomenc":
				if ($a != 1)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "CodeNomenc":
				if ($a != 2)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntHt":
				if ($a != 3)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntTtc":
				if ($a != 4)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
				
				break;
			
			default :  // valeur de balise inconnu donc on génère une erreur
				$erreur_traitement=TRUE;
				$erreur_message .= " Balise " . $VentilationCPLoyer . " inconnue dans le bloc VentilationsCPLoyers. </br>";
				break; 																																					
			}
		}
	
	foreach($ChargesHeaders as $m => $ChargeHeader) // On parcour les headers de lignes
		{
			$place=count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+$m+1;
			switch ($ChargeHeader) {
			
			case "Designation":
				if ($m != 0)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise " . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "Prix":
				if ($m != 1)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntHtLig":
				if ($m != 2)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntTvaLig":
				if ($m != 3)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntTtcLig":
				if ($m != 4)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
				
				break;
			
			default :  // valeur de balise inconnu donc on génère une erreur
				$erreur_traitement=TRUE;
				$erreur_message .= " Balise" . $ChargeHeader . " inconnue dans le bloc LignesCharges. </br>";
				break;                   
			}
		}
	
	foreach($VentilationsCPCharges as $a => $VentilationCPCharge) // On lit les balises du bloc VentilationsCP
		{
			$place=count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($ChargesHeaders)+$a+1;
			switch ($VentilationCPCharge) {
				
			case "LigneCredit":
				if ($a != 0)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "TypeNomenc":
				if ($a != 1)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "CodeNomenc":
				if ($a != 2)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" + $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntHt":
				if ($a != 3)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
								
				break;
				
			case "MntTtc":
				if ($a != 4)
				{
					$erreur_traitement=TRUE;
					$erreur_message .= " Balise" . $VentilationCPLoyer . " mal plac&eacutee, colonne " . $place . ". </br>";
				}
				
				break;
			
			default :  // valeur de balise inconnu donc on génère une erreur
				$erreur_traitement=TRUE;
				$erreur_message .= " Balise" . $VentilationCPLoyer . " inconnue dans le bloc VentilationsCPCharges. </br>";				

					
				break; 																																					
			}
		}
	if ($erreur_traitement) return array($erreur_traitement, $erreur_message, $doc, $nomFichier, $date, $e, $numeroAjouterBDD);
	

	$date = date("H_i"); // Variable qui donne heure
	$zip = new ZipArchive(); // On crée une nouvelle archive zip
	$filenamezip = 'tmp/XML_upload.zip'; // Le nom du fichier zip
		
	$e = 0; // Compteur pour nommer les fichiers en fotion de la ligne du fichier excel
	$f=0; // Compteur de nombre de fichiers contenant des erreurs
	
	// CAS d'un fichier unique
	
	//                                    $erreur_traitement = false;
	
	// On crée un nouveau document 
	$doc = new DomDocument('1.0','utf-8');
	$doc -> formatOutput = true;
		
	$roottop = $doc->createElement('cmd:Commandes'); // On crée principale balise
	$domAttribute1 = $doc->createAttribute('xmlns:cmd'); // On crée des attributs 
	$domAttribute2 = $doc->createAttribute('xmlns:xsi'); // On crée des attributs 
	$domAttribute3 = $doc->createAttribute('xsi:schemaLocation');// On crée des attributs
	$domAttribute1->value = 'http://www.gfi.fr/astre/astgf/Commande'; // On donne la valeur de l'attribut 1
	$domAttribute2->value = 'http://www.w3.org/2001/XMLSchema-instance'; // On donne la valeur de l'attribut 2
	$domAttribute3->value = 'http://www.gfi.fr/astre/astgf/Commande Commandes.xsd'; // On donne la valeur de l'attribut 3
	$roottop->appendChild($domAttribute1); // On ajoute l'attribut à la balise
	$roottop->appendChild($domAttribute2); // On ajoute l'attribut à la balise
	$roottop->appendChild($domAttribute3); // On ajoute l'attribut à la balise
	$doc->appendChild($roottop); // On ajoute la balise 'cmd:Commandes' au document

	while (($row = fgetcsv($inputFile,2000,";")) !== FALSE) // On boucle sur chaque ligne du fichier csv
	{
		// on ne traite que les lignes non vides
		
		if(strlen($row[0]) > 0)
		{
		
		/* $erreur_traitement = false;
		
		// On crée un nouveau document 
		$doc = new DomDocument('1.0','utf-8');
		$doc -> formatOutput = true; */
		
		// tu inserts une ligne dans la base si tu es en conversion
		if($convertir == true)
		{
			$requete = mysql_query("INSERT INTO `Interface_Astre`.`LigneTraitee` (`idLigneTraitee`, `numeroAction`, `numeroCommande`) 
								VALUES (NULL, '".$idAction."', '0');") OR die(mysql_error());
			$cptr=mysql_insert_id();
		}
		// tu récupères l'id généré
		
		$root = $doc->createElement('cmd:Commande'); // On crée principale balise
		
		$roottop->appendChild($root); // On ajoute la balise 'cmd:Commande' au document

		
		$containerContexte = $doc->createElement('cmd:Contexte');
		
		foreach($ContexteHeaders as $i => $ContexteHeader) // On lit les balises du bloc Contexte
		{		
			switch ($ContexteHeader) {
						 
			case "Budget" : // Quand on rencontre la balise Budget dans le fichiers csv on vérifie le codage 
			
				$child = $doc->createElement('cmd:'.$ContexteHeader); // On crée les balises 
				$child = $containerContexte->appendChild($child); // Que l'on met dans le bloc Contexte
				$value = $doc->createTextNode($row[$i]); // On crée l'élément correspondant

				if (strlen($row[$i]) == 1) $value = $doc->createTextNode('0'.$row[$i]);
				else $value = $doc->createTextNode(trim($row[$i])); // On crée l'élément correspondant

				$value = $child->appendChild($value); // On le place dans la balise
			
			break;
			
			default : 
			
				$child = $doc->createElement('cmd:'.$ContexteHeader); // On crée les balises 
				$child = $containerContexte->appendChild($child); // Que l'on met dans le bloc Contexte
				$value = $doc->createTextNode(trim($row[$i])); // On crée l'élément correspondant
				$value = $child->appendChild($value); // On le place dans la balise
				
			break;
			}
		}
   
		$root->appendChild($containerContexte); // On ferme balise contexte que l'on ajoute à la racine ( balise Commande )
		$containerDetail = $doc->createElement('cmd:DetailCmd'); // On crée balise DetailCmd
		

		
		foreach($DetailHeaders as $j => $DetailHeader) // On lit les balises du bloc DetailCmd
		{
			if ($row[$j+count($ContexteHeaders)] != "") // Si on a une donnée à chaque colonne (dans bloc DetailCmd)
				
			switch ($DetailHeader) {
					 
			case "Tiers" : // Quand on rencontre la balise Tiers dans le fichiers csv 
		
				
				$sql_Tiers = 'SELECT EXISTS (SELECT * FROM Tiers WHERE Numero="' . $row[$j+count($ContexteHeaders)] . '" ) AS Num_Tiers'; // Requête sql
				
				$result_Tiers = mysql_query($sql_Tiers); // Teste la requête SQL pour savoir si le Tiers est bien dans la BDD
				
				$req_Tiers = mysql_fetch_array($result_Tiers); // On affiche le résultat de la requête précédente. Renvoi True ou False
				
				if ($req_Tiers['Num_Tiers'] == true) // Si le Tiers existe, on remplie les balises normalement
				{ 
					$child = $doc->createElement('cmd:'.$DetailHeader); // On crée la balise Tiers 
					$child = $containerDetail->appendChild($child); // Que l'on met dans le bloc DetailCmd
					$value = $doc->createTextNode(trim($row[$j+count($ContexteHeaders)])); // On crée l'élément correspondant
					$value = $child->appendChild($value); // On le place dans la balise Tiers
				} 
				else // Si le Tiers n'hexiste pas
				{ 
					$erreur_traitement = true;
					$erreur_message .= "Tiers inconnu."."</br>";
				}
			
				break;					
				
			case "SerDem" : // Quand on rencontre la balise SerDem dans le fichiers csv

				$sql_SerDem = $row[$j+count($ContexteHeaders)]; // On retient la valeur de l'élément de la balise SerDem pour le contrôle avec la Ligne de credit
				$sqlExist_SerDem = 'SELECT EXISTS (SELECT * FROM Imputations WHERE ServiceDemandeur="' . $row[$j+count($ContexteHeaders)] . '") AS Ser_Dem'; // Requête sql
				$result_SerDem = mysql_query($sqlExist_SerDem); // Teste la requête SQL pour savoir si le Service Demandeur est bien dans la BDD
				$req_SerDem = mysql_fetch_array($result_SerDem); // On affiche le résultat de la requête précédente. Renvoi True ou False
				if ($req_SerDem['Ser_Dem'] == true) // Si le Service Demandeur existe, on remplie les balises normalement
				{ 
					$child = $doc->createElement('cmd:'.$DetailHeader); // On crée la balise SerDem
					$child = $containerDetail->appendChild($child); // Que l'on met dans le bloc DetailCmd
					$value = $doc->createTextNode(trim($row[$j+count($ContexteHeaders)])); // On crée l'élément correspondant
					$value = $child->appendChild($value); // On le place dans la balise SerDem
				} 
				else // Si le Service Demandeur n'existe pas
				{ 
					$erreur_traitement = true;
					$erreur_message .= "Service Demandeur inconnu."."</br>";
				}
				
				break;					
				
			case "SerGes" : // On vérifie que le Service Gestionnaire existe bien
				
				$sql_SerGes = $row[$j+count($ContexteHeaders)]; // On retient la valeur de l'élément de la balise SerGes pour le contrôle avec la Ligne de credit
				$sqlExist_SerGes = 'SELECT EXISTS (SELECT * FROM Imputations WHERE ServiceGestionnaire="' . $row[$j+count($ContexteHeaders)] . '") AS Ser_Ges';
				$result_SerGes = mysql_query($sqlExist_SerGes);
				$req_SerGes = mysql_fetch_array($result_SerGes); // Même cheminement que pour le Serive Demandeur
				if ($req_SerGes['Ser_Ges'] == true)
				{ 
					$child = $doc->createElement('cmd:'.$DetailHeader);
					$child = $containerDetail->appendChild($child);
					$value = $doc->createTextNode(trim($row[$j+count($ContexteHeaders)])); // Même cheminement que pour le Service Demandeur
					$value = $child->appendChild($value);
				} 
				else 
				{ 
					$erreur_traitement = true;
					$erreur_message .= "Service Gestionnaire inconnu."."</br>";
				}
				
				break;	
					
			 case "CodTypCmd":
				$child = $doc->createElement('cmd:'.$DetailHeader);
				$child = $containerDetail->appendChild($child);
				$value = $doc->createTextNode(trim($row[$j+count($ContexteHeaders)]));
				$value = $child->appendChild($value);
				

				$numeroAjouterBDD[]=$cptr; 
				$child = $doc->createElement('cmd:Numero');
				$child = $containerDetail->appendChild($child);
				$value = $doc->createTextNode("LOYCHA_".$cptr);
				$value = $child->appendChild($value);

				
				break;

			 case "Objet":
			 case "CodAdr":
			 case "ActeurDem":
			 case "SerDes":
			 case "TypCmd":
			 case "TypEngCmd":
			 case "StaCmd":
			 case "MultiBudg":
			 case "AdrSerGes":
			 
				$child = $doc->createElement('cmd:'.$DetailHeader);
				$child = $containerDetail->appendChild($child);
				$value = $doc->createTextNode(trim($row[$j+count($ContexteHeaders)]));
				$value = $child->appendChild($value);

				break;	
				
			}

		}		
		
		
		
		$root->appendChild($containerDetail);

		$containerLignes = $doc->createElement('cmd:Lignes'); // On crée bloc lignes
		$containerLigne = $doc->createElement('cmd:Ligne'); // On crée bloc ligne
		$containerLignes->appendChild($containerLigne); // On place bloc ligne dans bloc lignes
		$root->appendChild($containerLignes); // On place bloc lignes dans la racine ( bloc commande )
			
		foreach($LignesHeaders as $m => $LigneHeader) // On parcour les headers de lignes
		{			
			$child = $doc->createElement('cmd:'.$LigneHeader);
			$child = $containerLigne->appendChild($child);            // Sinon on boucle normalement dans le bloc ligne
			$value = $doc->createTextNode(trim($row[$m+count($ContexteHeaders)+count($DetailHeaders)]));
			$value = $child->appendChild($value);
		}
		
		$rowVentilationsCPLoyers = array();
		$erreurs_ventilationsCPloyers=array();
		for ($p = 0; $p < count($VentilationsCPLoyers); $p++)
		{
			$rowVentilationsCPLoyers[$p]= $row[$p + count($ContexteHeaders) + count($DetailHeaders) + count($LignesHeaders)]; // On regarde s'il y a au moins une donnée dans la balise $VentilationsCP
		}

		if (my_empty($rowVentilationsCPLoyers) == false)  // On vérifie que l'on a bien des données dans la balise VentilationsCP: s'il n'y en a pas, on ne l'a crée pas
		{
			// Sinon on la crée et on la remplie
			$containerVentilationsCP = $doc->createElement('cmd:VentilationsCP'); // On crée bloc VentilationsCP
		   $containerVentCP = $doc->createElement('cmd:VentCP'); // On crée bloc VentCP
		   $containerVentilationsCP->appendChild($containerVentCP); // On place bloc VentCP dans bloc VentilationsCP
		   $root->appendChild($containerVentilationsCP); // On place le bloc VentilationsCP dans le bloc Commande
			
			
			$NumResCPLoyer=0;
			$TypeNomencCPLoyer=0;
			$MilEngapCPLoyer=0;
			$OperationCPLoyer=0;
			foreach($VentilationsCPLoyers as $a => $VentilationCPLoyer) // On lit les balises du bloc VentilationsCP
			{
				
				switch ($VentilationCPLoyer) {
					
				case "LigneCredit":
				
					$sql_LigneCreditLoyer = 'SELECT EXISTS (SELECT * FROM Imputations WHERE Usages = "Loyer" AND ServiceDemandeur = "' .$sql_SerDem. '" AND ServiceGestionnaire = "' .$sql_SerGes. '" AND LigneDeCredit = "' .$row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]. '") AS Ligne_Credit_Loyer'; // Requête sql
					$result_LigneCreditLoyer = mysql_query($sql_LigneCreditLoyer); // Execute la requete pour savoir si on a bien pour une ligne de credit le bon service demandeur et le bon service gestionnaire
					$req_LigneCreditLoyer = mysql_fetch_array($result_LigneCreditLoyer); // Affiche True ou False
					if ($req_LigneCreditLoyer['Ligne_Credit_Loyer'] == True) // Si la requete renvoie true
					{ 
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $containerVentCP->appendChild($child); // On boucle normalement
						$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
						$value = $child->appendChild($value);
					} 
					else // Sinon on crée volontairement une erreur qui sera signalé lors du contrôle
					{ 
						$erreur_traitement = true;
						$erreur_message .= "Ligne de cr&eacutedit pour les loyers ne correspond pas aux services, ligne " .$e.'</br>';
					}
					
					break;
				
				case "NumRes":
				
					$child_mem_ResCredit = $doc->createElement('cmd:ResCredit');
					$containerVentCP->appendChild($child_mem_ResCredit);
					$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
					$child = $child_mem_ResCredit->appendChild($child);
					$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
					$value = $child->appendChild($value);
					$NumResCPLoyer = $value;
					
					break;
				
				case "NumLigRes":
				
					if ($NumResCPLoyer === 0)
					{
						$child_mem_ResCredit = $doc->createElement('cmd:ResCredit');
						$containerVentCP->appendChild($child_mem_ResCredit);
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $child_mem_ResCredit->appendChild($child);
						$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
						$value = $child->appendChild($value); 
					}
					else
					{
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $child_mem_ResCredit->appendChild($child);
						$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
						$value = $child->appendChild($value);
					}
					
					break;
				
				case "TypeNomenc":
				
					$child_mem_NomencMarCP = $doc->createElement('cmd:NomencMar');
					$containerVentCP->appendChild($child_mem_NomencMarCP);
					$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
					$child = $child_mem_NomencMarCP->appendChild($child);
					$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
					$value = $child->appendChild($value);
					$TypeNomencCPLoyer = $value;
					
					break;
					
				case "CodeNomenc":
					
					if ($TypeNomencCPLoyer === 0)
					{
						$child_mem_NomencMarCP = $doc->createElement('cmd:NomencMar');
						$containerVentCP->appendChild($child_mem_NomencMarCP);
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $child_mem_NomencMarCP->appendChild($child);
						$aa = trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]);
						if (strlen($aa) == 1 && intval ($aa) < 10) $aa = '0'.$aa;
						$value = $doc->createTextNode($aa);
						$value = $child->appendChild($value);
					}
					else
					{
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $child_mem_NomencMarCP->appendChild($child);
						$aa = trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]);
						if (strlen($aa) == 1 && intval ($aa) < 10) $aa = '0'.$aa;
						$value = $doc->createTextNode($aa);
						$value = $child->appendChild($value);
					}
					
					break;
					
				case "MilEngap":
				
					$child_mem_EngAP = $doc->createElement('cmd:EngAP');
					$containerVentCP->appendChild($child_mem_EngAP);
					$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
					$child = $child_mem_EngAP->appendChild($child);
					$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
					$value = $child->appendChild($value);
					$MilEngapCPLoyer = $value;
					
					break;
					
				case "NumEngap":
				
					if($MilEngapCPLoyer === 0)
					{
						$child_mem_EngAP = $doc->createElement('cmd:EngAP');
						$containerVentCP->appendChild($child_mem_EngAP);
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $child_mem_EngAP->appendChild($child);
						$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
						$value = $child->appendChild($value); 
					}
					else
					{
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $child_mem_EngAP->appendChild($child);
						$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
						$value = $child->appendChild($value);
					}
					
					break;
					
				case "Operation":
				
					$child_mem_OperationNatureCP = $doc->createElement('cmd:OperationNature');
					$containerVentCP->appendChild($child_mem_OperationNatureCP);
					$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
					$child = $child_mem_OperationNatureCP->appendChild($child);
					$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
					$value = $child->appendChild($value);
					$OperationCPLoyer = $value;
					
					break;
					
				case "OpeNat":
				
					if($OperationCPLoyer === 0)
					{
						$child_mem_OperationNatureCP = $doc->createElement('cmd:OperationNature');
						$containerVentCP->appendChild($child_mem_OperationNatureCP);
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $child_mem_OperationNatureCP->appendChild($child);
						$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
						$value = $child->appendChild($value);
					}
					else
					{
						$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
						$child = $child_mem_OperationNatureCP->appendChild($child);
						$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
						$value = $child->appendChild($value);
					}
					
					break;
				
				case "MntHt":
				case "MntTtc":
				
					$child = $doc->createElement('cmd:'.$VentilationCPLoyer);
					$child = $containerVentCP->appendChild($child);
					$value = $doc->createTextNode(trim($row[$a+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)]));
					$value = $child->appendChild($value);
					
					break;																																				
				}

			}
		}
					
			
		$rowChargesHeaders = array();
		$erreurs_charges=array();
		for ($c = 0; $c < count($ChargesHeaders); $c++)
		{
			$rowChargesHeaders[$c]= $row[$c + count($ContexteHeaders) + count($DetailHeaders) + count($LignesHeaders) + count($VentilationsCPLoyers) + count($VentilationsAP)]; // On regarde s'il existe bien une donnée dans la balise $Charges
		}
		
		if (my_empty($rowChargesHeaders) != true) // On vérifie que l'on a bien des données dans la balise Charges: s'il n'y en a pas, on ne met pas les données dans le fichier xml ( sera utilse pour maquette autre que Loyer et Charge )
		{
			$containerLigne = $doc->createElement('cmd:Ligne');
			$containerLignes->appendChild($containerLigne);
			
			foreach($ChargesHeaders as $x => $ChargeHeader)
			{
				
				$child = $doc->createElement('cmd:'.$ChargeHeader);
				$child = $containerLigne->appendChild($child);
				$value = $doc->createTextNode(trim($row[$x+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)]));
				$value = $child->appendChild($value);

			}
		}
		
		$rowVentilationsCPCharges = array();
		$erreurs_ventilationsCPcharges=array();
		for ($s = 0; $s < count($VentilationsCPCharges); $s++)
		{
			$rowVentilationsCPCharges[$s]= $row[$s +count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]; // On regarde s'il y a au moins une donnée dans la balise $VentilationsCPCharges
		}

		if (my_empty($rowVentilationsCPCharges) == false)  // On vérifie que l'on a bien des données dans la balise VentilationsCPCharges: s'il n'y en a pas, on ne l'a crée pas
		{

		   $containerVentCP = $doc->createElement('cmd:VentCP');
		   $containerVentilationsCP->appendChild($containerVentCP);

			$NumResCPCharge=0;
			$TypeNomencCPCharge=0;
			$MilEngapCPCharge=0;
			$OperationCPCharge=0;
			foreach($VentilationsCPCharges as $l => $VentilationCPCharge) // On lit les balises du bloc VentilationsCP
			{
				switch ($VentilationCPCharge) {
					
				case "LigneCredit":
				
					$sql_LigneCreditCharge = 'SELECT EXISTS (SELECT * FROM Imputations WHERE Usages = "Charges" AND ServiceDemandeur = "' .$sql_SerDem. '" AND ServiceGestionnaire = "' .$sql_SerGes. '" AND LigneDeCredit = "' .$row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders) + count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]. '") AS Ligne_Credit_Charge'; // Requête sql
					$result_LigneCreditCharge = mysql_query($sql_LigneCreditCharge); // Execute la requete pour savoir si on a bien pour une ligne de credit le bon service demandeur et le bon service gestionnaire
					
					$req_LigneCreditCharge = mysql_fetch_array($result_LigneCreditCharge); // Affiche True ou False
					if ($req_LigneCreditCharge['Ligne_Credit_Charge'] == True) // Si la requete renvoie true
					{ 
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $containerVentCP->appendChild($child); // On boucle normalement
						$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
						
						$value = $child->appendChild($value);
					} 
					else // Sinon on crée volontairement une erreur qui sera signalé lors du contrôle
					{ 
						$erreur_traitement = true;
						
						$erreur_message .= "Ligne de cr&eacutedit pour les charges ne correspond pas aux services, ligne " .$e.'</br>';
					}
					
					break;
				
				case "NumRes":
				
					$child_mem_ResCredit = $doc->createElement('cmd:ResCredit');
					$containerVentCP->appendChild($child_mem_ResCredit);
					$child = $doc->createElement('cmd:'.$VentilationCPCharge);
					$child = $child_mem_ResCredit->appendChild($child);
					$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
					$value = $child->appendChild($value);
					$NumResCPCharge = $value;
					
					break;
				
				case "NumLigRes":
				
					if ($NumResCPCharge	=== 0)
					{
						$child_mem_ResCredit = $doc->createElement('cmd:ResCredit');
						$containerVentCP->appendChild($child_mem_ResCredit);
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $child_mem_ResCredit->appendChild($child);
						$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
						$value = $child->appendChild($value); 
					}
					else
					{
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $child_mem_ResCredit->appendChild($child);
						$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
						$value = $child->appendChild($value);
					}
					
					break;
				
				case "TypeNomenc":
				
					$child_mem_NomencMarCP = $doc->createElement('cmd:NomencMar');
					$containerVentCP->appendChild($child_mem_NomencMarCP);
					$child = $doc->createElement('cmd:'.$VentilationCPCharge);
					$child = $child_mem_NomencMarCP->appendChild($child);
					$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
					$value = $child->appendChild($value);
					$TypeNomencCPCharge = $value;
					
					break;
					
				case "CodeNomenc":
					
					if ($TypeNomencCPCharge === 0)
					{
						$child_mem_NomencMarCP = $doc->createElement('cmd:NomencMar');
						$containerVentCP->appendChild($child_mem_NomencMarCP);
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $child_mem_NomencMarCP->appendChild($child);
						$a = trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]);
						if (strlen($a) == 1 && intval ($a) < 10) $a = '0'.$a;
						$value = $doc->createTextNode($a);
						$value = $child->appendChild($value);
					}
					else
					{
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $child_mem_NomencMarCP->appendChild($child);
						$a = trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]);
						if (strlen($a) == 1 && intval ($a) < 10) $a = '0'.$a;
						$value = $doc->createTextNode($a);
						$value = $child->appendChild($value);
					}
					
					break;
					
				case "MilEngap":
				
					$child_mem_EngAP = $doc->createElement('cmd:EngAP');
					$containerVentCP->appendChild($child_mem_EngAP);
					$child = $doc->createElement('cmd:'.$VentilationCPCharge);
					$child = $child_mem_EngAP->appendChild($child);
					$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
					$value = $child->appendChild($value);
					$MilEngapCPCharge = $value;
					
					break;
					
				case "NumEngap":
				
					if($MilEngapCPCharge === 0)
					{
						$child_mem_EngAP = $doc->createElement('cmd:EngAP');
						$containerVentCP->appendChild($child_mem_EngAP);
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $child_mem_EngAP->appendChild($child);
						$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
						$value = $child->appendChild($value); 
					}
					else
					{
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $child_mem_EngAP->appendChild($child);
						$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
						$value = $child->appendChild($value);
					}
					
					break;
					
				case "Operation":
				
					$child_mem_OperationNatureCP = $doc->createElement('cmd:OperationNature');
					$containerVentCP->appendChild($child_mem_OperationNatureCP);
					$child = $doc->createElement('cmd:'.$VentilationCPCharge);
					$child = $child_mem_OperationNatureCP->appendChild($child);
					$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
					$value = $child->appendChild($value);
					$OperationCPCharge = $value;
					
					break;
					
				case "OpeNat":
				
					if($OperationCPCharge === 0)
					{
						$child_mem_OperationNatureCP = $doc->createElement('cmd:OperationNature');
						$containerVentCP->appendChild($child_mem_OperationNatureCP);
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $child_mem_OperationNatureCP->appendChild($child);
						$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
						$value = $child->appendChild($value);
					}
					else
					{
						$child = $doc->createElement('cmd:'.$VentilationCPCharge);
						$child = $child_mem_OperationNatureCP->appendChild($child);
						$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
						$value = $child->appendChild($value);
					}
					
					break;
				
				case "MntHt":
				case "MntTtc":
				
					$child = $doc->createElement('cmd:'.$VentilationCPCharge);
					$child = $containerVentCP->appendChild($child);
					$value = $doc->createTextNode(trim($row[$l+count($ContexteHeaders)+count($DetailHeaders)+count($LignesHeaders)+count($VentilationsCPLoyers)+count($VentilationsAP)+count($ChargesHeaders)]));
					$value = $child->appendChild($value);
					
					break;
																																										
				}
			}
		}
		
		$e++;
		echo '</br>';
	}
	}
	return array($erreur_traitement, $erreur_message, $doc, $nomFichier, $date, $e, $numeroAjouterBDD);
}	
?>