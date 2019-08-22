<?php

class ETOCController extends BaseController
{
    public function __construct() {
        $this->ip =  Request::getClientIp();
        $this->debug = Config::get('etoc_epaf.debug');
        $this->application_type = Config::get('etoc_epaf.toc_application_type');
        $this->environment_name = Config::get('etoc_epaf.environment_name');
        $this->database = DB::connection()->getDatabaseName();
        $this->email = Config::get('etoc_epaf.email');
        $this->rep_email_no_zip = Config::get('etoc_epaf.rep_email_no_zip');

        $this->cc = Config::get('etoc_epaf.cc');

        $this->testing_fax = Config::get('etoc_epaf.toc_testing_fax');
        $this->testing_cwc = Config::get('etoc_epaf.toc_testing_cwc');
        $this->testing_email = Config::get('etoc_epaf.toc_testing_email');
        $this->testing_db = Config::get('etoc_epaf.toc_testing_db');
        $this->toc_usr_pdf = Config::get('etoc_epaf.toc_usr_pdf');
        $this->toc_usr_pdf_sp = Config::get('etoc_epaf.toc_usr_pdf_sp');
        $this->toc_ped_pdf = Config::get('etoc_epaf.toc_ped_pdf');
        $this->toc_rxc_pdf = Config::get('etoc_epaf.toc_rxc_pdf');
        $this->toc_outgoing_fax = Config::get('etoc_epaf.toc_outgoing_fax');
        $this->toc_ped_fax = Config::get('etoc_epaf.toc_ped_fax');
        $this->toc_rxc_fax = Config::get('etoc_epaf.toc_rxc_fax');
        $this->toc_fax_disposition = Config::get('etoc_epaf.toc_fax_disposition');
        $this->cwc_url = Config::get('etoc_epaf.cwc_url');
        $this->mgKey = Config::get('etoc_epaf.mgKey');
        $this->mgDomain = Config::get('etoc_epaf.mgDomain');

        $this->epsilon_id = '0000000000000';
        $this->error = '';



    }
    public function getForm()
    {

        Log::info("ETOCController->getForm()");
        return View::make('etoc_form');
    }
    public function getFormRequestPdf()
    {
        Log::info("ETOCController->getFormRequestPdf()");
        return View::make('etoc_form_duplicate_for_pdf');
    }
    public function postForm()
    {

        Log::info("\n\n############################################################################################");
        Log::info("-----------------> ETOC: postForm");
        Log::info("-----------------> application_type: $this->application_type");
        Log::info("-----------------> debug: $this->debug");
        Log::info("-----------------> database: $this->database");
        Log::info("-----------------> environment_name: $this->environment_name");
        Log::info("-----------------> toc_ped_Fax: $this->toc_ped_fax");
        Log::info("-----------------> email: $this->email");
        Log::info("-----------------> rep_email_no_zip: $this->rep_email_no_zip");
        Log::info("-----------------> toc_outgoing_fax: $this->toc_outgoing_fax");
        Log::info("-----------------> toc_rxc_fax: $this->toc_rxc_fax");
        Log::info("-----------------> toc_fax_disposition: $this->toc_fax_disposition");
        Log::info("-----------------> cwc_url: $this->cwc_url");
        Log::info("-----------------> testing_fax: $this->testing_fax");
        Log::info("-----------------> testing_cwc: $this->testing_cwc");
        Log::info("-----------------> testing_email: $this->testing_email");
        Log::info("-----------------> testing_db: $this->testing_db");
        Log::info("-----------------> toc_usr_pdf: $this->toc_usr_pdf");
        Log::info("-----------------> toc_usr_pdf_sp: $this->toc_usr_pdf_sp");
        Log::info("-----------------> toc_ped_pdf: $this->toc_ped_pdf");
        Log::info("-----------------> toc_rxc_pdf: $this->toc_rxc_pdf");
        Log::info("-----------------> mgKey: $this->mgKey");
        Log::info("-----------------> mgDomain: $this->mgDomain");
        Log::info("\n\n############################################################################################");
        $form_data = Input::all();

        // Create form object and pass POST data to it
        $etoc = new CDM\Synagis\ETOCForm($this->ip);
        $etoc->create($form_data);

        if (!$etoc->valid) {
            return $etoc->validatorError;
        }
        else {

            //CWC Servlet
            $cwc = new CDM\Synagis\CWC($this->cwc_url, $etoc->_data);

            if(!$this->testing_cwc) {
                $cwc->transmit();
                $this->epsilon_id = $cwc->get('IndividualID');
                Log::info("CWC->transmit()");

                if(!$cwc->succeeded) {
                    $message = '{
                                "success": "false",
                                "application_id": "0",
                                "message": "[FAIL]"
                            }';

                    Log::info("CWC->transmit() [FAIL]");
                    return $message;
                }

            } else {
                Log::info("CWC->transmit() [TESTMODE]");
            }

            // Fax to Pediatrician
            if ($etoc->get('TOC_signature') && !$this->error) {

                // If we're not in debug mode and there's no fax set in the config then use pediatrician fax
                if(!$this->debug && $this->toc_ped_fax=='') {
                    $this->toc_ped_fax = $etoc->_data['pediatrician_fax'];
                }


                // PDF
                $pdf = new CDM\Synagis\ETOCPDF();
                $pdf->addCoversheet($this->toc_outgoing_fax,  $etoc->_data['pediatrician_fax']);
                $pdfOutput = $pdf->create($etoc->_data, $this->toc_ped_pdf, 'S', true);

                //Fax
                $toc_return_id = substr(md5(rand()), 0, 12);
                $toc_fax = new CDM\Synagis\Fax();
                $toc_fax->create($this->toc_ped_fax, $pdfOutput, $etoc->_data['pediatrician_firstname']." ". $etoc->_data['pediatrician_lastname'], $toc_return_id, $this->toc_fax_disposition, $this->epsilon_id);
                if(!$this->testing_fax) {
                    $toc_fax->transmit();
                    Log::info("EFAX->transmit [TESTMODE]");
                    if(!$toc_fax->get('succeeded')) {
                        $this->error .= "<li>".$toc_fax->get('errormessage')."</li>";
                    }
                } else {
                    Log::info("EFAX->transmit [TESTMODE]");
                }


                if(!$this->debug && $this->email == '') {
                    $this->_email_to = isset($etoc->_data['rep_email']) ? $etoc->_data['rep_first_name']." ".$etoc->_data['rep_last_name']." <".$etoc->_data['rep_email'].">": $this->rep_email_no_zip;
                } else {
                    $this->_email_to = $this->email;
                }

                Log::info("Mailgun Rep email sent to: $this->_email_to");
                // Email Stuff
                $email_return_id = substr(md5(rand()), 0, 12);
                $email = new CDM\Synagis\Email($etoc->_data, $email_return_id, $this->mgKey, $this->mgDomain, $this->_email_to);
                if(!$this->testing_email) {
                    $email->transmit();
                    Log::info("Mailgun - email->transmit()");
                } else {
                    Log::info("Mailgun - email->transmit() [TESTMODE]");
                }
            }

            // RxCrossroads
            if ($etoc->get('A360_signature') && !$this->error) {
                // PDF
                $pdf = new CDM\Synagis\ETOCPDF();

                $pdf->addCoversheet($this->toc_outgoing_fax, $this->toc_rxc_fax);

                $pdfOutput = $pdf->create($etoc->_data, $this->toc_rxc_pdf, 'S', true);

                // Fax
                $a360_return_id = substr(md5(rand()), 0, 12);
                $a360_fax = new CDM\Synagis\Fax();
                $a360_fax->create($this->toc_rxc_fax, $pdfOutput, 'RXCrossroads', $a360_return_id, $this->toc_fax_disposition, $this->epsilon_id);
                if(!$this->testing_fax) {
                    $a360_fax->transmit();
                    Log::info("EFAX - a360_fax->transmit()");
                    if(!$a360_fax->get('succeeded')) {
                        $this->error .= "<li>".$a360_fax->get('errormessage')."</li>";
                    }
                } else {
                    Log::info("EFAX - a360_fax->transmit() [TESTMODE]");
                }
            }

            if(!$this->error && !$this->testing_db) {
                // Insert Application
                $application_id = $etoc->insert_application();
                //Pediatrician Fax
                if ($etoc->get('TOC_signature')) {
                    $optin_id = $etoc->insert_application_optin($application_id, '1');
                    $etoc->insert_application_messagestatus($optin_id, '1', '4', $toc_return_id, '', $toc_fax->get('errormessage'));
                     // Set Mail message status
                    $etoc->insert_application_messagestatus($optin_id, '3', '1', $email->get('response_id'), $email->get('errorcode'), $email->get('errormessage'));
                }
                //RxCrossroads Fax
                if ($etoc->get('A360_signature')) {
                    $optin_id = $etoc->insert_application_optin($application_id, '2');
                    $etoc->insert_application_messagestatus($optin_id, '1', '4', $a360_return_id, '', $a360_fax->get('errormessage'));
                }
                //CWC sevlet
                if ($etoc->get('CWC_signature')) {
                    $optin_id = $etoc->insert_application_optin($application_id, '3');

                }

                $etoc->insert_application_messagestatus($optin_id, '2', '1', $this->epsilon_id, $cwc->get('errorcode'), $cwc->get('errormessage'));


                Log::info("ETOC Form Post [SUCCESS]");
                 Log::info("\n\n############################################################################################");

                $message = '{
                                "success": "true",
                                "application_id": "'.$application_id.'",
                                "message": "[SUCCESS]"
                            }';

                return $message;
            }
            else {
                Log::info("ETOC Form Post  - [FAIL]");
                Log::info("\n\n############################################################################################");

                $message = '{
                                "success": "true",
                                "application_id": "0",
                                "message": "[FAIL] '.$this->error.'"
                            }';


