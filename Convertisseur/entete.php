<?php
	// Les $lien sont juste ici pour un problème avec les ' et les " en php
	$lienConv= "window.location.href = 'LienFile_Utilisateur.php';";
	$lienVerif= "window.location.href = 'LienFile_ligne.php';";
	$lienIndex = "window.location.href = 'index.php';";
	$lienTDB = "window.location.href = 'lienTDB.php';";	
	
	// Tous les $affiche sont ici les boutons du menu et sont comme des interrupteurs qui s'activent et se désactivent.
	// Par exemple, si l'on veut que le bouton accueil ne s'affiche que lorsque l'on est connecte, on le met dans le if... etc
	$afficherConv= '<button onclick="'.$lienConv.'" class="btn btn-outline-secondary bg-dark text-white" type="button"> Convertir </button>';
	$afficherVerif= '<button onclick="'.$lienVerif.'" class="btn btn-outline-secondary bg-dark text-white" type="button"> Vérifier </button>';
	$afficherAccueil= '<button onclick="'.$lienIndex.'" class="btn btn-outline-secondary bg-dark text-white" type="button"> Accueil </button>';
	//$afficherLogo= '<a class="navbar-brand" href="index.php"><img src="image/logocasvp.jpg" width="235" height="121"></a>';
	$afficherLogo= '<a class="navbar-brand" href="index.php"><img src="image/logocasvp1.png" width="210" height="70"></a>';
	
	//On vérifie si on est connecté afin d'afficher le bouton de deconnexion ainsi que le nom de la personne connectee
	if(isset($_SESSION['login']))
	{		
		$afficherIndex = '<button onclick="'.$lienIndex.'" class="btn btn-outline-secondary bg-dark text-white" type="button"> Accueil </button>';							
		$afficherTDB ='<button onclick="'.$lienTDB.'" class="btn btn-outline-secondary bg-dark text-white" type="button"> Tableau de bord </button>';

		//La requete ci-dessous permet de recuperer le nom et prenom de la personne connecte afin de l'afficher.
		mysql_connect('localhost', 'astre', 'stuGvh5ibMP9IGzr') OR die('Erreur de connexion à la base'); 
		mysql_select_db('Interface_Astre') OR die('Erreur de sélection de la base'); 
		$requete = mysql_query('SELECT nom, prenom 
								FROM LoginMdp
								WHERE idUtilisateur="'.$_SESSION['id'].'"') OR die('Erreur de la requête MySQL'); 
		$resultat = mysql_fetch_object($requete);
		mysql_close();
		
		//Bouton de connexion en variable pour ne pas qu'il soit present si personne n'est connecte
		$afficherDroite = $resultat->prenom." ".$resultat->nom;	
		$lienImage = '<img src="image/deco.png" width="20" height="20">';
		
	}
	
	// Si personne est connecte, on affiche le bouton de connexion
	else
	{	
		$afficherDroite =' <a class="text-dark" href="logout.php">se connecter</a>';
		$lienImage = '<img src="image/connexion.png" width="20" height="20">';
		$afficherConv = "";
	}
	$afficheImage = '<li><a href="logout.php"> '.$lienImage.'</a></li>';
	
	// Cette série de if est juste là pour éviter de mettre le bouton accueil à la page accueil ou le bouton tableau de bord sur le tableau de bord.
	if($page == "TDB")
	{
		$afficherTDB = "";
	}
	else
	{
		if($page == "Convertir")
		{
			$afficherConv = "";
		}
		else
		{
			if($page == "Verifier")
			{
				$afficherVerif = "";
			}
			else
			{
				if($page == "Accueil")
				{
					$afficherVerif = "";
					$afficherConv = "";
					$afficherAccueil = "";
				}
			
			}
		}
	}
	if($_SESSION['login'] == "admin") //L'admin ne doit pas pouvoir convertir.
	{						
		$afficherVerif = "";
		$afficherConv = "";
		$afficherAccueil = "";
		$afficherLogo= '<a class="navbar-brand" href="#"><img src="image/logocasvp.jpg" width="235" height="121"></a>';
	}

?>
<header>
	<nav class="navbar navbar-expand-lg navbar-light bg-secondary ">
		<?php echo $afficherLogo; ?>
		
		<div class="collapse navbar-collapse">		
			<div class="navbar-nav">
			 
				<?php		
				echo $afficherAccueil; 
				echo $afficherTDB;
				echo $afficherConv ;
				echo $afficherVerif ;					
				?>
				
			</div>			
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li class="font-weight-bold " ><?php echo  $afficherDroite ; ?></li>&nbsp;
			<?php echo $afficheImage; ?>
		</ul>


	</nav>
</header>
