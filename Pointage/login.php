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
		</br>
		</br>
		<h1 class="text-center">Gestion des présences</h1>
		<section>
			<div class="container text-center">
					


<?php
// on teste si nos variables sont définies
if (isset($_POST['login']) && isset($_POST['pwd']))
{
	include 'connexionBDD.php';
	connexionBDD();

	$sql = 'SELECT COUNT(*) FROM Utilisateur WHERE nomDeCompte="'.$_POST['login'].'" AND motDePasse="'.$_POST['pwd'].'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		
	$nb=mysql_fetch_array($req);
	
	// on vérifie les informations du formulaire, à savoir si le pseudo saisi est bien un pseudo autorisé, de même pour le mot de passe
	if ((int)$nb[0] > 0) 
        {
		// dans ce cas, tout est ok, on peut démarrer notre session

		// on la démarre 
		session_start ();
		// on enregistre les paramètres de notre visiteur comme variables de session ($login et $pwd) 
		$_SESSION['login'] = $_POST['login'];
		$_SESSION['pwd'] = $_POST['pwd'];
		
		$requete = mysql_query('SELECT idUtilisateur, numeroCentre 
								FROM Utilisateur 
								WHERE nomDeCompte="'.$_SESSION['login'].'" ') 					 
								OR die('Erreur de la requête MySQL'); 
		
		$resultat = mysql_fetch_object($requete);
		$_SESSION['id'] = $resultat->idUtilisateur;
		$_SESSION['numeroCentre'] = $resultat->numeroCentre;
		header ('location: pointage.php');
		
		mysql_free_result ($req);
		mysql_close ();
		
		exit();
	    }
	else
    {

		echo '<p class="text-center">
		Identifiant ou mot de passe incorrect.
		</p>';

	}
}
else 
{
	echo '<p class="text-center">Les variables du formulaire ne sont pas déclarées.</p>';
}



?>
			<div class="d-flex p-2 justify-content-center">
				<button onclick="window.location.href = 'lienConnexion.php';" class="btn btn-dark"> Retour </button>			
			</div>
		</div>
	</section>
</html>