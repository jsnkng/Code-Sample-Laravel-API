<?php
namespace CDM\Synagis;

use \Log;
use \Validator;

class EPAFValidator
{
    /*******************************************************************************
    *                                                                              *
    *                               Public methods                                 *
    *                                                                              *
    *******************************************************************************/
    public function __construct()
    {
        Log::info("########################");
        Log::info("Email->__construct() Mailgun");
        Log::info("EPAFValidator->__construct()");
    }
    public function validate($array)
    {
         $this->_rules = array(
            'patient_firstname' => 'required',
            'patient_lastname' => 'required',
            'patient_middlename' => '',
            'patient_birthdate' => 'required|date',
            'patient_insurance' => 'required',
            'patient_lb' => 'required|numeric',
            'patient_oz' => 'required|numeric',
            'patient_number' => '',
            'caregiver_firstname' => 'required',
            'caregiver_lastname' => 'required',
            'caregiver_mobile' => 'required_without_all:caregiver_email,caregiver_address,caregiver_city,caregiver_state,caregiver_zip',
            'caregiver_email' => 'required_without_all:caregiver_mobile,caregiver_address,caregiver_city,caregiver_state,caregiver_zip',
            'caregiver_address' => 'required_without_all:caregiver_mobile,caregiver_email',
            'caregiver_apt' => '',
            'caregiver_city' => 'required_without_all:caregiver_mobile,caregiver_email',
            'caregiver_state' => 'required_without_all:caregiver_mobile,caregiver_email',
            'caregiver_zip' => 'required_without_all:caregiver_mobile,caregiver_email',

            'pediatrician_firstname' => 'required',
            'pediatrician_lastname' => 'required',
            'pediatrician_practice' => '',
            'pediatrician_phone' => '',
            'pediatrician_fax' => 'sometimes|required',
            'pediatrician_address' => '',
            'pediatrician_suite' => '',
            'pediatrician_city' => '',
            'pediatrician_state' => '',
            'pediatrician_zip' => 'sometimes|required',
            'diagnosis_premature' => '',
            'diagnosis_weeks' => '',
            'diagnosis_days' => '',
            'diagnosis_bpdcldp' => '',
            'diagnosis_other' => '',
            'diagnosis_chd' => '',
            'diagnosis_othertext' => '',
            'dosing_inhospital' => '',
            'dosing_date' => '',
            'dosing_datenext' => '',
            'dosing_outpatient' => '',
            'A360_signature' => '',
            'A360_date' => 'required_with:A360_signature',
            'CWC_signature' => '',
            'CWC_date' => 'required_with:CWC_signature',
            'language_preference' => '',
            'auth_lang' => ''
        );
        $this->validator = Validator::make($array, $this->_rules);

        $this->validator->sometimes('diagnosis_premature', 'required_without:diagnosis_bpdcldp,diagnosis_chd,diagnosis_other', function ($input) {
            return !$input->diagnosis_bpdcldp && !$input->diagnosis_chd && !$input->diagnosis_other;
        });
        $this->validator->sometimes('diagnosis_bpdcldp', 'required_without:diagnosis_premature,diagnosis_chd,diagnosis_other', function ($input) {
            return !$input->diagnosis_premature && !$input->diagnosis_chd && !$input->diagnosis_other;
        });
        $this->validator->sometimes('diagnosis_chd', 'required_without:diagnosis_premature,diagnosis_bpdcldp,diagnosis_other', function ($input) {
            return !$input->diagnosis_bpdcldp && !$input->diagnosis_premature && !$input->diagnosis_other;
        });
        $this->validator->sometimes('diagnosis_other', 'required_without:diagnosis_premature,diagnosis_bpdcldp,diagnosis_chd', function ($input) {
            return !$input->diagnosis_bpdcldp && !$input->diagnosis_premature && !$input->diagnosis_chd;
        });

        $this->validator->sometimes('A360_signature', 'required_without:TOC_signature,CWC_signature', function ($input) {
            return !$input->TOC_signature && !$input->CWC_signature;
        });
        $this->validator->sometimes('CWC_signature', 'required_without:TOC_signature,A360_signature', function ($input) {
            return !$input->A360_signature && !$input->TOC_signature;
        });

        $messages = '';

        if ($this->validator->fails()) {
            $this->valid = false;
            $messages = $this->validator->messages();
            $this->validatorError = "<ul>";
            foreach ($messages->all('<li>:message</li>') as $message) {
                $this->validatorError .= $message;
            }
            $this->validatorError .= "</ul>";

            Log::info("EPAFValidator->validate() - Form invalid [FAIL]");
            Log::info("EPAFValidator->validate() - Form validation error: ".$this->validatorError);
        } else {
            $this->valid = true;
            $this->validatorError = false;
            Log::info("EPAFValidator->validate() - Form valid [SUCCESS]");
        }
    }

}
