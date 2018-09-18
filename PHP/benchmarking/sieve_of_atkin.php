<?php
function sieve_of_atkin($limit)
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
$memcache = new Memcache();
$memcache->connect("localhost", 11211);

$data = $memcache->get("sieve_of_atkin");
$limit = $data['limit'];
sieve_of_atkin($limit);
?>