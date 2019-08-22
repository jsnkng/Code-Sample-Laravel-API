<?php
// Unit tests for PHP eFax -- testing the eFax::parse_inbound_message() function

require_once('simpletest/autorun.php');
require_once('php/efax.php');

class Test_eFaxReceive extends UnitTestCase
{
    function get_default()
    {
        // WARNING: simpletest does not properly distinguish between null, 0 and ''...
        return array(
                'request_date' => -1,
                'request_type' => 'undefined',
                'fax_id' => 'n.a.',
                'fax_number' => 'Unknown',
                'csid' => null,
                'completion_date' => -1,
                'fax_name' => null,
                'docid' => 'n.a.',
                'pages' => 1,
                'fax_status' => null,
                'user_fields' => array(),
                'barcodes' => array(
                    null => array(
                        'key' => null,
                        'page' => null,
                        'sequence' => null,
                        'direction' => null,
                        'symbology' => null,
                        'x_start_a' => null,
                        'y_start_a' => null,
                        'x_start_b' => null,
                        'y_start_b' => null,
                        'x_end_a' => null,
                        'y_end_a' => null,
                        'x_end_b' => null,
                        'y_end_b' => null
                    )
                ),
                'files' => array()
            );
    }

    // Sub-Function used to verify the resulting barcode objects
    function barcode_check($barcode, $value)
    {
        $this->assertEqual($barcode->get_key(),       $value['key'],       "key is [" . $barcode->get_key() . "] instead of [" . $value['key'] . "]");
        $this->assertEqual($barcode->get_page(),      $value['page'],      "page is [" . $barcode->get_page() . "] instead of [" . $value['page'] . "]");
        $this->assertEqual($barcode->get_sequence(),  $value['sequence'],  "sequence is [" . $barcode->get_sequence() . "] instead of [" . $value['sequence'] . "]");
        $this->assertEqual($barcode->get_direction(), $value['direction'], "direction is [" . $barcode->get_direction() . "] instead of [" . $value['direction'] . "]");
        $this->assertEqual($barcode->get_symbology(), $value['symbology'], "symbology is [" . $barcode->get_symbology() . "] instead of [" . $value['symbology'] . "]");

        $x_start_a = "exists";
        $y_start_a = "exists";
        $x_start_b = "exists";
        $y_start_b = "exists";
        $x_end_a = "exists";
        $y_end_a = "exists";
        $x_end_b = "exists";
        $y_end_b = "exists";
        $barcode->get_points($x_start_a, $y_start_a, $x_start_b, $y_start_b,
                    $x_end_a, $y_end_a, $x_end_b, $y_end_b);
        $this->assertEqual($x_start_a, $value['x_start_a'], "X start point A is [" . $x_start_a . "] instead of [" . $value['x_start_a'] . "]");
        $this->assertEqual($y_start_a, $value['y_start_a'], "Y start point A is [" . $y_start_a . "] instead of [" . $value['y_start_a'] . "]");
        $this->assertEqual($x_start_b, $value['x_start_b'], "X start point B is [" . $x_start_b . "] instead of [" . $value['x_start_b'] . "]");
        $this->assertEqual($y_start_b, $value['y_start_b'], "Y start point B is [" . $y_start_b . "] instead of [" . $value['y_start_b'] . "]");
        $this->assertEqual($x_end_a,   $value['x_end_a'],   "X end point A is [" . $x_end_a . "] instead of [" . $value['x_end_a'] . "]");
        $this->assertEqual($y_end_a,   $value['y_end_a'],   "Y end point A is [" . $y_end_a . "] instead of [" . $value['y_end_a'] . "]");
        $this->assertEqual($x_end_b,   $value['x_end_b'],   "X end point B is [" . $x_end_b . "] instead of [" . $value['x_end_b'] . "]");
        $this->assertEqual($y_end_b,   $value['y_end_b'],   "Y end point B is [" . $y_end_b . "] instead of [" . $value['y_end_b'] . "]");
    }

