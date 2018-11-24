<?php
	require 'theAlgorithm.php';
	require 'graphBuilder.php';

	/*
		- DB friendly matrix representation
		- non-multigraph only
	*/
	function getMatrixString($m){
		$str = "";
		for($a = 0; $a < count($m); $a+=1){ 
			for($b = 0; $b < count($m); $b+=1){
				$str .= ',' . $m[$a][$b];
			}
	  	}
	  	return $str;
	}

	if(isset($_GET['hash']) && $_GET['hash'] =='someRandomString'){
		require_once('../../../../outerScripts/mysqli_connect_Thesis_OOP.php');

		$maxTime = 6;
		$runTime = microtime(true);
		while(microtime(true) - $runTime < $maxTime){
			$n = rand(25,100);
			$type = rand(1,3);
			$p = makeRandPermuteMatrix($n);
			$g = mux(makeRandGraphSimplified($n, $type));
			$pg = matrixMult(matrixTranspose($p), matrixMult($g, $p));

			$salt = rand(1,3);
			$spg = salt($pg, $salt);

			$permutation = getMatrixString($p);
			$graph1String = getMatrixString($g);
			$expectedResult = rand(0, 1);
			if($expectedResult == 1){
				$graph2String = getMatrixString($pg);
				$salt = 0;
			} else {
				$graph2String = getMatrixString($spg);
				$pg = $spg;
			} 

			$start = microtime(true);
			$temp = GI($g, $pg);
			$end = microtime(true);

			$avg = $temp[2];
			$leaf = $temp[1];
			$actualResult = $temp[0];
			$time = '' . ($end - $start);
			$edgeCount = edgeCount($g);
			$gSize = count($g);

			$table = "finalBatch5";
			$query = $dbc->prepare(
				"INSERT INTO " . $table .
				" (expectedIso, resultingIso, time, type, salt, leaf, avgStringLength, gSize, edgeCount) " .
				" VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
			);
			$query->bind_param(
				"iisiiidii", 
				$expectedResult, $actualResult, $time, $type, $salt, $leaf, $avg, $gSize, $edgeCount
			);
			$result = $query->execute();
			$query->store_result();
			$query->close();

			// always record when GI is wrong
			if($actualResult != $expectedResult){
				$table .= "Checks";
				$query = $dbc->prepare(
					"INSERT INTO " . $table . 
					" (g1, g2, expectedIso, resultingIso, permutation, time, type, salt, leaf, avgStringLength) " . 
					" VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
				);
				$query->bind_param(
					"ssiissiiid", 
					$graph1String, $graph2String, $expectedResult, $actualResult, $permutation, $time, $type, $salt, $leaf, $avg
				);
				$result = $query->execute();
				$query->store_result();
				$query->close();
			}
		}
			
		$dbc->close();
	}
?>
