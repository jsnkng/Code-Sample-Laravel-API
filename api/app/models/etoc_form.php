<?php
namespace CDM\Synagis;

use \Log;
use \DB;

class ETOCForm
{
    /*******************************************************************************
    *                                                                              *
    *                               Public methods                                 *
    *                                                                              *
    *******************************************************************************/
    public function __construct($ip="unavailable")
    {
        // Public vars
        $this->ip = $ip;
        $this->valid = '';
        $this->validator = '';
        $this->validatorError = '';
        $this->error = '';
        $this->application_type = 'etoc';

        Log::info("########################");
        Log::info("ETOCForm->__construct()");
    }

    public function create($array)
    {
        // Set form _data and validate form, setting
        // $this->validatorError and $this->valid

        Log::info("ETOCForm->create()");
        $this->instance($array);
        $etoc_validator = new ETOCValidator();
        $etoc_validator->validate($this->_data);
        $this->valid = $etoc_validator->valid;
        $this->validator = $etoc_validator->validator;
        $this->validatorError = $etoc_validator->validatorError;
    }

    public function get($value)
    {
        return $this->_data[$value];
    }


    public function update_application_messagestatus($statustype_id, $return_id, $status)
    {
        $application_messagestatus = DB::update("CALL update_application_messagestatus('".$statustype_id."','".$return_id."','0','".$status."');");
         Log::info("ETOCForm->update_application_messagestatus '".$statustype_id."','".$return_id."','','".$status."'");

    }

