<?php

class EPAFController extends BaseController
{
    public function __construct() {

        $this->ip =  Request::getClientIp();
        $this->debug = Config::get('etoc_epaf.debug');
        $this->environment_name = Config::get('etoc_epaf.environment_name');
        $this->database = DB::connection()->getDatabaseName();
        $this->email = Config::get('etoc_epaf.email');
        $this->cc = Config::get('etoc_epaf.cc');

        $this->testing_fax = Config::get('etoc_epaf.paf_testing_fax');
        $this->testing_cwc = Config::get('etoc_epaf.paf_testing_cwc');
        $this->testing_email = Config::get('etoc_epaf.paf_testing_email');
        $this->testing_db = Config::get('etoc_epaf.paf_testing_db');
        $this->paf_usr_pdf = Config::get('etoc_epaf.paf_usr_pdf');
        $this->paf_usr_pdf_sp = Config::get('etoc_epaf.paf_usr_pdf_sp');
        $this->paf_ped_pdf = Config::get('etoc_epaf.paf_ped_pdf');
        $this->paf_rxc_pdf = Config::get('etoc_epaf.paf_rxc_pdf');
        $this->paf_outgoing_fax = Config::get('etoc_epaf.paf_outgoing_fax');
        $this->paf_ped_fax = Config::get('etoc_epaf.paf_ped_fax');
        $this->paf_rxc_fax = Config::get('etoc_epaf.paf_rxc_fax');
        $this->paf_fax_disposition = Config::get('etoc_epaf.paf_fax_disposition');
        $this->cwc_url = Config::get('etoc_epaf.cwc_url');
        $this->application_type = Config::get('etoc_epaf.application_type');

        $this->epsilon_id = '0000000000000';
        $this->error = '';





    }
    public function getForm()
    {
        Log::info("EPAFController->getForm()");
        return View::make('epaf_form');
    }
    public function getFormRequestPdf()
    {
        Log::info("EPAFController->getFormRequestPdf()");
        return View::make('epaf_form_duplicate_for_pdf');
    }
    public function postForm()
    {
        Log::info("\n\n############################################################################################");
        Log::info("-----------------> EPAF: post_form");
        Log::info("-----------------> application_type: $this->application_type");
        Log::info("-----------------> debug: $this->debug");
        Log::info("-----------------> database: $this->database");
        Log::info("-----------------> environment_name: $this->environment_name");
        Log::info("-----------------> email: $this->email");
        Log::info("-----------------> paf_outgoing_fax: $this->paf_outgoing_fax");
        Log::info("-----------------> paf_rxc_fax: $this->paf_rxc_fax");
        Log::info("-----------------> paf_fax_disposition: $this->paf_fax_disposition");
        Log::info("-----------------> cwc_url: $this->cwc_url");
        Log::info("-----------------> testing_fax: $this->testing_fax");
        Log::info("-----------------> testing_cwc: $this->testing_cwc");
        Log::info("-----------------> paf_usr_pdf: $this->paf_usr_pdf");
        Log::info("-----------------> paf_usr_pdf_sp: $this->paf_usr_pdf_sp");
        Log::info("-----------------> paf_rxc_pdf: $this->paf_rxc_pdf");
        Log::info("\n\n############################################################################################");

        $form_data = Input::all();

        // Create form object and pass POST data to it
        $epaf = new CDM\Synagis\EPAFForm($this->ip);
        $epaf->create($form_data);

        if (!$epaf->valid) {
            return $epaf->validatorError;
        }
        else {

            //CWC Servlet
            $cwc = new CDM\Synagis\CWC($this->cwc_url, $epaf->_data);

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

            // RxCrossroads
            if ($epaf->get('A360_signature') && !$this->error) {
                // PDF
                $pdf = new CDM\Synagis\EPAFPDF();

                $pdf->addCoversheet($this->paf_outgoing_fax, $this->paf_rxc_fax);

                $pdfOutput = $pdf->create($epaf->_data, $this->paf_rxc_pdf, 'S', true);

                // Fax
                $a360_return_id = substr(md5(rand()), 0, 12);
                $a360_fax = new CDM\Synagis\Fax();
                $a360_fax->create($this->paf_rxc_fax, $pdfOutput, 'RXCrossroads', $a360_return_id, $this->paf_fax_disposition, $this->epsilon_id);

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

            if(!$this->error &&!$this->testing_db) {
                // Insert Application
                $application_id = $epaf->insert_application();

                if ($epaf->get('A360_signature')) {
                    $optin_id = $epaf->insert_application_optin($application_id, '2');
                    $epaf->insert_application_messagestatus($optin_id, '1', '4', $a360_return_id, '', $a360_fax->get('errormessage'));
                }
                if ($epaf->get('CWC_signature')) {
                    $optin_id = $epaf->insert_application_optin($application_id, '3');
                }
                $epaf->insert_application_messagestatus($optin_id, '2', '1',  $this->epsilon_id, $cwc->get('errorcode'), $cwc->get('errormessage'));

                Log::info("EPAF Form Post  - [SUCCESS]");
                Log::info("\n\n############################################################################################");

                $message = '{
                                "success": "true",
                                "application_id": "'.$application_id.'",
                                "message": "[SUCCESS]"
                            }';

                return $message;
            }
            else {
                Log::info("EPAF Form Post  - [FAIL]");
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
        Log::info("-----------------> EPAF: postFormRequestPdf");
        Log::info("-----------------> application_type: $this->application_type");
        Log::info("-----------------> debug: $this->debug");
        Log::info("-----------------> environment_name: $this->environment_name");
        Log::info("-----------------> paf_usr_pdf: $this->paf_usr_pdf");
        Log::info("-----------------> paf_usr_pdf_sp: $this->paf_usr_pdf_sp");
        Log::info("-----------------> paf_rxc_pdf: $this->paf_rxc_pdf");
        Log::info("\n\n############################################################################################");
        $form_data = Input::all();
        // Create form object and pass POST data to it
        // Form constructor validates form and sets a valid flag
        $epaf = new CDM\Synagis\EPAFForm();
        $epaf->create($form_data);

        if (!$epaf->valid) {
            return $epaf->validatorError;
        }
        else {
            $pdf_template = $epaf->_data['auth_lang'] == 'en' ? $this->paf_usr_pdf : $this->paf_usr_pdf_sp;

            // User PDF
            $pdf = new CDM\Synagis\EPAFPDF();
            $pdfOutput = $pdf->create($epaf->_data, $pdf_template, 'S', false);

            $length = strlen($pdfOutput);

            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="epaf.pdf"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . $length);
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            header('Pragma: public');

            echo $pdfOutput;
        Log::info("EPAFController->postFormRequestPdf()");

            exit;
        }
    }

    public function postFaxDisposition()
    {
        Log::info("\n\n############################################################################################");
        Log::info("-----------------> EPAF: post_form");
        Log::info("-----------------> application_type: $this->application_type");
        Log::info("-----------------> debug: $this->debug");
        Log::info("-----------------> environment_name: $this->environment_name");
        Log::info("-----------------> paf_outgoing_fax: $this->paf_outgoing_fax");
        Log::info("-----------------> paf_fax_disposition: $this->paf_fax_disposition");
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
        Log::info("EPAFController->postFaxDisposition() Incoming Disposition VarDump: \n$vardump");

        if($status == '0') {
            $statustype_id = '1';
        } else {
            $statustype_id = '2';
        }

        $form = new CDM\Synagis\EPAFForm();

        $status = "eFAX TransmissionID: $fax_id : eFax ID : $docid : $errormessage";
        Log::info("EPAFController->postFaxDisposition() status : $status");
        $form->update_application_messagestatus($statustype_id, $fax_id, $status);
    }

    public function postTrackedEvent()
    {

        $data = Input::all();
        $application_id = isset($data['application_id']) ? $data['application_id'] : 'no application id';
        $trackedevent_id = isset($data['trackedevent_id']) ? $data['trackedevent_id'] : 'no trackedevent id';

        Log::info("\n\n############################################################################################");
        Log::info("-----------------> EPAF postTrackedEvent");
        Log::info("-----------------> application_type: $this->application_type");
        Log::info("-----------------> debug: $this->debug");
        Log::info("-----------------> environment_name: $this->environment_name");
        Log::info("-----------------> application_id: $application_id");
        Log::info("-----------------> trackedevent_id: $trackedevent_id");
        Log::info("\n\n############################################################################################");

        $form = new CDM\Synagis\EPAFForm();
        $form->insert_application_trackedevent($application_id, $trackedevent_id);
    }
}
