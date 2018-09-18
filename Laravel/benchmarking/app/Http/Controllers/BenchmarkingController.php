<?php

namespace App\Http\Controllers;

use \Memcache;
use Illuminate\Http\Request;
use \App\Services\BenchmarkingService;

class BenchmarkingController extends Controller
{
	private $benchmarkingService;
	private $memcache;
	
	public function __construct(BenchmarkingService $benchmarkingService, Memcache $memcache) {
		$this->benchmarkingService = $benchmarkingService;
		$this->memcache = $memcache;
	}
	
    public function init(Request $request) {
		$this->memcache->set("matrix_multiplication", $this->benchmarkingService->getMatrixMultiplicationData());
		$this->memcache->set("sieve_of_atkin", $this->benchmarkingService->getSieveOfAtkinData());
		$this->memcache->set("quicksort", $this->benchmarkingService->getQuicksortData());
	}
	
	public function matrixMultiplication(Request $request) {
		$data = $this->memcache->get('matrix_multiplication');
		$dimension = $data['dimension'];
		$matrix_1 = $data['matrix_1'];
		$matrix_2 = $data['matrix_2'];
		BenchmarkingService::matrix_multiplication($matrix_1, $matrix_2, $dimension);
	}
	
	public function sieveOfAtkin(Request $request) {
		$limit = $this->memcache->get('sieve_of_atkin')['limit'];
		BenchmarkingService::sieve_of_atkin($limit);
	}
	
	public function quicksort(Request $request) {
		$data = $this->memcache->get('quicksort');
		$array = $data['array'];
		$size = $data['array_size'];
		BenchmarkingService::quicksort($array, 0, $size - 1);
	}
}
