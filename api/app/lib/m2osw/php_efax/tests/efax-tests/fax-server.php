<?php
// Unit tests for PHP eFax -- testing the eFax::send() function with http_request.php
// This is the server side script you want to copy on your HTTPS capable server to
// test the eFax class with efax_test_send_http_request.php

$data = $_POST['xml'];

// defaults is "Failure" code
$status_code = 2;

// This is really only a test, in a correct version you'd need to check
// the validity of the data and other such things.
$xml = new DOMDocument;
$xml->loadXML(urldecode($data));
$node_list = $xml->getElementsByTagName("TransmissionID");
if($node_list->length == 1)
{
    $id = $node_list->item(0)->nodeValue;
    switch($id)
    {
    case 'Success':
        $status_code = 1;
        break;

    case 'ChangeId':
        $status_code = 1; // must succeed to catch an invalid identifier
        $id = 'invalid-id';
        break;

    case 'MissingId':
        echo '1ab\r\n<','?xml version="1.0"?','>',
            '<OutboundResponse>',
            '<Transmission>',
            '<TransmissionControl>',
            //'<TransmissionID>', $id, '</TransmissionID>', -- missing identifier!
            '<DOCID>28881</DOCID>',
            '</TransmissionControl>',
            '<Response>',
            '<StatusCode>1</StatusCode>',
            '<StatusDescription>Success</StatusDescription>',
            '</Response>',
            '</Transmission>',
            '</OutboundResponse>';
        exit(0);

    case 'MissingDocId':
        echo '1ab\r\n<','?xml version="1.0"?','>',
            '<OutboundResponse>',
            '<Transmission>',
            '<TransmissionControl>',
            '<TransmissionID>', $id, '</TransmissionID>',
            //'<DOCID>28881</DOCID>', -- missing DOCID
            '</TransmissionControl>',
            '<Response>',
            '<StatusCode>1</StatusCode>',
            '<StatusDescription>Success</StatusDescription>',
            '</Response>',
            '</Transmission>',
            '</OutboundResponse>';
        exit(0);

    case 'Empty':
        // return nothing
        exit(0);

    case 'NoXML':
        echo 'An Invalid Response';
        exit(0);

    case 'NoStatusCode':
        echo '1ab\r\n<','?xml version="1.0"?','>',
            '<OutboundResponse>',
            '<Transmission>',
            '<TransmissionControl>',
            '<TransmissionID>', $id, '</TransmissionID>',
            '<DOCID>28881</DOCID>',
            '</TransmissionControl>',
            '<Response>',
            // '<StatusCode>', $status_code, '</StatusCode>', -- missing!
            '<StatusDescription>Success</StatusDescription>',
            '</Response>',
            '</Transmission>',
            '</OutboundResponse>';
        exit(0);

    case 'Pause':
        // pause for 2 minutes! the timeout is expected to be 1 min.
        // so the client should always timeout in this case.
        sleep(120);
        break;

    case 'FtpAnser':
        // fake non HTTP header -- handled by the fax-server.sh instead
        die('fax-server.php called with FtpAnswer when you should be calling fax-server.sh');

    case 'BadResponseCode':
        header('HTTP/1.1 500 Unexpected Response Code');
        break;

    case 'Redirect':
        if(!isset($_GET['skip']))
        {
            header('HTTP/1.1 301 Permanently Moved');
            header('Location: https://private.m2osw.com/fax-server.php?skip');
            exit(0);
        }
        // if the redirect worked the we return success
        $status_code = 1;
        break;

    case 'Redirect302':
        if(!isset($_GET['skip']))
        {
            header('HTTP/1.1 302 Temporarily Moved');
            header('Location: https://private.m2osw.com/fax-server.php?skip');
            exit(0);
        }
        // if the redirect worked the we return success
        $status_code = 1;
        break;

    case 'RedirectLoop':
        header('HTTP/1.1 301 Permanently Moved');
        header('Location: https://private.m2osw.com/fax-server.php');
        exit(0);

    case 'RedirectToHTTP':
        header('HTTP/1.1 302 Temporarily Moved');
        header('Location: http://private.m2osw.com/fax-server.php');
        exit(0);

    case 'BadRedirect':
        header('HTTP/1.1 302 Temporarily Moved');
        //header('Location: http://private.m2osw.com/fax-server.php');
        exit(0);

    }
}
else
{
    $id = rand();
}

// for some reasons eFax returns some spurious data before and after the XML
// this simulator does the same thing
echo "1cc\r\n<",'?xml version="1.0"?','>',
    '<OutboundResponse>',
    '<Transmission>',
    '<TransmissionControl>',
    '<TransmissionID>', $id, '</TransmissionID>',
    '<DOCID>28881</DOCID>',
    '</TransmissionControl>',
    '<Response>',
    '<StatusCode>', $status_code, '</StatusCode>',
    '<StatusDescription>' . ($status_code == 1 ? 'Success' : 'Failure') . '</StatusDescription>',
    ($status_code == 1 ? '' : '<ErrorLevel>User</ErrorLevel>'),
    ($status_code == 1 ? '' : '<ErrorMessage>An error occured!</ErrorMessage>'),
    '</Response>',
    '</Transmission>',
    '</OutboundResponse>',
    "\n\r\n0\r\n\r\n\n";
exit(0);
echo "1cc\r\n<",'?xml version="1.0"?','>',
    '<OutboundResponse>',
    '<Transmission>',
    '<TransmissionControl>',
    '<TransmissionID>', $id, '</TransmissionID>',
    '<DOCID>28881</DOCID>',
    '</TransmissionControl>',
    '<Response>',
    '<StatusCode>', $status_code, '</StatusCode>',
    '<StatusDescription>' . ($status_code == 1 ? 'Success' : 'Failure') . '</StatusDescription>',
    ($status_code == 1 ? '' : '<ErrorLevel>User</ErrorLevel>'),
    ($status_code == 1 ? '' : '<ErrorMessage>An error occured!</ErrorMessage>'),
    '</Response>',
    '</Transmission>',
    '</OutboundResponse>',
    "\n\r\n0\r\n\r\n\n";