    // Function used to verify the resulting data
    function check($efax, $value)
    {
        $this->assertEqual($efax->get_result_request_date(),    $value['request_date'],    "request_date is ["    . $efax->get_result_request_date()    . "] instead of [" . $value['request_date']    . "]");
        $this->assertEqual($efax->get_result_request_type(),    $value['request_type'],    "request_type is ["    . $efax->get_result_request_type()    . "] instead of [" . $value['request_type']    . "]");
        $this->assertEqual($efax->get_result_fax_id(),          $value['fax_id'],          "fax_id is ["          . $efax->get_result_fax_id()          . "] instead of [" . $value['fax_id']          . "]");
        $this->assertEqual($efax->get_result_fax_number(),      $value['fax_number'],      "fax_number is ["      . $efax->get_result_fax_number()      . "] instead of [" . $value['fax_number']      . "]");
        $this->assertEqual($efax->get_result_csid(),            $value['csid'],            "csid is ["            . $efax->get_result_csid()            . "] instead of [" . $value['csid']            . "]");
        $this->assertEqual($efax->get_result_completion_date(), $value['completion_date'], "completion_date is [" . $efax->get_result_completion_date() . "] instead of [" . $value['completion_date'] . "]");
        $this->assertEqual($efax->get_result_fax_name(),        $value['fax_name'],        "fax_name is ["        . $efax->get_result_fax_name()        . "] instead of [" . $value['fax_name']        . "]");
        $this->assertEqual($efax->get_result_docid(),           $value['docid'],           "docid is ["           . $efax->get_result_docid()           . "] instead of [" . $value['docid']           . "]");
        $this->assertEqual($efax->get_result_pages(),           $value['pages'],           "pages is ["           . $efax->get_result_pages()           . "] instead of [" . $value['pages']           . "]");
        $this->assertEqual($efax->get_result_fax_status(),      $value['fax_status'],      "fax_status is ["      . $efax->get_result_fax_status()      . "] instead of [" . $value['fax_status']      . "]");

        $user_fields = $efax->get_result_user_fields();
        $this->assertEqual(count($user_fields), count($value['user_fields']));
        foreach($user_fields as $name => $data)
        {
            $this->assertEqual($data, $value['user_fields'][$name]);
        }

        $barcodes = $efax->get_result_barcodes();
        //$this->assertEqual(count($barcodes), count($value['barcodes']));
        foreach($barcodes as $barcode)
        {
            $key = $barcode->get_key();
            $this->assertEqual($value['barcodes'][$key]['key'], $key, "barcode entry with key '$key' does not have the proper key '" . $value['barcodes'][$key]['key'] . "'");
            $value['barcodes'][$key]['found'] = true;
            $this->barcode_check($barcode, $value['barcodes'][$key]);
        }
        foreach($value['barcodes'] as $barcode)
        {
            $this->assertTrue($barcode['found'], "barcode entry with key '" . $barcode['key'] . "' was not processed.");
        }

        $files = $efax->get_result_files();
        $this->assertEqual(count($files), count($value['files']));
        foreach($files as $f)
        {
            $found = false;
            foreach($value['files'] as $file)
            {
                if(isset($file['found']))
                {
                    // already used up
                    continue;
                }
                //echo "T: ", $file['type'], " vs ", $f['type'],
                //    " (", ($file['type'] == $f['type'] ? 't' : 'f'), ")",
                //    " P: ", $file['page'], " vs ", $f['page'],
                //    " (", ($file['page'] == $f['page'] ? 't' : 'f'), ")",
                //    " C: ", $file['contents'], " vs ", $f['contents'],
                //    " (", ($file['contents'] == $f['contents'] ? 't' : 'f'), ")",
                //"\n";
                if($file['type'] == $f['type']
                && $file['page'] == $f['page']
                && $file['contents'] == $f['contents'])
                {
                    $file['found'] = true;
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "File not found (type: " . $f['type'] . ", page: " . $f['page'] . ")");
        }
    }

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
        $this->assertEqual($efax->parse_inbound_message($msg), $result);

        return $efax;
    }

    function test_Info()
    {
        echo "  Receive Tests\n";
    }

    function test_WrongTag()
    {
        // NOTE: the file being loaded here is not important, except for the root tag
        echo "    Wrong root tag (expects InboundPostRequest)\n";
        $this->expectException(new eFaxException("inbound request message does not include an InboundPostRequest tag"));
        $efax = $this->get_efax(true, '', '', "tests/efax-tests/xml/efax_receive_wrong.xml");
        $this->assertTrue(false, "exception not caught!");
    }

