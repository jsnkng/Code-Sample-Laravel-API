<?php
namespace CDM\Synagis;
use Mailgun\Mailgun;
use \Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Email
{
    /*******************************************************************************
    *                                                                              *
    *                               Public methods                                 *
    *                                                                              *
    *******************************************************************************/
    public function __construct($form, $return_id, $mgKey, $mgDomain, $email_to)
    {
        Log::info("########################");
        Log::info("Email->__construct() Mailgun");
        $this->_return_id = $return_id;
        $this->_mgDomain = $mgDomain;
        $this->_mgKey = $mgKey;
        $this->_email_to = $email_to;

        $this->response = '';
        $this->response_id = 'Mailgun';
        $this->errormessage = 'Mailgun [TEST]';
        $this->errorcode = 'Mailgun Disabled';

        //Create separate Email log
        $this->_view_log = new Logger('Email');
        $this->_view_log->pushHandler(new StreamHandler(dirname(dirname(__DIR__)).'/app/storage/logs/email.log', Logger::INFO));

        //Email template
        $api_dir = realpath('../');
        $email_dir = $api_dir."/app/views/emails/rep/";
        $email_file = "syna14cdpr0348_realtime_rep.html";
        $handle = fopen($email_dir.$email_file, "r");
        $email = fread($handle, filesize($email_dir.$email_file));
        fclose($handle);

        //Substitutions
        $patterns = array();
        $patterns[0] = '{{hospital_name}}';
        $patterns[1] = '{{pediatrician_firstname}}';
        $patterns[2] = '{{pediatrician_lastname}}';
        $patterns[3] = '{{pediatrician_phone}}';
        $patterns[4] = '{{pediatrician_address}}';
        $patterns[5] = '{{pediatrician_suite}}';
        $patterns[6] = '{{pediatrician_city}}';
        $patterns[7] = '{{pediatrician_state}}';
        $patterns[8] = '{{pediatrician_zip}}';
        $patterns[9] = '{{rep_first_name}}';
        $patterns[10] = '{{rep_last_name}}';
        $patterns[11] = '{{rep_email}}';
        $patterns[12] = '{{pediatrician_city_state_zip}}';
        $patterns[13] = '{{pediatrician_address_suite}}';
        $patterns[14] = '{{pediatrician_practice}}';

        $replacements = array();
        $replacements[0] = $form['hospital_name'];
        $replacements[1] = $form['pediatrician_firstname'];
        $replacements[2] = $form['pediatrician_lastname'];

        //Format phone number
        if($form['pediatrician_phone'] !='') {
            $replacements[3] = "<br />(".substr($form['pediatrician_phone'], 0, 3).") ".substr($form['pediatrician_phone'], 3, 3)."-".substr($form['pediatrician_phone'],6);
        } else {
            $replacements[3] = '';
        }
        $replacements[4] = $form['pediatrician_address'];
        $replacements[5] = $form['pediatrician_suite'];
        $replacements[6] = $form['pediatrician_city'];
        $replacements[7] = $form['pediatrician_state'];
        $replacements[8] = $form['pediatrician_zip'];
        $replacements[9] = isset($form['rep_first_name']) ? $form['rep_first_name'] : '';
        $replacements[10] = isset($form['rep_last_name']) ? $form['rep_last_name'] : '';
        $replacements[11] = isset($form['rep_email']) ? $form['rep_email'] : '';



        // Format city, state, zip
        if(($form['pediatrician_city'] !='' && $form['pediatrician_state'] != '' && $form['pediatrician_zip'] != '') || ($form['pediatrician_city'] !='' && $form['pediatrician_state'] != '' && $form['pediatrician_zip'] != ''))  {
            $replacements[12] = "<br />".$form['pediatrician_city'].", ".$form['pediatrician_state']." ".$form['pediatrician_zip'];
        } elseif($form['pediatrician_city'] =='' && $form['pediatrician_state'] != '' && $form['pediatrician_zip'] != '') {
            $replacements[12] = "<br />".$form['pediatrician_state']." ".$form['pediatrician_zip'];
        } elseif($form['pediatrician_city'] =='' && $form['pediatrician_state'] == '' && $form['pediatrician_zip'] != '') {
            $replacements[12] = "<br />".$form['pediatrician_zip'];
        } else {
            $replacements[12] = '';
        }

        //Pediatrician Address Suite
         if($form['pediatrician_address'] !='' && $form['pediatrician_suite'] != '') {
            $replacements[13] = "<br />".$form['pediatrician_address']." Suite ".$form['pediatrician_suite'];
         } elseif($form['pediatrician_address'] !='' && $form['pediatrician_suite'] == '') {
            $replacements[13] = "<br />".$form['pediatrician_address'];
         } elseif($form['pediatrician_address'] =='' && $form['pediatrician_suite'] == '') {
            $replacements[13] = "";
         }
        //Pediatrician Practice
         if($form['pediatrician_practice'] !='' ) {
            $replacements[14] = "<br />".$form['pediatrician_practice'];
         } else {
            $replacements[14] = "";

         }
        #Mailgun config
        $this->_mgClient = new Mailgun($this->_mgKey);
        $this->_email_from =  "etocforms@etoc-medimmune.com <etocforms@etoc-medimmune.com>";
        $this->_email_subject = 'Notification: An electronic TOC form has been submitted';

        // Prepare email html template
        $this->_email_html = preg_replace($patterns, $replacements, $email);

        // Prepare email text template
        $this->_email_text = '';

        // Prepare email images
        $this->_email_images = array('@'.$email_dir.'syna14cdpr0348_footer_background.gif',
                                    '@'.$email_dir.'syna14cdpr0348_footer_bottom.gif',
                                    '@'.$email_dir.'syna14cdpr0348_footer_left.gif',
                                    '@'.$email_dir.'syna14cdpr0348_footer_right.gif',
                                    '@'.$email_dir.'syna14cdpr0348_header_left.gif',
                                    '@'.$email_dir.'syna14cdpr0348_header_logo.gif',
                                    '@'.$email_dir.'syna14cdpr0348_header_right.gif',
                                    '@'.$email_dir.'syna14cdpr0348_logo_medimmune.png',
                                    '@'.$email_dir.'syna14cdpr0348_logo_synagis.jpg',
                                    '@'.$email_dir.'syna14cdpr0348_shadow_left.gif',
                                    '@'.$email_dir.'syna14cdpr0348_shadow_right.gif');

        $this->_application_type = $form['application_type'];

    }

    public function get($key)
    {
        Log::info($this->$key);
        return $this->$key;
    }

    public function transmit()
    {

        # Call Mailgun
        $response = $this->_mgClient->sendMessage(
            $this->_mgDomain,
            array('from'    => $this->_email_from,
                  'to'      => $this->_email_to,
                  'subject' => $this->_email_subject,
                  'text'    => $this->_email_text,
                  'html'    => $this->_email_html,
                  'o:campaign' => $this->_application_type),
            array('inline' => $this->_email_images)
          );

        if($response) {

            // Log Request object
            ob_start();
            var_dump($response);
            $vardump = ob_get_clean();
            // Log info to email.log
            $this->_view_log->addInfo($vardump);


            $this->succeeded = 'true';
            $this->errorcode = '';
            $this->response_id = $response->http_response_body->id;
            $this->message = $response->http_response_body->message;
            $this->errormessage = "Mailgun request successful $this->response_id $this->message";
            Log::info("Email->transmit() - Mailgun [SUCCESS]");

        } else {
            $this->succeeded = '';
            $this->errorcode = '';
            $this->response_id = $response->id;
            $this->message = $response->message;
            $this->errormessage = "Mailgun request error $this->response_id $this->message";
            Log::info("Email->transmit() - Mailgun [FAIL]");
        }

    }
}
