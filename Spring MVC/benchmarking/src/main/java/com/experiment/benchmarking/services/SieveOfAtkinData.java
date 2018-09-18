package com.experiment.benchmarking.services;

import java.io.Serializable;

public class SieveOfAtkinData implements Serializable {
	private int sieve_limit;
	
	public SieveOfAtkinData(int sieve_limit) {
		this.sieve_limit = sieve_limit;
	}
	
	public int getSieveLimit() {
		return sieve_limit;
	}
}
