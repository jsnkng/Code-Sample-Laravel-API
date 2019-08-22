<?php
// Unit tests for PHP eFax -- testing the eFax::send() function

require_once('php/efax.php4');

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


//////////////////////////////////////////////
//////////////////////////////////////////////
// TESTING SETUP FUNCTIONS
//////////////////////////////////////////////
//////////////////////////////////////////////
echo "\n--- SETUP FUNCTIONS ---\n\n";

// We check that the normal and raw account identifier functions
// do check the validity of the data as expected
function invalid_account_id()
{
    working_on("Testing account identifier validation.");

    $efax = new eFax;

    // NULL is not valid
    $r = $efax->set_account_id(null);
    if($r !== false) {
        failed('set_account_id(null) worked');
    }
    $r = $efax->set_raw_account_id(null);
    if($r !== false) {
        failed('set_raw_account_id(null) worked');
    }

    // An array is not acceptable
    $r = $efax->set_account_id(array(1, 2, 3));
    if($r !== false) {
        failed('set_account_id(array(...)) worked');
    }
    $r = $efax->set_raw_account_id(array(1, 2, 3));
    if($r !== false) {
        failed('set_raw_account_id(array(...)) worked');
    }

    // An object is not acceptable
    $o = new TestObject;
    $r = $efax->set_account_id($o);
    if($r !== false) {
        failed('set_account_id(new TestObject) worked');
    }
    $r = $efax->set_raw_account_id($o);
    if($r !== false) {
        failed('set_raw_account_id(new TestObject) worked');
    }
}
invalid_account_id();

// Check that valid data gets accepted properly
function valid_account_id()
{
    working_on("Testing valid account identifier.");

    $efax = new eFax;

    // Straight number
    $r = $efax->set_account_id('9169881450');
    if($r !== true) {
        failed('set_account_id(\'9169881450\') did not return true');
    }
    $r = $efax->set_raw_account_id('9169881450');
    if($r !== true) {
        failed('set_raw_account_id(\'9169881450\') did not return true');
    }

    // Composed numbers
    $r = $efax->set_account_id('+1 (916) 988-1450');
    if($r !== true) {
        failed('set_account_id(\'+1 (916) 988-1450\') did not return true');
    }
    $r = $efax->set_raw_account_id('+1 (916) 988-1450');
    if($r !== true) {
        failed('set_raw_account_id(\'+1 (916) 988-1450\') did not return true');
    }
}
valid_account_id();


// We check that the set_user_name() properly refuses invalid data
function invalid_user_name()
{
    working_on("Testing user name validation.");

    $efax = new eFax;

    // NULL is not valid
    $r = $efax->set_user_name(null);
    if($r !== false) {
        failed('set_user_name(null) worked');
    }

    // An array is not acceptable
    $r = $efax->set_user_name(array(1, 2, 3));
    if($r !== false) {
        failed('set_user_name(array(...)) worked');
    }

    // An object is not acceptable
    $o = new TestObject;
    $r = $efax->set_user_name($o);
    if($r !== false) {
        failed('set_user_name(new TestObject) worked');
    }

    // Name too long
    $n = "01234567890123456789O";
    for($i = 20; $i < 100; ++$i, $n = $n . "M") {
        $r = $efax->set_user_name($n);
        if($r !== false) {
            failed('set_user_name(<long name>) worked');
        }
    }
}
invalid_user_name();

// Test valid user name with set_user_name()
function valid_user_name()
{
    working_on("Testing valid user name.");

    $efax = new eFax;

    // All the possible length of a name
    for($n = ''; strlen($n) < 20; $n = $n . "N") {
        $r = $efax->set_user_name($n);
        if($r !== true) {
            failed('set_user_name(<valid name>) did not return true');
        }
    }
}
valid_user_name();




// We check that the set_user_password() properly refuses invalid data
function invalid_user_password()
{
    working_on("Testing user password validation.");

    $efax = new eFax;

    // NULL is not valid
    $r = $efax->set_user_password(null);
    if($r !== false) {
        failed('set_user_password(null) worked');
    }

    // An array is not acceptable
    $r = $efax->set_user_password(array(1, 2, 3));
    if($r !== false) {
        failed('set_user_password(array(...)) worked');
    }

    // An object is not acceptable
    $o = new TestObject;
    $r = $efax->set_user_password($o);
    if($r !== false) {
        failed('set_user_password(new TestObject) worked');
    }

    // Name too long
    $n = "01234567890123456789O";
    for($i = 20; $i < 100; ++$i, $n = $n . "M") {
        $r = $efax->set_user_password($n);
        if($r !== false) {
            failed('set_user_password(<long name>) worked');
        }
    }
}
invalid_user_password();

// Test valid password name with set_user_password()
function valid_user_password()
{
    working_on("Testing valid user passwords.");

    $efax = new eFax;

    // All the possible length of a name
    for($n = ''; strlen($n) < 20; $n = $n . "N") {
        $r = $efax->set_user_password($n);
        if($r !== true) {
            failed('set_user_password(<valid name>) did not return true');
        }
    }
}
valid_user_password();




// We check that the add_file() properly refuses invalid "mime types"
function test_add_file()
{
    working_on("Testing add_file().");

    $efax = new eFax;

    // Test with an invalid name
    $r = $efax->add_file('???', 'file data');
    if($r !== false) {
        failed('add_file(\'???\', ...) worked');
    }

    // Test all the valid names
    $names = array(
            "doc",  "DOC",
            "xls",  "XLS",
            "tif",  "TIF",
            "pdf",  "PDF",
            "txt",  "TXT",
            "html", "HTML",
            "htm",  "HTM",
            "rtf",  "RTF",
        );
    foreach($names as $n) {
        $r = $efax->add_file($n, 'file data');
        if($r !== true) {
            failed('add_file('.$n.') failed');
        }
    }
}
test_add_file();




