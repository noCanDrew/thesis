<?php

	/*
		- Makes random graph with vertex count = $n via:
			1. Returns a simple graph
			2. Returns a simple directed graph
			3. Returns an unweighted bipartite graph
			4. Returns a complete bipartite graph
			5. Returns a weighted bipartite graph
			6. Returns a loopy graph
			7. Returns a loopy wieghted graph
			8. Returns a loopy weighted multigraph
			9. Returns a loopy weighted directed multigraph
		- All non-bipartite graphs returned have a rough density between 0.25 and 0.75
		- Bipartite graphs returned have a rough density between 0.25 and 0.75 among 
		eligable edges
	*/
	function makeRandGraph($n, $option){
		if($n < 5) $n = 5;
		if($option == 1) return makeRandSimpleGraph($n);
		elseif($option == 2) return makeRandSimpleDirectedGraph($n);
		elseif($option == 3) return makeRandBipartiteGraph($n);
		elseif($option == 4) return makeCompleteBipartiteGraph($n);
		elseif($option == 5) return makeRandWeightedBipartiteGraph($n);
		elseif($option == 6) return makeRandLoopyGraph($n);
		elseif($option == 7) return makeRandLoopyWeightedGraph($n);
		elseif($option == 8) return makeRandLoopyWeightedMultiGraph($n);
		else return makeRandLoopyWeightedDirectedMultiGraph($n);
	}

	function makeRandGraphSimplified($n, $option){
		if($n < 5) $n = 5;
		if($option == 1) return makeRandSimpleGraph($n);
		elseif($option == 2) return makeRandBipartiteGraph($n);
		elseif($option == 3) return makeRandLoopyGraph($n);
	}

	function makeRandPermuteMatrix($n){
		$used = array();
		$temp = array();
		for($a = 0; $a < $n; $a+=1){
	      	do{																		  
		      	$b = rand(0, $n - 1);
	      	}while(in_array($b, $used));
	      	$used[$a] = $b;
	      	
	      	$row = array();
	      	for($c = 0; $c < $n; $c+=1){
	      		if($c == $b) $row[$c] = 1;
	      		else $row[$c] = 0;
	      	}
	      	$temp[$a] = $row;
		}
		return $temp;
	}

	function makeRandSimpleGraph($n){
		while(true){
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		      		$p = rand(0,99); 
		      		if($b == $a || $p < $density){
		      			$g[$a][$b] = 0;
		      			$g[$b][$a] = 0;
		      		} 
		      		else{
		      			$g[$a][$b] = 1;
		      			$g[$b][$a] = 1;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	function makeRandSimpleDirectedGraph($n){
		while(true){
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		      		$p = rand(0,99); 
		      		if($b == $a || $p < $density){
		      			$g[$a][$b] = 0;
		      		} 
		      		else{
		      			$g[$a][$b] = 1;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	function makeRandBipartiteGraph($n){
		while(true){
			$bipSize = rand(1, $n-1);
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		    		$p = rand(0,99); 
		      		if($p < $density || ($b < $bipSize && $a < $bipSize) || 
		      		($b >= $bipSize && $a >= $bipSize)){
		      			$g[$a][$b] = 0;
		      			$g[$b][$a] = 0;
		      		} 
		      		else{
		      			$g[$a][$b] = 1;
		      			$g[$b][$a] = 1;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	function makeCompleteBipartiteGraph($n){
		while(true){
			$bipSize = rand(1, $n-1);
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		      		if(($b < $bipSize && $a < $bipSize) || 
		      		($b >= $bipSize && $a >= $bipSize)){
		      			$g[$a][$b] = 0;
		      			$g[$b][$a] = 0;
		      		} 
		      		else{
		      			$g[$a][$b] = 1;
		      			$g[$b][$a] = 1;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	function makeRandWeightedBipartiteGraph($n){
		while(true){
			$bipSize = rand(1, $n-1);
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		    		$p = rand(0,99); 
		      		if($p < $density || ($b < $bipSize && $a < $bipSize) || 
		      		($b >= $bipSize && $a >= $bipSize)){
		      			$g[$a][$b] = 0;
		      			$g[$b][$a] = 0;
		      		} 
		      		else{
		      			$w = rand(1, 7);
		      			$g[$a][$b] = $w;
		      			$g[$b][$a] = $w;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	function makeRandLoopyGraph($n){
		while(true){
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		      		$p = rand(0,99); 
		      		if($p < $density){
		      			$g[$a][$b] = 0;
		      			$g[$b][$a] = 0;
		      		} 
		      		else{
		      			$g[$a][$b] = 1;
		      			$g[$b][$a] = 1;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	function makeRandLoopyWeightedGraph($n){
		while(true){
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		      		$p = rand(0,99); 
		      		if($p < $density){
		      			$g[$a][$b] = 0;
		      			$g[$b][$a] = 0;
		      		} 
		      		else{
		      			$w = rand(1, 7);
		      			$g[$a][$b] = $w;
		      			$g[$b][$a] = $w;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	function makeRandLoopyWeightedMultiGraph($n){
		while(true){
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		      		$p = rand(0,99); 
		      		if($p < $density){
		      			$g[$a][$b] = 0;
		      			$g[$b][$a] = 0;
		      		} 
		      		else{
		      			$num = rand(1,9);
		      			$w = array();
		      			for($c = 0; $c < $num; $c+=1){
		      				$w[$c] = rand(1, 7);
		      			}
		      			$g[$a][$b] = $w;
		      			$g[$b][$a] = $w;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	function makeRandLoopyWeightedDirectedMultiGraph($n){
		while(true){
			$g = array();
			for($a = 0; $a < $n; $a+=1){
				$g[$a] = array();
			}

			$density = rand(25,75);
			for($a = 0; $a < $n; $a+=1){
		    	for($b = 0; $b < $n; $b+=1){
		      		$p = rand(0,99); 
		      		if($p < $density){
		      			$g[$a][$b] = 0;
		      		} 
		      		else{
		      			$num = rand(1,9);
		      			$w = array();
		      			for($c = 0; $c < $num; $c+=1){
		      				$w[$c] = rand(1, 7);
		      			}
		      			$g[$a][$b] = $w;
		      		} 
		    	}
		  	}
		  	if(isConnected($g)) return $g; 
		}
	}

	/*
		- Builds a random k-regular graph
		- In order to avoid baising in construction, a graph must be built and
		then tested for k-regularity. If it fails, a new graph is built and
		tested the same way. Repeat ad nauseam.
		- Not efficient
		- Process explained at: 
		https://egtheory.wordpress.com/2012/03/29/random-regular-graphs/
	*/
	function kRegularBuilder($n, $k){
		$numEdges = 0;
		$g = array();
		$round = 0;
		while($numEdges != $n*$k/2 || !isConnected($g)){
			$numEdges = 0;
			$break = false;
			if($round > 1000) break; // fail safe to prevent over trying

			$points = range(0,$n*$k-1);
			unset($buckets);
			$buckets = array();
			for($a = 0; $a < $n; $a++){
				for($b = 0; $b < $k; $b++){
					$rand = rand(0, count($points) - 1);
					$buckets[$a][$b] = $points[$rand];
					unset($points[$rand]);
					$points = array_values($points);
				}
			}

			// pair the points
			$points = range(0, $n*$k-1);
			unset($pairs);
			$pairs = array();
			for($a = 0; $a < 0.5*$n*$k; $a++){
				$rand = rand(0, count($points) - 1);
				$x = $points[$rand];
				unset($points[$rand]);
				$points = array_values($points);
				$rand = rand(0, count($points) - 1);
				$y = $points[$rand];
				unset($points[$rand]);
				$points = array_values($points);
				$pairs[$a] = array($x, $y);
			}

			// build graph
			$g = array();
			$offset = 0;
			for($a = 0; $a < count($buckets); $a++){
				for($b = 0; $b < count($buckets); $b++){
					$g[$a][$b] = 0;
				}
			}
			for($a = 0; $a < count($pairs); $a++){
				$pair = array();
				for($b = 0; $b < count($buckets); $b++){
					if(count(array_intersect($pairs[$a], $buckets[$b])) > 0){
						array_push($pair, $b);
						if(count($pair) == 2){
							if($g[$pair[0]][$pair[1]] == 1){
								$break = true;
								break;
							}
							$g[$pair[0]][$pair[1]] = 1;
							$g[$pair[1]][$pair[0]] = 1;
							$numEdges += 1;
							unset($pair);
							$pair = array();
							break;
						}
					}
					if($b == count($buckets) - 1){
						$break = true;
						$g[$pair[0]][$pair[0]] = 1;
						unset($pair);
						$pair = array();
						break;
					}
				}
				if($break) break;
			}
		}
		return $g;
	}

	/*
		Builds and returns the [n, k]-johnson graph
	*/
	function johnyBuilder($n, $k){
		$GLOBALS["list"] = array();
		$arr = range(1, $n);
		$data = array(); 

		/*
			- Modified version of: https://www.geeksforgeeks.org/print-all-possible-
			combinations-of-r-elements-in-a-given-array-of-size-n/
		*/
		function combinations($arr, $data, $start, $end, $index, $k){
		    if($index == $k){
		    	array_push($GLOBALS["list"], $data);
		    	return;
		    } else {
				for($a = $start; $a <= $end && $end - $a + 1 >= $k - $index; $a++){ 
			        $data[$index] = $arr[$a]; 
			        combinations($arr, $data, $a + 1, $end, $index + 1, $k);
			    } 
		    }
		} 

		combinations($arr, $data, 0, $n - 1, 0, $k); 
		$johny = $GLOBALS["list"];
		$g = array();

		for($a = 0; $a < count($johny); $a++){
			for($b = 0; $b < count($johny); $b++){
				if($a != $b){
					$intersect = array_intersect($johny[$a], $johny[$b]);
					if(count($intersect) == $k-1) $g[$a][$b] = 1;
					else $g[$a][$b] = 0;
				} else $g[$a][$b] = 0;
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
		- Utility used to convert directed graphs into undirected graphs
		- Preserves bijective properties due to lemma 4.8
	*/
    function mirrorDirections($g){
    	for($a = 0; $a < count($g); $a+=1){
    		for($b = 0; $b < count($g); $b+=1){
    			if($g[$a][$b] != 0 && $g[$b][$a] == 0) $g[$b][$a] = -1;
    		}
    	}
    	return $g;  
    }

    /*
		- Utility used to convert mutli-edges into singular weighted edge.
		- Preserves bijective properties due to lemma 4.9
    */
    function mux($g){
    	for($a = 0; $a < count($g); $a+=1){
    		for($b = 0; $b < count($g); $b+=1){
    			if(is_array($g[$a][$b])){
    				$temp = $g[$a][$b];
    				sort($temp);
    				$w = '';
    				for($c = 0; $c < count($temp); $c+=1){
    					$w .= $temp[$c] . '9';
    				} 
    				$g[$a][$b] = $w;
    			}
    		}
    	}
    	return $g; 
    }

	/*
		Assumes $m1 and $m2 are equally sized
	*/
	function matrixMult($m1, $m2){
		$m1 = mux($m1);
		$m2 = mux($m2);
		$ans = array();
		for($a = 0; $a < count($m1); $a+=1){
			for($b = 0; $b < count($m1); $b+=1){
				$ans[$a][$b] = 0;
				for($c = 0; $c < count($m1); $c+=1){
					$ans[$a][$b] += $m1[$a][$c] * $m2[$c][$b];
				}
			}
		}
		return($ans);
	}

	function matrixTranspose($m){
		return array_map(NULL, ...$m);
	}

	/*
		Prints adjacency matrix in a nice, human readable format
	*/
	function printMatrix($m){
		for($a = 0; $a < count($m); $a+=1){ 
			$temp = $m[$a];
	    	for($b = 0; $b < count($temp); $b+=1){
	      		if(!is_array($temp[$b])) echo $temp[$b] . "&nbsp;&nbsp;";
	      		else{
	      			echo '{';
	      			for($c = 0; $c < count($temp[$b]); $c+=1) echo $temp[$b][$c] . ', ';
	      			echo "}&nbsp;&nbsp;";
	      		}
	    	}
	    	echo '<br>';
	  	}
	}

	/*
		Determines if a graph is continuous or not. 
	*/
    function isConnected($g){
    	$g = mux($g);

    	$colorArr = array();
    	for($a = 0; $a < count($g); $a+=1) $colorArr[$a] = -1;

    	$colorArr[0] = 1; // src
    	$q = array();
        array_push($q, 0);

        while(!empty($q)){
        	$u = array_shift($q);
        	for($a = 0; $a < count($g); $a+=1){
        		if(($g[$u][$a] != 0 || $g[$a][$u] != 0) && $colorArr[$a] == -1){
        			$colorArr[$a] = 1 - $colorArr[$u];
        			array_push($q, $a);
        		} 
        	}
        	if(!in_array(-1, $colorArr)) break;
        }
    	if(in_array(-1, $colorArr))return false;
    	else return true;
    }

	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////

    /*
		- Slightly alters input graph by either:
			1. Transplanting an edge
			2. Removing an edge
			3. Changing an edge weight

		- Lock is for making sure graph has very high likelyhood of being continuous.
		But some graphs may not be able to be transformed in these ways, so after 10
		tries, just return the non-continuous graph (a trivial case for GI()). 
    */
    function salt($g, $option){
    	$lock = 0;
    	while($lock < 10){
	    	if($option == 1) $g = salt1($g);
	    	elseif($option == 2) $g = salt2($g);
	    	else $g = salt3($g);

	    	if(isConnected($g)) break;
	    	else $lock += 1;
	    }

    	return $g;
    }

    /*
		Transplant edge
    */
    function salt1($g){
    	$n = rand(1,10);
    	for($m = 0; $m < $n; $m+=1){
    		$count = 0;
	    	while($count < 1000){
	    		$v0 = rand(0, count($g)-1);
		    	$v1 = rand(0, count($g)-1);
		    	$v2 = rand(0, count($g)-1);
		    	$v3 = rand(0, count($g)-1);
		    	if($v0 != $v2 && $v1 != $v3 && $v0 != $v3 && $v1 != $v2 &&
		    	  ($g[$v0][$v1] != 0 && $g[$v2][$v3] == 0)) break;
		    	else $count += 1;
	    	}

	    	$temp = $g[$v0][$v1];
	    	$g[$v0][$v1] = $g[$v2][$v3];
	    	$g[$v2][$v3] = $temp;
    	}
    	return $g;
    }

    /*
		Remove edge
    */
    function salt2($g){
    	$lock = false;
    	for($a = 0; $a < count($g); $a+=1){
    		for($b = 0; $b < count($g); $b+=1){
    			if($g[$a][$b] != 0 || $g[$b][$a] != 0){
    				$g[$a][$b] = 0;
    				$g[$b][$a] = 0;
    				$lock = true;
    				break;
    			}
    		}
    		if($lock) break;
    	}
    	return $g;
    }

    /*
		Change edge weight
    */
	function salt3($g){
		$lock = false;
    	for($a = 0; $a < count($g); $a+=1){
    		for($b = 0; $b < count($g); $b+=1){
    			if($g[$a][$b] != 0){
    				$g[$a][$b] = 1023456789;
    				$lock = true;
    				break;
    			}
    		}
    		if($lock) break;
    	}
    	return $g;
	}
?>