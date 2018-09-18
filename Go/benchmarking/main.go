package main

import (
    "net/http"
    "log"
	"./benchmarking"
)

func main() {
	// register routes
    http.HandleFunc("/init", benchmarking.Init)
	http.HandleFunc("/matrix_multiplication", benchmarking.MatrixMultiplication)
	http.HandleFunc("/sieve_of_atkin", benchmarking.SieveOfAtkin)
	http.HandleFunc("/quicksort", benchmarking.Quicksort)
	
	// listen and serve
    err := http.ListenAndServe(":8080", nil)
    if err != nil {
        log.Fatal("ListenAndServe: ", err)
    }
}