// We check that the add_recipient() properly refuses invalid data
function invalid_add_recipient()
{
    working_on("Testing add recipient validation.");

    $efax = new eFax;

    $ten = "0123456789";
    $fifty = str_repeat($ten, 5);

    // Name too long
    $n = $fifty."O";
    for($i = 50; $i < 100; ++$i, $n = $n . "M") {
        $r = $efax->add_recipient($n, "Company", "9169881450");
        if($r !== false) {
            failed('add_recipient(<long name>, ..., ...) worked');
        }
    }

    // Company too long
    $n = $fifty."O";
    for($i = 50; $i < 100; ++$i, $n = $n . "M") {
        $r = $efax->add_recipient("Name", $n, "9169881450");
        if($r !== false) {
            failed('add_recipient(..., <long company>, ...) worked');
        }
    }

    // Fax too short
    $n = "";
    for($i = 0; $i < 5; ++$i, $n = $n . "5") {
        $r = $efax->add_recipient("Name", "Company", $n);
        if($r !== false) {
            failed('add_recipient(..., ..., <short fax>) worked');
        }
    }

    // Fax too long
    $n = $ten.$ten."12345"."9";
    for($i = 25; $i < 100; ++$i, $n = $n . "9") {
        $r = $efax->add_recipient("Name", "Company", $n);
        if($r !== false) {
            failed('add_recipient(..., ..., <long fax>) worked');
        }
    }
}
invalid_add_recipient();

// Test valid recipients with add_recipient()
function valid_add_recipient()
{
    working_on("Testing valid add recipient.");

    $efax = new eFax;

    // All the possible length of a name
    for($n = ''; strlen($n) < 50; $n = $n . "N") {
        $r = $efax->add_recipient($n, "Company", "9169881450");
        if($r !== true) {
            failed('add_recipient(<valid name>, ..., ...) did not return true');
        }
    }

    // All the possible length of a company name
    for($n = ''; strlen($n) < 50; $n = $n . "C") {
        $r = $efax->add_recipient("Name", $n, "9169881450");
        if($r !== true) {
            failed('add_recipient(..., <valid company>, ...) did not return true');
        }
    }

    // All the possible length of a fax with spaces, parenthesis, etc.
    for($n = '12345'; strlen($n) < 25; $n = $n . "9") {
        $r = $efax->add_recipient("Name", "Company", $n);
        if($r !== true) {
            failed('add_recipient(..., ..., <valid fax #>) did not return true');
        }
        $e = " " . $n . " ";
        $r = $efax->add_recipient("Name", "Company", $e);
        if($r !== true) {
            failed('add_recipient(..., ..., <valid fax # with spaces>) did not return true');
        }
        $e = "+" . $n . "+";
        $r = $efax->add_recipient("Name", "Company", $e);
        if($r !== true) {
            failed('add_recipient(..., ..., <valid fax # with +\'s>) did not return true');
        }
        $e = "-" . $n . "-";
        $r = $efax->add_recipient("Name", "Company", $e);
        if($r !== true) {
            failed('add_recipient(..., ..., <valid fax # with -\'s>) did not return true');
        }
        $e = "(" . $n . "(";
        $r = $efax->add_recipient("Name", "Company", $e);
        if($r !== true) {
            failed('add_recipient(..., ..., <valid fax # with (\'s>) did not return true');
        }
        $e = ")" . $n . ")";
        $r = $efax->add_recipient("Name", "Company", $e);
        if($r !== true) {
            failed('add_recipient(..., ..., <valid fax # with )\'s>) did not return true');
        }
    }
}
valid_add_recipient();





// We check that the set_outbound_url() properly refuses invalid data
function invalid_outbound_url()
{
    working_on("Testing outbound url validation.");

    $efax = new eFax;

    // Refuse null
    $r = $efax->set_outbound_url(null);
    if($r !== false) {
        failed('set_outbound_url(null) worked');
    }

    // Refuse arrays
    $r = $efax->set_outbound_url(array(1, 2, 3));
    if($r !== false) {
        failed('set_outbound_url(array(1, 2, 3)) worked');
    }

    // Refuse objects
    $o = new TestObject;
    $r = $efax->set_outbound_url($o);
    if($r !== false) {
        failed('set_outbound_url(<object>) worked');
    }

    // Too small a string (under 12 chars)
    for($n = ''; strlen($n) < 12; $n = $n . '+') {
        $r = $efax->set_outbound_url($n);
        if($r !== false) {
            failed('set_outbound_url(<small URL>) worked');
        }
    }
}
invalid_outbound_url();

// Test valid outbound urls with set_outbound_url()
function valid_outbound_url()
{
    working_on("Testing valid outbound url.");

    $efax = new eFax;

    // Check "valid" URLs
    for($n = str_repeat('U', 12); strlen($n) < 100; $n = $n . '+') {
        $r = $efax->set_outbound_url($n);
        if($r !== true) {
            failed('set_outbound_url(<small URL>) did not return true');
        }
    }
}
valid_outbound_url();





// We check that the set_fax_id() properly refuses invalid data
function invalid_fax_id()
{
    working_on("Testing fax id validation.");

    $efax = new eFax;

    // Refuse arrays
    $r = $efax->set_fax_id(array(1, 2, 3));
    if($r !== false) {
        failed('set_fax_id(array(1, 2, 3)) worked');
    }

    // Refuse objects
    $o = new TestObject;
    $r = $efax->set_fax_id($o);
    if($r !== false) {
        failed('set_fax_id(<object>) worked');
    }

    // Too large a string
    for($n = str_repeat('#', 16); strlen($n) < 100; $n = $n . '+') {
        $r = $efax->set_fax_id($n);
        if($r !== false) {
            failed('set_fax_id(<long id>) worked');
        }
    }
}
invalid_fax_id();

