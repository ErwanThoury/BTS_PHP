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
			<?php include("entete.php"); ?>
			<section>
				</br>
				</br>
				</br>
				</br>
				<div class="container" style="width:90%">
					<ul class="list-group">

	
							<?php  
							// est utile pour ouvrir la page montrant l'erreur
								if(isset($_GET["erreur"]))
								{	
									$base = mysql_connect ('Localhost', 'astre', 'stuGvh5ibMP9IGzr'); // On se connecte a la base
									mysql_select_db ('Interface_Astre', $base) ; // On travaille dans Interface Astre
									$requete = mysql_query("SELECT pathdepot
															FROM LoginMdp
															WHERE idutilisateur = '".$_SESSION['id']."';") 
															OR die(mysql_error()); 
									$resultat = mysql_fetch_object($requete);
									$fichierTxt= $resultat->pathdepot.$_GET["erreur"];
									//$fichierTxt= "/var/www/html/".$_GET["erreur"];
									fopen($fichierTxt,"r");
									$fichier = file($fichierTxt);
									$total = count($fichier); 
									for($i = 0; $i < $total; $i++) { 
										// On affiche ligne par ligne le contenu du fichier 
										// avec la fonction nl2br pour ajouter les sauts de lignes 
										echo "<li href='#' class='list-group-item list-group-item-dark'>".$fichier[$i]."</li>"; 
									} 
									fclose($fichierTxt);	
								}
							?>

					</ul>
					<button onclick="window.location.href = 'lienTDBConversion.php';" class="btn btn-outline-secondary bg-dark text-white" type="button"> Retour </button>
				</div>		
			</section>
    </body>
</html>
