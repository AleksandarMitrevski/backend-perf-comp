using System.Web;
using System.Web.Http;
using System.Web.SessionState;
using benchmarking.Services;
using Enyim.Caching;
using Enyim.Caching.Memcached;

namespace benchmarking.Controllers
{
    public class BenchmarkingController : ApiController
    {
        private IBenchmarkingService benchmarkingService;
        private static MemcachedClient memcache;

        public BenchmarkingController(IBenchmarkingService service)
        {
            this.benchmarkingService = service;
            if(BenchmarkingController.memcache == null)
                BenchmarkingController.memcache = new MemcachedClient();
        }

        [AcceptVerbs("GET")]
        [Route("benchmarking/init")]
        public IHttpActionResult Init()
        {
            memcache.Store(StoreMode.Set, "matrix_multiplication", benchmarkingService.GetMatrixMultiplicationData());
            memcache.Store(StoreMode.Set, "sieve_of_atkin", benchmarkingService.GetSieveOfAtkinData());
            memcache.Store(StoreMode.Set, "quicksort", benchmarkingService.GetQuicksortData());
            return Ok();
        }

        [AcceptVerbs("GET")]
        [Route("benchmarking/matrix_multiplication")]
        public IHttpActionResult MatrixMultiplication()
        {
            MatrixMultiplicationData data = memcache.Get<MatrixMultiplicationData>("matrix_multiplication");
            benchmarkingService.MatrixMultiplication(data.Matrix1, data.Matrix2, data.MatrixDimension);
            return Ok();
        }

        [AcceptVerbs("GET")]
        [Route("benchmarking/sieve_of_atkin")]
        public IHttpActionResult SieveOfAtkin()
        {
            SieveOfAtkinData data = memcache.Get<SieveOfAtkinData>("sieve_of_atkin");
            benchmarkingService.SieveOfAtkin(data.SieveLimit);
            return Ok();
        }

        [AcceptVerbs("GET")]
        [Route("benchmarking/quicksort")]
        public IHttpActionResult Quicksort()
        {
            QuicksortData data = memcache.Get<QuicksortData>("quicksort");
            benchmarkingService.Quicksort(data.QuicksortArray, 0, data.QuicksortArray.Length - 1);
            return Ok();
        }
    }
}
