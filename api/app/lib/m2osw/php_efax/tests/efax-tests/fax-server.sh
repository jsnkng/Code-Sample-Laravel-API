#!/bin/sh
# Unit tests for PHP eFax -- testing the eFax::send() function with http_request.php
# This is a server side script used to check an invalid scheme name in the reply.

# fake "FTP" header using an HTTP like scheme
echo "HTTP/1.1 503 Service Unavailable"
#echo "Date: `date`"
echo "Content-Type: text/xml"
echo ""
echo "<?xml version="1.0"?>"
echo "<OutboundResponse><Transmission><TransmissionControl><TransmissionID>Ignore</TransmissionID>"
echo "<DOCID>28881</DOCID></TransmissionControl><Response><StatusCode>1</StatusCode>"
echo "<StatusDescription>Success</StatusDescription></Response></Transmission></OutboundResponse>"
