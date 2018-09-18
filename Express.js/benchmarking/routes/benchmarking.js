var express = require('express');
var router = express.Router();

var benchmarking_controller = require('../controllers/benchmarkingController');

router.get('/init', benchmarking_controller.init);
router.get('/matrix_multiplication', benchmarking_controller.matrix_multiplication);
router.get('/sieve_of_atkin', benchmarking_controller.sieve_of_atkin);
router.get('/quicksort', benchmarking_controller.quicksort);

module.exports = router;
