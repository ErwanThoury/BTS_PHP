<?php
	$jour = date("l d F Y ");
	$leJour["jour"]="";
	$leJour["formate"]="";
	for ($i = 1; $i <= 78; $i++) {		
		
		
		$testJ = date('d',strtotime($jour));
		$testH = date('G',strtotime($jour));
		$testM = date('i',strtotime($jour));
		$testS = date('s',strtotime($jour));
		$testMo = date('m',strtotime($jour));
		$testA = date('y',strtotime($jour));
		
		$jourFormate = "20".$testA."-".$testMo."-".$testJ." "; 
		
		$test = mktime($testH, $testM, $testS, $testMo, $testJ, $testA); 
		$test = getdate($test);
		$test = $test["weekday"];	
		if($test != "Sunday" && $test != "Saturday")
		{			
			$leJour["jour"]=$jour;
			$leJour["formate"]=$jourFormate;
			$lesJours[]=$leJour;
		}
		$jour = date('l d F Y ',strtotime($jour. ' + 1 days'));
	}
	echo json_encode($lesJours);
	
?>