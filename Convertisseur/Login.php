<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Identification</title>
    </head>
    <body>
			<header>
				<nav class="navbar navbar-expand-lg navbar-light bg-secondary ">
					<a class="navbar-brand" href="index.php"><img src="image/logocasvp.jpg" width="235" height="121"></a>
					<div class="collapse navbar-collapse">
					</div>
				</nav>
			</header>
			</br>
			</br>
			</br>
			</br>
			<h1 class="text-center">Interface Astre</h1>
			<section>
				<div class="container text-center">
					


<?php
// on teste si nos variables sont d�finies
if (isset($_POST['login']) && isset($_POST['pwd']))
{
	$base = mysql_connect ('Localhost', 'astre', 'stuGvh5ibMP9IGzr');
	mysql_select_db ('Interface_Astre', $base) ;

	$sql = 'SELECT COUNT(*) FROM LoginMdp WHERE Login="'.$_POST['login'].'" AND Password="'.$_POST['pwd'].'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		
	$nb=mysql_fetch_array($req);
	
	// on v�rifie les informations du formulaire, � savoir si le pseudo saisi est bien un pseudo autoris�, de m�me pour le mot de passe
	if ((int)$nb[0] > 0) 
        {
		// dans ce cas, tout est ok, on peut d�marrer notre session

		// on la d�marre 
		session_start ();
		// on enregistre les param�tres de notre visiteur comme variables de session ($login et $pwd) 
		$_SESSION['login'] = $_POST['login'];
		$_SESSION['pwd'] = $_POST['pwd'];
		
		$requete = mysql_query('SELECT idUtilisateur 
								FROM LoginMdp 
								WHERE Login="'.$_SESSION['login'].'" ') 					 
								OR die('Erreur de la requ�te MySQL'); 
		
		$resultat = mysql_fetch_object($requete);
		$_SESSION['id'] = $resultat->idUtilisateur;
						  
		
		//La requete permet d'enregistrer dans l'historique notre connexion
		$requeteAjout = mysql_query("INSERT INTO `Interface_Astre`.`Action` (`idaction`, `date`, `numtyp`, `numutilisateur`, `reussite`, `retour`, `libelle`, `erreur`, `nomfichierbase`) 
									VALUES (NULL, '".date("Y-m-d G:i:s")."', '3', '".$resultat->idUtilisateur."', '0', '', '', '','');") 
									OR die('Erreur de la requ�te MySQL'); 
						mysql_close();
		
		// on redirige notre utilisateur sur la page de conversion
		//header ('location: LienFile_Utilisateur.php');
		header ('location: lienTDB.php');
		
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
	echo '<p class="text-center">Les variables du formulaire ne sont pas d�clar�es.</p>';
}



?>
					<div class="d-flex p-2 justify-content-center">
							<button onclick="window.location.href = 'LienMdp_Utilisateur.php';" class="btn btn-dark"> Retour </button>			
					</div>
				</div>
			</section>
</html>