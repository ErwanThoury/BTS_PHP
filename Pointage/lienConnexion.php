<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Identification</title>
    </head>
    <body>
		<?php $page="Accueil"; include("entete.php"); ?>
			</br>
			</br>
			<h1 class="text-center">Gestion de l'accueil en ESI</h1>
			</br>
			<h2 class="text-center">Identification</h2>
			<section>
					<div class="identification d-flex justify-content-center">
						<div class="container text-center" style="width:20%">
							<form action="login.php" method="POST">
								<div class="form-group">
									<label for="exampleInputEmail1">Nom de compte</label>
									<input type="login" class="form-control" name="login" placeholder="ex: louis.dupont" required>
								 </div>
								 <div class="form-group">
									<label for="exampleInputPassword1">Mot de passe</label>
									<input type="password" class="form-control" name="pwd" placeholder="ex: 1234" required>
								 </div>

								 <button type="submit" class="btn btn-outline-secondary bg-dark text-white">Connexion</button>
							</form>							
						</div>
					</div>
					
			</section>
	</body>
</html>