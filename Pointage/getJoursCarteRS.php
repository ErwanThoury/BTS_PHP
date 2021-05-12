<?php
	$jour = date("l d F Y ");
	
	for ($i = 1; $i <= 60; $i++) {		
		
		$jour = date('l d F Y ',strtotime($jour. ' + 1 days'));
		$testJ = date('d',strtotime($jour));
		$testH = date('G',strtotime($jour));
		$testM = date('i',strtotime($jour));
		$testS = date('s',strtotime($jour));
		$testMo = date('m',strtotime($jour));
		$testA = date('y',strtotime($jour));
		
		$jourFormate = "20".$testA."-".$testMo."-".$testJ." "; 
		
			
		$leJour["jour"]=$jour;
		$leJour["formate"]=$jourFormate;
		$lesJours[]=$leJour;
		
		
	}
	echo json_encode($lesJours);
	
?>