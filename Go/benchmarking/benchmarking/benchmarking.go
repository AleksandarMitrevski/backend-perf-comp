package benchmarking

import (
	"bytes"
	"encoding/gob"
	"runtime"
	"time"
	"math/rand"
    "net/http"
	"github.com/bradfitz/gomemcache/memcache"
)

type matrix_multiplication_data struct {
	Dimension uint
	Matrix_1 []int
	Matrix_2 []int
}

type sieve_of_atkin_data struct {
	Limit uint
}

type quicksort_data struct {
	Array_size uint
	String_length uint
	Array []string
}

var gc_counter uint = 0
func handle_gc() {
	// we run out of memory very quickly
	gc_counter++
	if gc_counter == 50 {
		runtime.GC()
		gc_counter = 0
	}
}

var memcached *memcache.Client = nil

func generate_matrix(dimension uint) []int {
	size := dimension * dimension
	matrix := make([]int, size)
	for i := uint(0); i < size; i++ {
		matrix[i] = rand.Int()
	}
	return matrix
}

func generate_random_string(length uint) string {
	pool := "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
	var buffer bytes.Buffer
	for i := uint(0); i < length; i++ {
		char_index := rand.Intn(len(pool))
		buffer.WriteByte(pool[char_index])
	}
	return buffer.String()
}

func generate_array(size uint, string_length uint) []string {
	result := make([]string, size)
	for i := uint(0); i < size; i++ {
		result[i] = generate_random_string(string_length)
	}
	return result
}

func marshallStruct(value interface{}) []byte {
	var buffer bytes.Buffer
	encoder := gob.NewEncoder(&buffer)
	err := encoder.Encode(value)
	if err != nil {
		panic("marshallStruct() can not marshall value: " + err.Error())
	}
	return buffer.Bytes()
}

func unmarshalMatrixMultiplicationDataStruct(byteSlice []byte) matrix_multiplication_data {
	buffer := bytes.NewBuffer(byteSlice)
	decoder := gob.NewDecoder(buffer)
	result := matrix_multiplication_data{}
	err := decoder.Decode(&result)
	if err != nil {
		panic("unmarshalMatrixMultiplicationDataStruct() can not unmarshall value: " + err.Error())
	}
	return result
}

func unmarshalSieveOfAtkinDataStruct(byteSlice []byte) sieve_of_atkin_data {
	buffer := bytes.NewBuffer(byteSlice)
	decoder := gob.NewDecoder(buffer)
	result := sieve_of_atkin_data{}
	err := decoder.Decode(&result)
	if err != nil {
		panic("unmarshalSieveOfAtkinDataStruct() can not unmarshall value: " + err.Error())
	}
	return result
}

func unmarshalQuicksortDataStruct(byteSlice []byte) quicksort_data {
	buffer := bytes.NewBuffer(byteSlice)
	decoder := gob.NewDecoder(buffer)
	result := quicksort_data{}
	err := decoder.Decode(&result)
	if err != nil {
		panic("unmarshalQuicksortDataStruct() can not unmarshall value: " + err.Error())
	}
	return result
}

func Init(w http.ResponseWriter, r *http.Request) {
    memcached = memcache.New("127.0.0.1:11211")
	memcached.Timeout = 5000 * time.Millisecond	// large timeout required for quicksort, that's a lot of data being retrieved
	
	var matrix_dimension uint = 100;
	var matrix_1 = generate_matrix(matrix_dimension);
	var matrix_2 = generate_matrix(matrix_dimension);

	var sieve_limit uint = 250000;

	var quicksort_array_size uint = 30000;
	var quicksort_string_length uint = 100;
	var quicksort_array = generate_array(quicksort_array_size, quicksort_string_length);

	data_matrix_multiplication := matrix_multiplication_data {
		Dimension: matrix_dimension,
		Matrix_1: matrix_1,
		Matrix_2: matrix_2,
	}
	data_sieve_of_atkin := sieve_of_atkin_data {
		Limit: sieve_limit,
	}
	data_quicksort := quicksort_data {
		Array_size: quicksort_array_size,
		String_length: quicksort_string_length,
		Array: quicksort_array,
	}
	
	err := memcached.Set(&memcache.Item{
		Key: "matrix_multiplication",
		Value: marshallStruct(data_matrix_multiplication),
	})
	if err != nil {
		panic("Init() can not cache matrix multiplication data")
	}
	err = memcached.Set(&memcache.Item{
		Key: "sieve_of_atkin",
		Value: marshallStruct(data_sieve_of_atkin),
	})
	if err != nil {
		panic("Init() can not cache sieve of atkin data")
	}
	err = memcached.Set(&memcache.Item{
		Key: "quicksort",
		Value: marshallStruct(data_quicksort),
	})
	if err != nil {
		panic("Init() can not cache quicksort data")
	}
}

