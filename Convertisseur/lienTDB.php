<?php
// On prolonge la session
session_start();
// On teste si la variable de session existe et contient une valeur
if(empty($_SESSION['login'])) 
{
  // Si inexistante ou nulle, on redirige vers le formulaire de login
  header('location: LienMdp_Utilisateur.php');
  exit();
}
?>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Conversion du document .csv</title>
    </head>
    <body>
			
			<?php  $page="TDB"; include("entete.php"); ?>
			
			<section>
				</br>
				</br>
				</br>
				<?php
					mysql_connect('localhost', 'astre', 'stuGvh5ibMP9IGzr') OR die('Erreur de connexion à la base'); 
					mysql_select_db('Interface_Astre') OR die('Erreur de sélection de la base'); 

					
					
					
					//Requete permettant de selectionner les bons parametres dans la bdd.
					$requete = mysql_query("SELECT host, port, identifiant, motDePasse, fichierDistant, partieRecherche
											FROM Parametres
											WHERE idParametre=1;") 
											OR die(mysql_error()); 
					$resultat = mysql_fetch_object($requete);
					
					//Tous les paramètres pour pouvoir intéragir avec le serveur distant. 
					$repertoireDistant =$resultat->fichierDistant; 
					$host = $resultat->host;          				
					$port = $resultat->port;                       
					$username = $resultat->identifiant; 
					$password = $resultat->motDePasse;  
					$recherche = $resultat->partieRecherche; //C'est le mot à rechercher. En effet, nous allons parcourir tout le répertoire distant à la rechercher des "rapport"
					$requetedeux = mysql_query("SELECT pathdepot
												FROM LoginMdp
												WHERE idutilisateur = '".$_SESSION['id']."';") 
												OR die('Erreur de la requête MySQL'); 
					$fichierDepot = mysql_fetch_object($requetedeux);

					$connection = ssh2_connect($host, $port);
					$auth = @ssh2_auth_password($connection, $username, $password); //Connexion et authentification au serveur sftp
					$sftp = @ssh2_sftp($connection);										
					$resultats = array();
					
					if (is_dir("ssh2.sftp://".$sftp.$repertoireDistant)) //On vérifie si c'est bien un répertoire
					{
						if ($handle = opendir("ssh2.sftp://".$sftp.$repertoireDistant)) //On vérifie si on l'a bien ouvert
						{	
							
							while(($file = readdir($handle)) !== FALSE) //Tant qu'il y a des fichiers, on continue la recherche
							{				
								$raccourci =  strstr($file ,$recherche ); //On cherche si il y a bien le mot recherché: à savoir "rapport"
										
								if($raccourci == "_rapport.xml" || $raccourci == "_rapport.XML") //Si il y a bien rapport, on ajoute à la liste
								{
									$resultats[] = $file;
									
								}			
							}
							closedir($handle); //On ferme le répertoire
						}
						
						
						foreach($resultats as $resultat) //On va lire chaque rapport
						{	
							$handle = "ssh2.sftp://".$sftp.$repertoireDistant."/".$resultat; // On connait donc le chemin des "rapports" à traiter
							
							$handle = fopen($handle, "r"); //On les lit
							
							if ($handle) //Si on l'a bien ouvert...
							{
								
								$numeroLigneChiffre = "";
								$numeroLigne = "";
								
								while (($line = fgets($handle)) !== false) //... on parcourt chaque ligne.
								{
									
									$simple = $line;
									$p = xml_parser_create();
									xml_parse_into_struct($p, $simple, $vals, $index); //permettra de récupérer les données dans les balises xml entre autres
									xml_parser_free($p);
									$clef=array_keys($index);
									
									/* structure attendue dans le fichier retour :
									<cmd:CmdCre xmlns:cmd="http://www.gfi.fr/astre/astgf/Commande" RefExt="LOYCHA_1">
										<cmd:CmdId>392811</cmd:CmdId>
										<cmd:Organisme>CASVP</cmd:Organisme>
										<cmd:Exercice>2019</cmd:Exercice>
										<cmd:Budget>01</cmd:Budget>
										<cmd:NumCmd>C19/017141</cmd:NumCmd>
									  </cmd:CmdCre>

									ATTENTION : codage du numéro de ligne doit LOYCHA_XXXXXXX (7 caractères)*/

									if($clef[0] == "CMD:CMDCRE")
									{
										//Cela nous permet de savoir sur quelle ligne traitee on met le numero de commande
										$numeroLigne=$vals[0]["attributes"]["REFEXT"];
										$numeroLigneChiffre = substr($numeroLigne, 7);
									}
									
									if($clef[0] == "CMD:NUMCMD" && strlen($numeroLigneChiffre) > 0) //Si la ligne que l'on lit correspond au numero de commande, on le recupere
									{
										$numeroCommande = $vals[0]["value"];
										//On le met ensuite dans la bdd
										$requete = mysql_query("UPDATE `LigneTraitee` 
																SET `numeroCommande`= '".$numeroCommande."'
																WHERE `idLigneTraitee`='".$numeroLigneChiffre."';") OR die(mysql_error());
										
									}
								}
								
								fclose($handle); 
								//On met dans la table Action le nom du rapport: Cela nous permettra au final de retrouver notre fichier et de le consulter.
								$requete = mysql_query("UPDATE `Action` 
														SET `retour`= '".$resultat."'
														WHERE idaction=(SELECT numeroAction FROM LigneTraitee WHERE `idLigneTraitee`='".$numeroLigneChiffre."');") OR die(mysql_error());
								
								$fichierLocal = $fichierDepot->pathdepot."/".$resultat;	
								$fichierSFTP_Distant = "ssh2.sftp://".$sftp.$repertoireDistant."/".$resultat;

								
								$fichierSFTP_Distant_src = fopen($fichierSFTP_Distant, 'r');						 //On ouvre le fichier que l'on veut envoyer
								if($fichierSFTP_Distant_src)
								{									
									$fichierLocal_des = fopen($fichierLocal, 'w'); 								 	//On ouvre le fichier que l'on veut remplir
									if($fichierLocal_des)
									{
										$envoi = stream_copy_to_stream($fichierSFTP_Distant_src, $fichierLocal_des); //On remplit le fichier avec ce que l'on a envoyé
										fclose($fichierSFTP_Distant_src); 											 //On referme
										fclose($fichierLocal_des); 													 //On referme
									}
								}
								// aller chercher le nom du fichier en base plutot que faire un traitement de chaine
								$requete = mysql_query("SELECT libelle
														FROM Action
														WHERE idaction=(SELECT numeroAction FROM LigneTraitee WHERE `idLigneTraitee`='".$numeroLigneChiffre."');") OR die(mysql_error());
								$fichierOrigine = mysql_fetch_object($requete); 
								

								if($envoi) //Si le fichier a bien été envoyé, on supprime le fichier sur le serveur distant
								{

									$suppression=ssh2_sftp_unlink( $sftp , $repertoireDistant.$fichierOrigine->libelle );

									$suppression=ssh2_sftp_unlink( $sftp , $repertoireDistant."/".$resultat );

								}
							}
							
						}
					}
			
				?>
				</br>
				<div class="container" style="width:95%">
					<div class="btn-group" role="group">
						<button type="button" onclick="window.location.href = 'lienTDB.php';" class="btn btn-outline-dark btn-sm ">Tout</button>
						<button type="button" onclick="window.location.href = 'lienTDBConnexion.php';" class="btn btn-outline-dark btn-sm">Connexion</button>
						<button type="button" onclick="window.location.href = 'lienTDBConversion.php';" class="btn btn-outline-dark btn-sm">Conversion</button>						
					</div>
					<table class="table table-striped table-bordered table-dark table-hover">
					  <thead>
						<tr>					
						  <th scope="col">n°</th>
						  <th scope="col">Action</th>
						  <th scope="col">Libellé du fichier</th>
						  <th scope="col">Base</th>
						  <th scope="col">Date</th>
						  <th scope="col">Réussite</th>
						  <th scope="col">Retour</th>
						  <th scope="col">Erreur</th>	
						</tr>
					  </thead>
					  <tbody>
						<?php 
							$requete = mysql_query('SELECT Action.idaction, libelleaction, date, libelle, nomfichierbase, reussite, erreur, retour 
													FROM TypeAction INNER JOIN Action ON TypeAction.idaction = Action.numtyp
													WHERE numutilisateur="'.$_SESSION['id'].'" ORDER BY date DESC') 
													OR die('Erreur de la requête MySQL'); 
							 mysql_close();
							 
							 /** 
							 * On récupère les données 
							 * Tant qu'une ligne sera présente, la boucle continuera 
							 */ 
							 $i=1;
							 while($resultat = mysql_fetch_object($requete)) //On affiche les données dans le tableau.
							 { 			
								if($resultat->libelleaction == "conversion")
								{ 							 							 
									if($resultat->reussite == 1)
									{
										$couleur="text-success"; // On met la réussite en vert
										$texteReussite="Oui"; //On met le texte de la réussite qui devient oui 
									}	
									else
									{
										$couleur="text-danger"; // On met la réussite en rouge
										$texteReussite="Non"; //On met le texte de la réussite qui devient non
									}
								}	
									$a = $resultat->date;
									$b = date_create($a);
									$c = date_format($b, 'd/m/Y');
								  echo ' 
									<tr>
									  <th scope="row" style="width:1%">'.$i.'</th>
									  <td>'.$resultat->libelleaction.'</td> 								  
									  <td>'.$resultat->libelle.'</td>									  									  
									  <td style="width:10%" >'.$resultat->nomfichierbase.'</td>																		  
									  <td style="width:20%">'.$c.'</td>
									  <td class="'.$couleur.'">'.$texteReussite.'</td>
									  <td><a href="lireRapport.php?rapport='.$resultat->retour.'">'.$resultat->retour.'</a></td>
									  <td><a href="lireErreur.php?erreur='.$resultat->erreur.'">'.$resultat->erreur.'</a></td>
									</tr>	
								  ';								  
								  $i++;
								  $couleur=""; // On remet à zero pour les suivants
								  $texteReussite=""; //On remet à zero pour les suivants 
							 }  
						?>	
					  
						
					  </tbody>
					</table>
				</div>

			</section>
	

</html>





