// Test valid fax identifier with set_fax_id()
function valid_fax_id()
{
    working_on("Testing valid fax id.");

    $efax = new eFax;

    // Accept null so we can reset the id
    $r = $efax->set_fax_id(null);
    if($r !== true) {
        failed('set_fax_id(null) did not return true');
    }

    // Accept numerics
    for($n = -100; $n <= 100; ++$n) {
        $r = $efax->set_fax_id($n);
        if($r !== true) {
            failed('set_fax_id(<number>) did not return true');
        }
    }

    // Check valid strings
    for($n = ""; strlen($n) < 16; $n = $n . '+') {
        $r = $efax->set_fax_id($n);
        if($r !== true) {
            failed('set_fax_id(<valid fax id>) did not return true');
        }
    }
}
valid_fax_id();





// We check that the add_disposition_email() properly refuses invalid data
function invalid_disposition_email()
{
    working_on("Testing disposition email validation.");

    $efax = new eFax;

    // Refuse null emails
    $r = $efax->add_disposition_email(null, null);
    if($r !== false) {
        failed('add_disposition_email(..., null) worked');
    }

    // Refuse arrays
    $r = $efax->add_disposition_email(array(1, 2, 3), "email");
    if($r !== false) {
        failed('add_disposition_email(array(1, 2, 3), ...) worked');
    }
    $r = $efax->add_disposition_email(null, array(1, 2, 3));
    if($r !== false) {
        failed('add_disposition_email(..., array(1, 2, 3)) worked');
    }

    // Refuse objects
    $o = new TestObject;
    $r = $efax->add_disposition_email($o, "email");
    if($r !== false) {
        failed('add_disposition_email(<object>, ...) worked');
    }
    $r = $efax->add_disposition_email(null, $o);
    if($r !== false) {
        failed('add_disposition_email(..., <object>) worked');
    }
    $r = $efax->add_disposition_email($o, $o);
    if($r !== false) {
        failed('add_disposition_email(..., <object>) worked');
    }

    // Too large a string
    $r = $efax->add_disposition_email(123, "email");
    if($r !== false) {
        failed('add_disposition_email(<number>, ...) worked');
    }
    $r = $efax->add_disposition_email(null, 123);
    if($r !== false) {
        failed('add_disposition_email(..., <number>) worked');
    }
    $r = $efax->add_disposition_email(123, 123);
    if($r !== false) {
        failed('add_disposition_email(..., <number>) worked');
    }
}
invalid_disposition_email();

// Test valid password name with add_recipient()
function valid_disposition_email()
{
    working_on("Testing valid disposition email.");

    $efax = new eFax;

    // Accept null name
    $r = $efax->add_disposition_email(null, "email");
    if($r !== true) {
        failed('add_disposition_email(null, ...) did not return true');
    }

    // Accept two strings, multiple times
    for($i = 0; $i < 10; ++$i) {
        $r = $efax->add_disposition_email("name", "email");
        if($r !== true) {
            failed('add_disposition_email("name", "email") did not return true');
        }
    }
}
valid_disposition_email();





// We check that the set_disposition_url() properly refuses invalid data
function invalid_disposition_url()
{
    working_on("Testing disposition URL validation.");

    $efax = new eFax;

    // Refuse numbers
    $r = $efax->set_disposition_url(123);
    if($r !== false) {
        failed('set_disposition_url(123) worked');
    }

    // Refuse arrays
    $r = $efax->set_disposition_url(array(1, 2, 3));
    if($r !== false) {
        failed('set_disposition_url(array(1, 2, 3)) worked');
    }

    // Refuse objects
    $o = new TestObject;
    $r = $efax->set_disposition_url($o);
    if($r !== false) {
        failed('set_disposition_url(<object>) worked');
    }

    // Too large a string
    for($n = str_repeat('#', 101); strlen($n) < 200; $n = $n . '+') {
        $r = $efax->set_disposition_url($n);
        if($r !== false) {
            failed('set_disposition_url(<long url>) worked');
        }
    }
}
invalid_disposition_url();

// Test valid disposition_url identifier with set_disposition_url()
function valid_disposition_url()
{
    working_on("Testing valid disposition URL.");

    $efax = new eFax;

    // Accept null so we can reset the id
    $r = $efax->set_disposition_url(null);
    if($r !== true) {
        failed('set_disposition_url(null) did not return true');
    }

    // Check valid strings
    for($n = ""; strlen($n) < 101; $n = $n . '+') {
        $r = $efax->set_disposition_url($n);
        if($r !== true) {
            failed('set_disposition_url(<valid dispostion URL>) did not return true');
        }
    }
}
valid_disposition_url();





// Test set_disposition_level()
function test_disposition_level()
{
    working_on("Testing disposition level.");

    $efax = new eFax;

    // valid levels
    $levels = array(
            0,
            $efax->RESPOND_ERROR,
            $efax->RESPOND_SUCCESS,
            $efax->RESPOND_ERROR | $efax->RESPOND_SUCCESS,
        );

    // Refuse invalid numbers
    for($l = -500; $l <= 500; ++$l) {
        if(!in_array($l, $levels)) {
            $r = $efax->set_disposition_level($l);
            if($r !== false) {
                failed('set_disposition_level(<invalid>) worked');
            }
        }
    }

    // Accept any valid level
    foreach($levels as $l) {
        $r = $efax->set_disposition_level($l);
        if($r !== true) {
            failed('set_disposition_level(<valid>) did not return true');
        }
    }

}
test_disposition_level();





// Test set_disposition_method()
function test_disposition_method()
{
    working_on("Testing disposition method.");

    $efax = new eFax;

    // valid methods
    $methods = array(
            null,
            "POST",
            "EMAIL",
            "NONE",
        );

    // Refuse anything else
    for($n = ""; strlen($n) < 10; $n = $n . 'B') {
        $r = $efax->set_disposition_method($n);
        if($r !== false) {
            failed('set_disposition_method(<invalid>) worked');
        }
    }

    // Accept any valid level
    foreach($methods as $m) {
        $r = $efax->set_disposition_method($m);
        if($r !== true) {
            failed('set_disposition_method(<valid>) did not return true');
        }
    }
}
test_disposition_method();





