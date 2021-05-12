

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
			<?php $page="Convertir"; include("entete.php"); ?>
			<section>
			</br>
			</br>
			</br>
			</br>
				<div class="identification d-flex justify-content-center">
					<div class="container text-center" style="width:90%">
						<div class="d-flex p-2 justify-content-center">
							<div class="card bg-secondary text-white mb-3" style="max-width: 200rem">
								<form enctype="multipart/form-data" action="main_utilisateur.php" method="POST">
									<p>Charger puis envoyer votre fichier pour la conversion</p>								
									<input type="file" name="conversion" value="fileupload" id="fileupload" style="width: 60rem; margin: 5px"> 
									</br>
									<button type="submit" class="btn btn-outline-secondary bg-dark text-white" >Convertir le fichier</button>															
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
    </body>
</html>

