require 'dalli'

class BenchmarkingController < ApplicationController
  include BenchmarkingHelper
  
  def init
	@@memcache = Dalli::Client.new('localhost:11211', { value_max_bytes: 10.megabytes }) 
	@@memcache.set("matrix_multiplication", BenchmarkingHelper.generate_matrix_multiplication_data)
	@@memcache.set("sieve_of_atkin", BenchmarkingHelper.generate_sieve_of_atkin_data)
	@@memcache.set("quicksort", BenchmarkingHelper.generate_quicksort_data)
	head :ok
  end

  def matrix_multiplication
	data = @@memcache.get("matrix_multiplication")
	dimension = data["dimension"]
	matrix_1 = data["matrix_1"]
	matrix_2 = data["matrix_2"]
	BenchmarkingHelper.matrix_multiplication(matrix_1, matrix_2, dimension)
	head :ok
  end

  def sieve_of_atkin
	limit = @@memcache.get("sieve_of_atkin")["limit"]
	BenchmarkingHelper.sieve_of_atkin(limit)
	head :ok
  end

  def quicksort
	data = @@memcache.get("quicksort")
	array = data["array"]
	size = data["array_size"]
	BenchmarkingHelper.quicksort(array, 0, size - 1)
	head :ok
  end
end
