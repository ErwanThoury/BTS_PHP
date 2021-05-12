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
	
	//On ouvre la base de données pour directement donner les chiffres de présence au chargement de la page
	include 'connexionBDD.php';
	connexionBDD();	
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

	<section>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-8" class="container text-center">
					Saisir le début du nom recherché:  <input class="form-control" type="text" id="nom"><br/>
					Liste des noms: <br/>
					<select id="listeNoms"  class="custom-select" size="7"></select>							
				</div>
				<div class="col-md-4 ">
					<p id="nomP">Nom:  </p>
					<p id="prenom">Prénom: </p>
					<p id="nationalite">Nationalité: </p> 			
					<p id="date">Année de naissance: </p>	
					<p id="numCarteRS">Carte RS n°: <input type="text" id="txtCarteRS" value=""/></p>
					<p id="delivranceRS">Date délivrance: <input type="text" id="txtDelivranceRS" value=""/></p>
					<p id="txtDateFinValiditeRS"></br>Fin de validité de la carte n°******** : 04-04-2020</p>	
					<select id='lstJour' class='custom-select' size='1'></select>
					<button type="button" id="btnRenouvelerRS" class="btn btn-info ">Modifier</button>
				</div>
			</div>
			</br>
		</div>
	</section>
</body>

<script>
	$("#txtDateFinValiditeRS").hide();
	$("#lstJour").hide();

	$(document).ready(function(){
	
		$("#btnRenouvelerRS").click(function(){
			$.ajax({
				url: "setRS.php",
				type: "POST",
				data: "date=" + $("#lstJour").val()
					+ "&idUsager=" +$("#listeNoms").val()
					+ "&txtCarte=" +$("#txtCarte").val()
					+ "&dateDelivrance=" +$("#txtDelivranceRS").val(),
				success: function(data){
					$("#listeNoms").click();
				}				
			});
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
		
		//Permet de récupérer les infos d'un usager lorsque l'on clique sur son nom
		$("#listeNoms").click(function(){
			$.ajax({
				url: "getInfos.php",
				type: "POST",
				data: "nomRecherche=" + $("#listeNoms").val(),
				success: function(data){
					var obj = JSON.parse(data);
					$("#date").empty();
					$("#prenom").empty();
					$("#nomP").empty();
					$("#dateCreation").empty();
					$("#txtDateFinValiditeRS").empty();
					$("#delivranceRS").empty();
					$("#numCarteRS").empty();
					$("#nationalite").empty();
					$("#nomP").append("Nom: "+obj.nom);
					$("#date").append("Année de naissance: "+obj.anneeDeNaissance);
					$("#prenom").append("Prénom: "+obj.prenom);
					$("#numCarteRS").append("Carte RS n°: <input type='text' id='txtCarte' value='"+obj.numeroCarte+"'/>");
					$("#nationalite").append("Nationalité: "+obj.nationaliteLibelle);
										
					dateD=obj.dateDelivrance;
					dateD=dateD.split("-"); 		
					dateFormatD=dateD[2]+"-"+dateD[1]+"-"+dateD[0];		
					$("#delivranceRS").append("Date délivrance: <input type='text' id='txtDelivranceRS' value='"+dateFormatD+"'/>");
					
					date=obj.dateCreation; 	
					date=date.split("-"); 					
					date=date[2]+"-"+date[1]+"-"+date[0];
					$("#dateCreation").append("Fiche créée le: "+date);
					$("#txtDateFinValiditeRS").show();			
					var now = new Date();
					var annee   = now.getFullYear();
					var mois    = now.getMonth() +1;
					var jour    = now.getDate();

					date=obj.dateExpirationCarte; 	
					date=date.split("-"); 
					
					dateFormat=date[2]+"-"+date[1]+"-"+date[0];	
					if(dateFormatD=="00-00-0000")
					{
						if(mois<10)
						{
							mois="0"+mois;
						}
						if(jour<10)
						{
							jour="0"+jour;
						}
						dateFormatZ = jour+"-"+mois+"-"+annee;
						$("#delivranceRS").empty();
						$("#delivranceRS").append("Date délivrance: <input type='text' id='txtDelivranceRS' value='"+dateFormatZ+"'/>");						
					}
					if(obj.numeroCarte==0)
					{
						
						$("#txtDateFinValiditeRS").append("Aucune carte");
						$("#lstJour").hide();
						
					}
					else
					{
						if(date[0] < annee)
						{
							$("#txtDateFinValiditeRS").append("Carte n°"+obj.numeroCarte+" expirée");
						}
						else
						{
							date[1]=Number(date[1]);	
							date[2]=Number(date[2]);
							if(date[2] < jour && date[1] <= mois)
							{
								$("#txtDateFinValiditeRS").append("Carte n°"+obj.numeroCarte+" expirée");
							}
							else
							{
								$("#txtDateFinValiditeRS").append("Fin de validité de la carte n°"+obj.numeroCarte+" : "+dateFormat);
							}					
						}
						$.ajax({
							url: "getJoursCarteRS.php",
							type: "POST",
							success: function(data){
								$("#lstJour").show();
								$("#lstJour").empty();
								$("#btnRenouvelerRS").show();
								var tab = JSON.parse(data);		
								$.each(tab, function(index, ligne){
									var nomPrenom = "<option value="+ligne["formate"]+">"+ligne["jour"] + "</option>";	
									$("#lstJour").append(nomPrenom);
								});
							}
						});
					}				
				}
				
			});						
		});
	});
</script>

</html>
