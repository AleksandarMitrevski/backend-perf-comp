using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace benchmarking.Services
{
    [Serializable]
    public class MatrixMultiplicationData
    {
        public int MatrixDimension { get; }
        public int[] Matrix1 { get; }
        public int[] Matrix2 { get; }
        
        public MatrixMultiplicationData(int matrix_dimension, int[] matrix_1, int[] matrix_2)
        {
            this.MatrixDimension = matrix_dimension;
            this.Matrix1 = matrix_1;
            this.Matrix2 = matrix_2;
        }
    }

    [Serializable]
    public class SieveOfAtkinData
    {
        public int SieveLimit { get; }

        public SieveOfAtkinData(int sieve_limit)
        {
            this.SieveLimit = sieve_limit;
        }
    }

    [Serializable]
    public class QuicksortData
    {
        public string[] QuicksortArray { get; }
        public int QuicksortArrayStringLength { get; }
        
        public QuicksortData(string[] quicksort_array, int quicksort_array_string_length)
        {
            this.QuicksortArray = quicksort_array;
            this.QuicksortArrayStringLength = quicksort_array_string_length;
        }
    }
}