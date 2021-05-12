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
	<title> Rendez-vous</title>
	<meta http-equiv="Content-Type" content="text/html;UTF-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
	<?php $page="Accueil"; include("entete.php");?>
	</br>

	<section>			
		
		<div class="col-md-12">		
			<div class="row">
				
				<div class="col-md-8" class="container text-center">
					Saisir le début du nom désiré:  <input class="form-control " type="text" id="nom"><br/>				
					Liste des noms: <br/>
					<select id="listeNoms"  class="custom-select" size="4"></select>
				</div>
				<div class="col-md-4 ">
					Rendez-vous de l'usager sélectionné:<br/>
					<select id="listeUsagerRDV"  class="custom-select" size="9"></select>
					<button type="button" class="btn btn-info btn-sm " value="2" id="btnEstVenu">est venu</button>
					<button type="button" class="btn btn-info btn-sm" value="1" id="btnNEstPasVenu">n'est pas venu</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-info btn-sm" id="btnSupp">Supprimer</button>
				</div>
			</div>
			</br>
		</div>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-2" class="container text-center">
					Liste des services:<br/>
					<select id="listeRDV"  class="custom-select" size="3"></select>
					<button type="button" class="btn btn-info" id="priseDeRDV">Prendre RDV</button>
				</div>
				<div class="col-md-3 ">
					Sélection du jour:<br/>
					<select id="listeJour"  class="custom-select" size="1"></select>
				</div>
				<div class="col-md-3">
					Autres RDV du jour:<br/>
					<!--<select id="listeJourRDV"  class="custom-select" size="5"></select>-->
					<p id="listeJourRDV"> </p>
					
				</div>
			</div>
			</br>
			
		</div>
		
	</section>
</body>

<script>
	$(document).ready(function(){	

		$.ajax({
			url: "getListeDesPresencesRDV.php"	,			
			type: "POST",
			data:"centre="+<?php echo $centre; ?>,
			success: function(data){
				var tab = JSON.parse(data);
				$.each(tab, function(index, ligne){ //Remplit la liste des présences
					var nomPrenom = "<option value="+ligne["idTypePresence"]+">"+ligne["libelle"] + "</option>";						
					$("#listeRDV").append(nomPrenom);
				});
			}
		});	
		
		$.ajax({
			url: "getListeJours.php",			
			type: "POST",
			success: function(data){
				var tab = JSON.parse(data);
				
				$.each(tab, function(index, ligne){ //Remplit la liste des présences
					var nomPrenom = "<option value="+ligne["formate"]+">"+ligne["jour"] + "</option>";	
					$("#listeJour").append(nomPrenom);
				});
			}
		});
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
		
		$("#priseDeRDV").click(function(){
			$.ajax({
				url: "setRDV.php",
				type: "POST",
				data: "date=" + $("#listeJour").val()
					+ "&prestation=" + $("#listeRDV").val()
					+ "&idUsager=" + $("#listeNoms").val()
					+ "&centre="+<?php echo $centre; ?>,
				success: function(data){	
					$("#listeNoms").click();
					$("#listeJour").click();
				}
			});
		});
		
		$("#btnEstVenu").click(function(){
			$.ajax({
				url: "updateRDV.php",
				type: "POST",
				data: "idRdv=" + $("#listeUsagerRDV").val()
					+ "&presence=" + $("#btnEstVenu").val(),
				success: function(data){	
					$("#listeNoms").click();
				}
			});
		});
		$("#btnNEstPasVenu").click(function(){
			$.ajax({
				url: "updateRDV.php",
				type: "POST",
				data: "idRdv=" + $("#listeUsagerRDV").val()
					+ "&presence=" + $("#btnNEstPasVenu").val(),
				success: function(data){
					$("#listeNoms").click();
				}
			});
		});
		$("#btnSupp").click(function(){
			$.ajax({
				url: "deleteRDV.php",
				type: "POST",
				data: "idRdv=" + $("#listeUsagerRDV").val(),
				success: function(data){
					$("#listeNoms").click();
					$("#listeJour").click();					
				}
			});
		});
		
		$("#listeNoms").click(function(){
			$.ajax({
				url: "getRDV.php",
				type: "POST",
				data: "idUsager=" + $("#listeNoms").val()		
					+ "&centre="+<?php echo $centre; ?>,
				success: function(data){	
					$("#listeUsagerRDV").empty();
					var tab = JSON.parse(data);
					$.each(tab, function(index, ligne){
						//On notera le value="" utile ici afin de manipuler la fiche dans la bdd de l'usager aisément 
						$date=ligne["date"].split("-");
						$date=$date[2]+"-"+$date[1]+"-"+$date[0];
						
						if(ligne["presenceRDV"]==1)
						{
							var nomPrenom = "<option class='text-danger' value="+ligne["idRdv"]+">"+ligne["libelle"] + " " + $date +"</option>";	
						}
						else if(ligne["presenceRDV"]==2)
						{
							var nomPrenom = "<option class='text-success' value="+ligne["idRdv"]+">"+ligne["libelle"] + " " + $date +"</option>";
						}
						else
						{
							var nomPrenom = "<option value="+ligne["idRdv"]+">"+ligne["libelle"] + " " + $date +"</option>";
						}					
						$("#listeUsagerRDV").append(nomPrenom);
					});
				}
			});
		});			
		$("#listeJour").click(function(){
			$.ajax({
				url: "getRDVs.php",
				type: "POST",
				data: "dateSel=" + $("#listeJour").val()
					+ "&presence=" + $("#listeRDV").val()
					+ "&centre="+<?php echo $centre; ?>,
				success: function(data){	
					$("#listeJourRDV").empty();

					var tab = JSON.parse(data);
					$.each(tab, function(index, ligne){

						//On notera le value="" utile ici afin de manipuler la fiche dans la bdd de l'usager aisément 
						//var nomPrenom = "<option value="+ligne["idRdv"]+">"+ligne["nom"] + " " + ligne["prenom"]+"</option>";
						var nomPrenom = "<p value="+ligne["idRdv"]+">&nbsp;"+ligne["nom"] + " " + ligne["prenom"]+"</br></p>";				
						$("#listeJourRDV").append(nomPrenom);
					});
				}
			});
		});
		$("#listeRDV").click(function(){
			$("#listeJour").click();
		});
	});
</script>

</html>