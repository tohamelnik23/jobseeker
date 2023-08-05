<?php 
namespace App\Http\Controllers; 
use App\Models\Message;

class BaseController extends Controller
{


    /**
	 * @var array
	 */
    public $data = [];

    /**
	 * @param $name
	 * @param $value
	 */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

     /**
	 * @param $name
	 * @return mixed
	 */
    public function __get($name)
    {
        return $this->data[$name];
    }

     /**
	 * @param $name
	 * @return bool
	 */
    public function __isset($name)
    {
        return isset($this->data[ $name ]);
    }

    /**
     * UserBaseController constructor.
     */
    public function __construct(){ 
        $this->middleware(function ($request, $next) { 
            return $next($request);
        });  
    } 
} 