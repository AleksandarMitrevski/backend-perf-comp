package com.experiment.benchmarking.services;

import java.io.Serializable;

public class QuicksortData implements Serializable {
	private String[] quicksort_array;
	private int quicksort_array_string_length;
	
	public QuicksortData(String[] quicksort_array, int quicksort_array_string_length) {
		this.quicksort_array = quicksort_array;
		this.quicksort_array_string_length = quicksort_array_string_length;
	}
	
	public String[] getQuicksortArray() {
		return quicksort_array;
	}
	
	public int getQuicksortArrayStringLength() {
		return quicksort_array_string_length;
	}
}
