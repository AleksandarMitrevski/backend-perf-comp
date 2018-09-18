from django.urls import path
from benchmarkingapp import views

urlpatterns = [
	path('init', views.init, name='init'),
	path('matrix_multiplication', views.matrix_multiplication, name='matrix_multiplication'),
	path('sieve_of_atkin', views.sieve_of_atkin, name='sieve_of_atkin'),
	path('quicksort', views.quicksort, name='quicksort'),
]