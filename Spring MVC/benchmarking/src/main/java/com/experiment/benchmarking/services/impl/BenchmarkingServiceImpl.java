package com.experiment.benchmarking.services.impl;

import java.io.IOException;
import java.net.InetAddress;
import java.net.InetSocketAddress;
import java.util.Random;

import org.springframework.stereotype.Service;

import com.experiment.benchmarking.services.MatrixMultiplicationData;
import com.experiment.benchmarking.services.SieveOfAtkinData;
import com.experiment.benchmarking.services.QuicksortData;
import com.experiment.benchmarking.services.BenchmarkingService;

@Service
public class BenchmarkingServiceImpl implements BenchmarkingService {

	private static Random PRNG = new Random();
	
	public static int[] generate_matrix(int dimension) {
		int[] matrix = new int[dimension * dimension];
		for(int i = 0, length = dimension * dimension; i < length; ++i){
			matrix[i] = PRNG.nextInt();
		}
		return matrix;
	}
	
	private static String generate_random_string(int size) {
		String pool = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		String result = "";
		for(int i = 0; i < size; ++i){
			int pool_index = PRNG.nextInt(pool.length());
			result += pool.charAt(pool_index);
		}
		return result;
	}
	
	public static String[] generate_array(int size, int string_length) {
		String[] result = new String[size];
		for(int i = 0; i < size; ++i){
			result[i] = generate_random_string(string_length);
		}
		return result;
	}
	
	@Override
	public MatrixMultiplicationData getMatrixMultiplicationData() {
		int matrix_dimension = 100;
		int[] matrix_1 = generate_matrix(matrix_dimension);
		int[] matrix_2 = generate_matrix(matrix_dimension);
		return new MatrixMultiplicationData(matrix_dimension, matrix_1, matrix_2);
	}
	
	@Override
	public SieveOfAtkinData getSieveOfAtkinData() {
		int sieve_limit = 250000;
		return new SieveOfAtkinData(sieve_limit);
	}
	
	@Override
	public QuicksortData getQuicksortData() {
		int quicksort_array_size = 30000;
		int quicksort_string_length = 100;
		String[] quicksort_array = generate_array(quicksort_array_size, quicksort_string_length);
		return new QuicksortData(quicksort_array, quicksort_string_length);
	}
	
	@Override
	public int[] matrix_multiplication(int[] matrix_1, int[] matrix_2, int dimension) {
		int[] result = new int[dimension * dimension];
		for(int i = 0; i < dimension; ++i){
			for(int j = 0; j < dimension; ++j){
				int r_index = i * dimension + j;
				//result[r_index] = 0;
				for(int index = 0; index < dimension; ++index){
					result[r_index] += matrix_1[i * dimension + index] + matrix_2[index * dimension + j];
				}
			}
		}
		return result;
	}

	@Override
	public boolean[] sieve_of_atkin(int limit) {
		boolean[] sieve = new boolean[limit + 1];
	    for (int i = 0; i < limit; ++i)
	        sieve[i] = false;
		
		sieve[2] = true;
		sieve[3] = true;
		
	    for (int x = 1; x * x < limit; ++x)
	    {
	        for (int y = 1; y * y < limit; ++y) 
	        {
	            int n = (4 * x * x) + (y * y);
	            if (n <= limit && (n % 12 == 1 || n % 12 == 5))
	                sieve[n] ^= true;
	 
	            n = (3 * x * x) + (y * y);
	            if (n <= limit && n % 12 == 7)
	                sieve[n] = true;
	 
	            n = (3 * x * x) - (y * y);
	            if (x > y && n <= limit && n % 12 == 11)
	                sieve[n] ^= true;
	        }
	    }
	 
	    for (int r = 5; r * r < limit; r++) {
	        if (sieve[r]) {
	            for (int i = r * r; i < limit; i += r * r)
	                sieve[i] = false;
	        }
	    }
	 
	    return sieve;
	}
	
	private static void swap(String[] array, int index_1, int index_2)
	{
		String temp = array[index_1];
		array[index_1] = array[index_2];
		array[index_2] = temp;
	}

	private static int partition(String[] array, int low, int high) 
	{ 
	    String pivot = array[high];
	    int i = (low - 1);
	  
	    for(int j = low; j <= high - 1; ++j) 
	    { 
	        if(array[j].compareTo(pivot) <= 0) 
	        { 
	            ++i;
	            swap(array, i, j); 
	        } 
	    } 
	    swap(array, i + 1, high); 
	    return i + 1; 
	} 

	private static void quicksort_algorithm(String[] array, int low, int high) 
	{ 
	    if(low < high)
	    { 
	        int pi = partition(array, low, high); 
	  
	        quicksort_algorithm(array, low, pi - 1); 
	        quicksort_algorithm(array, pi + 1, high); 
	    } 
	}

	@Override
	public void quicksort(String[] array, int low, int high) {
		quicksort_algorithm(array, low, high);
	}

}
