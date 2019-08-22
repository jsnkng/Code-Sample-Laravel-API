<?php
// Unit tests for PHP eFax -- testing the eFax::parse_inbound_message() function

require_once('../../php/efax.php4');

// This is to test the PHP 4.x version and thus we cannot use
// the PHPUnit toolkit.
//
// We use functions so we do not create global variables which
// can then result in false results. It has the side effect of
// naming each test.

// Break the code and exit with an error
// $msg is the error message
function failed($msg)
{
    echo "\n";
    echo "***\n";
    echo "*** ERROR: $msg\n";
    echo "***\n";
    echo "\n";
    die("Process stopped. Please fix the code before continuing.\n");
}
function working_on($msg)
{
    static $cnt = 0;
    ++$cnt;
    if($cnt < 10) echo " ";
    echo $cnt.". ".$msg."\n";
}

// A nothing object for test purposes
class TestObject
{
}


// Create a new eFax object, initialize it and parse the specified XML file
function get_efax($result, $user_name, $password, $filename)
{
    $efax = new eFax;
    if($user_name)
    {
        $efax->set_user_name($user_name);
    }
    if($password)
    {
        $efax->set_user_password($password);
    }
    $msg = file_get_contents($filename, FILE_TEXT);
    $r = $efax->parse_inbound_message($msg);
    if($r != $result) {
        failed('parse_inbound_message() did not return the expected value.');
    }

    return $efax;
}


//////////////////////////////////////////////
//////////////////////////////////////////////
// TESTING INBOUND PARSING FUNCTIONS
//////////////////////////////////////////////
//////////////////////////////////////////////
echo "\n--- INBOUDN PARSING ---\n\n";

// We check that the normal and raw account identifier functions
// do check the validity of the data as expected
function complete_test()
{
    working_on("Testing a complete inbound XML file.");

    // first parse the XML file
    $efax = get_efax(true, 'my username', 'secret password', "xml/efax_receive_full1.xml");

    // now check the results
    $d = gmmktime(20, 50, 37, 12, 24, 2007) + 8 * 60 * 60;
    $r = $efax->get_result_request_date();
    if($r != $d) {
        failed("get_result_request_date() returned an unexpected date.");
    }

    if($efax->get_result_request_type() != 'Incoming Fax') {
        failed("get_result_request_type() returned an unexpected type.");
    }

    if($efax->get_result_fax_id() != '9169881450') {
        failed("get_result_fax_id() returned an unexpected account identifier.");
    }

    $d = gmmktime(19, 41, 9, 11, 30, 1995) + 8 * 60 * 60;
    $r = $efax->get_result_completion_date();
    if($r != $d) {
        failed("get_result_completion_date() returned an unexpected date.");
    }

    if($efax->get_result_fax_name() != 'Some Name') {
        failed("get_result_fax_name() returned an unexpected name.");
    }

    if($efax->get_result_fax_status() != 5) {
        failed("get_result_fax_status() returned an unexpected status.");
    }

    if($efax->get_result_pages() != 4) {
        failed("get_result_pages() returned an unexpected number of pages.");
    }

    if($efax->get_result_csid() != 'Made to Order Software Corp.') {
        failed("get_result_csid() returned an unexpected identifier.");
    }

    if($efax->get_result_fax_number() != '9165551212') {
        failed("get_result_fax_number() returned an unexpected fax number.");
    }

    if($efax->get_result_docid() != 'Unique Identifier') {
        failed("get_result_docid() returned an unexpected document identifier.");
    }

    $fields = $efax->get_result_user_fields();
    if(count($fields) != 3) {
        failed("get_result_user_fields() returned an unexpected number of fields.");
    }
    if($fields['var1'] != 'content1') {
        failed("get_result_user_fields() returned an unexpected var1.");
    }
    if($fields['var3'] != 'content3') {
        failed("get_result_user_fields() returned an unexpected var3.");
    }
    if($fields['var5'] != 'content5') {
        failed("get_result_user_fields() returned an unexpected var5.");
    }

    $barcodes = $efax->get_result_barcodes();
    foreach($barcodes as $b) {
        switch($b->get_key()) {
        case 'Barcode I':
            if($b->get_page() != 3) {
                failed("get_result_barcodes() returned an unexpected page number (I).");
            }
            if($b->get_sequence() != 184) {
                failed("get_result_barcodes() returned an unexpected sequence number (I).");
            }
            if($b->get_direction() != 'Left/Right') {
                failed("get_result_barcodes() returned an unexpected direction (I).");
            }
            if($b->get_symbology() != 'Greek3') {
                failed("get_result_barcodes() returned an unexpected symbology (I).");
            }
            $b->get_points($xsa, $ysa, $xsb, $ysb, $xea, $yea, $xeb, $yeb);
            if($xsa !=  1.03  || $ysa !=  2.06  || $xsb !=  3.09  || $ysb !=  4.12
            || $xea != 12.001 || $yea != 13.207 || $xeb != 14.903 || $yeb != 15.008) {
                failed("get_result_barcodes() returned an unexpected points (I).");
            }
            break;

        case 'Barcode II':
            if($b->get_page() != 4) {
                failed("get_result_barcodes() returned an unexpected page number (II).");
            }
            if($b->get_sequence() != 104) {
                failed("get_result_barcodes() returned an unexpected sequence number (II).");
            }
            if($b->get_direction() != 'Right/Left') {
                failed("get_result_barcodes() returned an unexpected direction (II).");
            }
            if($b->get_symbology() != 'Latin1') {
                failed("get_result_barcodes() returned an unexpected symbology (II).");
            }
            $b->get_points($xsa, $ysa, $xsb, $ysb, $xea, $yea, $xeb, $yeb);
            if($xsa !=  9.03  || $ysa !=  8.06  || $xsb !=  7.09  || $ysb !=  6.12
            || $xea != 32.041 || $yea != 33.267 || $xeb != 34.983 || $yeb != 35.028) {
                failed("get_result_barcodes() returned an unexpected points (II).");
            }
            break;

        default:
            failed("get_result_barcodes() returned an unexpected key.");

        }
    }

    $files = $efax->get_result_files();
    if(count($files) != 4) {
        failed("get_result_files() returned an unexpected number of files.");
    }
    foreach($files as $f) {
        if(!is_array($f)) {
            failed("get_result_files() should have returned an array of arrays.");
        }
        // the type does not change between pages
        if($f["type"] != "tif") {
            failed("get_result_files() returned an unexpected type.");
        }
        switch($f["page"]) {
        case 1:
            if($f["contents"] != "This is the content of page 1") {
                failed("get_result_files() returned unexpected contents on page 1.");
            }
            break;

        case 2:
            if($f["contents"] != "This is the content of page 2") {
                failed("get_result_files() returned unexpected contents on page 2.");
            }
            break;

        case 3:
            if($f["contents"] != "This is the content of page 3") {
                failed("get_result_files() returned unexpected contents on page 3.");
            }
            break;

        case 4:
            if($f["contents"] != "This is the content of page 4") {
                failed("get_result_files() returned unexpected contents on page 4.");
            }
            break;

        default:
            failed("get_result_files() has an unexpected page.");

        }
    }
}
complete_test();







echo "\n";
echo "+++\n";
echo "+++ All tests passed!\n";
echo "+++\n";
echo "\n";
exit(0);
