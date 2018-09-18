Rails.application.routes.draw do
  get 'benchmarking/init'

  get 'benchmarking/matrix_multiplication'

  get 'benchmarking/sieve_of_atkin'

  get 'benchmarking/quicksort'

  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
end
