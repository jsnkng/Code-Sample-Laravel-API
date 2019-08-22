<?php
namespace CDM\Synagis;

use \Log;
use \fpdf\FPDF;
use \fpdi\FPDI;

class EPAFPDF
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
        Log::info("EPAFPDF->__construct()");
        $this->_fpdf = new FPDI('P', 'mm', 'Letter');
    }
    public function addCoversheet($sending_fax, $recieving_fax )
    {
        $date = date("F j, Y");
            $this->_fpdf->addPage();
            $this->_fpdf->SetAutoPageBreak(true,'5');


            $this->_fpdf->SetFont('Arial','BU',17);
            $this->_fpdf->SetXY(80,20);
            $this->_fpdf->Cell(50, 5, "FAX COVER SHEET", 0, 0, 'C');

            $this->_fpdf->SetFont('Arial','B',14);
            $this->_fpdf->SetXY(30,80);
            $this->_fpdf->Cell(50, 5, "From: $sending_fax", 0, 0, 'L');
            $this->_fpdf->SetXY(30,92);
            $this->_fpdf->Cell(50, 5, "To: $recieving_fax", 0, 0, 'L');
            $this->_fpdf->SetXY(30,104);
            $this->_fpdf->Cell(50, 5, "Date: $date", 0, 0, 'L');

            $this->_fpdf->Line(30, 120, 185, 120);

            $this->_fpdf->SetTextColor(252,13,27);
            $this->_fpdf->SetFont('Arial','B',15);
            $this->_fpdf->SetXY(30,128);
            $this->_fpdf->Cell(50, 5, "ATTENTION: ", 0, 0, 'L');
            $this->_fpdf->SetXY(30,140);
            $to_create_reg_mark = iconv('UTF-8', 'windows-1252', "SYNAGIS® (palivizumab) Coordinator - ");
            $this->_fpdf->Cell(50, 5, $to_create_reg_mark, 0, 0, 'L');

            $this->_fpdf->SetFont('Arial','BU',15);
            $this->_fpdf->SetXY(137,140);
            $this->_fpdf->Cell(50, 5, "Urgent Action Requested", 0, 0, 'C');


            $this->_fpdf->SetTextColor(0,0,0);
            $this->_fpdf->SetFont('Arial','B',15);
            $this->_fpdf->SetXY(40,248);
            $this->_fpdf->Cell(145, 5, "Note: If you have received this transmission in error,", 0, 0, 'C');
            $this->_fpdf->SetXY(40,258);
            $this->_fpdf->Cell(145, 5, "please destroy this communication", 0, 0, 'C');

        Log::info("EPAFPDF->add_coversheet()");

    }
    public function create($form, $pdf_template, $type, $coversheet)
    {
        $this->_fpdf->AddFont('Windsong', '', 'windsong.php');

        $numPages = $this->_fpdf->setSourceFile($pdf_template);

        if (strpos($pdf_template, 'ePAFPediatrician')) {
            Log::info("EPAFPDF->create() Pediatrician PDF");
            $fill_form_pages = array('1');
        } elseif (strpos($pdf_template, 'ePAFRxCrossroads')) {
            Log::info("EPAFPDF->create() RxCrossroads PDF");
            $fill_form_pages = array('1');
        } elseif (strpos($pdf_template, 'ePAFUser')) {
            Log::info("EPAFPDF->create() Patient PDF");
            $fill_form_pages = array('2');
        }



            for ($i=1; $i<=$numPages; $i++) {
                $tplIdx =  $this->_fpdf->importPage($i, '/BleedBox');
                $this->_fpdf->addPage();
                $this->_fpdf->SetAutoPageBreak(true,'5');
                $this->_fpdf->SetFont('Helvetica','',9);
                $this->_fpdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

                if($i==1) {
                    $this->_fpdf->SetFont('Helvetica','B',10);
                    $this->_fpdf->SetXY(191,5);
                    $this->_fpdf->Cell(20, 5, 'DIGITAL', 0, 0, 'L');
                }

                if(in_array($i, $fill_form_pages)) {
                    $this->_fpdf->SetFont('Helvetica','B',10);
                    $this->_fpdf->SetXY(191,5);
                    $this->_fpdf->Cell(20, 5, 'DIGITAL', 0, 0, 'L');
                $this->_fpdf->SetFont('Helvetica','',9);
                $this->_fpdf->SetXY(32,22);
                $this->_fpdf->Cell(75, 5, $form['patient_firstname']." ".$form['patient_middlename']." ".$form['patient_lastname'], 0, 0, 'L');
                $this->_fpdf->SetXY(150,22);
                $this->_fpdf->Cell(60, 5, $form['patient_insurance'], 0, 0, 'L');
                $this->_fpdf->SetXY(26,30);
                $this->_fpdf->Cell(25, 5, $form['patient_birthdate'], 0, 0, 'L');
                $this->_fpdf->SetXY(88,30);
                $this->_fpdf->Cell(6, 5, $form['patient_lb'], 0, 0, 'L');
                $this->_fpdf->SetXY(106,30);
                $this->_fpdf->Cell(6, 5, $form['patient_oz'], 0, 0, 'L');
                // $this->_fpdf->SetXY(125,30);
                // $this->_fpdf->Cell(25, 5, $form['patient_dischargedate'], 0, 0, 'L');
                $this->_fpdf->SetXY(174,30);
                $this->_fpdf->Cell(25, 5, $form['patient_number'], 0, 0, 'L');
                $this->_fpdf->SetXY(50,38);
                $this->_fpdf->Cell(50, 5, $form['caregiver_firstname']." ".$form['caregiver_lastname'], 0, 0, 'C');

                if($form['caregiver_mobile'] != '') {
                    $this->_fpdf->SetXY(110,38);
                    $this->_fpdf->Cell(27, 5, "(".substr($form['caregiver_mobile'], 0, 3).") ".substr($form['caregiver_mobile'], 3, 3)."-".substr($form['caregiver_mobile'],6), 0, 0, 'C');
                }
                // $this->_fpdf->SetXY(135,38);
                // $this->_fpdf->Cell(27, 5, $form['caregiver_home'], 0, 0, 'L');
                $this->_fpdf->SetXY(156,38);
                $this->_fpdf->Cell(27, 5, $form['caregiver_email'], 0, 0, 'C');
                $this->_fpdf->SetXY(46,46);
                $this->_fpdf->Cell(55, 5, $form['caregiver_address'], 0, 0, 'C');
                $this->_fpdf->SetXY(108,46);
                $this->_fpdf->Cell(10, 5, $form['caregiver_apt'], 0, 0, 'C');
                $this->_fpdf->SetXY(124,46);
                $this->_fpdf->Cell(50, 5, $form['caregiver_city'], 0, 0, 'C');
                $this->_fpdf->SetXY(178,46);
                $this->_fpdf->Cell(10, 5, $form['caregiver_state'], 0, 0, 'C');
                $this->_fpdf->SetXY(186,46);
                $this->_fpdf->Cell(25, 5, $form['caregiver_zip'], 0, 0, 'C');
                // $this->_fpdf->SetXY(35,52);
                // $this->_fpdf->Cell(60, 5, $form['hospital_name'], 0, 0, 'L');
                // $this->_fpdf->SetXY(100,52);
                // $this->_fpdf->Cell(65, 5, $form['hospital_contact'], 0, 0, 'L');
                // $this->_fpdf->SetXY(170,52);
                // $this->_fpdf->Cell(30, 5, $form['hospital_phone'], 0, 0, 'L');
                $this->_fpdf->SetXY(40,54);
                $this->_fpdf->Cell(60, 5, $form['pediatrician_firstname']." ".$form['pediatrician_lastname'], 0, 0, 'C');

                $this->_fpdf->SetXY(80,54);
                $this->_fpdf->Cell(65, 5, $form['pediatrician_practice'], 0, 0, 'C');

                if($form['pediatrician_phone'] != '') {
                    $this->_fpdf->SetXY(130,54);
                    $this->_fpdf->Cell(50, 5, "(".substr($form['pediatrician_phone'], 0, 3).") ".substr($form['pediatrician_phone'], 3, 3)."-".substr($form['pediatrician_phone'],6), 0, 0, 'C');
                }
                if($form['pediatrician_fax'] != '') {
                    $this->_fpdf->SetXY(175,54);
                    $this->_fpdf->Cell(30, 5, "(".substr($form['pediatrician_fax'], 0, 3).") ".substr($form['pediatrician_fax'], 3, 3)."-".substr($form['pediatrician_fax'],6), 0, 0, 'C');
                }
                $this->_fpdf->SetXY(58,62);
                $this->_fpdf->Cell(45, 5, $form['pediatrician_address'], 0, 0, 'L');
                $this->_fpdf->SetXY(112,62);
                $this->_fpdf->Cell(10, 5, $form['pediatrician_suite'], 0, 0, 'C');
                $this->_fpdf->SetXY(124,62);
                $this->_fpdf->Cell(50, 5, $form['pediatrician_city'], 0, 0, 'C');
                $this->_fpdf->SetXY(178,62);
                $this->_fpdf->Cell(10, 5, $form['pediatrician_state'], 0, 0, 'C');
                $this->_fpdf->SetXY(186,62);
                $this->_fpdf->Cell(25, 5, $form['pediatrician_zip'], 0, 0, 'C');

                if ($form['diagnosis_premature'] == 'true') {
                    $this->_fpdf->SetFont('Helvetica','B',12);
                    $this->_fpdf->SetXY(5,79);
                    $this->_fpdf->Cell(5, 5, 'X', 0, 0, 'L');
                    $this->_fpdf->SetFont('Helvetica','',9);
                }
                if (isset($form['diagnosis_weeks'])) {
                    $this->_fpdf->SetXY(50,79);
                    $this->_fpdf->Cell(5, 5, $form['diagnosis_weeks'], 0, 0, 'L');
                }
                if (isset($form['diagnosis_days'])) {
                    $this->_fpdf->SetXY(55,79);
                    $this->_fpdf->Cell(5, 5, '/ '.$form['diagnosis_days'], 0, 0, 'L');
                }
                if ($form['diagnosis_bpdcldp'] == 'true') {
                    $this->_fpdf->SetFont('Helvetica','B',12);
                    $this->_fpdf->SetXY(133,79);
                    $this->_fpdf->Cell(5, 5, 'X', 0, 0, 'L');
                    $this->_fpdf->SetFont('Helvetica','',9);
                }
                // if (isset($form['diagnosis_other'])) {
                //     $this->_fpdf->SetFont('Helvetica','B',12);
                //     $this->_fpdf->SetXY(15,85);
                //     $this->_fpdf->Cell(5, 5, 'X', 0, 0, 'L');
                //     $this->_fpdf->SetFont('Helvetica','',9);
                // }
                if ($form['diagnosis_chd'] == 'true') {
                    $this->_fpdf->SetFont('Helvetica','B',12);
                    $this->_fpdf->SetXY(133,90);
                    $this->_fpdf->Cell(5, 5, 'X', 0, 0, 'L');
                    $this->_fpdf->SetFont('Helvetica','',9);
                }
                if (isset($form['diagnosis_othertext'])) {
                    $this->_fpdf->SetXY(40,85);
                    $this->_fpdf->Cell(65, 5, $form['diagnosis_othertext'], 0, 0, 'C');
                }
                if ($form['dosing_inhospital'] == 'true') {
                    $this->_fpdf->SetFont('Helvetica','B',12);
                    $this->_fpdf->SetXY(5,102);
                    $this->_fpdf->Cell(5, 5, 'X', 0, 0, 'C');
                    $this->_fpdf->SetFont('Helvetica','',9);
                }
                if (isset($form['dosing_date'])) {
                    $this->_fpdf->SetXY(100,102);
                    $this->_fpdf->Cell(25, 5, $form['dosing_date'], 0, 0, 'C');
                }
                if ($form['dosing_datenext'] != '') {
                     $this->_fpdf->SetFont('Helvetica','B',12);
                    $this->_fpdf->SetXY(5,108);
                    $this->_fpdf->Cell(5, 5, 'X', 0, 0, 'L');
                    $this->_fpdf->SetFont('Helvetica','',9);
                    $this->_fpdf->SetXY(40,108);
                    $this->_fpdf->Cell(25, 5, $form['dosing_datenext'], 0, 0, 'C');
                }
                // if (isset($form['TOC_hospital'])) {
                //     $this->_fpdf->SetXY(53,117);
                //     $this->_fpdf->Cell(50, 5, $form['TOC_hospital'], 0, 0, 'L');
                // }
                // if (isset($form['TOC_signature'])) {
                //     $this->_fpdf->SetXY(53,143);
                //     $this->_fpdf->Cell(80, 5, $form['TOC_signature'], 0, 0, 'L');
                // }
                // if (isset($form['TOC_date'])) {
                //     $this->_fpdf->SetXY(175,143);
                //     $this->_fpdf->Cell(50, 5, $form['TOC_date'], 0, 0, 'L');
                // }
                if (isset($form['A360_signature'])) {
                    $this->_fpdf->SetFont('Windsong','',28);
                    $this->_fpdf->SetXY(70,192);
                    $this->_fpdf->Cell(80, 5, $form['A360_signature'], 0, 0, 'C');
                }
                if (isset($form['A360_date'])) {
                    $this->_fpdf->SetFont('Helvetica','',9);
                    $this->_fpdf->SetXY(162,192);
                    $this->_fpdf->Cell(50, 5, $form['A360_date'], 0, 0, 'C');
                }
                if (isset($form['CWC_signature'])) {
                    $this->_fpdf->SetFont('Windsong','',28);
                    $this->_fpdf->SetXY(70,255);
                    $this->_fpdf->Cell(80, 5, $form['CWC_signature'], 0, 0, 'C');
                }
                if (isset($form['CWC_date'])) {
                    $this->_fpdf->SetFont('Helvetica','',9);
                    $this->_fpdf->SetXY(162,256);
                    $this->_fpdf->Cell(50, 5, $form['CWC_date'], 0, 0, 'C');
                }
            }
        }
        return $this->_fpdf->Output('form.pdf', $type);
        Log::info("EPAFPDF->::create()");
    }

}
