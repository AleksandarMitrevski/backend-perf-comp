<?php
function matrix_multiplication($matrix_1, $matrix_2, $dimension){
	$result = array();
	for($i = 0; $i < $dimension; ++$i){
		for($j = 0; $j < $dimension; ++$j){
			$r_index = $i * $dimension + $j;
			$result[$r_index] = 0;
			for($index = 0; $index < $dimension; ++$index){
				$result[$r_index] += $matrix_1[$i * $dimension + $index] + $matrix_2[$index * $dimension + $j];
			}
		}
	}
	return $result;
}

$memcache = new Memcache();
$memcache->connect("localhost", 11211);

$data = $memcache->get("matrix_multiplication");
$dimension = $data['dimension'];
$matrix_1 = $data['matrix_1'];
$matrix_2 = $data['matrix_2'];
matrix_multiplication($matrix_1, $matrix_2, $dimension);
?>