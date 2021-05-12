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
								if(isset($_GET["rapport"]))
								{	
									
									$base = mysql_connect ('Localhost', 'astre', 'stuGvh5ibMP9IGzr'); // On se connecte a la base
									mysql_select_db ('Interface_Astre', $base) ; // On travaille dans Interface Astre
									$requete = mysql_query("SELECT pathdepot
															FROM LoginMdp
															WHERE idutilisateur = '".$_SESSION['id']."';") 
															OR die(mysql_error()); 
									$resultat = mysql_fetch_object($requete);
									$fichierRapport= $resultat->pathdepot."/".$_GET["rapport"];
									
									$handle = fopen($fichierRapport, "r"); //On les lit
									$compteurCarte = 1;
									if ($handle) //Si on l'a bien ouvert...
									{
										echo '<div class="container text-center">
												<div class="d-flex  justify-content-center">
													<div class="card text-white bg-white mb-3" style="max-width: 0rem; margin: 0px">';
										while (($line = fgets($handle)) !== false) //... on parcourt chaque ligne.
										{		
												$simple = $line;
												$p = xml_parser_create();
												xml_parse_into_struct($p, $simple, $vals, $index); //permettra de récupérer les données dans les balises xml entre autres
												xml_parser_free($p);
												$clef=array_keys($index);

												if($clef[0] == "CMD:CMDCRE")
												{
													echo '</div><div class="card bg-dark text-white mb-3" style="max-width: 20rem; margin: 1px">';
													$compteurCarte = $compteurCarte + 1;
													
												}
																					
												echo "<p class='text-white' >".$line."</p>"; 

																		
										}
										fclose($handle);
										echo '</div></div></div>';	
									} 
			
								}
							?>

					</ul>
					<button onclick="window.location.href = 'lienTDBConversion.php';" class="btn btn-outline-secondary bg-dark text-white" type="button"> Retour </button>
				</div>		
			</section>
    </body>
</html>
