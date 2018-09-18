package com.experiment.benchmarking.services;

import java.io.IOException;

public interface BenchmarkingService {
	
	MatrixMultiplicationData getMatrixMultiplicationData();
	SieveOfAtkinData getSieveOfAtkinData();
	QuicksortData getQuicksortData();
	
	int[] matrix_multiplication(int[] matrix_1, int[] matrix_2, int dimension);
	boolean[] sieve_of_atkin(int limit);
	void quicksort(String[] array, int low, int high);
}
