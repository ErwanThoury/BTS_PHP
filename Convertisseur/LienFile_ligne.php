<?php session_start(); ?>

<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Conversion du document .csv</title>
    </head>
    <body>
		<div id="bloc_chargement">
			<?php $page="Verifier"; include("entete.php"); ?>
			<section>
			</br>
			</br>
			</br>
			</br>

				<div class="identification d-flex justify-content-center">
					<div class="container text-center" style="width:90%">
						<div class="d-flex p-2 justify-content-center">
							<div class="card bg-secondary text-white mb-3" style="max-width: 200rem">
								<form enctype="multipart/form-data" action="main_ligne.php" method="POST">
									<p>Charger puis envoyer votre fichier pour vérifier la conversion</p>																
									<input type="file" name="conversion" value="fileupload" id="fileupload" style="width: 60rem; margin: 5px"> 
									</br>
									<button type="submit" class="btn btn-outline-secondary bg-dark text-white" >Vérifier le fichier</button>															
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
    </body>
</html>