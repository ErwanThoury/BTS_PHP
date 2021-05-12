<?php
 session_start(); 

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Accueil</title>
    </head>
    <body>
		<div id="bloc_login">
			<?php $page="Accueil"; include("entete.php"); ?>			
			</br>
			</br>
			</br>
			</br>

			<h1 class="text-center">Interface Astre</h1>
			<section>
				<div class="container text-center">
					<div class="d-flex p-2 justify-content-center">

							<?php
							if($_SESSION['login'])
							{
								$lien = "window.location.href = 'LienFile_Utilisateur.php';";
								$afficherLien = '<div class="card bg-secondary text-white mb-3" style="max-width: 20rem; margin: 5px">
												 <p> Si vous souhaitez vous connecter pour pouvoir convertir et vérifier la bonne conformité vos fichiers csv en fichiers xml, veuillez cliquer sur le lien suivant: </p>
												 <button onclick="'.$lien.'" class="btn btn-dark"> Convertisseur en ligne </button></div>';
							}
							else
							{
								$lien =  "window.location.href = 'LienMdp_Utilisateur.php';";
								$afficherLien = "";
							}
							echo $afficherLien;
							?>
						</br>
						</br>
						<div class="card bg-secondary text-white mb-3" style="max-width: 20rem; margin: 5px">
							<p> Si vous souhaitez simplement accéder au logiciel de vérification de la conformité des fichiers xml en ligne, veuillez cliquer sur le lien suivant: </p>
							<button onclick="window.location.href = 'LienFile_ligne.php';" class="btn btn-dark"> V&eacuterification de votre fichier de donn&eacutees</a> </button>
						</div>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>