    public function instance($array)
    {
        // Instantitate object with array _data
        $this->_data = array();

        $this->_data['application_type'] = $this->application_type;
        $this->_data['patient_firstname'] = isset($array['patient_firstname']) ? ucwords(strtolower(substr($array['patient_firstname'], 0, 16))) : '';
        $this->_data['patient_lastname'] = isset($array['patient_lastname']) ? ucwords(strtolower(substr($array['patient_lastname'], 0, 16))) : '';
        $this->_data['patient_middlename'] = isset($array['patient_middlename']) ? ucwords(strtolower(substr($array['patient_middlename'], 0, 16))) : '';
        $this->_data['patient_birthdate'] = isset($array['patient_birthdate']) ? substr($array['patient_birthdate'], 0, 30) : '';
        $this->_data['patient_insurance'] = isset($array['patient_insurance']) ? ucwords(strtolower(substr($array['patient_insurance'], 0, 30))) : '';
        $this->_data['patient_lb'] = isset($array['patient_lb']) ? substr($array['patient_lb'], 0, 5) : '';
        $this->_data['patient_oz'] = isset($array['patient_oz']) ? substr($array['patient_oz'], 0, 5) : '';
        $this->_data['patient_dischargedate'] = isset($array['patient_dischargedate']) ? substr($array['patient_dischargedate'], 0, 12) : '';
        $this->_data['patient_number'] = isset($array['patient_number']) ? substr($array['patient_number'], 0, 18) : '';
        $this->_data['caregiver_firstname'] = isset($array['caregiver_firstname']) ? ucwords(strtolower(substr($array['caregiver_firstname'], 0, 20))) : '';
        $this->_data['caregiver_lastname'] = isset($array['caregiver_lastname']) ? ucwords(strtolower(substr($array['caregiver_lastname'], 0, 20))) : '';
        $this->_data['caregiver_mobile'] = isset($array['caregiver_mobile']) ? substr($array['caregiver_mobile'], 0, 15) : '';
        // $this->_data['caregiver_home'] = isset($array['caregiver_home']) ? $array['caregiver_home'] : '';
        $this->_data['caregiver_email'] = isset($array['caregiver_email']) ? strtolower(substr($array['caregiver_email'], 0, 30)) : '';
        $this->_data['caregiver_address'] = isset($array['caregiver_address']) ? ucwords(strtolower(substr($array['caregiver_address'], 0, 30))) : '';
        $this->_data['caregiver_apt'] = isset($array['caregiver_apt']) ? substr($array['caregiver_apt'], 0, 12) : '';
        $this->_data['caregiver_city'] = isset($array['caregiver_city']) ? ucwords(strtolower(substr($array['caregiver_city'], 0, 20))) : '';
        $this->_data['caregiver_state'] = isset($array['caregiver_state']) ? substr($array['caregiver_state'], 0, 10) : '';
        $this->_data['caregiver_zip'] = isset($array['caregiver_zip']) ? substr($array['caregiver_zip'], 0, 10) : '';
        $this->_data['hospital_name'] = isset($array['hospital_name']) ? ucwords(strtolower(substr($array['hospital_name'], 0, 30))) : '';

        $this->_data['hospital_contact_firstname'] = isset($array['hospital_contact_firstname']) ? ucwords(strtolower(substr($array['hospital_contact_firstname'], 0, 16))) : '';
        $this->_data['hospital_contact_lastname'] = isset($array['hospital_contact_lastname']) ? ucwords(strtolower(substr($array['hospital_contact_lastname'], 0, 16))) : '';
        $this->_data['hospital_contact'] = $this->_data['hospital_contact_firstname'].' '.$this->_data['hospital_contact_lastname'];


        $this->_data['hospital_phone'] = isset($array['hospital_phone']) ? substr($array['hospital_phone'], 0, 15) : '';
        $this->_data['hospital_zip'] = isset($array['hospital_zip']) ? substr($array['hospital_zip'], 0, 10) : '';
        $this->_data['pediatrician_firstname'] = isset($array['pediatrician_firstname']) ? ucwords(strtolower(substr($array['pediatrician_firstname'], 0, 15))) : '';
        $this->_data['pediatrician_lastname'] = isset($array['pediatrician_lastname']) ? ucwords(strtolower(substr($array['pediatrician_lastname'], 0, 15))) : '';
        $this->_data['pediatrician_practice'] = isset($array['pediatrician_practice']) ? ucwords(strtolower(substr($array['pediatrician_practice'], 0, 25))) : '';
        $this->_data['pediatrician_phone'] = isset($array['pediatrician_phone']) ? substr($array['pediatrician_phone'], 0, 15) : '';
        $this->_data['pediatrician_fax'] = isset($array['pediatrician_fax']) ? substr($array['pediatrician_fax'], 0, 15) : '';
        $this->_data['pediatrician_address'] = isset($array['pediatrician_address']) ? ucwords(strtolower(substr($array['pediatrician_address'], 0, 30))) : '';
        $this->_data['pediatrician_suite'] = isset($array['pediatrician_suite']) ? substr($array['pediatrician_suite'], 0, 12) : '';
        $this->_data['pediatrician_city'] = isset($array['pediatrician_city']) ? ucwords(strtolower(substr($array['pediatrician_city'], 0, 24))) : '';
        $this->_data['pediatrician_state'] = isset($array['pediatrician_state']) ? substr($array['pediatrician_state'], 0, 30) : '';
        $this->_data['pediatrician_zip'] = isset($array['pediatrician_zip']) ? substr($array['pediatrician_zip'], 0, 12) : '';
        $this->_data['diagnosis_premature'] = isset($array['diagnosis_premature']) ? $array['diagnosis_premature'] : '';
        $this->_data['diagnosis_bpdcldp'] = isset($array['diagnosis_bpdcldp']) ? $array['diagnosis_bpdcldp']  : '';
        $this->_data['diagnosis_other'] = isset($array['diagnosis_other']) ? $array['diagnosis_other'] : '';
        $this->_data['diagnosis_chd'] = isset($array['diagnosis_chd']) ? $array['diagnosis_chd']: '';
        $this->_data['diagnosis_weeks'] = isset($array['diagnosis_weeks']) ? substr($array['diagnosis_weeks'], 0, 10) : '';
        $this->_data['diagnosis_days'] = isset($array['diagnosis_days']) ? substr($array['diagnosis_days'], 0, 10) : '';
        $this->_data['diagnosis_othertext'] = isset($array['diagnosis_othertext']) ? ucwords(strtolower(substr($array['diagnosis_othertext'], 0, 30))) : '';
        $this->_data['dosing_date'] = isset($array['dosing_date']) ? substr($array['dosing_date'], 0, 12) : '';
        $this->_data['dosing_datenext'] = isset($array['dosing_datenext']) ? substr($array['dosing_datenext'], 0, 12) : '';
        $this->_data['dosing_inhospital'] = isset($array['dosing_inhospital']) ? substr($array['dosing_inhospital'], 0, 10) : '';
        $this->_data['dosing_outpatient'] = isset($array['dosing_outpatient']) ? substr($array['dosing_outpatient'], 0, 10) : '';
        $this->_data['TOC_hospital'] = isset($array['TOC_hospital']) ? ucwords(strtolower(substr($array['TOC_hospital'], 0, 24))) : '';
        $this->_data['TOC_signature'] = isset($array['TOC_signature']) ? ucwords(strtolower(substr($array['TOC_signature'], 0, 24))) : '';
        $this->_data['TOC_date'] = isset($array['TOC_date']) ? substr($array['TOC_date'], 0, 15) : '';
        $this->_data['A360_signature'] = isset($array['A360_signature']) ? ucwords(strtolower(substr($array['A360_signature'], 0, 24))) : '';
        $this->_data['A360_date'] = isset($array['A360_date']) ? substr($array['A360_date'], 0, 15) : '';
        $this->_data['CWC_signature'] = isset($array['CWC_signature']) ? ucwords(strtolower(substr($array['CWC_signature'], 0, 24))) : '';
        $this->_data['CWC_date'] = isset($array['CWC_date']) ? substr($array['CWC_date'], 0, 15) : '';
        $this->_data['language_preference'] = isset($array['language_preference']) ? substr($array['language_preference'], 0, 5) : '';
        $this->_data['auth_lang'] = isset($array['auth_lang']) ? substr($array['auth_lang'], 0, 5) : '';


        Log::info("ETOCForm->instance()");

        $this->get_representative();
    }


