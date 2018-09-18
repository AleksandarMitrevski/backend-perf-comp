<?php

namespace App\Services;

class BenchmarkingService
{
	private static function generate_matrix($dimension)
	{
		$matrix = array();
		for($i = 0, $length = $dimension * $dimension; $i < $length; ++$i){
			$matrix[$i] = mt_rand();
		}
		return $matrix;
	}

	private static function generate_random_string($length)
	{		
		$pool = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$result = "";
		for($i = 0; $i < $length; ++$i){
			$pool_index = mt_rand(0, strlen($pool) - 1);
			$result .= substr($pool, $pool_index, 1);
		}
		return $result;
	}

	private static function generate_array($size, $string_length)
	{
		$result = array();
		for($i = 0; $i < $size; ++$i){
			$result[$i] = self::generate_random_string($string_length);
		}
		return $result;
	}
	
	public function getMatrixMultiplicationData() {
		$matrix_dimension = 100;
		$matrix_1 = self::generate_matrix($matrix_dimension);
		$matrix_2 = self::generate_matrix($matrix_dimension);
		return array(
			"dimension" => $matrix_dimension,
			"matrix_1" => $matrix_1,
			"matrix_2" => $matrix_2
		);
	}
	
	public function getSieveOfAtkinData() {
		$sieve_limit = 250000;
		return array(
			"limit" => $sieve_limit
		);
	}
	
	public function getQuicksortData() {
		$quicksort_array_size = 30000;
		$quicksort_string_length = 100;
		$quicksort_array = self::generate_array($quicksort_array_size, $quicksort_string_length);
		return array(
			"array_size" => $quicksort_array_size,
			"string_length" => $quicksort_string_length,
			"array" => $quicksort_array
		);
	}
	
	public static function matrix_multiplication($matrix_1, $matrix_2, $dimension){
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
	
	public static function sieve_of_atkin($limit)
	{
		$sieve[$limit] = 0;
		for ($i = 0; $i < $limit; ++$i)
			$sieve[$i] = false;
		
		$sieve[2] = true;
		$sieve[3] = true;
		
		for ($x = 1; $x * $x < $limit; ++$x)
		{
			for ($y = 1; $y * $y < $limit; ++$y) 
			{
				$n = (4 * $x * $x) + ($y * $y);
				if ($n <= $limit && ($n % 12 == 1 || $n % 12 == 5))
					$sieve[$n] ^= true;
	 
				$n = (3 * $x * $x) + ($y * $y);
				if ($n <= $limit && $n % 12 == 7)
					$sieve[$n] = true;
	 
				$n = (3 * $x * $x) - ($y * $y);
				if ($x > $y && $n <= $limit && $n % 12 == 11)
					$sieve[$n] ^= true;
			}
		}
	 
		for ($r = 5; $r * $r < $limit; $r++) {
			if ($sieve[$r]) {
				for ($i = $r * $r; $i < $limit; $i += $r * $r)
					$sieve[$i] = false;
			}
		}
	 
		return $sieve;
	}
	
	private static function swap(&$array, $index_1, $index_2)
	{
		$temp = $array[$index_1];
		$array[$index_1] = $array[$index_2];
		$array[$index_2] = $temp;
	}

	private static function partition(&$array, $low, $high) 
	{ 
		$pivot = $array[$high];
		$i = ($low - 1);
	  
		for($j = $low; $j <= $high - 1; ++$j) 
		{ 
			if($array[$j] <= $pivot) 
			{ 
				++$i;
				self::swap($array, $i, $j); 
			} 
		} 
		self::swap($array, $i + 1, $high); 
		return $i + 1; 
	} 

	public static function quicksort(&$array, $low, $high) 
	{ 
		if($low < $high)
		{ 
			$pi = self::partition($array, $low, $high); 
	  
			self::quicksort($array, $low, $pi - 1); 
			self::quicksort($array, $pi + 1, $high); 
		} 
	}
}