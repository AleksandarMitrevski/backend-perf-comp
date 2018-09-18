#define MATRIX_DIMENSION 100

int * matrix_multiplication(int *matrix_1, int *matrix_2){
	int *result = (int *)malloc(MATRIX_DIMENSION * MATRIX_DIMENSION * sizeof(int));
	for(int i = 0; i < MATRIX_DIMENSION; ++i){
		for(int j = 0; j < MATRIX_DIMENSION; ++j){
			int r_index = i * MATRIX_DIMENSION + j;
			result[r_index] = 0;
			for(int index = 0; index < MATRIX_DIMENSION; ++index){
				result[r_index] += matrix_1[i * MATRIX_DIMENSION + index] + matrix_2[index * MATRIX_DIMENSION + j];
			}
		}
	}
	return result;
}

int main()
{
	int *matrix_1 = (int *)malloc(MATRIX_DIMENSION * MATRIX_DIMENSION * sizeof(int));
	int *matrix_2 = (int *)malloc(MATRIX_DIMENSION * MATRIX_DIMENSION * sizeof(int));
	int *result = matrix_multiplication(matrix_1, matrix_2);
	free(result);
	return 0;
}