// Test set_duplicate_id()
function test_duplicate_id()
{
    working_on("Testing duplicate id.");

    $efax = new eFax;

    // valid methods
    $ids = array(
            null,
            true,
            false,
        );

    // Refuse anything other than true, false and null
    $r = $efax->set_duplicate_id("string");
    if($r !== false) {
        failed('set_duplicate_id(<string>) worked');
    }

    $r = $efax->set_duplicate_id(123);
    if($r !== false) {
        failed('set_duplicate_id(<number>) worked');
    }

    $r = $efax->set_duplicate_id(array(1, 2, 3));
    if($r !== false) {
        failed('set_duplicate_id(<array>) worked');
    }

    $o = new TestObject;
    $r = $efax->set_duplicate_id($o);
    if($r !== false) {
        failed('set_duplicate_id(<object>) worked');
    }

    // Accept any valid level
    foreach($ids as $i) {
        $r = $efax->set_duplicate_id($i);
        if($r !== true) {
            failed('set_duplicate_id(<valid>) did not return true');
        }
    }
}
test_duplicate_id();





// Test set_fax_header()
function test_fax_header()
{
    working_on("Testing fax header.");

    $efax = new eFax;

    // Refuse anything other than a string and null
    $r = $efax->set_fax_header(true);
    if($r !== false) {
        failed('set_fax_header(true) worked');
    }

    $r = $efax->set_fax_header(false);
    if($r !== false) {
        failed('set_fax_header(false) worked');
    }

    $r = $efax->set_fax_header(123);
    if($r !== false) {
        failed('set_fax_header(<number>) worked');
    }

    $r = $efax->set_fax_header(array(1, 2, 3));
    if($r !== false) {
        failed('set_fax_header(<array>) worked');
    }

    $o = new TestObject;
    $r = $efax->set_fax_header($o);
    if($r !== false) {
        failed('set_fax_header(<object>) worked');
    }

    // Refuse headers that are too long
    for($n = str_repeat('O', 81); strlen($n) <= 200; $n = $n . 'L') {
        $r = $efax->set_fax_header($n);
        if($r !== false) {
            failed('set_fax_header(<long header>) worked');
        }
    }

    // Accept null
    $r = $efax->set_fax_header(null);
    if($r !== true) {
        failed('set_fax_header(null) did not return true');
    }

    // Accept any valid header string
    for($n = ""; strlen($n) <= 80; $n = $n . 'H') {
        $r = $efax->set_fax_header($n);
        if($r !== true) {
            failed('set_fax_header(<valid>) did not return true');
        }
    }
}
test_fax_header();





// Test set_priority()
function test_priority()
{
    working_on("Testing priority.");

    $efax = new eFax;

    // valid priorities
    $priorities = array(
            null,
            "NORMAL",
            "Normal",
            "NoRmAl",
            "normal",
            "HIGH",
            "High",
            "HiGh",
            "high",
        );

    // Refuse true
    $r = $efax->set_priority(true);
    if($r !== false) {
        failed('set_priority(true) worked');
    }

    // Refuse false
    $r = $efax->set_priority(false);
    if($r !== false) {
        failed('set_priority(false) worked');
    }

    // Refuse numbers
    $r = $efax->set_priority(123);
    if($r !== false) {
        failed('set_priority(<number>) worked');
    }

    // Refuse array
    $r = $efax->set_priority(array(1, 2, 3));
    if($r !== false) {
        failed('set_priority(<array>) worked');
    }

    // Refuse objects
    $o = new TestObject;
    $r = $efax->set_priority($o);
    if($r !== false) {
        failed('set_priority(<object>) worked');
    }

    // Refuse invalid strings
    for($n = ""; strlen($n) < 20; $n = $n . 'B') {
        $r = $efax->set_priority($n);
        if($r !== false) {
            failed('set_priority(<invalid>) worked');
        }
    }

    // Accept any valid priority
    foreach($priorities as $p) {
        $r = $efax->set_priority($p);
        if($r !== true) {
            failed('set_priority(<valid>) did not return true');
        }
    }
}
test_priority();





// Test set_resolution()
function test_resolution()
{
    working_on("Testing resolution.");

    $efax = new eFax;

    // valid resolutions
    $resolutions = array(
            "STANDARD",
            "Standard",
            "StAnDaRd",
            "standard",
            "FINE",
            "Fine",
            "FiNe",
            "fine",
        );

    // Refuse true
    $r = $efax->set_resolution(true);
    if($r !== false) {
        failed('set_resolution(true) worked');
    }

    // Refuse false
    $r = $efax->set_resolution(false);
    if($r !== false) {
        failed('set_resolution(false) worked');
    }

    // Refuse numbers
    $r = $efax->set_resolution(123);
    if($r !== false) {
        failed('set_resolution(<number>) worked');
    }

    // Refuse array
    $r = $efax->set_resolution(array(1, 2, 3));
    if($r !== false) {
        failed('set_resolution(<array>) worked');
    }

    // Refuse objects
    $o = new TestObject;
    $r = $efax->set_resolution($o);
    if($r !== false) {
        failed('set_resolution(<object>) worked');
    }

    // Refuse invalid strings
    for($n = ""; strlen($n) < 20; $n = $n . 'B') {
        $r = $efax->set_resolution($n);
        if($r !== false) {
            failed('set_resolution(<invalid>) worked');
        }
    }

    // Accept any valid priority
    foreach($resolutions as $p) {
        $r = $efax->set_resolution($p);
        if($r !== true) {
            failed('set_resolution(<valid>) did not return true');
        }
    }
}
test_resolution();





