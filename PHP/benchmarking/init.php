<?php
function generate_matrix($dimension)
{
	$matrix = array();
	for($i = 0, $length = $dimension * $dimension; $i < $length; ++$i){
		$matrix[$i] = mt_rand();
	}
	return $matrix;
}

function generate_random_string($length)
{		
	$pool = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$result = "";
	for($i = 0; $i < $length; ++$i){
		$pool_index = mt_rand(0, strlen($pool) - 1);
		$result .= substr($pool, $pool_index, 1);
	}
	return $result;
}

function generate_array($size, $string_length)
{
	$result = array();
	for($i = 0; $i < $size; ++$i){
		$result[$i] = generate_random_string($string_length);
	}
	return $result;
}

$matrix_dimension = 100;
$matrix_1 = generate_matrix($matrix_dimension);
$matrix_2 = generate_matrix($matrix_dimension);

$sieve_limit = 250000;

$quicksort_array_size = 30000;
$quicksort_string_length = 100;
$quicksort_array = generate_array($quicksort_array_size, $quicksort_string_length);

$memcache = new Memcache();
$memcache->connect("localhost", 11211);

$memcache->set("matrix_multiplication", array(
	"dimension" => $matrix_dimension,
	"matrix_1" => $matrix_1,
	"matrix_2" => $matrix_2
));
$memcache->set("sieve_of_atkin", array(
	"limit" => $sieve_limit
));
$memcache->set("quicksort", array(
	"array_size" => $quicksort_array_size,
	"string_length" => $quicksort_string_length,
	"array" => $quicksort_array
));
?>