    public function get_representative()
    {
        $representative = DB::select("CALL get_representative(
            '".$this->_data['pediatrician_zip']."');");

        if(!isset($representative[0]->id)) {
             $this->_data['rep_id'] = '0';

            Log::info("ETOCForm->get_representative() [FAIL] return representative_id: ".$this->_data['rep_id'] );
            return;
        }
         $this->_data['rep_id'] = $representative[0]->id;
         $this->_data['rep_first_name'] = $representative[0]->first_name;
         $this->_data['rep_last_name'] = $representative[0]->last_name;
         $this->_data['rep_email'] = $representative[0]->email;

        Log::info("ETOCForm->get_representative() [SUCCESS] return representative_id: ".$this->_data['rep_id'] );
            return;


    }

    public function insert_pediatrician()
    {
        $pediatrician = DB::select("CALL insert_pediatrician(
            '".$this->_data['rep_id']."',
            '".addslashes($this->_data['pediatrician_zip'])."',
            '".addslashes($this->_data['pediatrician_fax'])."');");
        Log::info("ETOCForm->insert_pediatrician() [SUCCESS] return pediatrician_id: ".$pediatrician[0]->pediatrician_id);
        return $pediatrician[0]->pediatrician_id;

    }

    public function insert_application()
    {
        $pediatrician_id = $this->insert_pediatrician();

        $application = DB::select("CALL insert_application(
            '".$pediatrician_id."',
            '".addslashes($this->application_type)."',
            '".addslashes($this->_data['language_preference'])."',
            '".addslashes($this->_data['hospital_name'])."',
            '".addslashes($this->_data['hospital_contact'])."',
            '".addslashes($this->_data['hospital_phone'])."',
            '".addslashes($this->_data['hospital_zip'])."',
            '".addslashes($this->ip)."');");
        Log::info("ETOCForm->insert_application() [SUCCESS] return application_id ".$application[0]->application_id);
        return $application[0]->application_id;
    }

    public function insert_application_messagestatus(
        $optin_id,
        $messagetype_id,
        $statustype_id,
        $return_id,
        $errorcode,
        $errormessage)
    {
        $application_messagestatus = DB::select("CALL insert_application_messagestatus(
            '".$optin_id."',
            '".$messagetype_id."',
            '".$statustype_id."',
            '".$return_id."',
            '".$errorcode."',
            '".$errormessage."');");
        Log::info("ETOCForm->insert_application_messagestatus() [SUCCESS] return application_messagestatus_id ".$application_messagestatus[0]->application_messagestatus_id);
        return $application_messagestatus[0]->application_messagestatus_id;
    }

    public function insert_application_optin($application_id, $optintype_id)
    {
       $application_optin_id = DB::select("CALL insert_application_optin(
            '".$application_id."',
            '".$optintype_id."');");
        Log::info("ETOCForm->insert_application_optin() [SUCCESS] return application_optin_id ".$application_optin_id[0]->application_optin_id);
        return $application_optin_id[0]->application_optin_id;
    }

       public function insert_application_trackedevent($application_id, $trackedevent_id)
    {
       $application_trackedevent =  DB::select("CALL insert_application_trackedevent(
            '".$application_id."',
            '".$trackedevent_id."');");
        Log::info("ETOCForm->insert_application_trackedevent() [SUCCESS] return application_trackedevent_id ".$application_trackedevent[0]->application_tracked_event_id);
        return $application_trackedevent[0]->application_tracked_event_id;
    }
}
