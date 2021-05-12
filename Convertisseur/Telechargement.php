<?php
function forcerTelechargement($nomfichier) // Fonction pour télécharger le fichier .xml
 {
     
	// téléchargement du fichier 
	header('Content-disposition: attachment; filename='.$nomfichier); 
	header('Content-Type: application/force-download'); 
	header('Content-Transfer-Encoding: fichier');  
	header('Content-Length: '.filesize($nomfichier)); 
	header('Content-MD5: '.base64_encode(md5_file($nomfichier)));
	header('Pragma: no-cache'); 
	header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0'); 
	header('Expires: 0'); 
	readfile($nomfichier);

}
?>