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
			<?php $page="TDB"; include("entete.php"); ?>
			<section>
				</br>
				</br>
				</br>
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
							 mysql_connect('localhost', 'astre', 'stuGvh5ibMP9IGzr') OR die('Erreur de connexion à la base'); 
							 mysql_select_db('Interface_Astre') OR die('Erreur de sélection de la base'); 

							 $requete = mysql_query('SELECT Action.idaction, libelleaction, date, libelle, nomfichierbase, reussite, erreur, retour
													 FROM TypeAction INNER JOIN Action ON TypeAction.idaction = Action.numtyp 
													 WHERE numutilisateur="'.$_SESSION['id'].'" AND numtyp="1" ORDER BY date DESC') 					 
													 OR die('Erreur de la requête MySQL'); 

							 mysql_close(); 

							 /** 
							 * On récupère les données 
							 * Tant qu'une ligne sera présente, la boucle continuera 
							 */ 
							 $i=1;
							 while($resultat = mysql_fetch_object($requete)) //On affiche les données dans le tableau.
							 { 
								if($resultat->reussite == 1)
								{
									$couleur="text-success"; // On met la réussite en vert
									$texteReussite="Oui"; //On met le texte de la réussite qui devient oui 
									
								}	
								else
								{
									$couleur="text-danger"; // On met la réussite en vert
									$texteReussite="Non"; //On met le texte de la réussite qui devient oui 
								}
								$a = $resultat->date;
								$b = date_create($a);
								$c = date_format($b, 'd/m/Y');
								  echo '
								  
								  
									<tr>
									  <th scope="row" style="width:1%">'.$i.'</th>
									  <td>'.$resultat->libelleaction.'</td> 								  
									  <td>'.$resultat->libelle.'</td>									  									  
									  <td style="width:20%" >'.$resultat->nomfichierbase.'</td>																		  
									  <td style="width:10%">'.$c.'</td>
									  <td class="'.$couleur.'">'.$texteReussite.'</td>
									  <td><a href="lireRapport.php?rapport='.$resultat->retour.'">'.$resultat->retour.'</a></td>
									  <td><a href="lireErreur.php?erreur='.$resultat->erreur.'">'.$resultat->erreur.'</a></td>
									 
									</tr>	
								  
								  
								  '; 
								  $i++;
							 }  
						?>
					  </tbody>
					</table>
				</div>

			</section>
    </body>
</html>