// Test set_self_busy()
function test_self_busy()
{
    working_on("Testing self busy.");

    $efax = new eFax;

    // valid methods
    $ids = array(
            null,
            true,
            false,
        );

    // Refuse anything other than true, false and null
    $r = $efax->set_self_busy("string");
    if($r !== false) {
        failed('set_self_busy(<string>) worked');
    }

    $r = $efax->set_self_busy(123);
    if($r !== false) {
        failed('set_self_busy(<number>) worked');
    }

    $r = $efax->set_self_busy(array(1, 2, 3));
    if($r !== false) {
        failed('set_self_busy(<array>) worked');
    }

    $o = new TestObject;
    $r = $efax->set_self_busy($o);
    if($r !== false) {
        failed('set_self_busy(<object>) worked');
    }

    // Accept any valid level
    foreach($ids as $i) {
        $r = $efax->set_self_busy($i);
        if($r !== true) {
            failed('set_self_busy(<valid>) did not return true');
        }
    }
}
test_self_busy();



//////////////////////////////////////////////
//////////////////////////////////////////////
// TESTING SEND FUNCTION
//////////////////////////////////////////////
//////////////////////////////////////////////
echo "\n--- SEND FUNCTION ---\n\n";

// Test the access control tags
function test_access_control()
{
    working_on("Testing access control output.");

    $efax = new eFax;

    // with nothing, error
    $r = $efax->access_control_tags();
    if($r !== false) {
        failed('access_control_tags() worked without name and password');
    }

    // with just a name
    $efax->set_user_name('efax-test');
    $r = $efax->access_control_tags();
    if($r !== false) {
        failed('access_control_tags() worked without a password');
    }

    // eliminate the name!
    $efax = null;
    $efax = new eFax;

    // with just a name
    $efax->set_user_password('efax-passwd');
    $r = $efax->access_control_tags();
    if($r !== false) {
        failed('access_control_tags() worked without a name');
    }

    // test a valid case
    $efax->set_user_name('efax-test');
    $r = $efax->access_control_tags();
    if(!is_string($r)) {
        failed('access_control_tags() did not worked with valid data');
    }
    if($r != "<UserName>efax-test</UserName><Password>efax-passwd</Password>") {
        failed('access_control_tags() did not return the expected XML code');
    }
}
test_access_control();

// Test the transmission control tags
function test_transmission_control()
{
    working_on("Testing transmission control output.");

    // if we ask for a duplicate id we must have one
    $efax = new eFax;
    $efax->set_duplicate_id(TRUE);
    $r = $efax->transmission_control_tags();
    if($r !== false) {
        failed('transmission_control_tags() worked with duplicate_id() but no ID');
    }

    // test with no specific info
    $efax = new eFax;
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (nothing)');
    }
    if($r != '<TransmissionControl><Resolution>STANDARD</Resolution></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (nothing)');
    }

    // test FINE resolution
    $efax = new eFax;
    $efax->set_resolution('Fine');
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (resolution)');
    }
    if($r != '<TransmissionControl><Resolution>FINE</Resolution></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (resolution)');
    }

    // test fax ID
    $efax = new eFax;
    $efax->set_fax_id('efax-id');
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (fax_id)');
    }
    if($r != '<TransmissionControl><TransmissionID>efax-id</TransmissionID><Resolution>STANDARD</Resolution></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (fax_id)');
    }

    // test duplicate ID
    $efax = new eFax;
    // no ID necessary when testing with false
    $efax->set_duplicate_id(false);
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (duplicate_id--false)');
    }
    if($r != '<TransmissionControl><NoDuplicates>ENABLE</NoDuplicates><Resolution>STANDARD</Resolution></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (duplicate_id--false)');
    }
    // add an id when true
    $efax->set_duplicate_id(true);
    $efax->set_fax_id('efax-id');
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (duplicate_id--true)');
    }
    if($r != '<TransmissionControl><TransmissionID>efax-id</TransmissionID><NoDuplicates>DISABLE</NoDuplicates><Resolution>STANDARD</Resolution></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (duplicate_id--true)');
    }

    // test priority
    $efax = new eFax;
    $efax->set_priority('norMAL');
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (priority--normal)');
    }
    if($r != '<TransmissionControl><Resolution>STANDARD</Resolution><Priority>NORMAL</Priority></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (priority--normal)');
    }
    $efax->set_priority('High');
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (priority--high)');
    }
    if($r != '<TransmissionControl><Resolution>STANDARD</Resolution><Priority>HIGH</Priority></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (priority--high)');
    }

    // test self busy
    $efax = new eFax;
    $efax->set_self_busy(true);
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (self-busy--true)');
    }
    if($r != '<TransmissionControl><Resolution>STANDARD</Resolution><SelfBusy>ENABLE</SelfBusy></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (self-busy--true)');
    }
    $efax->set_self_busy(false);
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (self-busy--false)');
    }
    if($r != '<TransmissionControl><Resolution>STANDARD</Resolution><SelfBusy>DISABLE</SelfBusy></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (self-busy--false)');
    }

    // test fax header
    $efax = new eFax;
    $efax->set_fax_header('fax-header');
    $r = $efax->transmission_control_tags();
    if(!is_string($r)) {
        failed('transmission_control_tags() failed with valid data (fax-header)');
    }
    if($r != '<TransmissionControl><Resolution>STANDARD</Resolution><FaxHeader>fax-header</FaxHeader></TransmissionControl>') {
        failed('transmission_control_tags() did not return the expected XML code (fax-header)');
    }
}
test_transmission_control();


