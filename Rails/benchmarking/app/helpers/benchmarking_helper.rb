module BenchmarkingHelper
	def self.generate_matrix(dimension)
		Array.new(dimension * dimension) { 
			Random.rand() < 0.5 ? -Random.rand(1 << 32) : Random.rand((1 << 32) - 1)
		}
	end
	
	def self.generate_random_string(length)
		pool = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
		result = ""
		(0...length).each do |i|
			pool_index = Random.rand(pool.length)
			result += pool[pool_index]
		end
		result
	end
	
	def self.generate_array(size, string_length)
		Array.new(size) { self.generate_random_string(string_length) }
	end
	
	def self.generate_matrix_multiplication_data
		matrix_dimension = 100
		matrix_1 = self.generate_matrix(matrix_dimension)
		matrix_2 = self.generate_matrix(matrix_dimension)
		{
			"dimension" => matrix_dimension,
			"matrix_1" => matrix_1,
			"matrix_2" => matrix_2
		}
	end
	
	def self.generate_sieve_of_atkin_data
		sieve_limit = 250000
		{
			"limit" => sieve_limit
		}
	end
	
	def self.generate_quicksort_data
		quicksort_array_size = 30000
		quicksort_string_length = 100
		quicksort_array = self.generate_array(quicksort_array_size, quicksort_string_length)
		{
			"array_size" => quicksort_array_size,
			"string_length" => quicksort_string_length,
			"array" => quicksort_array
		}
	end
	
	def self.matrix_multiplication(matrix_1, matrix_2, dimension)
		result = Array.new(dimension * dimension, 0)
		(0...dimension).each do |i|
			(0...dimension).each do |j|
				r_index = i * dimension + j;
				#result[r_index] = 0
				(0...dimension).each do |index|
					result[r_index] += matrix_1[i * dimension + index] + matrix_2[index * dimension + j]
				end
			end
		end
		result
	end
	
	def self.sieve_of_atkin(limit)
		sieve = Array.new(limit, false)
		#for ($i = 0; $i < $limit; ++$i)
		#	$sieve[$i] = false;
		
		sieve[2] = true
		sieve[3] = true
		
		x = 1
		while x * x < limit
			y = 1
			while y * y < limit
				n = (4 * x * x) + (y * y)
				if n <= limit && (n % 12 == 1 || n % 12 == 5)
					sieve[n] ||= true
				end
	 
				n = (3 * x * x) + (y * y)
				if n <= limit && n % 12 == 7
					sieve[n] ||= true
				end
	 
				n = (3 * x * x) - (y * y)
				if x > y && n <= limit && n % 12 == 11
					sieve[n] ||= true
				end
					
				y += 1
			end
			x += 1
		end
	 
		r = 5
		while r * r < limit
			if sieve[r]
				((r * r)...limit).step(r * r) do |i|
					sieve[i] = false
				end	
			end
			r += 1
		end

		sieve
	end
	
	def self.swap(array, index_1, index_2)
		temp = array[index_1]
		array[index_1] = array[index_2]
		array[index_2] = temp
	end

	def self.partition(array, low, high) 
		pivot = array[high]
		i = (low - 1)
		
		(low..high-1).each do |j|
			if array[j] <= pivot
				i += 1
				swap(array, i, j)
			end
		end
		swap(array, i + 1, high)
		i + 1
	end

	def self.quicksort(array, low, high) 
		if low < high
			pi = partition(array, low, high)
			
			quicksort(array, low, pi - 1)
			quicksort(array, pi + 1, high)
		end
	end
end
