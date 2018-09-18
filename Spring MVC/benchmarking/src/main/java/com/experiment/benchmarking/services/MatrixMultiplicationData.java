package com.experiment.benchmarking.services;

import java.io.Serializable;

public class MatrixMultiplicationData implements Serializable {
	private int matrix_dimension;
	private int[] matrix_1;
	private int[] matrix_2;
	
	public MatrixMultiplicationData(int matrix_dimension, int[] matrix_1, int[] matrix_2) {
		this.matrix_dimension = matrix_dimension;
		this.matrix_1 = matrix_1;
		this.matrix_2 = matrix_2;
	}
	
	public int getMatrixDimension() {
		return matrix_dimension;
	}
	
	public int[] getMatrix1() {
		return matrix_1;
	}
	
	public int[] getMatrix2() {
		return matrix_2;
	}
}
