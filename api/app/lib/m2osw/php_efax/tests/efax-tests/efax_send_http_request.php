<?php
// Unit tests for PHP eFax -- testing the eFax::send() function with http_request.php

require_once('simpletest/autorun.php');
require_once('php/efax.php');

class Test_eFaxReceive extends UnitTestCase
{
    // this function creates a valid fax by default, tweak it to generate an
    // invalid request before you call the send() function
    function get_efax($user_name, $password)
    {
        $efax = new eFax(false);
        $efax->set_account_id("9169881450");
        if($user_name)
        {
            $efax->set_user_name($user_name);
        }
        if($password)
        {
            $efax->set_user_password($password);
        }
        $efax->add_file("txt", "This is the content of my text file");
        $efax->add_recipient("Alexis Wilke", "Made to Order Software", "9169881450");
        // We do not run our tests with the real thing because it would send real faxes!
        //$efax->set_outbound_url("https://secure.efaxdeveloper.com/EFax_WebFax.serv");
        // If you want to test with your server or change fax-server.php behavior, you
        // must copy that file to your own secure server
        $efax->set_outbound_url("https://private.m2osw.com/fax-server.php");
        $efax->set_fax_id("Success"); // fax-server replies with a success response

        // this increases the likelihood that our fields go through as expected
        $efax->set_disposition_level(eFax::RESPOND_ERROR | eFax::RESPOND_SUCCESS);
        $efax->set_disposition_method("POST");
        $efax->set_duplicate_id(false);
        $efax->set_fax_header("   @DATE @TIME Made to Order Software Corporation");
        $efax->set_priority("HIGH");
        $efax->set_resolution("STANDARD");
        $efax->set_self_busy(true);

        return $efax;
    }

    function test_Info()
    {
        echo "  Send Tests with the http_request class\n";
    }

    function test_Send()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that a valid request works\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $result = $efax->send($efax->message());
        $this->assertTrue($result, "the valid request returned an error");
    }

    function test_Redirect()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that a valid request can redirect us\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("Redirect");
        $result = $efax->send($efax->message());
        $this->assertTrue($result, "the valid request returned an error");
    }

    function test_Redirect302()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that a valid request can redirect us with 302\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("Redirect302");
        $result = $efax->send($efax->message());
        $this->assertTrue($result, "the valid request returned an error");
    }

    function test_RedirectLoop()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that too many redirects fail\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("RedirectLoop");
        $this->expectException(new http_request_exception("Too many or forbidden redirects"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "exception not thrown");
    }

    function test_RedirectToHTTP()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that redirects to http://... URLs fail\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("RedirectToHTTP");
        $this->expectException(new http_request_exception("Unsupported Internet Scheme; we only support HTTPS, and SSL for the scheme"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "exception not thrown");
    }

    // Apache does not send 301/302 replies without a Location so
    // we cannot test those for now
    //function test_BadRedirect()
    //{
    //    // NOTE: the test requires a real server to reply to us, although it
    //    //       does not need to be an e-Fax server.
    //    echo "    Test that a redirect without a Location field fails\n";
    //    $efax = $this->get_efax('m2osw', 'top-secret', '');
    //    $efax->set_fax_id("BadRedirect");
    //    $this->expectException(new http_request_exception("Unsupported Internet Scheme; we only support HTTPS, and SSL for the scheme"));
    //    $result = $efax->send($efax->message());
    //    $this->assertTrue(false, "exception not thrown");
    //}

    function test_IDMismatch()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that the Fax ID must match\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("ChangeId");
        $this->expectException(new eFaxException("response TransmissionID (invalid-id) does not match the identifier sent (ChangeId)"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "exception not thrown");
    }

    function test_MissingID()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that the Fax ID must be returned in reply if one is sent\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("MissingId");
        $this->expectException(new eFaxException("response does not include a TransmissionID tag"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "exception not thrown");
    }

    function test_MissingDOCID()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that the Documentation ID must be present\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("MissingDocId");
        $this->expectException(new eFaxException("response does not include a DOCID tag"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "exception not thrown");
    }

    function test_EmptyResponse()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that the reply cannot be empty\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("Empty");
        $this->expectException(new eFaxException("parse_response() cannot be called with an empty response."));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "exception not thrown");
    }

    function test_NoXMLResponse()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that the reply must be valid XML\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("NoXML");
        $this->expectException(new eFaxException("response does not include an <?xml tag"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "exception not thrown");
    }

    function test_NoStatusCode()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that a reply must have a status code\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("NoStatusCode");
        $this->expectException(new eFaxException("response does not include a StatusCode tag"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "exception not thrown");
    }

    function test_BadScheme()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test an unsupported scheme (HTTP)\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_outbound_url("http://www.m2osw.com/fax-server.php");
        $this->expectException(new http_request_exception("Unsupported Internet Scheme; we only support HTTPS, and SSL for the scheme"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "invalid scheme not detected");
    }

    // With Apache you cannot generate an invalid scheme in the answer
    // and creating a mini-server with SSL is no small task!
    //function test_BadSchemeInAnswer()
    //{
    //    // NOTE: the test requires a real server to reply to us, although it
    //    //       does not need to be an e-Fax server.
    //    echo "    Test an unsupported scheme in answer (FTP)\n";
    //    $efax = $this->get_efax('m2osw', 'top-secret', '');
    //    $efax->set_outbound_url("https://private.m2osw.com/cgi-bin/fax-server.cgi");
    //    $efax->set_fax_id("FtpAnswer");
    //    $this->expectException(new http_request_exception("Invalid scheme in response"));
    //    $result = $efax->send($efax->message());
    //    $this->assertTrue(false, "invalid scheme not detected");
    //}

    function test_BadResponseCode()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test an unexpected response code (HTTP/1.1 <bad code> <error message>)\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("BadResponseCode");
        $this->expectException(new http_request_exception("Unsupported code in response (500)"));
        $result = $efax->send($efax->message());
        $this->assertTrue(false, "invalid scheme not detected");
    }

    function test_Timeout()
    {
        // NOTE: the test requires a real server to reply to us, although it
        //       does not need to be an e-Fax server.
        echo "    Test that we get a timeout when server doesn't respond soon enough (this test takes 1 to 2 minutes TIMES FIVE!)\n";
        $efax = $this->get_efax('m2osw', 'top-secret', '');
        $efax->set_fax_id("Pause");
        $result = $efax->send($efax->message());
        $this->assertTrue(!$result, "test did not timeout as expected");
    }
};


// vim: ts=4 sw=4
