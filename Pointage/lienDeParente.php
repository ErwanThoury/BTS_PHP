<?php
	// On prolonge la session
	session_start();
	// On teste si la variable de session existe et contient une valeur
	if(empty($_SESSION['login'])) 
	{
	  // Si inexistante ou nulle, on redirige vers le formulaire de connexion
	  header('location: lienConnexion.php');
	  exit();
	}
	$centre=$_SESSION['numeroCentre']; //On enregistre le numero du centre
?>
<html>
<head>
	<title> Lien de parenté	</title>
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
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-8" class="container text-center">
					Saisir le début du nom du père / de la mère:  <input class="form-control" type="text" id="nom"><br/>
					Liste des noms: <br/>
					<select id="listeNoms"  class="custom-select" size="7"></select>
				</div>
				<div ulclass="col-md-4 ">
					<p id="txtNomP">Nom: </p>
					<p id="txtPrenomP">Prénom: </p>
					<p id="txtNationaliteP">Nationalité: </p> 			
					<p id="txtDateP">Année de naissance:</p>
					<p id="nbEnfants">Nombre d'enfants: </p>
					<select id="listeEnfants"  size="1"></select>
					</br>
				</div>
			</div>
			</br>
		</div>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3" class="container text-center">
					Nom de l'enfant:  <input class="form-control" type="text" id="nomEnfant"><br/>
				</div>
				<div class="col-md-3" class="container text-center">
					Prénom de l'enfant:  <input class="form-control" type="text" id="prenomEnfant"><br/>
				</div>
				<div class="col-md-3 ">			
					 Année de naissance:  <input class="form-control" type="text" id="anneeEnfant"><br/>	
				</div>
				<div class="col-md-3 ">
					 </br>
					 <button type="button" id="btnAjouter" class="btn btn-outline-secondary bg-dark text-white">Ajouter</button>	
				</div>
			</div>
			</br>
		</div>
	</section>
</body>

<script>
	$("#listeEnfants").hide();
	$(document).ready(function(){
		//Permet de remplir la liste de noms des usagers dont le nom commence par les lettres précisées dans la zone de texte
		$("#nom").keyup(function(){
			$.ajax({
				url: "getNoms.php",
				type: "POST",
				data: "debutLib=" + $("#nom").val(),
				success: function(data){	
					var tab = JSON.parse(data);
					$("#listeNoms").empty();
					$("#lesNoms").empty();
					//On affiche dans la liste chaque nom commençant par les lettres précisées dans la zone de texte
					$.each(tab, function(index, ligne){
						//On notera le value="" utile ici afin de manipuler la fiche dans la bdd de l'usager aisément 
						var nomPrenom = "<option value="+ligne["idUsager"]+">"+ligne["nom"] + " " + ligne["prenom"]+"</option>";						
						$("#listeNoms").append(nomPrenom);
					});
				}
			});
		});	
		
		//Permet de récupérer les infos d'un usager lorsque l'on clique sur son nom
		$("#listeNoms").click(function(){
			$.ajax({
				url: "getInfos.php",
				type: "POST",
				data: "nomRecherche=" + $("#listeNoms").val(),
				success: function(data){
					var obj = JSON.parse(data);
					switch(obj.nationalite)
					{
						 case '1':
							obj.nationalite = "Francais";
							
							break;		
						case '2':
							obj.nationalite = "UE";
							break;
						case '3':
							obj.nationalite = "Autre";
							break;
					}
					
					
					$("#txtDateP").empty();
					$("#txtPrenomP").empty();
					$("#txtNomP").empty();
					$("#txtNationaliteP").empty();
					$("#txtNomP").append("Nom: "+obj.nom);
					$("#txtDateP").append("Année de naissance: "+obj.anneeDeNaissance);
					$("#txtPrenomP").append("Prénom: "+obj.prenom);
					$("#txtNationaliteP").append("Nationalité: "+obj.nationalite);
				}						
			});						
		
			$.ajax({
					url: "getFamille.php",
					type: "POST",
					data: "nomRecherche=" + $("#listeNoms").val(),
					success: function(data){
						$("#listeEnfants").hide();
						$("#listeEnfants").empty();
						$("#nbEnfants").empty();
						if(data != "0")
						{
							$("#listeEnfants").show();
							var tab = JSON.parse(data);
							$.each(tab, function(index, ligne){								
								var nomPrenom = "<option value="+ligne["idEnfant"]+">"+ligne["nomEnfant"] + " " + ligne["prenomEnfant"]+ "</option>";				
								$("#listeEnfants").append(nomPrenom);
							});
							nbEnfants = tab.length;
							nbEnfants = "Nombre d'enfants: "+nbEnfants;
							$("#nbEnfants").append(nbEnfants);
						}
					}						
			});
		});

		$("#btnAjouter").click(function(){
			$.ajax({
				url: "setNouvelEnfant.php",
				type: "POST",
				data: "nomAjoute=" + $("#nomEnfant").val()
					+ "&nomPereMere=" + $("#listeNoms").val()
					+ "&prenomAjoute=" + $("#prenomEnfant").val()
					+ "&anneeAjoute=" + $("#anneeEnfant").val(),
				success: function(data){
						
					$("#listeNoms").click();
				}
			});
		});
	});
</script>

</html>