func matrix_multiplication(matrix_1 []int, matrix_2 []int, dimension uint) []int {
	result := make([]int, dimension * dimension)
	for i := uint(0); i < dimension; i++ {
		for j := uint(0); j < dimension; j++ {
			r_index := i * dimension + j
			//result[r_index] = 0
			for index := uint(0); index < dimension; index++ {
				result[r_index] += matrix_1[i * dimension + index] + matrix_2[index * dimension + j]
			}
		}
	}
	return result
}

func MatrixMultiplication(w http.ResponseWriter, r *http.Request) {
	item, err := memcached.Get("matrix_multiplication")
	if err != nil {
		panic("MatrixMultiplication can not retrieve data: " + err.Error())
	}
	data := unmarshalMatrixMultiplicationDataStruct(item.Value)
	matrix_multiplication(data.Matrix_1, data.Matrix_2, data.Dimension)
	handle_gc()
}

func sieve_of_atkin(limit uint) []bool {
	sieve := make([]bool, limit)
    for i := uint(0); i < limit; i++ {
        sieve[i] = false;
	}
	
	sieve[2] = true;
	sieve[3] = true;
	
    for x := uint(1); x * x < limit; x++ {
        for y := uint(1); y * y < limit; y++ {
            n := (4 * x * x) + (y * y)
            if n <= limit && (n % 12 == 1 || n % 12 == 5){
                sieve[n] = sieve[n] || true
			}
 
            n = (3 * x * x) + (y * y)
            if n <= limit && n % 12 == 7 {
                sieve[n] = sieve[n] || true
			}
 
            n = (3 * x * x) - (y * y)
            if x > y && n <= limit && n % 12 == 11 {
                sieve[n] = sieve[n] || true
			}
        }
    }
 
    for r := uint(5); r * r < limit; r++ {
        if sieve[r] {
            for i := r * r; i < limit; i += r * r {
                sieve[i] = false;
			}
        }
    }
 
    return sieve;
}

func SieveOfAtkin(w http.ResponseWriter, r *http.Request) {
	item, err := memcached.Get("sieve_of_atkin")
	if err != nil {
		panic("SieveOfAtkin can not retrieve data: " + err.Error())
	}
	data := unmarshalSieveOfAtkinDataStruct(item.Value)
	sieve_of_atkin(data.Limit)
	handle_gc()
}

func swap(array []string, index_1 uint, index_2 uint) {
	var temp = array[index_1];
	array[index_1] = array[index_2];
	array[index_2] = temp;
}

func partition(array []string, low uint, high uint) uint {
	pivot := array[high]
	i := (low - 1)

	for j := low; j <= high - 1; j++ { 
		if array[j] <= pivot {
			i++
			swap(array, i, j)
		}
	}
	swap(array, i + 1, high)
	return i + 1
}

func quicksort(array []string, low uint, high uint) {
	if low < high {
        pi := partition(array, low, high)
  
        quicksort(array, low, pi - 1)
        quicksort(array, pi + 1, high)
    }
}

func Quicksort(w http.ResponseWriter, r *http.Request) {
	item, err := memcached.Get("quicksort")
	if err != nil {
		panic("Quicksort can not retrieve data: " + err.Error())
	}
	data := unmarshalQuicksortDataStruct(item.Value)
	array := data.Array
	array_size := data.Array_size
	quicksort(array, 0, array_size - 1)
	handle_gc()
}