// Test the disposition control tags
function test_disposition_control()
{
    working_on("Testing disposition control output.");

    // test with nothing but the defaults (i.e. both)
    $efax = new eFax;
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (nothing)');
    }
    if($r != '<DispositionControl><DispositionLevel>BOTH</DispositionLevel></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (nothing)');
    }

    // test other disposition levels
    $efax = new eFax;
    $efax->set_disposition_level($efax->RESPOND_ERROR);
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (level:error)');
    }
    if($r != '<DispositionControl><DispositionLevel>ERROR</DispositionLevel></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (level:error)');
    }
    $efax->set_disposition_level($efax->RESPOND_SUCCESS);
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (level:success)');
    }
    if($r != '<DispositionControl><DispositionLevel>SUCCESS</DispositionLevel></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (level:success)');
    }
    $efax->set_disposition_level($efax->RESPOND_ERROR | $efax->RESPOND_SUCCESS);
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (level:both)');
    }
    if($r != '<DispositionControl><DispositionLevel>BOTH</DispositionLevel></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (level:both)');
    }
    $efax->set_disposition_level(0);
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (level:0)');
    }
    if($r != '<DispositionControl><DispositionLevel>NONE</DispositionLevel></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (level:0)');
    }

    // test a diposition URL
    $efax = new eFax;
    $efax->set_disposition_url('https://secure.m2osw.com/efax.php');
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (disposition URL)');
    }
    if($r != '<DispositionControl><DispositionURL>https://secure.m2osw.com/efax.php</DispositionURL><DispositionLevel>BOTH</DispositionLevel></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (disposition URL)');
    }

    // test a diposition method
    $efax = new eFax;
    $efax->set_disposition_method('poSt');
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (disposition method--post)');
    }
    if($r != '<DispositionControl><DispositionLevel>BOTH</DispositionLevel><DispositionMethod>POST</DispositionMethod></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (disposition method--post)');
    }
    $efax->set_disposition_method('eMail');
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (disposition method--email)');
    }
    if($r != '<DispositionControl><DispositionLevel>BOTH</DispositionLevel><DispositionMethod>EMAIL</DispositionMethod></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (disposition method--email)');
    }
    $efax->set_disposition_method('None');
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (disposition method--none)');
    }
    if($r != '<DispositionControl><DispositionLevel>BOTH</DispositionLevel><DispositionMethod>NONE</DispositionMethod></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (disposition method--none)');
    }

    // test emails
    $efax = new eFax;
    $efax->set_disposition_method('Email');
    $efax->add_disposition_email('n1', 'e1');
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (disposition emails--#1)');
    }
    if($r != '<DispositionControl><DispositionLevel>BOTH</DispositionLevel><DispositionMethod>EMAIL</DispositionMethod><DispositionEmails><DispositionEmail><DispositionRecipient>n1</DispositionRecipient><DispositionAddress>e1</DispositionAddress></DispositionEmail></DispositionEmails></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (disposition emails--#1)');
    }

    $efax->set_disposition_method('POST');
    $efax->add_disposition_email('n2', 'e2');
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (disposition emails--#2, with POST)');
    }
    if($r != '<DispositionControl><DispositionLevel>BOTH</DispositionLevel><DispositionMethod>POST</DispositionMethod></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (disposition emails--#2, with POST)');
    }

    $efax->set_disposition_method(null);
    $efax->add_disposition_email(null, 'e3');
    $r = $efax->disposition_control_tags();
    if(!is_string($r)) {
        failed('disposition_control_tags() failed with valid data (disposition emails--#3)');
    }
    if($r != '<DispositionControl><DispositionLevel>BOTH</DispositionLevel><DispositionEmails>'
                . '<DispositionEmail><DispositionRecipient>n1</DispositionRecipient><DispositionAddress>e1</DispositionAddress></DispositionEmail>'
                . '<DispositionEmail><DispositionRecipient>n2</DispositionRecipient><DispositionAddress>e2</DispositionAddress></DispositionEmail>'
                . '<DispositionEmail><DispositionAddress>e3</DispositionAddress></DispositionEmail>'
            . '</DispositionEmails></DispositionControl>') {
        failed('disposition_control_tags() did not return the expected XML code (disposition emails--#3)');
    }
}
test_disposition_control();



// Test the recipients tags
function test_recipients()
{
    working_on("Testing recipients output.");

    // Check an empty setup
    $efax = new eFax;
    $r = $efax->recipients_tags();
    if($r !== false) {
        failed('recipients_tags() succeeded without any recipients defined');
    }

    // Check a setup with recipients
    $efax->add_recipient('efax-name', 'efax-company', 'efax-number');
    $r = $efax->recipients_tags();
    if(!is_string($r)) {
        failed('recipients_tags() failed with recipients (1)');
    }
    if($r != '<Recipients>'
                . '<Recipient><RecipientName>efax-name</RecipientName><RecipientCompany>efax-company</RecipientCompany><RecipientFax>efaxnumber</RecipientFax></Recipient>'
            . '</Recipients>') {
        failed('recipients_tags() did not return the expected XML code (one recipient)');
    }
    // 2nd recipient, no name
    $efax->add_recipient(null, 'efax-c2', 'efax-n2');
    $r = $efax->recipients_tags();
    if(!is_string($r)) {
        failed('recipients_tags() failed with recipients (2)');
    }
    if($r != '<Recipients>'
                . '<Recipient><RecipientName>efax-name</RecipientName><RecipientCompany>efax-company</RecipientCompany><RecipientFax>efaxnumber</RecipientFax></Recipient>'
                . '<Recipient><RecipientCompany>efax-c2</RecipientCompany><RecipientFax>efaxn2</RecipientFax></Recipient>'
            . '</Recipients>') {
        failed('recipients_tags() did not return the expected XML code (2 recipients)');
    }
    // 3rd recipient, no company
    $efax->add_recipient('efax-name3', null, 'efax-n3');
    $r = $efax->recipients_tags();
    if(!is_string($r)) {
        failed('recipients_tags() failed with recipients (3)');
    }
    if($r != '<Recipients>'
                . '<Recipient><RecipientName>efax-name</RecipientName><RecipientCompany>efax-company</RecipientCompany><RecipientFax>efaxnumber</RecipientFax></Recipient>'
                . '<Recipient><RecipientCompany>efax-c2</RecipientCompany><RecipientFax>efaxn2</RecipientFax></Recipient>'
                . '<Recipient><RecipientName>efax-name3</RecipientName><RecipientFax>efaxn3</RecipientFax></Recipient>'
            . '</Recipients>') {
        failed('recipients_tags() did not return the expected XML code (3 recipients)');
    }
    // 4th recipient, no name nor company
    $efax->add_recipient(null, null, 'efax-n4');
    $r = $efax->recipients_tags();
    if(!is_string($r)) {
        failed('recipients_tags() failed with recipients (4)');
    }
    if($r != '<Recipients>'
                . '<Recipient><RecipientName>efax-name</RecipientName><RecipientCompany>efax-company</RecipientCompany><RecipientFax>efaxnumber</RecipientFax></Recipient>'
                . '<Recipient><RecipientCompany>efax-c2</RecipientCompany><RecipientFax>efaxn2</RecipientFax></Recipient>'
                . '<Recipient><RecipientName>efax-name3</RecipientName><RecipientFax>efaxn3</RecipientFax></Recipient>'
                . '<Recipient><RecipientFax>efaxn4</RecipientFax></Recipient>'
            . '</Recipients>') {
        failed('recipients_tags() did not return the expected XML code (4 recipients)');
    }
}
test_recipients();




