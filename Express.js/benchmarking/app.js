var express = require('express');
var benchmarkingRouter = require('./routes/benchmarking');
var app = express();

app.use('', benchmarkingRouter);

module.exports = app;
