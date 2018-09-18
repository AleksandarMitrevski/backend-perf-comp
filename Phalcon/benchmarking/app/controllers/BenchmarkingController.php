<?php

use Phalcon\Http\Response;

class BenchmarkingController extends \Phalcon\Mvc\Controller
{
	public function initAction()
    {
		$benchmarkingService = $this->di->get("benchmarkingService");
		
		$memcache = $this->di->get("memcache");
		$memcache->set("matrix_multiplication", $benchmarkingService->getMatrixMultiplicationData());
		$memcache->set("sieve_of_atkin", $benchmarkingService->getSieveOfAtkinData());
		$memcache->set("quicksort", $benchmarkingService->getQuicksortData());
		
		return new Response();
    }
	
	public function matrix_multiplicationAction()
    {
		$data = $this->di->get("memcache")->get("matrix_multiplication");
		$dimension = $data['dimension'];
		$matrix_1 = $data['matrix_1'];
		$matrix_2 = $data['matrix_2'];
		BenchmarkingService::matrix_multiplication($matrix_1, $matrix_2, $dimension);
		return new Response();
    }
	
	public function sieve_of_atkinAction()
    {
		$limit = $this->di->get("memcache")->get("sieve_of_atkin")['limit'];
		BenchmarkingService::sieve_of_atkin($limit);
		return new Response();
    }

	public function quicksortAction()
    {
		$data = $this->di->get("memcache")->get("quicksort");
		$array = $data['array'];
		$size = $data['array_size'];
		BenchmarkingService::quicksort($array, 0, $size - 1);
		return new Response();
    }
}

