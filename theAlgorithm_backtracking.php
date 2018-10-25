<?php

	/*
		Returns the graph complement of a simple graph input
	*/
	function invertGraph($g){
    	for($a = 0; $a < count($g); $a+=1){
    		for($b = 0; $b < count($g); $b+=1){
    			if($g[$a][$b] == 0) $g[$a][$b] = 1;
                else $g[$a][$b] = 0;
    		}
    	}
    	return $g;
    }

    /*
		Returns number of edges in graph
    */
	function edgeCount($g){
    	$count = 0;
    	for($a = 0; $a < count($g); $a+=1){
    		for($b = 0; $b < count($g); $b+=1){
    			if($g[$a][$b] != 0) $count += 1;
    		}
    	}
    	return $count;  
    }

    /*
		- Returns true if graph is bipartite.
		- Returns false if graph is not bipartite.

		- NOTE: Used BFS approach described at:
		geeksforgeeks.org/check-graphs-cycle-odd-length/
	*/
    function isBipartite($g){
    	for($a = 0; $a < count($g); $a+=1){
    		if($g[$a][$a] == 1) return false;
    	}

    	$colorArr = array();
    	for($a = 0; $a < count($g); $a+=1) $colorArr[$a] = -1;

    	$colorArr[0] = 1;
    	$q = array();
        array_push($q, 0);

        while(!empty($q)){
        	$u = array_shift($q);
        	for($a = 0; $a < count($g); $a+=1){
        		if($g[$u][$a] != 0 && $colorArr[$a] == -1){
        			$colorArr[$a] = 1 - $colorArr[$u];
        			array_push($q, $a);
        		} elseif($g[$u][$a] != 0 && $colorArr[$a] == $colorArr[$u]){ 
        			return false;
        		}
        	}
        }
    	return true;
    }

    /*
		- Complete bipartite graphs have e = |v1| * |v2| edges where v1 and v2 
		are the two bipartitions of vertices in G.
		- This function returns an array with three values. 
			- The first element is true if the input graph is complete bipartite.
			- The second is the size of the first bipartition of the input graph.
			- The third is the size of the second bipartition of the input graph. 
    */
	function completeBipartite($g){
		$colorArr = array();
    	for($a = 0; $a < count($g); $a+=1) $colorArr[$a] = -1;

    	$colorArr[0] = 1;
    	$q = array();
        array_push($q, 0);

        while(!empty($q)){
        	$u = array_shift($q);
        	for($a = 0; $a < count($g); $a+=1){
        		if($g[$u][$a] != 0 && $colorArr[$a] == -1){
        			$colorArr[$a] = 1 - $colorArr[$u];
        			array_push($q, $a);
        		} elseif($g[$u][$a] != 0 && $colorArr[$a] == $colorArr[$u]){
        			return false;
        		}
        	}
        }
        $reds = 0;
        for($a = 0; $a < count($colorArr); $a+=1) if($colorArr[$a] == 1) $reds+=1;
        if(edgeCount($g)/2 == $reds*(count($colorArr) - $reds)) return true;
		else return false;
	}

    /*
		- A bad joke
		- See Lemma 4.3's note for justification
    */
    function completeTheGraph($g){
    	for($a = 0; $a < count($g); $a+=1){
    		for($b = 0; $b < count($g); $b+=1){
    			if($g[$a][$b] == 0) $g[$a][$b] = 8;
    		}
    	}
    	return $g;
    }

	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////

    /*
		- Input graphs are assumed to be connected graphs
		- Returned array includes:
			- 1 if isomorphic, 0 if not
			- which leaf of tree was used in GI check
			- the average length of strings in gamma (space used)
		- Returns false if graphs a,b are varient in vertex and/or edge count
		- Calls canonicalNameCompare(a, b) if both graphs have odd cycles.
		- Returns true if graphs are complete bipartite with equivelent bipartitions.
		- Returns false if graphs are complete bipartite with un-equivelent bipartitions.
		- Calls canonicalNameCompare() on inversions of a,b if a,b are both bipartite, 
		  not complete bipartite, and are invarient for edges, vertices
		- Returns false if one of the input graphs is bipartite but the other is not
    */
	function GI($a, $b){
		$a = mux($a); // from graphBuilder.php
		$b = mux($b);		
		
		if(edgeCount($a) != edgeCount($b) || count($a) != count($b)){
			$r = array(0, 0, 0);
			return $r;
		}else{
			$a = mirrorDirections($a); // from graphBuilder.php
			$b = mirrorDirections($b);

			if(!isConnected($a) || !isConnected($b)){
				$r = array(0, 1, 0);
				return $r;
			}

			$aBipartite = isBipartite($a);
			$bBipartite = isBipartite($b);

			if(!$aBipartite && !$bBipartite){
				$temp = canonicalNameCompare($a, $b, false);
				$r = array($temp[0], 2, $temp[1]);
				return $r;
			}elseif($aBipartite && $bBipartite){
				if(completeBipartite($a) && completeBipartite($b)){
					$a = completeTheGraph($a);
					$b = completeTheGraph($b);
					$temp = canonicalNameCompare($a, $b, false);
					$r = array($temp[0], 3, $temp[1]);
					return $r;
				}else{ 
					$temp = canonicalNameCompare($a, $b, true);
					$r = array($temp[0], 4, $temp[1]);
					return $r;
				} 
			}else{
				$r = array(0, 5, 0);
				return $r;
			}
		}
	}

	/*
		Tests the gamma graphs of two input graphs for isomorphism.

		- Modified version of original function described in "theAlgorithms.php"
		- Instead of performing 2D sort, calls upon bt() for GI determination. 
	*/
	function canonicalNameCompare($g1, $g2, $invert){
		$og1 = $g1;
		$og2 = $g2;
		if($invert){
			$g1 = invertGraph($g1);
			$g2 = invertGraph($g2);
		}
		$gamma1 = overColor($g1);
		$gamma2 = overColor($g2);

		$avg = 0;
		for($a = 0; $a < count($gamma1); $a+=1){
			for($b = 0; $b < count($gamma1); $b+=1){
				$gamma1[$a][$b] = $og1[$a][$b] . 'y' . 
					base_convert($gamma1[$a][$b], 3, 10);
				$gamma2[$a][$b] = $og2[$a][$b] . 'y' . 
					base_convert($gamma2[$a][$b], 3, 10);
				$avg += strlen($gamma1[$a][$b]);
			}
			sort($gamma1[$a]);
			sort($gamma2[$a]);
		}
		$avg = $avg/count($gamma1)/count($gamma1);

		$gammaA = array();
		$gammaB = array();
		for($a = 0; $a < count($gamma1); $a+=1){
			$gammaA[$a] = '';
			$gammaB[$a] = '';
			for($b = 0; $b < count($gamma1); $b+=1){
				$gammaA[$a] .= $gamma1[$a][$b];	
				$gammaB[$a] .= $gamma2[$a][$b];	
			}
		}
		$gammaAtemp = $gammaA;
		$gammaBtemp = $gammaB;
		sort($gammaAtemp);
		sort($gammaBtemp);

		if($gammaAtemp === $gammaBtemp){
			$og1 = eightToZero($og1);
			$og2 = eightToZero($og2);
			if(completeBipartite($og1) && 
				edgeCount($og1) == count($og1)*count($og1)/2){
				$ret = true;
			} else {
				$used = array();
				for($a = 0; $a < count($gammaA); $a+=1){
					$used[$a] = -1;
				}

				$permutationMatrix = array();
				for($a = 0; $a < count($gammaA); $a+=1){
					for($b = 0; $b < count($gammaB); $b+=1){
						$permutationMatrix[$a][$b] = 0;
					}
				}
				$ret = bt($gammaA, $gammaB, $og1, $og2, $permutationMatrix, $used, 0);
			}
		} else {
			$ret = false;
		}

		$r = array();
		if($ret){
			$r[0] = 1;
			$r[1] = $avg;
		}else{
			$r[0] = 0;
			$r[1] = $avg;
		}
		return $r;
	}

	/*
		- Takes graph as input, returns gamma matrix
		- See paper for proper explination of purpose 
	*/
	function overColor($g){
		$gamma = array();
		for($a = 0; $a < count($g); $a+=1){
			$gamma[$a] = array();
			for($b = 0; $b < count($g); $b+=1){
				$gamma[$a][$b] = '';
			}
		}

		for($v = 0; $v < count($g); $v+=1){
			$crc = '1';
			$colorA = array();
			for($a = 0; $a < count($g); $a+=1) $colorA[$a] = '2';

			$lock = false;
			$lock2 = false;
			$colorA[$v] = $crc;
			$gamma[$v][$v] = $crc;

			while(!$lock || !$lock2){ 
				if($lock) $lock2 = true;

				if($crc == '0') $o = '1';
				else $o = '0';

				$colorTemp = array();
				$colorTemp = $colorA;
				for($a = 0; $a < count($g); $a+=1){
					if($colorTemp[$a] == $crc){
						for($b = 0; $b < count($g[$a]); $b+=1){
							if($g[$a][$b] != 0){
								$colorA[$b] = $o;
								$gamma[$v][$b] .= $o;
							}
						}
					}
				}
				$crc = $o;

				// Used to distinguish rounds
				for($a = 0; $a < count($gamma[$v]); $a+=1){
					$gamma[$v][$a] .= '2';
				}
				
				$acheck = true;
				for($a = 0; $a < count($colorA) - 1; $a+=1){
					if($colorA[$a] != $colorA[$a+1]){
						$acheck = false;
						break;
					}
				}
				if($acheck || $lock2) $lock = true;
			}
		}
		return $gamma;
	}

	/*
		- Backtracking algorithm used for searching permutation space
		- Returns true if a permutation between input graphs g1, g2 exists,
		else returns false
		- This is guaranteed by the very inefficent but still polytime operation
		of graph permutation and direct matrix equality checking. 
		- Prunes search space by leveraging the gamma matrices for g1 and g2 and
		their invarient nature. "if($gammaA[$row] == $gammaB[$b])"
		- Algorithm itself is still factorial time bounded though.
	*/
	function bt($gammaA, $gammaB, $g1, $g2, $p, $used, $row){
		if($row == count($g1)){
			if(matrixMult(matrixTranspose($p), matrixMult($g1, $p)) !== $g2) 
				return false;
			else return true;
		} else {
			for($b = 0; $b < count($gammaB); $b+=1){
				if($used[$b] == -1){
					if($gammaA[$row] == $gammaB[$b]){
						$tempP = $p;
						$tempUsed = $used;
						$tempP[$row][$b] = 1; 
						$tempUsed[$b] = $row;
						if(f($gammaA, $gammaB, $g1, $g2, $tempP, $tempUsed, $row + 1)) 
							return true;
					}
				}
			}
			return false;
		}
	}
?>