// Test the files tags
function test_files()
{
    working_on("Testing files output.");

    // Check an empty setup
    $efax = new eFax;
    $r = $efax->files_tags();
    if($r !== false) {
        failed('files_tags() succeeded without any files defined');
    }

    // Check a setup with files
    $text1 = 'This is my text data to be transmitted in this message.';
    $efax->add_file('txt', $text1);
    $r = $efax->files_tags();
    if(!is_string($r)) {
        failed('files_tags() failed with files (1)');
    }
    if($r != '<Files>'
                . '<File><FileContents>' . base64_encode($text1) . '</FileContents><FileType>txt</FileType></File>'
            . '</Files>') {
        failed('files_tags() did not return the expected XML code (one file)');
    }
    // 2nd file, to make sure the loop works
    $text2 = 'this is an RTF file with tags and such, see?';
    $efax->add_file('RTF', $text2);
    $r = $efax->files_tags();
    if(!is_string($r)) {
        failed('files_tags() failed with files (2)');
    }
    if($r != '<Files>'
                . '<File><FileContents>' . base64_encode($text1) . '</FileContents><FileType>txt</FileType></File>'
                . '<File><FileContents>' . base64_encode($text2) . '</FileContents><FileType>rtf</FileType></File>'
            . '</Files>') {
        failed('files_tags() did not return the expected XML code (2 files)');
    }
}
test_files();



// Test the complete transmission tag
function test_transmission_tags()
{
    working_on("Testing transmission tag output.");

    // TODO: we would need to check that all the
    // sub-functions that return false are caught
    // properly!

    // test with nothing but the defaults
    $text1 = 'This is my text data to be transmitted in this message.';
    $efax = new eFax;
    $efax->add_recipient(null, null, 'efax-recipient');
    $efax->add_file('txt', $text1);
    $r = $efax->transmission_tags();
    if(!is_string($r)) {
        failed('transmission_tags() failed with valid data (nothing)');
    }
    if($r != '<TransmissionControl><Resolution>STANDARD</Resolution></TransmissionControl>'
            . '<DispositionControl><DispositionLevel>BOTH</DispositionLevel></DispositionControl>'
            . '<Recipients><Recipient><RecipientFax>efaxrecipient</RecipientFax></Recipient></Recipients>'
            . '<Files><File><FileContents>' . base64_encode($text1) . '</FileContents><FileType>txt</FileType></File></Files>') {
        failed('transmission_tags() did not return the expected XML code (nothing)');
    }

}
test_transmission_tags();




// Test the complete message tags (i.e. full XML)
function test_message_tags()
{
    working_on("Testing message tags output.");

    // TODO: we would need to check that all the
    // sub-functions that return false are caught
    // properly!

    // test with nothing but the defaults
    $text1 = 'This is my text data to be transmitted in this message.';
    $efax = new eFax;
    $efax->add_recipient(null, null, 'efax-recipient');
    $efax->add_file('txt', $text1);
    $efax->set_user_password('efax-passwd');
    $efax->set_user_name('efax-test');
    $r = $efax->message();
    if(!is_string($r)) {
        failed('message() failed with valid data (nothing)');
    }
    if($r != '<?xml version="1.0"?>'
            . '<OutboundRequest>'
                . '<AccessControl>'
                    . '<UserName>efax-test</UserName><Password>efax-passwd</Password>'
                . '</AccessControl>'
                . '<Transmission>'
                    . '<TransmissionControl><Resolution>STANDARD</Resolution></TransmissionControl>'
                    . '<DispositionControl><DispositionLevel>BOTH</DispositionLevel></DispositionControl>'
                    . '<Recipients><Recipient><RecipientFax>efaxrecipient</RecipientFax></Recipient></Recipients>'
                    . '<Files><File><FileContents>' . base64_encode($text1) . '</FileContents><FileType>txt</FileType></File></Files>'
                . '</Transmission>'
            . '</OutboundRequest>') {
        failed('message() did not return the expected XML code (nothing)');
    }
}
test_message_tags();




///////////////////////////
// Now we need to have the HTTP Request object available

