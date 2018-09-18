var chance = require('chance')();
var Memcached = require('memcached');

var memcache = function(){
	return new Memcached("127.0.0.1:11211", {
		maxValue: 10485760
	});
}();

exports.init = function(req, res) {
	var generate_matrix = function(dimension){
		var matrix = [];
		for(var i = 0, length = dimension * dimension; i < length; ++i){
			matrix.push(chance.integer());
		}
		return matrix;
	},
	generate_random_string = function(length){
		return chance.string({
			"pool": "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",
			"length": length
		});
	},
	generate_array = function(size, string_length){
		var result = [];
		for(var i = 0; i < size; ++i){
			result.push(generate_random_string(string_length));
		}
		return result;
	};
	
	var matrix_dimension = 100;
	var matrix_1 = generate_matrix(matrix_dimension);
	var matrix_2 = generate_matrix(matrix_dimension);

	var sieve_limit = 250000;

	var quicksort_array_size = 30000;
	var quicksort_string_length = 100;
	var quicksort_array = generate_array(quicksort_array_size, quicksort_string_length);
	
	memcache.set("matrix_multiplication", {
		"dimension": matrix_dimension,
		"matrix_1": matrix_1,
		"matrix_2": matrix_2
	}, 7200, function(err){
		
	});
	memcache.set("sieve_of_atkin", {
		"limit": sieve_limit
	}, 7200, function(err){
		
	});
	memcache.set("quicksort", {
		"array_size": quicksort_array_size,
		"string_length": quicksort_string_length,
		"array": quicksort_array
	}, 7200, function(err){
		
	});
	
	// note that even on failure it returns 200
	res.status(200).send("");
};

var matrix_multiplication = function(matrix_1, matrix_2, dimension){
	var result = [];
	for(var i = 0; i < dimension; ++i){
		for(var j = 0; j < dimension; ++j){
			var r_index = i * dimension + j;
			result[r_index] = 0;
			for(var index = 0; index < dimension; ++index){
				result[r_index] += matrix_1[i * dimension + index] + matrix_2[index * dimension + j];
			}
		}
	}
	return result;
};

exports.matrix_multiplication = function(req, res) {
	memcache.get("matrix_multiplication", function(err, data){
		if(!err){
			matrix_multiplication(data.matrix_1, data.matrix_2, data.dimension);
			res.status(200).send("");
		}else{
			res.status(500);
		}
	});
};

var sieve_of_atkin = function(limit) {
	var sieve = [];
    for (var i = 0; i < limit; ++i)
        sieve[i] = false;
	
	sieve[2] = true;
	sieve[3] = true;
	
    for (var x = 1; x * x < limit; ++x)
    {
        for (var y = 1; y * y < limit; ++y) 
        {
            var n = (4 * x * x) + (y * y);
            if (n <= limit && (n % 12 == 1 || n % 12 == 5))
                sieve[n] = sieve[n] || true;
 
            n = (3 * x * x) + (y * y);
            if (n <= limit && n % 12 == 7)
                sieve[n] = sieve[n] || true;
 
            n = (3 * x * x) - (y * y);
            if (x > y && n <= limit && n % 12 == 11)
                sieve[n] = sieve[n] || true;
        }
    }
 
    for (var r = 5; r * r < limit; r++) {
        if (sieve[r]) {
            for (var i = r * r; i < limit; i += r * r)
                sieve[i] = false;
        }
    }
 
    return sieve;
};

exports.sieve_of_atkin = function(req, res) {
	memcache.get("sieve_of_atkin", function(err, data){
		if(!err){
			sieve_of_atkin(data.limit);
			res.status(200).send("");
		}else{
			res.status(500);
		}
	});
};

var swap = function(array, index_1, index_2) {
	var temp = array[index_1];
	array[index_1] = array[index_2];
	array[index_2] = temp;
},
partition = function(array, low, high) { 
	var pivot = array[high];
	var i = (low - 1);

	for(var j = low; j <= high - 1; ++j) 
	{ 
		if(array[j] <= pivot) 
		{ 
			++i;
			swap(array, i, j); 
		} 
	} 
	swap(array, i + 1, high); 
	return i + 1; 
}, 
quicksort = function(array, low, high) {
    if(low < high)
    { 
        var pi = partition(array, low, high); 
  
        quicksort(array, low, pi - 1); 
        quicksort(array, pi + 1, high); 
    } 
};

exports.quicksort = function(req, res) {
	memcache.get("quicksort", function(err, data){
		if(!err){
			var array = data.array;
			var size = data.array_size;
			quicksort(array, 0, size - 1);
			res.status(200).send("");
		}else{
			res.status(500);
		}
	});
};