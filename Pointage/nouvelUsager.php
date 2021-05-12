<?php
	// On prolonge la session
	session_start();
	// On teste si la variable de session existe et contient une valeur
	if(empty($_SESSION['login'])) 
	{
	  // Si inexistante ou nulle, on redirige vers le formulaire de login
	  header('location: lienConnexion.php');
	  exit();
	}	
	//On ouvre la base de données pour directement donner les chiffres de présence au chargement de la page
	include 'connexionBDD.php';
	connexionBDD();	
	$centre=$_SESSION['numeroCentre'];			

?>
<html>
<head>
	<title> Recherche des noms   </title>
	<meta http-equiv="Content-Type" content="text/html;UTF-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
	<?php $page="Accueil"; include("entete.php");?>
	</br>
	</br>
	</br>
	<section>
					<div class="identification d-flex justify-content-center">
						<div class="container text-center" style="width:20%">
								<div class="form-group">
									<label for="exampleInputEmail1">Nom:</label>
									<input type="text" class="form-control" id="txtNom" placeholder="ex: Dupont" required>
								 </div>
								 <div class="form-group">
									<label for="exampleInputPassword1">Prenom:</label>
									<input type="text" class="form-control" id="txtPrenom"  placeholder="ex: Louis" required>
								 </div>
								 <div class="form-group">
									<label for="exampleInputPassword1">Année de naissance:</label>
									<input type="text" class="form-control" id="txtAnneeDeNaissance"  placeholder="ex: 1966" required>
								 </div>
								 <div class="form-group">
									<label>Sexe:</label> </br>
									<label class="radio-inline"><input type="radio" name="rdoSexe" value="H" checked>Homme</label>
									<label class="radio-inline"><input type="radio" name="rdoSexe" value="F" >Femme</label>
								 </div>
								 <div class="form-group">
									<label>Nationalité:</label> </br>
									<select id="nationalite"  class="custom-select" size="1"></select>
								 </div>
								 <div class="form-group">
									<label>Orientation:</label> </br>
									<select id="orientation"  class="custom-select" size="1"></select>
								 </div>
								 <button type="button" id="btnAjouter" class="btn btn-outline-secondary bg-dark text-white">Ajouter</button>							
						</div>
					</div>
					
	</section>
</body>
<script>
	$(document).ready(function(){
		//Permet de remplir au chargement de la page la liste des nationalités
		$.ajax({
			url: "getNationalite.php",
			type: "POST",
			success: function(data){
				var tab = JSON.parse(data);
				$.each(tab, function(index, ligne){
					if(ligne["idNationalite"] == 3)
					{
						var nomPrenom = "<option value="+ligne["idNationalite"]+" selected>"+ligne["libelle"] + "</option>";						
						$("#nationalite").append(nomPrenom);
					}
					else
					{
						var nomPrenom = "<option value="+ligne["idNationalite"]+">"+ligne["libelle"] + "</option>";						
						$("#nationalite").append(nomPrenom);
					}

				});
			}
		
		});
		//Permet de remplir au chargement de la page la liste des orientations
		$.ajax({
			url: "getOrientation.php",
			type: "POST",
			success: function(data){
				var tab = JSON.parse(data);
				$.each(tab, function(index, ligne){
					if(ligne["idOrientation"] == 0) //Place l'orientation "Aucune" comme orientation de base
					{
						var nomPrenom = "<option value="+ligne["idOrientation"]+" selected>"+ligne["libelleOrientation"] + "</option>";						
						$("#orientation").append(nomPrenom);
					}
					else
					{
						var nomPrenom = "<option value="+ligne["idOrientation"]+">"+ligne["libelleOrientation"] + "</option>";						
						$("#orientation").append(nomPrenom);
					}

				});
			}
		
		});
		//Permet d'ajouter un usager à la BDD
		$("#btnAjouter").click(function(){
			$.ajax({
				url: "setUsager.php",
				type: "POST",
				data: "nom="+$("#txtNom").val()
					+ "&prenom="+$("#txtPrenom").val()
					+ "&anneeDeNaissance="+$("#txtAnneeDeNaissance").val()
					+ "&nationalite="+$("#nationalite").val()
					+ "&sexe="+$('input[name=rdoSexe]:checked').val()
					+ "&numOrientation=" +$("#orientation").val(),
				success: function(data){
					document.location.href="pointage.php"; 
					
				},
			});						
		});
	});
</script>
</html>