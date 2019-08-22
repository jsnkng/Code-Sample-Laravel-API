<?php
// Unit tests for PHP eFax -- Testing the eFaxBarcode class

require_once('simpletest/autorun.php');
require_once('php/efax.php');

class Test_eFaxBarcode extends UnitTestCase
{
    // Function used to verify the resulting object
    function check($barcode, $value)
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

    function test_Info()
    {
        echo "  Barcode Tests\n";
    }

    function test_Empty()
    {
        for($idx = 1; $idx <= 6; ++$idx)
        {
            echo "    Empty barcode file $idx\n";
            $xml = new DOMDocument;
            $this->assertTrue($xml->load("tests/efax-tests/xml/efax_barcode_empty$idx.xml"));
            $this->check(new eFaxBarcode($xml),
                array(
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
                ));
        }
    }

    function test_KeyOnly()
    {
        echo "    Key Only Test\n";
        $xml = new DOMDocument;
        $this->assertTrue($xml->load("tests/efax-tests/xml/efax_barcode_keyonly.xml"));
        $this->check(new eFaxBarcode($xml),
            array(
                'key' => 'Key Only Test',
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
            ));
    }

    function test_BasicInfo()
    {
        echo "    Basic Info Test\n";
        $xml = new DOMDocument;
        $this->assertTrue($xml->load("tests/efax-tests/xml/efax_barcode_basicinfo.xml"));
        $this->check(new eFaxBarcode($xml),
            array(
                'key' => 'Basic Info Test',
                'page' => null,
                'sequence' => 951,
                'direction' => '2-Dimentional',
                'symbology' => 'ANSI-1354',
                'x_start_a' => null,
                'y_start_a' => null,
                'x_start_b' => null,
                'y_start_b' => null,
                'x_end_a' => null,
                'y_end_a' => null,
                'x_end_b' => null,
                'y_end_b' => null
            ));
    }

    function test_Squattered1()
    {
        echo "    Squattered Data Test 1\n";
        $xml = new DOMDocument;
        $this->assertTrue($xml->load("tests/efax-tests/xml/efax_barcode_squattered1.xml"));
        $this->check(new eFaxBarcode($xml),
            array(
                'key' => null,
                'page' => null,
                'sequence' => 184,
                'direction' => null,
                'symbology' => 'Greek3',
                'x_start_a' => 1.03,
                'y_start_a' => null,
                'x_start_b' => 3.09,
                'y_start_b' => null,
                'x_end_a' => null,
                'y_end_a' => 13.207,
                'x_end_b' => null,
                'y_end_b' => 15.008
            ));
    }

    function test_Squattered2()
    {
        echo "    Squattered Data Test 2\n";
        $xml = new DOMDocument;
        $this->assertTrue($xml->load("tests/efax-tests/xml/efax_barcode_squattered2.xml"));
        $this->check(new eFaxBarcode($xml),
            array(
                'key' => null,
                'page' => 1001,
                'sequence' => null,
                'direction' => 'Right/Left',
                'symbology' => null,
                'x_start_a' => 1.03,
                'y_start_a' => 2.06,
                'x_start_b' => 3.09,
                'y_start_b' => 4.12,
                'x_end_a' => null,
                'y_end_a' => null,
                'x_end_b' => null,
                'y_end_b' => null
            ));
    }

    function test_AllInfo()
    {
        echo "    All Info Test\n";
        $xml = new DOMDocument;
        $this->assertTrue($xml->load("tests/efax-tests/xml/efax_barcode_allinfo.xml"));
        $this->check(new eFaxBarcode($xml),
            array(
                'key' => 'All Info Test',
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
            ));
    }
};

?>
