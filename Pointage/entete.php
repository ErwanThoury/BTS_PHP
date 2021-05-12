<?php
	if(isset($_SESSION['login']))
	{		
		$lienAjout = "window.location.href = 'nouvelUsager.php';";
		$afficherAjouter= '<button onclick="'.$lienAjout.'" class="btn btn-outline-info bg-info text-white" type="button"> Nouvel usager </button>';
		$lienAccueil = "window.location.href = 'pointage.php';";
		$afficherAccueil= '<button onclick="'.$lienAccueil.'" class="btn btn-outline-info bg-info text-white" type="button"> Accueil </button>';
		$lienRDV = "window.location.href = 'priseDeRDV.php';";
		$afficherRDV= '<button onclick="'.$lienRDV.'" class="btn btn-outline-info bg-info text-white" type="button"> Rendez-vous </button>';		
		$lienParente = "window.location.href = 'lienDeParente.php';";
		$afficherParente= '<button onclick="'.$lienParente.'" class="btn btn-outline-info bg-info text-white" type="button"> Famille </button>';
		$lienRS = "window.location.href = 'carteRS.php';";
		$afficherRS= '<button onclick="'.$lienRS.'" class="btn btn-outline-info bg-info text-white" type="button"> Carte RS </button>';
		if($centre == 1)
		{
			$afficherParente ="";
		}
		//La requete ci-dessous permet de recuperer le nom et prenom de la personne connecte afin de l'afficher.
		mysql_connect('localhost', 'root', 'maria') OR die('Erreur de connexion à la base'); 
		mysql_select_db('GestionFluxESI') OR die('Erreur de sélection de la base'); 
		$requete = mysql_query('SELECT nom, prenom, nomCentre
								FROM Utilisateur INNER JOIN Centre on numeroCentre = id
								WHERE idUtilisateur="'.$_SESSION['id'].'"') OR die('Erreur de la requête MySQL'); 
		$resultat = mysql_fetch_object($requete);
		mysql_close();
		
		//Bouton de connexion en variable pour ne pas qu'il soit present si personne n'est connecte
		$afficherDroite = $resultat->prenom." ".$resultat->nom;	
		$lienImage = '<img src="image/deco.png" width="20" height="20">';
		$afficherCentre = $resultat->nomCentre." - ";
	}
	$afficheImage = '<li><a href="deconnexion.php"> '.$lienImage.'</a></li>';
	$afficherLogo= '<a class="navbar-brand" href="pointage.php"><img src="image/logocasvp1.png" width="210" height="70"></a>';
?>
<header>
	<nav class="navbar navbar-expand-lg navbar-light bg-info ">
		<?php echo $afficherLogo; ?>
		
		<div class="collapse navbar-collapse">		
			<div class="navbar-nav">
				<?php echo $afficherAccueil.$afficherAjouter.$afficherRDV.$afficherParente.$afficherRS;?>
			</div>			
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li class="font-weight-bold " ><?php echo  $afficherCentre.$afficherDroite ; ?></li>&nbsp;
			<?php echo $afficheImage; ?> 
		</ul>
		
	</nav>
</header>
