require 'test_helper'

class BenchmarkingControllerTest < ActionDispatch::IntegrationTest
  test "should get init" do
    get benchmarking_init_url
    assert_response :success
  end

  test "should get matrix_multiplication" do
    get benchmarking_matrix_multiplication_url
    assert_response :success
  end

  test "should get sieve_of_atkin" do
    get benchmarking_sieve_of_atkin_url
    assert_response :success
  end

  test "should get quicksort" do
    get benchmarking_quicksort_url
    assert_response :success
  end

end
