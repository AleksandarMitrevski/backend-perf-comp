<?php
function swap(&$array, $index_1, $index_2)
{
	$temp = $array[$index_1];
	$array[$index_1] = $array[$index_2];
	$array[$index_2] = $temp;
}

function partition(&$array, $low, $high) 
{ 
    $pivot = $array[$high];
    $i = ($low - 1);
  
    for($j = $low; $j <= $high - 1; ++$j) 
    { 
        if($array[$j] <= $pivot) 
        { 
            ++$i;
            swap($array, $i, $j); 
        } 
    } 
    swap($array, $i + 1, $high); 
    return $i + 1; 
} 

function quicksort(&$array, $low, $high) 
{ 
    if($low < $high)
    { 
        $pi = partition($array, $low, $high); 
  
        quicksort($array, $low, $pi - 1); 
        quicksort($array, $pi + 1, $high); 
    } 
}

$memcache = new Memcache();
$memcache->connect("localhost", 11211);

$data = $memcache->get("quicksort");
$array = $data['array'];
$size = $data['array_size'];
quicksort($array, 0, $size - 1);
?>