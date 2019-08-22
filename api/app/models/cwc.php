<?php
namespace CDM\Synagis;

use \Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use \Aura\Http\Message\Request;
class CWC
{
    /*******************************************************************************
    *                                                                              *
    *                               Public methods                                 *
    *                                                                              *
    *******************************************************************************/
    public function __construct($endpoint, $form)
    {
        $this->form = $form;
        $this->message = '';
        $this->errormessage = 'CWC [TEST]';
        $this->errorcode = '';
        $this->_url = $endpoint;
        //Create separate CWC log
        $this->_view_log = new Logger('CWC');
        $this->_view_log->pushHandler(new StreamHandler(dirname(dirname(__DIR__)).'/app/storage/logs/cwc.log', Logger::INFO));

        Log::info("########################");
        Log::info("CWC->__construct()");
    }

    public function get($key)
    {
        Log::info($this->$key);
        return $this->$key;
    }

    public function transmit()
    {

        $http = include dirname(dirname(__DIR__)).'/vendor/aura/http/scripts/instance.php';
        $request = $http->newRequest();
        $request->setMethod(Request::METHOD_POST);
        $request->setContent(http_build_query($this->form));

        // the request timeout in seconds
        $http->transport->options->setTimeout(30);

        $request->setUrl($this->_url);
        $stack = $http->send($request);

        $response = json_decode($stack[0]->content);

        //     // Log Request object
        //     ob_start();
        //     var_dump($request);
        //     $vardump = ob_get_clean();
        //     // Log info to cwc.log
        //     $this->_view_log->addInfo($vardump);

        //     // Log Response object
        //     ob_start();
        //     var_dump($stack[0]->content);
        //     $vardump = ob_get_clean();
        //     // Log info to cwc.log
        //     $this->_view_log->addInfo($vardump);

        $succeeded = $response->succeeded ? "[SUCCESS]" : "[FAIL]";
        $this->succeeded = $response->succeeded;
        $this->feedback = isset($response->feedback) ? $response->feedback : '';
        $this->IndividualID = $response->IndividualID;
        // $this->return_id = $this->IndividualID;
        $this->errormessage = "CWC servlet $succeeded $this->feedback: Epsilon User ID: $this->IndividualID";
        // Log::info("CWC::Servlet transmit");
        Log::info("CWC->transmit() $succeeded $this->feedback: Epsilon User ID: $this->IndividualID");

    }

    /*******************************************************************************
    *                                                                              *
    *                              Protected methods                               *
    *                                                                              *
    *******************************************************************************/
}
