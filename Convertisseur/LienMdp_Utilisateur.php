<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Identification</title>
    </head>
    <body>
		<div id="bloc_login">
			<header>
				<nav class="navbar navbar-expand-lg navbar-light bg-secondary ">
					<a class="navbar-brand" href="index.php"><img src="image/logocasvp1.jpg" width="250" height="90"></a>
					<div class="collapse navbar-collapse">
						<div class="navbar-nav">
						</div>
					</div>
				</nav>
			</header>
			</br>
			</br>
			</br>
			</br>
			<h1 class="text-center">Identification</h1>
			<section>
					<div class="identification d-flex justify-content-center">
						<div class="container text-center" style="width:20%">
							<form action="Login.php" method="POST">
								<div class="form-group">
									<label for="exampleInputEmail1">Nom de compte</label>
									<input type="login" class="form-control" name="login" placeholder="ex: louis.dupont" required>
								 </div>
								 <div class="form-group">
									<label for="exampleInputPassword1">Mot de passe</label>
									<input type="password" class="form-control" name="pwd" placeholder="ex: 1234" required>
								 </div>

								 <button type="submit" class="btn btn-outline-secondary bg-dark text-white">Connexion</button>
								 <a class="btn btn-outline-secondary bg-dark text-white" href="index.php">Retour</a> 
							</form>							
						</div>
					</div>
					
			</section>
		</div>
	</body>
</html>