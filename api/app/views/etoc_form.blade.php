@extends('layouts.master')

@section('content')
<html>
<body>


    {{ Form::open(array('action' => 'ETOCController@postForm')) }}

    <fieldset>
        <legend>Patient's Information</legend>
        {{ Form::label('patient_firstname', 'First Name') }}
        {{ Form::text('patient_firstname', null,  array('class' => 'form-control', 'ng-model' => 'harroo')) }}
        {{ $errors->first('patient_firstname', '<p>:message</p>') }}
        <br>
        {{ Form::label('patient_middlename', 'Middle Name') }}
        {{ Form::text('patient_middlename', null,  array('class' => 'form-control')) }}
        {{ $errors->first('patient_middlename', '<p>:message</p>') }}
        <br>
        {{ Form::label('patient_lastname', 'Last Name') }}
        {{ Form::text('patient_lastname', null,  array('class' => 'form-control')) }}

        {{ $errors->first('patient_lastname', '<p>:message</p>') }}
        <br>

        {{ Form::label('patient_birthdate', 'Birthdate') }}
        {{ Form::input('date', 'patient_birthdate', null,  array('class' => 'form-control')) }}

        <br>
    <label>Birth weight</label><br>
        {{ Form::label('patient_lb', 'lb.') }}
        {{ Form::input('number', 'patient_lb', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('patient_oz', 'oz.') }}
        {{ Form::input('number', 'patient_oz', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('patient_dischargedate','Date of discharge') }}
        {{ Form::input('date', 'patient_dischargedate', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('patient_number','Medical record #') }}
        {{ Form::text('patient_number', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('patient_insurance', 'Patient\'s insurance carrier') }}
        {{ Form::text('patient_insurance', null,  array('class' => 'form-control')) }}

    </fieldset>
<br><br>
    <fieldset>
        <legend>Patient/Caregiver Information</legend>

        {{ Form::label('caregiver_firstname', 'First Name') }}
        {{ Form::text('caregiver_firstname', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('caregiver_lastname', 'Last Name') }}
        {{ Form::text('caregiver_lastname', null,  array('class' => 'form-control')) }}
        <br>



        {{ Form::label('caregiver_home', 'Home phone') }}
        {{ Form::input('tel', 'caregiver_home', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('caregiver_mobile', 'Mobile phone') }}
        {{ Form::input('tel', 'caregiver_mobile', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('caregiver_email', 'Email') }}
        {{ Form::text('caregiver_email', null,  array('class' => 'form-control')) }}
        <br>


        {{ Form::label('caregiver_address', 'Address') }}
        {{ Form::text('caregiver_address', null,  array('class' => 'form-control')) }}

        <br>
        {{ Form::label('caregiver_apt', 'Apt #') }}
        {{ Form::text('caregiver_apt', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('caregiver_city', 'City') }}
        {{ Form::text('caregiver_city', null,  array('class' => 'form-control')) }}

        <br>
        {{ Form::label('caregiver_state', 'State') }}
        {{ Form::text('caregiver_state', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('caregiver_zip', 'Zip') }}
        {{ Form::text('caregiver_zip', null,  array('class' => 'form-control')) }}

    </fieldset>
<br><br>
    <fieldset>
        <legend>Hospital Information</legend>
        {{ Form::label('hospital_name', 'Hospital name') }}
        {{ Form::text('hospital_name', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('hospital_contact', 'Hospital contact') }}
        {{ Form::text('hospital_contact', null,  array('class' => 'form-control')) }}
        <br>


        {{ Form::label('hospital_phone', 'Hospital phone') }}
        {{ Form::input('tel', 'hospital_phone', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('hospital_zip', 'Hospital zip') }}
        {{ Form::text('hospital_zip', null,  array('class' => 'form-control')) }}
    </fieldset>
<br><br>
    <fieldset>
        <legend>Pediatrician Information</legend>
        {{ Form::label('pediatrician_firstname', 'First name') }}
        {{ Form::text('pediatrician_firstname', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('pediatrician_lastname', 'Last name') }}
        {{ Form::text('pediatrician_lastname', null,  array('class' => 'form-control')) }}
        <br>


        {{ Form::label('pediatrician_practice', 'Practice name') }}
        {{ Form::text('pediatrician_practice', null,  array('class' => 'form-control')) }}
        <br>


        {{ Form::label('pediatrician_phone', 'Phone') }}
        {{ Form::input('tel', 'pediatrician_phone', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('pediatrician_fax', 'Fax') }}
        {{ Form::input('tel', 'pediatrician_fax', null,  array('class' => 'form-control')) }}
        <br>


        {{ Form::label('pediatrician_address', 'Address') }}
        {{ Form::text('pediatrician_address', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('pediatrician_suite', 'Suite') }}
        {{ Form::text('pediatrician_suite', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('pediatrician_city', 'City') }}
        {{ Form::text('pediatrician_city', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('pediatrician_state', 'State') }}
        {{ Form::text('pediatrician_state', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('pediatrician_zip', 'Zip') }}
        {{ Form::text('pediatrician_zip', null,  array('class' => 'form-control')) }}

    </fieldset>
<br><br>
    <fieldset>
        <legend>High-risk infant</legend>
        {{ Form::label('diagnosis_premature', 'Premature') }}
        {{ Form::checkbox('diagnosis_premature', 'premature') }}
<br>

        {{ Form::label('diagnosis_weeks', 'Weeks') }}
        {{ Form::text('diagnosis_weeks', null,  array('class' => 'form-control')) }}

        {{ Form::label('diagnosis_days', 'Days') }}
        {{ Form::text('diagnosis_days', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('diagnosis_other', 'Risk factors/other') }}
        {{ Form::checkbox('diagnosis_other', 'other') }}

        <br>


        {{ Form::label('diagnosis_bpdcldp', 'Bronchopulmanary dysplasia/chronic lung disease of prematurity (BPD/CLDP)') }}
        {{ Form::checkbox('diagnosis_bpdcldp', 'bpdcldp') }}
        <br>

        {{ Form::label('diagnosis_chd', 'Hemodynamically significant congenital heart disease (CHD)') }}
        {{ Form::checkbox('diagnosis_chd', 'chd') }}

        <br>


        {{ Form::label('diagnosis_othertext', 'Explain') }}
        {{ Form::text('diagnosis_othertext', null,  array('class' => 'form-control')) }}
    </fieldset>
<br><br>
    <fieldset>
        <legend>Dosing assessment/schedule</legend>
        {{ Form::label('dosing_inhospital', 'Patient received initial dose in hospital on') }}
        {{ Form::checkbox('dosing_inhospital', 'inhospital') }}
<br>
        {{ Form::label('dosing_date', 'dosing_date') }}
        {{ Form::input('date', 'dosing_date', null,  array('class' => 'form-control')) }}

        {{ Form::label('dosing_datenext', 'dosing_datenext') }}
        {{ Form::input('date', 'dosing_datenext', null,  array('class' => 'form-control')) }}
        <br>

        {{ Form::label('dosing_outpatient', 'Patient should be considered for Synagis<sup>&reg;</sup> (palivizumab) in the outpatient setting') }}
        {{ Form::checkbox('dosing_outpatient', 'outpatient') }}
    </fieldset>
<br><br>
    <fieldset>
        <legend>Transition of Care</legend>
        {{ Form::label('TOC_hospital', 'Hospital name') }}
        {{ Form::text('TOC_hospital', null,  array('class' => 'form-control')) }}
        <br>
        {{ Form::label('TOC_signature', 'Enter your name') }}
        {{ Form::text('TOC_signature', null,  array('class' => 'form-control')) }}

        {{ Form::label('TOC_date', 'Date') }}
        {{ Form::input('date', 'TOC_date', null,  array('class' => 'form-control')) }}
    </fieldset>
<br><br>

    <fieldset>
        <legend>MedImmune Access 360<sup>&trade;</sup> Patient Authorization</legend>
        {{ Form::label('A360_signature', 'Enter your name') }}
        {{ Form::text('A360_signature', null,  array('class' => 'form-control')) }}

        {{ Form::label('A360_date', 'Date') }}
        {{ Form::input('date', 'A360_date', null,  array('class' => 'form-control')) }}
    </fieldset>
<br><br>
    <fieldset>
        <legend>Cradle with Care<sup>sm</sup></legend>
        {{ Form::label('CWC_signature', 'Enter your name') }}
        {{ Form::text('CWC_signature', null,  array('class' => 'form-control')) }}

        {{ Form::label('CWC_date', 'Date') }}
        {{ Form::input('date', 'CWC_date', null,  array('class' => 'form-control')) }}
</fieldset>

<br><br>
        <fieldset>
            <legend>Language Preference</legend>
            {{ Form::label('language_preference', 'Language') }}
            {{ Form::input('text', 'language_preference', null,  array('class' => 'form-control')) }}
        </fieldset>
<br><br>
        {{ Form::submit('Submit') }}
        {{  Form::close()  }}

    </body>
    </html>
    @stop
