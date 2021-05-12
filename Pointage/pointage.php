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
	
	//fonction utilisée pour donner le nombres de pointages pour chaque type
	function affDebut($numeroType, $nCentre)
	{
		$requete = mysql_query("SELECT count(*) AS 'nombre' 
								FROM Presence
								WHERE DATE(dateHeurePointage) = DATE(NOW()) AND numeroTypePresence=$numeroType AND numeroCentre='$nCentre'");				
		$resultat = mysql_fetch_object($requete);

		$nbPointage = $resultat->nombre;
		return $nbPointage;
	}
	$nbAccueil = affDebut("1",$centre);
	$nbDouche = affDebut("2",$centre);
	$nbMaraude = affDebut("3",$centre);
	$nbPsy = affDebut("4",$centre);
	$nbSS = affDebut("5",$centre);
	$nbSI = affDebut("6",$centre);
	$nbCRS = affDebut("7",$centre);
	$nbCPAM = affDebut("8",$centre);
	$nbJuriste = affDebut("9",$centre);
	$nbVestiaire = affDebut("10",$centre);
	mysql_close();	
	
	//Si nous ne sommes pas à Saint Didier, n'affiche pas certain bouton
	$afficherStDidier="";
	$afficherReneCoty="";
	$afficherReneCotyBis="";
	$afficherEnfant='';
	if($centre == 1)
	{
		
		$afficherStDidier='
		<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-info">
					<input type="radio" name="options" id="btnSS"  value ="5"> Serv. social
				</label>
			</div>
			<p class="font-weight-bold " id="txt5">'.$nbSS.'</p>
		</div>
		<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-info">
					<input type="radio" name="options" id="btnCPAM"  value ="8"> CPAM
				</label>
			</div>
	     	<p class="font-weight-bold " id="txt8">'.$nbCPAM.'</p>
		</div>
		<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-info ">
					<input type="radio" name="options" id="btnPsy" value ="4"> Psy
				</label>
			</div>
			<p class="font-weight-bold " id="txt4">'.$nbPsy.'</p>
		</div>  					
		<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-info ">					  
					<input type="radio" name="options" id="btnJuriste" value ="9"> Juriste
				</label>
			</div>
			<p class="font-weight-bold " id="txt9">'.$nbJuriste.' </p>
		</div>
		';
	}
	else
	{
		$afficherEnfant="<p id='nbEnfants'>Nombre d'enfants: </p>
					<select id='listeEnfants'   size='1'></select>";
		$afficherReneCoty='		
		<p>Orientation: <select id="orientation"   size="1"></select> </p> 
		<div id="siOrientation"></div>	
		';
		$afficherReneCotyBis="
		<button type='button' id='renouveler' class='btn btn-info ' value='0'>Inscrire à l'orientation</button>
		";
	}
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
					<p>Prestations du jour (sans RDV):</p>			
					<div class="d-flex ">			
						<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-info ">
									<input type="radio" name="options" id="btnPointer" value ="1"> Accueil
								</label>
							</div>
							<p class="font-weight-bold " id="txt1"><?php echo $nbAccueil; ?></p>
						</div>  					
						<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-info">
									<input type="radio" name="options" id="btnDouche"  value ="2"> Douche
								</label>
							</div>
							<p class="font-weight-bold " id="txt2"><?php echo $nbDouche; ?></p>
						</div>			
						
						<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-info ">					  
									<input type="radio" name="options" id="btnSI" value ="6"> Soins inf.
								</label>
							</div>
							<p class="font-weight-bold " id="txt6"><?php echo $nbSI; ?></p>
						</div>					
						<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-info ">
									<input type="radio" name="options" id="btnCRS" value ="7"> Carte RS
								</label>
							</div>
							<p class="font-weight-bold " id="txt7"><?php echo $nbCRS; ?></p>
						</div> 
						<?php echo $afficherStDidier;  //Si on est à St Didier, on affiche des boutons supplémentaires?> 				
						<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-info ">					  
									<input type="radio" name="options" id="btnVestiaire" value ="10"> Vestiaire
								</label>
							</div>
							<p class="font-weight-bold " id="txt10"><?php echo $nbVestiaire; ?></p>
						</div>
					</div>
					<div class="d-flex ">
						<div class="card text-dark text-center mb-3" style="max-width: 20rem; margin: 1px">
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-primary ">					  
									<input type="radio" name="options" id="btnMaraude" value ="3"> Maraude
								</label>
							</div>
							<p class="font-weight-bold " id="txt3"><?php echo $nbMaraude; ?></p>
						</div>
					</div>		
				</div>
				<div class="col-md-4 ">
					<p id="dateCreation">Date Création:</p>
					<p id="nomP">Nom:  <input  type="text" id="txtNom" value=""/></p>
					<p id="prenom">Prénom: <input type="text" id="txtPrenom" value=""/></p>
					<p>Nationalité: <select id="sltNationalite"  size="1"></select></p> 			
					<p id="date">Année de naissance: <input type="text" id="txtAnneeDeNaissance" value=""/></p>	
					<p id="numCarteRS">Carte RS n°: <input type="text" id="txtCarteRS" value=""/></p>
					
					<?php echo $afficherEnfant;?>
					<p>  </p>
					<?php echo $afficherReneCoty;?>
					<button type="button" id="modifier" class="btn btn-info ">Modifier</button>
					<?php echo $afficherReneCotyBis;?>
					<p></br>Historique des pointages: </p>
					<select id="listeTypePresence" class="custom-select" size="3"></select>
					<button type="button" id="btnSupp" class="btn btn-info ">Supprimer</button>
				</div>
			</div>
			</br>
		</div>
	</section>
</body>

<script>
	function maFonction() {
		alert("a");
	}

	$("#listeEnfants").hide();


	$(document).ready(function(){
		
		//Fonction permettant de remplir automatiquement la liste des orientations
		$.ajax({
			url: "getOrientation.php",
			type: "POST",
			success: function(data){
				var tab = JSON.parse(data);
				$.each(tab, function(index, ligne){ //Remplit la liste des orientations
					if(ligne["idOrientation"] == 0) //Place l'orientation "Aucune" comme orientation de base
					{
						//On notera le value="" utile ici afin de récupérer aisément l'orientation pour pouvoir la manipuler 
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
		
		//Permet de renouveler une orientation ou de changer
		$("#renouveler").click(function(){
			$.ajax({
				url: "setInscrire.php",
				type: "POST",
				data: "inscrire=" + $("#renouveler").val()
					+ "&dateExp=" +$("#listeNoms").val()
					+ "&numOrientation=" +$("#orientation").val(),
				success: function(data){
					$("#siOrientation").empty();
					$("#renouveler").empty();
					//Si une orientation autre que "Autre" est sélectionnée, on affiche la date d'expiration + on affiche le bouton prolonger
					if($("#orientation").val()!=0) 
					{
						var txtOrientation = "<p>Expire le: "+data+" </p>";	
						$("#siOrientation").append(txtOrientation);
						$("#renouveler").val("1");
						$("#renouveler").append("Prolonger/Changer");
					}
					//Si "Autre" est l'orientation sélectionnée, on change le bouton en "Inscrire à l'orientation"
					else
					{
						$("#renouveler").val("0");
						$("#renouveler").append("Inscrire à l'orientation");
					}
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

					$("#numCarteRS").empty();
					$("#nomP").append("Nom: <input id='txtNom' type='text' value='"+obj.nom+"'/>");
					$("#date").append("Année de naissance: <input id='txtAnneeDeNaissance' type='text' value='"+obj.anneeDeNaissance+"'/>");
					$("#prenom").append("Prénom: <input id='txtPrenom' type='text' value='"+obj.prenom+"'/>");
					$("#numCarteRS").append("Carte RS n°: <input id='txtCarteRS' type='text' value='"+obj.numeroCarte+"'/>");
					date=obj.dateCreation; 	
					date=date.split("-"); 					
					date=date[2]+"-"+date[1]+"-"+date[0];
					$("#dateCreation").append("Fiche créée le: "+date);
			

					
					
					
					
					//Permet d'alimenter la liste des nationalités			
					$.ajax({
						url: "getNationalite.php",
						type: "POST",
						success: function(data){
							var tab = JSON.parse(data);
							$("#sltNationalite").empty();
							$.each(tab, function(index, ligne){
								//Permet de montrer la nationalité de l'usager
								if(ligne["idNationalite"] == obj.nationalite)
								{
									//On notera le value="" utile ici afin de pouvoir récupérer aisément l'id de la nationalité
									var nomPrenom = "<option value="+ligne["idNationalite"]+" selected>"+ligne["libelle"] + "</option>";						
									$("#sltNationalite").append(nomPrenom);
								}
								else
								{
									var nomPrenom = "<option value="+ligne["idNationalite"]+">"+ligne["libelle"] + "</option>";						
									$("#sltNationalite").append(nomPrenom);
								}

							});
						}
						
					});
					$.ajax({
					url: "getFamille.php",
					type: "POST",
					data: "nomRecherche=" + $("#listeNoms").val(),
					success: function(data)
						{
							$("#listeEnfants").hide();
							$("#listeEnfants").empty();
							$("#nbEnfants").empty();
							if(data != "0")
							{
								$("#listeEnfants").show();
								var tab = JSON.parse(data);
								$.each(tab, function(index, ligne){								
									var nomPrenom = "<option value="+ligne["idEnfant"]+">"+ligne["nomEnfant"] + " " + ligne["prenomEnfant"]+", né en "+ligne["anneeDeNaissance"]+"</option>";				
									$("#listeEnfants").append(nomPrenom);
								});
								nbEnfants = tab.length;
								nbEnfants = "Nombre d'enfants: "+nbEnfants;
								$("#nbEnfants").append(nbEnfants);
							}
						}						
					});
					$("#renouveler").empty();
					$("#siOrientation").empty();
					//permet de remplir la liste des orientations en mettant en évidence l'orientation de l'usager
					$.ajax({
						url: "getOrientation.php",
						type: "POST",
						success: function(data){
							var tab = JSON.parse(data);
							$("#orientation").empty();
							$.each(tab, function(index, ligne){
								if(ligne["idOrientation"] == obj.numeroOrientation)
								{
									//On notera le value="" utile ici afin de pouvoir récupérer aisément l'id de l'orientation 
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
					
					//Permet de voir si l'orientation est différente de "Aucune". Si tel est le cas: On affiche la date d'expiration ainsi que le bouton prolonger
					if(obj.numeroOrientation != 0)
					{
						var dateExp = obj.dateExpirationOrientation;
						var txtOrientation = "<p>Expire le: "+dateExp+" </p>";						
						
						$("#renouveler").val("1");
						$("#renouveler").append("Prolonger/Changer");
						$("#siOrientation").append(txtOrientation);
						var valu = $("#renouveler").val();
						
					}
					//Permet de voir si l'orientation est "Aucune". Si tel est le cas: On n'affiche pas la date d'expiration.
					else
					{
						$("#renouveler").val("0");
						$("#renouveler").append("Inscrire à l'orientation");
						var valu = $("#renouveler").val();
						
					}
					$.ajax({
						url: "getTypePresenceUsager.php",
						type: "POST",
						data: "idUsager=" + $("#listeNoms").val()
							+ "&centre="+<?php echo $centre; ?>,
						success: function(data){
							var tab = JSON.parse(data);
							$("#listeTypePresence").empty();
							var grille = new Array();
							$.each(tab, function(index, ligne){
								
								if(ligne["libelle"]=="Maraude")
								{		
									dateE=ligne["dateHeurePointage"].split(" ");
									dateFormatE=dateE[0].split("-"); 						
									dateE=dateFormatE[0]+"-"+dateFormatE[1]+"-"+dateFormatE[2];							
									grille.push(dateE);
									
								}								
							});
							
							$.each(tab, function(index, ligne){
								
								date=ligne["dateHeurePointage"]; 
								
								date=date.split(" ");
								dateFormat=date[0].split("-"); 	
								origine = dateFormat[0]+"-"+dateFormat[1]+"-"+dateFormat[2];					
								date=dateFormat[2]+"-"+dateFormat[1]+"-"+dateFormat[0]+" "+date[1];
								if(grille.includes(origine)==true)
								{
									//On notera le value="" utile ici afin de pouvoir récupérer aisément l'id de l'orientation 
									var nomPrenom = "<option value="+ligne["idPresence"]+"> M/"+ligne["libelle"] + ", " +date+"</option>";
									$("#listeTypePresence").append(nomPrenom);										
								}
								else
								{
									//On notera le value="" utile ici afin de pouvoir récupérer aisément l'id de l'orientation 
									var nomPrenom = "<option value="+ligne["idPresence"]+">"+ligne["libelle"] + ", " +date+"</option>";
									$("#listeTypePresence").append(nomPrenom);	
								}
							
							});
						}		
					});
				}
				
			});						
		});
		//Permet de modifier la fiche dans la bdd d'un usager si une information est érronée ou à mettre à jour.
		$("#modifier").click(function(){
			$.ajax({
				url: "updateInfo.php",
				type: "POST",
				data: "idModifie=" + $("#listeNoms").val() 
					+ "&nomModifie="+$("#txtNom").val()
					+ "&prenomModifie="+$("#txtPrenom").val()
					+ "&anneeDeNaissanceModifiee="+$("#txtAnneeDeNaissance").val()
					+ "&nationaliteModifiee="+$("#sltNationalite").val()
					+ "&carteRS="+$("#txtCarteRS").val(),
				success: function(data){
					//Permet d'afficher que la modification  a bien été effectué.
					alert("Modification effectuée!");
					$("#listeNoms").click();
				},
			});						
		});

		//Fonction qui permet pointer une présence et de retourner le nombre de présence selon le pointage.
		$("input[name='options']").click(function(){		
			$.ajax({
				url: "setPresence.php",
				type: "POST",
				data: "nomAjoute=" + $("#listeNoms").val() 
					+ "&centre="+<?php echo $centre; ?>
					+ "&typePresence="+$('input[name=options]:checked').val(),
				success: function(data){
					x = $('input[name=options]:checked').val(); //récupère le numéro du bouton sélectionné
					//maFonction();
					var obj = JSON.parse(data);
					$("#txt"+x).empty();
					$("#txt"+x).append(obj.nombreAccueil); //Permet de changer le nombre de pointage du bouton sélectionné
					$("#listeNoms").click();
				},
			});						
		});	
		$("#btnSupp").click(function(){		
			$.ajax({
				url: "deletePresence.php",
				type: "POST",
				data: "idPresence=" + $("#listeTypePresence").val(),
				success: function(data){
					location.reload() ;
					
				},
			});
			
		});
	});
</script>

</html>
