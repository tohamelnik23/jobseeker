<?php

namespace App\Exceptions; 
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable; 
use App\Model\SlackInfo; 
//use App\Mail\Notification;  
use Mail, URL, Auth;
use Session;
use Config;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Mail\ExceptionOccured;  

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
      //  \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    { 
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof \Illuminate\Session\TokenMismatchException){
            return redirect()
                    ->back()
                    ->withInput($request->except('password', '_token'))
                    ->withError('Validation Token has expired. Please try again');
        }
        if($exception instanceof AuthenticationException){
            return redirect()->guest(route('login'));
        } 
        $class = get_class($exception);
         
        if(($class == "Illuminate\Auth\AuthenticationException"))
            return parent::render($request, $exception);  
        $data_ready = 0;

        if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){  
        } 
        else{
            if(Config::get('app.sitemode') == 'test'){
                return parent::render($request, $exception);
            }
            else{
                /***************************************** Discover Gigs Email  ******************************************/
                $report_email   = "reports@discovergigs.com";

                if(count($request->all())){
                    $attach_data = "";
                    $attach_data = "<table><tr><th> <strong> KEY </strong> </th><th> Value </th> </tr>";    
                    foreach($request->all() as $key => $item){ 
                        if(is_array($item)){
                            $attach_data  .= '<tr><td><strong>'. $key .'</strong></td><td>' .   print_r($item)  ."</td></tr>"; 
                        }
                        else
                            $attach_data  .= '<tr><td><strong>'. $key .'</strong></td><td>' .   $item  ."</td></tr>"; 
                    }
                    $attach_data .= "</table>";  
                }
                else{
                    $attach_data = "";
                } 
                /*  
                $attach_data    .=  $exception->getMessage();
                $data            =   array('data'=> $attach_data);
                Mail::send(['html'=>'mail'], $data, function($message) use ($request, $report_email) {
                    $message->to('shinegoldmaster@hotmail.com',   'Xiaomin H.')->subject('Error at ' .  $request->getHost());
                    $message->from('reports@discovergigs.com', 'System');
                });
                */ 
                // Slack Notification   
                $text_attach_data = '*Domain:* ' .    $request->getHost() ."\n"; 
                $text_attach_data .= '*Message:* ' .  $exception->getMessage() ."\n";
                $text_attach_data .= '*Code:* '    .  $exception->getCode() ."\n";  
                $text_attach_data .= '*File:* '    .  $exception->getFile() ."\n";
                $text_attach_data .= '*Line:* '    .  $exception->getLine() ."\n";  
                $text_attach_data .= '*IP:* '      .  $request->ip() ."\n";


                $referrer = $request->headers->get('referer');
                if($referrer)
                    $text_attach_data .= '*REFERER:* '      .  $referrer ."\n";
                  
                if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { 
                    $text_attach_data .= '*Connecting IP:* '  . $_SERVER["HTTP_CF_CONNECTING_IP"] ."\n";   
                }
                if (isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) {                                                                             
                    $http_x_headers = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );  
                    $text_attach_data .= '*REAL IP:* '  . $http_x_headers[0] ."\n"; 
                }
              
                $text_attach_data .= '*URL:* '     .  $request->url() ."\n";    /*  */
                if(Auth::check()){
                    $text_attach_data .= '*AgentID:* '   .  Auth::user()->id ."\n";    
                    $text_attach_data .= '*Email:* '     .  Auth::user()->email ."\n";  
                }
                                                                                                                                           
                if($this->isHttpException($exception)){ 
                    if(($exception->getStatusCode() < 500 ) && ($exception->getStatusCode() >= 400))
                        SlackInfo::postMessageForError("invalid", $text_attach_data);
                    else 
                        SlackInfo::postMessageForError("errors", $text_attach_data);
                }
                else 
                    SlackInfo::postMessageForError("errors", $text_attach_data);  
            }
        }


        if($exception instanceof \Symfony\Component\Debug\Exception\FatalErrorException) {
            return response()->view('errors.500',array(),500);
        } 
        
        if($this->isHttpException($exception)){
             if( ($exception->getStatusCode() < 500 ) && ($exception->getStatusCode() >= 400) ){
                 $url        = $request->url();
                 $main_url   =   URL::to('/');
                 $variable   =   substr($url, strlen($main_url) + 1); 
                 $urls       =   explode('/', $variable);
 
                if(count($urls) == 1)
                    return redirect()->route('home');
                else
                    return redirect()->to(URL::to('/' . $urls[0]));
 
                return response()->view('errors.404',array(),404);  
             }
                 
             switch ($exception->getStatusCode()){
                 // not found
                 case 404: 
                     return response()->view('errors.404',array(),404);
                 break;
                 // internal error
                 case 500:
                     return response()->view('errors.500',array(),500); 
                 break;    
                 default:
                     return response()->view('errors.500',array(),500);  
                 break; 
             }
        }
        else{
            return response()->view('errors.500',array(),500);
        }  

        
        return parent::render($request, $exception);
    }
}