                return $message;
            }
        }
    }


    public function postFormRequestPdf()
    {
        Log::info("\n\n############################################################################################");
        Log::info("-----------------> ETOC postFormRequestPdf");
        Log::info("-----------------> application_type: $this->application_type");
        Log::info("-----------------> debug: $this->debug");
        Log::info("-----------------> environment_name: $this->environment_name");
        Log::info("-----------------> toc_usr_pdf: $this->toc_usr_pdf");
        Log::info("-----------------> toc_usr_pdf_sp: $this->toc_usr_pdf_sp");
        Log::info("-----------------> toc_ped_pdf: $this->toc_ped_pdf");
        Log::info("-----------------> toc_rxc_pdf: $this->toc_rxc_pdf");
        Log::info("\n\n############################################################################################");

        $form_data = Input::all();
        // Create form object and pass POST data to it
        // Form constructor validates form and sets a valid flag
        $etoc = new CDM\Synagis\ETOCForm();
        $etoc->create($form_data);

        if (!$etoc->valid) {
            return $etoc->validatorError;
        }
        else {
            $pdf_template = $etoc->_data['auth_lang'] == 'en' ? $this->toc_usr_pdf : $this->toc_usr_pdf_sp;

            // User PDF
            $pdf = new CDM\Synagis\ETOCPDF();

            $pdfOutput = $pdf->create($etoc->_data, $pdf_template, 'S', false);

            $length = strlen($pdfOutput);

            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="etoc.pdf"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . $length);
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            header('Pragma: public');

            echo $pdfOutput;

            exit;
        }
    }

    public function postFaxDisposition()
    {
        Log::info("\n\n############################################################################################");
        Log::info("-----------------> ETOC postFaxDisposition");
        Log::info("-----------------> application_type: $this->application_type");
        Log::info("-----------------> debug: $this->debug");
        Log::info("-----------------> environment_name: $this->environment_name");
        Log::info("-----------------> toc_outgoing_fax: $this->toc_outgoing_fax");
        Log::info("-----------------> toc_fax_disposition: $this->toc_fax_disposition");
        Log::info("\n\n############################################################################################");

        $data = stripslashes($_POST['xml']);

        $fax = new CDM\Synagis\Fax();
        $fax->receive($data);
        $fax_id = $fax->get('fax_id');
        $docid = $fax->get('docid');
        $status = $fax->get('status');
        $errormessage = $fax->get('errormessage');

        // Log Response object
        ob_start();
        var_dump($data);
        $vardump = ob_get_clean();
        Log::info("EFAX Incoming Disposition VarDump: \n$vardump");


        if($status == '0') {
            $statustype_id = '1';
        } else {
            $statustype_id = '2';
        }

        $form = new CDM\Synagis\ETOCForm();

        $status = "eFAX TransmissionID: $fax_id : eFax ID : $docid : $errormessage";
        Log::info("EFAX status : $status");
        $form->update_application_messagestatus($statustype_id, $fax_id, $status);
    }

    public function postTrackedEvent()
    {
        $data = Input::all();
        $application_id = isset($data['application_id']) ? $data['application_id'] : 'no application id';
        $trackedevent_id = isset($data['trackedevent_id']) ? $data['trackedevent_id'] : 'no trackedevent id';

        Log::info("\n\n############################################################################################");
        Log::info("-----------------> ETOC postTrackedEvent");
        Log::info("-----------------> application_type: $this->application_type");
        Log::info("-----------------> debug: $this->debug");
        Log::info("-----------------> environment_name: $this->environment_name");
        Log::info("-----------------> application_id: $application_id");
        Log::info("-----------------> trackedevent_id: $trackedevent_id");
        Log::info("\n\n############################################################################################");

        $form = new CDM\Synagis\ETOCForm();
        $form->insert_application_trackedevent($application_id, $trackedevent_id);
    }
}