// Test the complete transmission tag
function test_send()
{
    working_on("Testing the send() itself.");

    // TODO: we need to check all sorts of possible
    //     error cases!

    // test with nothing but the defaults
    $text1 = 'This is my text data to be transmitted in this message.';
    $efax = new eFax;
    $efax->set_fax_id("efax #123");
    $efax->set_outbound_url("http://efax.m2osw.com/efax-post.php");
    $efax->set_account_id("9169881450");
    $efax->add_recipient(null, null, 'efax-recipient');
    $efax->add_file('txt', $text1);
    $efax->set_user_password('efax-passwd');
    $efax->set_user_name('efax-test');
    $msg = $efax->message();
    $r = $efax->send($msg);
    if($r !== true) {
        failed('message() failed with valid data (nothing)');
    }
    if($r != '<?xml version="1.0"?>'
            . '<OutboundRequest>'
                . '<AccessControl>'
                    . '<UserName>efax-test</UserName><Password>efax-passwd</Password>'
                . '</AccessControl>'
                . '<Transmission>'
                    . '<TransmissionControl><Resolution>STANDARD</Resolution></TransmissionControl>'
                    . '<DispositionControl><DispositionLevel>BOTH</DispositionLevel></DispositionControl>'
                    . '<Recipients><Recipient><RecipientFax>efaxrecipient</RecipientFax></Recipient></Recipients>'
                    . '<Files><File><FileContents>' . base64_encode($text1) . '</FileContents><FileType>txt</FileType></File></Files>'
                . '</Transmission>'
            . '</OutboundRequest>') {
        failed('message() did not return the expected XML code (nothing)');
    }
}
test_send();







//////////////////////////////////////////////
//////////////////////////////////////////////
// TESTING DISPOSITION FUNCTION
//////////////////////////////////////////////
//////////////////////////////////////////////
echo "\n--- DISPOSITION FUNCTION ---\n\n";

// Test the complete transmission tag
function test_disposition()
{
    working_on("Testing disposition.");

    // test with nothing
    $efax = new eFax;
    $r = $efax->parse_disposition('<ignored>');
    if($r !== false) {
        failed('transmission_tags() worked without name and password');
    }

    // test with a name only
    $efax = new eFax;
    $efax->set_user_name('efax-test');
    $r = $efax->parse_disposition('<ignored>');
    if($r !== false) {
        failed('transmission_tags() worked with only a name');
    }

    // test with a password only
    $efax = new eFax;
    $efax->set_user_password('efax-passwd');
    $r = $efax->parse_disposition('<ignored>');
    if($r !== false) {
        failed('transmission_tags() worked with only a password');
    }

    // test with an invalid tag
    $efax = new eFax;
    $efax->set_user_name('efax-test');
    $efax->set_user_password('efax-passwd');
    $r = $efax->parse_disposition('<?xml version="1.0"?><InvalidTag/>');
    if($r !== false) {
        failed('transmission_tags() worked without the expected tag.');
    }

    // test with a valid tag, but invalid name
    $efax = new eFax;
    $efax->set_user_name('efax-test');
    $efax->set_user_password('efax-passwd');
    $r = $efax->parse_disposition('<?xml version="1.0"?><OutboundDisposition UserName="wrong-name"/>');
    if($r !== false) {
        failed('transmission_tags() worked with the wrong user name.');
    }
    if($efax->get_error_level() != "System") {
        failed('transmission_tags() failed with the wrong error level (login name).');
    }
    if($efax->get_error_description() != "Invalid login name.") {
        failed('transmission_tags() failed with the wrong error description (login name).');
    }

    // test with a valid tag, but invalid password
    $efax = new eFax;
    $efax->set_user_name('efax-test');
    $efax->set_user_password('efax-passwd');
    $r = $efax->parse_disposition('<?xml version="1.0"?><OutboundDisposition UserName="efax-test" Password="wrong-passwd"/>');
    if($r !== false) {
        failed('transmission_tags() worked with the wrong password.');
    }
    if($efax->get_error_level() != "System") {
        failed('transmission_tags() failed with the wrong error level (password).');
    }
    if($efax->get_error_description() != "Invalid password.") {
        failed('transmission_tags() failed with the wrong error description (password).');
    }

    // test with a valid tag including all the possible parameters
    $efax = new eFax;
    $efax->set_user_name('efax-test');
    $efax->set_user_password('efax-passwd');
    $r = $efax->parse_disposition('<?xml version="1.0"?>'
        . '<OutboundDisposition UserName="efax-test" Password="efax-passwd"'
            . ' TransmissionID="9867" DOCID="1239876"'
            . ' FaxNumber="9169881450" CompletionDate="2007-11-30 12:34:09"'
            . ' FaxStatus="100" RecipientCSID="6545372"'
            . ' Duration="0.8" PagesSent="3" NumberOfRetries="5"'
        . '/>');
    if($r !== true) {
        failed('transmission_tags() failed with good user name and passowrd.');
    }
    if($efax->get_result_fax_id() != "9867") {
        failed('transmission_tags() return the wrong transmission identifier.');
    }
    if($efax->get_result_docid() != "1239876") {
        failed('transmission_tags() return the wrong DOCID.');
    }
    if($efax->get_result_fax_number() != "9169881450") {
        failed('transmission_tags() return the wrong fax number.');
    }
    $date = gmmktime(12, 34, 9, 11, 30, 2007) + 8 * 60 * 60;
    if($efax->get_result_completion_date() != $date) {
        failed('transmission_tags() return the wrong completion date.');
    }
    if($efax->get_result_fax_status() != "100") {
        failed('transmission_tags() return the wrong fax status.');
    }
    if($efax->get_result_error_message() != "out of memory to process FS file") {
        failed('transmission_tags() return the wrong fax status message.');
    }
    if($efax->get_result_error_class() != "Z") {
        failed('transmission_tags() return the wrong fax status class.');
    }
    if($efax->get_result_duration() != 48) {
        failed('transmission_tags() return the wrong duration.');
    }
    if($efax->get_result_pages() != 3) {
        failed('transmission_tags() return the wrong number of pages.');
    }
    if($efax->get_result_retries() != 5) {
        failed('transmission_tags() return the wrong number of retries.');
    }

}
test_disposition();







echo "\n";
echo "+++\n";
echo "+++ All tests passed!\n";
echo "+++\n";
echo "\n";
exit(0);
