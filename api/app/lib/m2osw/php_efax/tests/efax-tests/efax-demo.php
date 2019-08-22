<?php
// test used against a read (demo) eFaxDeveloper account

require("efax.php");

header("Content-Type: text/html");

echo "<html><head><title>eFax Demo</title></head><body>";

//try
//{
    $efax = new eFax(false);

echo "<p>Setting up basic data</p>";
    $efax->set_account_id("8667627409");
    $efax->set_user_name("sureshk");
    $efax->set_user_password("sureshk");
    $efax->add_recipient("Alexis Wilke", "Made to Order Software", "9162206482");

echo "<p>Setting up text file</p>";
    $efax->add_file("txt", "This is the content of my text file");
    $string = file_get_contents("sample.pdf");
    $efax->add_file("pdf", $string);
echo "<p>Setting up PDF file: size is ", strlen($string), "</p>";

    // Not necessary, that's the default
    //$efax->set_outbound_url("https://secure.efaxdeveloper.com/EFax_WebFax.serv");

    // rand() needs to change each time for set_duplicate_id(false) to work
    //$efax->set_fax_id("Fax #" . rand());
    $efax->set_fax_id("Fax #12345");

echo "<p>Setup disposition</p>";
    $efax->add_disposition_email("Alexis Wilke", "alexis@example.com");
    //$efax->set_disposition_url("https://secure.example.com/fax-disposition.php");

    $efax->set_disposition_level(eFax::RESPOND_ERROR | eFax::RESPOND_SUCCESS);
    $efax->set_disposition_method("EMAIL");

echo "<p>Setup additional parameters</p>";
    $efax->set_duplicate_id(true);
    $efax->set_fax_header("   @DATE @TIME Made to Order Software Corporation");
    $efax->set_priority("NORMAL");
    $efax->set_resolution("STANDARD");
    $efax->set_self_busy(true);

    try
    {
        echo "<p>Send message: &quot;", htmlentities($efax->message()), "&quot;</p>";
        $result = $efax->send($efax->message());
        echo "<p>The send function returned.</p>";

        if($result)
        {
            echo "<p>It worked<p>";
        }
        else
        {
            echo "<p>It failed (send() function returned false); error message: \"", $efax->get_error_description(), "\"</p>";
        }
    }
    catch(eFaxException $e)
    {
        echo "<p>eFaxException! [{$e->getMessage()}]</p>";
    }

//}
//catch(eFaxException $e)
//{
//}