    function test_MissingInit1()
    {
        // NOTE: the file being loaded here is not important, except for the root tag
        echo "    Missing username and password\n";
        $this->expectException(new eFaxException("parsing of an inbound message requires a user name and password"));
        $efax = $this->get_efax(true, '', '', "tests/efax-tests/xml/efax_receive_missing1.xml");
        $this->assertTrue(false, "exception not caught!");
    }

    function test_MissingInit2()
    {
        // NOTE: the file being loaded here is not important, except for the root tag
        echo "    Missing password\n";
        $this->expectException(new eFaxException("parsing of an inbound message requires a user name and password"));
        $efax = $this->get_efax(true, 'my username', '', "tests/efax-tests/xml/efax_receive_missing1.xml");
        $this->assertTrue(false, "exception not caught!");
    }

    function test_MissingInit3()
    {
        // NOTE: the file being loaded here is not important, except for the root tag
        echo "    Missing username\n";
        $this->expectException(new eFaxException("parsing of an inbound message requires a user name and password"));
        $efax = $this->get_efax(true, '', 'secret password', "tests/efax-tests/xml/efax_receive_missing1.xml");
        $this->assertTrue(false, "exception not caught!");
    }

    function test_MissingInit4()
    {
        echo "    Missing AccessControl\n";
        $this->expectException(new eFaxException("inbound request message does not include an InboundPostRequest/AccessControl tag"));
        $efax = $this->get_efax(true, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_missing1.xml");
        $this->assertTrue(false, "exception not caught!");
    }

    function test_MissingInit5()
    {
        echo "    Missing AccessControl/UserName (and Password)\n";
        $this->expectException(new eFaxException("inbound request message does not include an InboundPostRequest/AccessControl/UserName tag"));
        $efax = $this->get_efax(true, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_missing2.xml");
        $this->assertTrue(false, "exception not caught!");
    }

    function test_MissingInit6()
    {
        echo "    Missing AccessControl/Password\n";
        $this->expectException(new eFaxException("inbound request message does not include an InboundPostRequest/AccessControl/Password tag"));
        $efax = $this->get_efax(true, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_missing3.xml");
        $this->assertTrue(false, "exception not caught!");
    }

    function test_MissingInit7()
    {
        echo "    Missing AccessControl/UserName (but Password is defined)\n";
        $this->expectException(new eFaxException("inbound request message does not include an InboundPostRequest/AccessControl/UserName tag"));
        $efax = $this->get_efax(true, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_missing4.xml");
        $this->assertTrue(false, "exception not caught!");
    }

    function test_InvalidName()
    {
        echo "    Invalid user name and/or password\n";
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_access1.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid login name.');
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_access2.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid password.');
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_access3.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid login name.');
    }

    function test_Empty()
    {
        echo "    Empty test\n";
        $efax = $this->get_efax(true, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_empty1.xml");
        $this->check($efax, $this->get_default());
    }

    function test_InvalidRequestDate()
    {
        echo "    Invalid Request Date\n";
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_request_date1.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid date and time in RequestDate.');
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_request_date2.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid date and time in RequestDate.');
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_request_date3.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid date and time in RequestDate.');
    }

    function test_InvalidReceivedDate()
    {
        echo "    Invalid Received Date\n";
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_received_date1.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid date and time in DateReceived.');
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_received_date2.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid date and time in DateReceived.');
        $efax = $this->get_efax(false, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_invalid_received_date3.xml");
        $this->assertEqual($efax->get_error_description(), 'Invalid date and time in DateReceived.');
    }

    function test_CompleteForms()
    {
        echo "    Complete Forms\n";

        $efax = $this->get_efax(true, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_full1.xml");
        $content = $this->get_default();
        $content['request_date'] = 1198529437 + 8 * 60 * 60;        // date -u +%s -d '2007/12/24 20:50:37'
        $content['request_type'] = 'Incoming Fax';
        $content['fax_id'] = '9169881450';
        $content['fax_number'] = '9165551212';
        $content['csid'] = 'Made to Order Software Corp.';
        $content['completion_date'] = 817760469 + 8 * 60 * 60;    // date -u +%s -d '1995/11/30 19:41:09'
        $content['fax_name'] = 'Some Name';
        $content['docid'] = 'Unique Identifier';
        $content['pages'] = 4;
        $content['fax_status'] = 5;
        $content['user_fields'] = array(
                'var1' => 'content1',
                'var3' => 'content3',
                'var5' => 'content5'
            );
        $content['barcodes'] = array(
                'Barcode I' => array(
                        'key' => 'Barcode I',
                        'page' => 3,
                        'sequence' => 184,
                        'direction' => 'Left/Right',
                        'symbology' => 'Greek3',
                        'x_start_a' => 1.03,
                        'y_start_a' => 2.06,
                        'x_start_b' => 3.09,
                        'y_start_b' => 4.12,
                        'x_end_a' => 12.001,
                        'y_end_a' => 13.207,
                        'x_end_b' => 14.903,
                        'y_end_b' => 15.008
                    ),
                'Barcode II' => array(
                        'key' => 'Barcode II',
                        'page' => 4,
                        'sequence' => 104,
                        'direction' => 'Right/Left',
                        'symbology' => 'Latin1',
                        'x_start_a' => 9.03,
                        'y_start_a' => 8.06,
                        'x_start_b' => 7.09,
                        'y_start_b' => 6.12,
                        'x_end_a' => 32.041,
                        'y_end_a' => 33.267,
                        'x_end_b' => 34.983,
                        'y_end_b' => 35.028
                    )
            );
        $content['files'] = array(
                array(
                    'type' => 'tif',
                    'page' => 1,
                    'contents' => 'This is the content of page 1'
                ),
                array(
                    'type' => 'tif',
                    'page' => 2,
                    'contents' => 'This is the content of page 2'
                ),
                array(
                    'type' => 'tif',
                    'page' => 3,
                    'contents' => 'This is the content of page 3'
                ),
                array(
                    'type' => 'tif',
                    'page' => 4,
                    'contents' => 'This is the content of page 4'
                )
            );
        $this->check($efax, $content);

        $efax = $this->get_efax(true, 'my username', 'secret password', "tests/efax-tests/xml/efax_receive_full2.xml");
        $content = $this->get_default();
        $content['request_date'] = 1202947262 + 8 * 60 * 60;        // date -u +%s -d '2007/12/24 20:50:37'
        $content['request_type'] = 'New Fax';
        $content['fax_id'] = '9165551212';
        $content['fax_number'] = '9169881450';
        $content['csid'] = 'm2osw.com';
        $content['completion_date'] = 817760469 + 8 * 60 * 60;    // date -u +%s -d '1995/11/30 19:41:09'
        $content['fax_name'] = 'Some Name';
        $content['docid'] = 'Unique Identifier';
        $content['pages'] = 2;
        $content['fax_status'] = 7;
        $content['user_fields'] = array(
                'field_a' => 'content1',
                'field_b' => 'content3',
                'field_c' => 'content5'
            );
        $content['barcodes'] = array(
                '2nd barcode' => array(
                        'key' => '2nd barcode',
                        'page' => 1,
                        'sequence' => 2,
                        'direction' => 'Top/Bottom',
                        'symbology' => 'BC2',
                        'x_start_a' => 9.03,
                        'y_start_a' => 8.06,
                        'x_start_b' => 7.09,
                        'y_start_b' => 6.12,
                        'x_end_a' => 32.041,
                        'y_end_a' => 33.267,
                        'x_end_b' => 34.983,
                        'y_end_b' => 35.028
                    ),
                '1st barcode' => array(
                        'key' => '1st barcode',
                        'page' => 1,
                        'sequence' => 1,
                        'direction' => 'Bottom/Top',
                        'symbology' => 'BC1',
                        'x_start_a' => 1.03,
                        'y_start_a' => 2.06,
                        'x_start_b' => 3.09,
                        'y_start_b' => 4.12,
                        'x_end_a' => 12.001,
                        'y_end_a' => 13.207,
                        'x_end_b' => 14.903,
                        'y_end_b' => 15.008
                    )
            );
        $content['files'] = array(
                array(
                    'type' => 'pdf',
                    'page' => 1,
                    'contents' => 'Page one of the second test'
                ),
                array(
                    'type' => 'pdf',
                    'page' => 2,
                    'contents' => 'Second and last page of the 2nd test!'
                )
            );
        $this->check($efax, $content);
    }
};

