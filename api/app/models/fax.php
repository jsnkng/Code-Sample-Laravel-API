<?php
namespace CDM\Synagis;

use \Log;
use \efax;

class Fax
{
    /*******************************************************************************
    *                                                                              *
    *                               Public methods                                 *
    *                                                                              *
    *******************************************************************************/
    public function __construct()
    {
        $this->_account_id = "6092285521";
        $this->_user_name = "cdmadm";
        $this->_user_password = "cdmadm";
        $this->_outbound_url = "https://secure.efaxdeveloper.com/EFax_WebFax.serv";
        $this->_disposition_email = "admin@cdm210.com";
        $this->title = "";

        $this->response = '1';
        $this->errormessage = 'eFAX [TEST]';
        $this->errorcode = 'eFAX Disabled';
        Log::info("########################");
        Log::info("Fax->__construct()");
    }
    public function create($number, $pdf, $recipient, $return_id, $disposition_url, $epsilon_id)
    {
        $this->_disposition_url = $disposition_url;
        $this->epsilon_id = $epsilon_id;
        $this->number = $number;
        $this->pdf = $pdf;
        $this->recipient = $recipient;
        $this->return_id = $return_id;
        Log::info("Fax->create()");
    }
    public function get($key)
    {
        return $this->$key;
    }

    public function set($key, $value)
    {
        $this->$key = $value;
    }

    public function transmit()
    {
        $efax =  new eFax(false); // use 'true' to use the PEAR HttpRequest class (not recommended)

        // mandatory parameters
        $efax->set_account_id($this->_account_id);
        $efax->set_user_name($this->_user_name);
        $efax->set_user_password($this->_user_password);
        $efax->add_file("pdf", $this->pdf);
        $efax->add_recipient($this->recipient, $this->title, $this->number);

        // Though this is mandatory, the constructor sets the default that
        // you should not need to modify.
        $efax->set_outbound_url($this->_outbound_url);

        // mandatory if set_duplicated_id(false);
        $efax->set_fax_id($this->return_id);

        // mandatory if set_disposition_method("EMAIL");
        $efax->add_disposition_email($this->_user_name , $this->_disposition_email);

        // mandatory if set_disposition_method("POST");
        $efax->set_disposition_url($this->_disposition_url);

        // optional flags
        $efax->set_disposition_language("en");
        $efax->set_disposition_level(eFax::RESPOND_ERROR | eFax::RESPOND_SUCCESS);
        $efax->set_disposition_method("POST");
        $efax->set_duplicate_id(false);
        $efax->set_fax_header("   @DATE @TIME Synagis ETOC/EPAF [$this->epsilon_id]");
        $efax->set_priority("HIGH");
        $efax->set_resolution("STANDARD");
        $efax->set_self_busy(true);

        // ready to send the fax
        $this->response = $efax->send($efax->message());

        if($this->response) {
            $this->succeeded = 'true';
            $this->errorcode = '';
            $this->errormessage = "[SUCCESS] Fax transmission return_id: $this->return_id";
        } else {
            $this->succeeded = '';
            $this->errorcode = '';
            $this->errormessage = "[FAIL] Fax transmission return_id $this->return_id";
        }
        Log::info("Fax->transmit() Dialing $this->number $this->errormessage");
    }

    public function receive($xml) {


        $efax = new eFax(false);
        // the parser checks the validity of the user name and password
        $efax->set_user_name($this->_user_name);
        $efax->set_user_password($this->_user_password);

        // parse the XML message
        if($efax->parse_disposition($xml))
        {
            $this->fax_id = $efax->get_result_fax_id();
            $this->docid = $efax->get_result_docid();
            $this->fax_number = $efax->get_result_fax_number();
            $this->completion_date = $efax->get_result_completion_date();
            $this->status = $efax->get_result_fax_status();
            $this->errormessage = $efax->get_result_error_message();
            $this->csid = $efax->get_result_csid();
            $this->duration = $efax->get_result_duration();
            $this->pages = $efax->get_result_pages();
            $this->retries = $efax->get_result_retries();

            $this->succeeded = 'true';
            $this->errorcode = '';
            // $this->errormessage = 'Fax disposition receipt successful.';


            // now tell eFax that we accepted the disposition
            Log::info("Fax->receive() [SUCCESS] message: $this->errormessage");
            echo "Post Successful\n";
            return true;
        }
        else
        {
            $this->succeeded = '';
            $this->errorcode = '';
            $this->errormessage = 'Fax disposition failed to parse.';

             // handle error case
            Log::info("Fax->receive() [FAIL] $this->errormessage");
            return false;
        }

    }


    /*******************************************************************************
    *                                                                              *
    *                              Protected methods                               *
    *                                                                              *
    *******************************************************************************/
}
