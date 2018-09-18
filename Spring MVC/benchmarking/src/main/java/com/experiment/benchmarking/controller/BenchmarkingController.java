package com.experiment.benchmarking.controller;

import java.io.IOException;

import javax.servlet.http.HttpServletRequest;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.ModelAndView;

import com.experiment.benchmarking.services.MatrixMultiplicationData;
import com.experiment.benchmarking.services.SieveOfAtkinData;

import net.spy.memcached.AddrUtil;
import net.spy.memcached.MemcachedClient;

import com.experiment.benchmarking.services.QuicksortData;
import com.experiment.benchmarking.services.BenchmarkingService;

@Controller
public class BenchmarkingController {

	private static MemcachedClient memcache;
	
	@Autowired
    private BenchmarkingService benchmarkingService;

    public void setBenchmarkingService(BenchmarkingService benchmarkingService) {
        this.benchmarkingService = benchmarkingService;
    }
	
	@RequestMapping(value="/init")
	public ResponseEntity<HttpStatus> init(HttpServletRequest request) {
		try{
			if(memcache == null)
				memcache = new MemcachedClient(
					AddrUtil.getAddresses("127.0.0.1:11211"));
			memcache.set("matrix_multiplication", 0, benchmarkingService.getMatrixMultiplicationData());
			memcache.set("sieve_of_atkin", 0, benchmarkingService.getSieveOfAtkinData());
			memcache.set("quicksort", 0, benchmarkingService.getQuicksortData());
			return new ResponseEntity<HttpStatus>(HttpStatus.OK);
		}catch (IOException e){
			return new ResponseEntity<HttpStatus>(HttpStatus.INTERNAL_SERVER_ERROR);
		}
	}
	
	@RequestMapping(value="/matrix_multiplication")
	public ResponseEntity<HttpStatus> matrix_multiplication(HttpServletRequest request) {
		MatrixMultiplicationData data = (MatrixMultiplicationData)memcache.get("matrix_multiplication");
		int[] matrix_1 = data.getMatrix1();
		int[] matrix_2 = data.getMatrix2();
		int matrix_dimension = data.getMatrixDimension();
		benchmarkingService.matrix_multiplication(matrix_1, matrix_2, matrix_dimension);
		return new ResponseEntity<HttpStatus>(HttpStatus.OK);
	}
	
	@RequestMapping(value="/sieve_of_atkin")
	public ResponseEntity<HttpStatus> sieve_of_atkin(HttpServletRequest request) {
		SieveOfAtkinData data = (SieveOfAtkinData)memcache.get("sieve_of_atkin");
		int limit = data.getSieveLimit();
		benchmarkingService.sieve_of_atkin(limit);
		return new ResponseEntity<HttpStatus>(HttpStatus.OK);
	}
	
	@RequestMapping(value="/quicksort")
	public ResponseEntity<HttpStatus> quicksort(HttpServletRequest request) {
		QuicksortData data = (QuicksortData)memcache.get("quicksort");
		String[] array = data.getQuicksortArray();
		benchmarkingService.quicksort(array, 0, array.length - 1);
		return new ResponseEntity<HttpStatus>(HttpStatus.OK);
	}
}
