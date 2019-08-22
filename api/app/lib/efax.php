<?php

/** \file efax.php
 *
 * \brief eFax class implementation
 *
 *    Implementation of the efax class used to create faxes that
 *    are compatible with eFax Developer.
 *
 * \section copyright Copyright (c) 2007-2013 Made to Order Software Corp.
 *
 *    All Rights Reserved.
 *
 *    This software and its associated documentation contains
 *    proprietary, confidential and trade secret information
 *    of Made to Order Software Corp. and except as provided by
 *    written agreement with Made to Order Software Corp.
 *
 *    a) no part may be disclosed, distributed, reproduced,
 *       transmitted, transcribed, stored in a retrieval system,
 *       adapted or translated in any form or by any means
 *       electronic, mechanical, magnetic, optical, chemical,
 *       manual or otherwise,
 *
 *    and
 *
 *    b) the recipient is not entitled to discover through reverse
 *       engineering or reverse compiling or other such techniques
 *       or processes the trade secrets contained therein or in the
 *       documentation.
 */

/** \mainpage
 *
 * \section summary Summary
 *
 * \par
 * \ref intro
 *
 * \par
 * \ref product
 *
 * \par
 * \ref require
 *
 * \par
 * \ref send
 *
 * \par
 * \ref disposition
 *
 * \par
 * \ref inbound
 *
 * \par
 * \ref security
 *
 * \par
 * \ref failures
 *
 * \par
 * \ref errors
 *
 * \par
 * \ref changes
 *
 * \par
 * \ref copyright
 *
 * \section intro Introduction
 *
 * Welcome to the PHP eFax documentation.
 *
 * What is eFax? eFax is a company that let's you send and
 * receive faxes via the Internet (see http://www.efax.com).
 * The faxes can be sent to a standard paper fax or to a
 * virtual fax machine (i.e. to your eFax account.)
 * There are two interfaces:
 *
 * (1) an email interface used to send and receive documents from
 * a set of standard email addresses.
 *
 * (2) a secure HTTPS API called eFax Developer or business eFax.
 * It can be used in a totally automated way to send and receive
 * faxes via a web interface using the standard HTTPS protocol
 * for communication.
 *
 * At Made to Order Software, we use eFax for Order Made! Our
 * easy to use ordering system for restaurants.
 * https://secure.m2osw.com/resto/system/list.php
 *
 * To use the eFax class, create an eFax object and either set
 * the parameter to send a fax, or parse the disposition message.
 * That's it! The rest of the work is handled internally by the
 * class.
 *
 * \warning
 * \b IMPORTANT \b NOTE: \n
 * The secure HTTPS API requires YOU to have a secure website with
 * a valid certificate to accept eFax disposition and inbound
 * fax messages. YOUR certificate must be validated by an entity such
 * as godaddy.com or verysign.com (there are hundreds of companies
 * offering certificates now a day.) Without a valid certificate,
 * dispositions from eFax Developer will NOT work. This is beyond
 * what we can do for you with the PHP eFax library.
 *
 * \par
 * Of course, if you do not want to use dispositions or receive inbound
 * messages the certificate is not required, but you will not benefit
 * from as many features as you could otherwise make use of.
 *
 * \par
 * Back to \ref summary
 *
 * \section product How do I get my own copy of this product?
 *
 * The source code for this class is available for sale on our
 * http://www.m2osw.com website.
 *
 * Click on <a href="http://www.m2osw.com/products" target="_blank">Products</a>
 * at the top, search for PHP eFax, and
 * <a href="https://secure.m2osw.com/cart/add/p37_q1-iphp_efax?destination=cart" target="_blank">add
 * it to your cart</a>. Then simply go through our checkout process.
 * A few seconds after we receive your payment, you will gain
 * access to the download area where you will be able to download
 * the PHP eFax package.
 *
 * If you already purchased a license then you have an account with us and you
 * can simply go back to <a href="https://secure.m2osw.com/user">your account</a>
 * and download a new copy of the library. If you forgot your password, then
 * use the <a href="https://secure.m2osw.com/user/password">request new
 * password</a> form to be sent a link to your email address. That link will
 * let you enter a new password for your account. The license is permanent so
 * you do not have to re-purchase a new license to re-download a copy of
 * the library.
 *
 * \par
 * Back to \ref summary
 *
 * \section require Requirements
 *
 * Up to version 1.5, the class requires the availability of HttpRequest.
 * Below are information on how to install HttpRequest on your server.
 *
 * Since version 1.6, HttpRequest is optional. You may instead use the
 * accompanying http_request class. To do so, create the eFax object
 * passing false as the optional parameter.
 *
 * \code
 * $efax = new eFax(false);
 * ...
 * $efax->send(); // at this point the http_request class is loaded
 * \endcode
 *
 * \note
 * The default is to make use o the PEAR HttpRequest class as before.
 * This is so our existing customers do not get a big surprise in
 * the event they upgrade (they do not have to since there is no
 * real code changes that require existing servers that work to make
 * use of the new version.)
 *
 * \subsection http_request_mswindows Installing the HttpRequest library on Microsoft Windows
 *
 * \warning
 * For new installation, we recommend that you use <code>new eFax(false)</code>
 * when creating an eFax object instead of using the HttpRequest class from PEAR.
 *
 * For a \b Microsoft \b Windows system, you can find pre-compiled
 * versions of the HttpRequest module, called php_http.dll at the
 * following URL:
 *
 * <a href="http://pecl4win.php.net/list.php" target="_blank">http://pecl4win.php.net/list.php</a>
 *
 * Please, make sure that the version is correct for your system.
 * The home page for the PECL code for Windows is at
 * <a href="http://pecl4win.php.net/index.php" target="_blank">PECL4WIN</a>.
 *
 * A good tutorial for IIS and even Unix users on how to install
 * HttpRequest can be found on
 * <a href="http://www.iis-aid.com/articles/how_to_guides/enabling_php_pecl_extensions_for_windows"
 * target="_blank">IIS Aid</a>.
 *
 * \subsection http_request_debian Installing the HttpRequest library on Debian systems
 *
 * \warning
 * For new installation, we recommend that you use <code>new eFax(false)</code>
 * when creating an eFax object instead of using the HttpRequest class from PEAR.
 *
 * If it is not already installed on your \b Debian system (this also
 * works under \b Ubuntu) use the following commands to retrieve and
 * recompile a version on your system:
 *
 * \code
 *    sudo apt-get install php-pear
 *    sudo apt-get install php5-dev
 *    sudo pecl install pecl_http
 * \endcode
 *
 * Then edit your /etc/php/apache2/php.ini file and add the following line
 * at the end:
 *
 * \code
 *    extension=http.so
 * \endcode
 *
 * \subsection http_request_redhat Installing the HttpRequest library on Fedora systems
 *
 * \warning
 * For new installation, we recommend that you use <code>new eFax(false)</code>
 * when creating an eFax object instead of using the HttpRequest class from PEAR.
 *
 * For \b Fedora, \b RedHat and other RPM based systems, use \c yum instead
 * of \c apt-get. The pecl command is the same.
 *
 * \code
 *    sudo yum install php-xml
 *    sudo yum install php-devel
 *    sudo pecl install pecl_http
 *    sudo yum install php-pear-HTTP-Request
 * \endcode
 *
 * The php-xml is to get the DOMDocument support.
 *
 * Once the HTTP Request module installed, add the following at the end
 * of your /etc/php.ini file:
 *
 * \code
 *    extension=http.so
 * \endcode
 *
 * \par
 * Back to \ref summary
 *
 * \section send Sending a Fax
 *
 * The following code shows how you create an eFax object, initializes
 * it to send a fax and finally \c eFax::send() the fax.
 *
 * The parameters are only examples. You will need to set the parameters
 * to what you need for your specific needs.
 *
 * \code
 *    $efax = new eFax(false); // use 'true' to use the PEAR HttpRequest class (not recommended)
 *
 *    // mandatory parameters
 *    $efax->set_account_id("9169881450");
 *    $efax->set_user_name("made_to_order_software");
 *    $efax->set_user_password("TopSecret");
 *    $efax->add_file("txt", "This is the content of my text file");
 *    $efax->add_recipient("Alexis Wilke", "Made to Order Software", "9169881450");
 *
 *    // Though this is mandatory, the constructor sets the default that
 *    // you should not need to modify.
 *    $efax->set_outbound_url("https://secure.efaxdeveloper.com/EFax_WebFax.serv");
 *
 *    // mandatory if set_duplicated_id(false);
 *    $efax->set_fax_id("Fax #123456");
 *
 *    // mandatory if set_disposition_method("EMAIL");
 *    $efax->add_disposition_email("Alexis Wilke", "alexis@m2osw.com");
 *
 *    // mandatory if set_disposition_method("POST");
 *    $efax->set_disposition_url("https://secure.m2osw.com/fax-disposition.php");
 *
 *    // optional flags
 *    $efax->set_disposition_language("en");
 *    $efax->set_disposition_level(eFax::RESPOND_ERROR | eFax::RESPOND_SUCCESS);
 *    $efax->set_disposition_method("POST");
 *    $efax->set_duplicate_id(false);
 *    $efax->set_fax_header("   @DATE @TIME Made to Order Software Corporation");
 *    $efax->set_priority("HIGH");
 *    $efax->set_resolution("STANDARD");
 *    $efax->set_self_busy(true);
 *
 *    // ready to send the fax
 *    $result = $efax->send($efax->message());
 *    if($result)
 *    {
 *        ... // handle success
 *    }
 *    else
 *    {
 *        ... // handle failure
 *    }
 * \endcode
 *
 * \sa
 *    eFax::set_account_id()
 *    eFax::add_disposition_email()
 *    eFax::set_disposition_url()
 *    eFax::set_disposition_language()
 *    eFax::set_disposition_level()
 *    eFax::set_disposition_method()
 *    eFax::set_duplicate_id()
 *    eFax::set_fax_header()
 *    eFax::set_fax_id()
 *    eFax::add_file()
 *    eFax::set_outbound_url()
 *    eFax::set_priority()
 *    eFax::add_recipient()
 *    eFax::set_resolution()
 *    eFax::set_self_busy()
 *    eFax::set_user_name()
 *    eFax::set_user_password()
 *    eFax::send()
 *
 * \par
 * Back to \ref summary
 *
 * \section disposition Parsing the fax disposition
 *
 * Whenever you receive the fax disposition message from eFax,
 * call the \c eFax::parse_disposition() function with the XML data
 * included in the message. Then you can use different get
 * functions to retrieve the resulting information.
 *
 * The disposition is sent by eFax once the fax was successfully
 * sent or a failure was discovered. It is sent to your server using
 * the disposition URL you specified when sending the fax to the
 * eFax server. In our example it is set using the eFax::set_disposition_url()
 * function as follow:
 *
 * \code
 *    $efax->set_disposition_url("https://secure.m2osw.com/fax-disposition.php");
 * \endcode
 *
 * The following is an example of fax-disposition.php code:
 *
 * \code
 *    // Get the XML message
 *    $xml = stripslashes($_POST["xml"]);
 *
 *    $efax = new eFax(false);
 *
 *    // the parser checks the validity of the user name and password
 *    $efax->set_user_name("made_to_order_software");
 *    $efax->set_user_password("TopSecret");
 *
 *    // parse the XML message
 *    if($efax->parse_disposition($xml))
 *    {
 *        // get the results and do something with it
 *        // \c eFax::get_result_fax_id() returns the identifier that you sent to
 *        // eFax using the \c eFax::set_fax_id() function; very useful to know
 *        // which fax is being disposed of
 *        $my_var = $efax->get_result_fax_id();
 *        $my_var = $efax->get_result_docid();
 *        $my_var = $efax->get_result_fax_number();
 *        $my_var = $efax->get_result_completion_date();
 *        $my_var = $efax->get_result_fax_status();
 *        $my_var = $efax->get_result_csid();
 *        $my_var = $efax->get_result_duration();
 *        $my_var = $efax->get_result_pages();
 *        $my_var = $efax->get_result_retries();
 *
 *        // now tell eFax that we accepted the disposition
 *        echo "Post Successful\n";
 *    }
 *    else
 *    {
 *        ... // handle error case
 *    }
 * \endcode
 *
 * \sa
 *    eFax::get_result_fax_id()
 *    eFax::get_result_docid()
 *    eFax::get_result_fax_number()
 *    eFax::get_result_completion_date()
 *    eFax::get_result_fax_status()
 *    eFax::get_result_csid()
 *    eFax::get_result_duration()
 *    eFax::get_result_pages()
 *    eFax::get_result_retries()
 *    eFax::parse_disposition()
 *    eFax::set_user_name()
 *    eFax::set_user_password()
 *
 * \par
 * Back to \ref summary
 *
 * \section inbound Receiving an inbound fax request
 *
 * Whenever you receive a fax from a sender, eFax Developer posts
 * a notification to a URL you specify in your eFax Developer account.
 * This HTTP POST message includes an XML file that PHP eFax can
 * parse for you. See the \c eFax::parse_inbound_message() function
 * for additional information.
 *
 * \warning
 * Remember that you MUST have a valid certificate to receive
 * notifications from eFax. Otherwise the connection from eFax
 * fails since they do not accept to connect to a non-secure
 * server.
 *
 * After the call to the parse function you can use different get
 * functions to retrieve the inbound fax information.
 *
 * Inbound requests are sent to the URL you define in your eFax Developer
 * account. At that URL, you must have a PHP file that includes what
 * follows:
 *
 * \warning
 * The user name and password for the inbound eFax processing are
 * defined in the inbound settings screen. These can be made the
 * same as the outbound user name and password, although I would
 * suggest you use a different user name and a different password
 * to increase your security level.
 *
 * \code
 *    ... -- some initialization code such as require_once('efax.php');
 *
 *    // Get the XML message
 *    $xml = stripslashes($_POST["xml"]);
 *
 *    $efax = new eFax(false);
 *
 *    // the parser checks the validity of the user name and password
 *    // (setup in Inbound Settings of your eFax developer account;
 *    // will be empty strings by default which fails with PHP eFax)
 *    $efax->set_user_name("made_to_order_software");
 *    $efax->set_user_password("TopSecret");
 *
 *    // parse the XML message
 *    if($efax->parse_inbound_message($xml))
 *    {
 *        // get the results and do something with it
 *
 *        // InboundPostRequest/RequestControl/RequestDate
 *        $my_var = $efax->get_result_request_date();
 *
 *        // InboundPostRequest/RequestControl/RequestType
 *        $my_var = $efax->get_result_request_type();
 *
 *        // InboundPostRequest/FaxControl/AccountID
 *        $my_var = $efax->get_result_fax_id();
 *
 *        // InboundPostRequest/FaxControl/ANI
 *        $my_var = $efax->get_result_fax_number();
 *
 *        // InboundPostRequest/FaxControl/CSID
 *        $my_var = $efax->get_result_csid();
 *
 *        // InboundPostRequest/FaxControl/DateReceived
 *        $my_var = $efax->get_result_completion_date();
 *
 *        // InboundPostRequest/FaxControl/FaxName
 *        $my_var = $efax->get_result_fax_name();
 *
 *        // InboundPostRequest/FaxControl/MCFID
 *        $my_var = $efax->get_result_docid();
 *
 *        // InboundPostRequest/FaxControl/PageCount
 *        $my_var = $efax->get_result_pages();
 *
 *        // InboundPostRequest/FaxControl/Status
 *        $my_var = $efax->get_result_fax_status();
 *
 *        // InboundPostRequest/FaxControl/UserFieldControl/*
 *        $my_var = $efax->get_result_user_fields();
 *
 *        // InboundPostRequest/FaxControl/BarcodeControl/*
 *        $my_var = $efax->get_result_barcodes();
 *
 *        // InboundPostRequest/FaxControl/FileContents
 *        //   or
 *        // InboundPostRequest/FaxControl/PageContentControl/*
 *        $my_var = $efax->get_result_files();
 *    }
 *    else
 *    {
 *        ... // handle error case
 *    }
 * \endcode
 *
 * \sa
 *    eFax::get_result_barcodes()
 *    eFax::get_result_completion_date()
 *    eFax::get_result_csid()
 *    eFax::get_result_docid()
 *    eFax::get_result_fax_id()
 *    eFax::get_result_fax_name()
 *    eFax::get_result_fax_number()
 *    eFax::get_result_fax_status()
 *    eFax::get_result_files()
 *    eFax::get_result_pages()
 *    eFax::get_result_request_date()
 *    eFax::get_result_request_type()
 *    eFax::get_result_user_fields()
 *    eFax::parse_inbound_message()
 *    eFax::set_user_name()
 *    eFax::set_user_password()
 *
 * \par
 * Back to \ref summary
 *
 * \section security Security considerations
 *
 * In eFax, there are several built in security features.
 *
 * The main feature is that it only works with an SSL connection.
 * This clearly means secure! The only drawback is that you need a valid
 * certificate in order to send and receive faxes with your system.
 *
 * The second feature is the login and password. These are passed in the XML
 * data. The one drawback with these is that they are written
 * in clear in the XML data. In other words, anyone can read them if they somehow
 * intercept your message (but remember, on the Internet the message is encrypted
 * and thus no one can read the login and password.)
 *
 * \warning
 * Do not save the XML in clear on your hard drive unless you know for
 * sure that it is safe. If you want to save it for storage or archival, think
 * about removing the login and password first.
 *
 * Similarly, whenever you create an XML file to be sent to eFax Developer, you need
 * to incorporate the login and password in clear in that XML document. So watch
 * out and consider saving that information in a protected place on your hard
 * disk. A place only accessible to the part of your application dealing with the
 * sending and receiving of faxes.
 *
 * Another less obvious security feature is the use of the XML format. That ensures
 * a strong structure preventing many invalid requests. For instance, for inbound
 * messages, we check a certain number of entries that need to be valid for the
 * request to be accepted as an eFax Developer request. This part is handled by
 * the eFax class so you do not have to worry about it.
 *
 * \section failures Handling eFax failures
 *
 * Whenever you receive an eFax failure such as
 * "Your request failed due to invalid data"
 * you need to check out your eFax object setup and the data that
 * you are sending. I suggest you try sending documents that you
 * trust are correct (was created with genuine tools.) If those
 * fail too, then there is another problem. Note that at first you probably
 * want to try sending a text file. These are the easiest to test with.
 * Only make sure your lines are about 80 characters wide because eFax does
 * not reformat your documents.
 *
 * The debugging is somewhat difficult because eFax Developer does not
 * give you much info about what goes right and what goes wrong. Note,
 * however, that they keep a copy of the outbound response on their server.
 * So if somehow you do not get that response, you can at least see what
 * they were going to tell you.
 *
 * The main reason why you would not receive the response is because you
 * did not specify a URL with the HTTPS scheme (secure). Or because your
 * certificate cannot be verified by eFax Developer. In most cases, if
 * you can verify your certificate with your browser and it does not give
 * you an error, then eFax Developer will have no problem with it.
 *
 * Note that some eFax errors will not happen. For instance, the
 * \b "Account identifier argument was not passed" error
 * is prevented by the eFax class, which checks that you specified the
 * identifier before it forwards the XML packet to the eFax Developer
 * server.
 *
 * Other failures are possible. For instance, the fax number may not be
 * valid.
 *
 * Internally, the eFax class automatically retries sending your
 * document up to 5 times when an error occurs. In most cases, this is
 * used when the connection to the eFax server fails (times out.)
 * If after 5 times it cannot connect and send the fax to the eFax
 * Developer server, then it returns with <code>false</code>. In this case,
 * you will NOT get any other error from eFax Developer since they do
 * not even know you wanted to contact them. Failure to connect happen
 * often at times when they receive a large number of faxes simultaneously.
 * It is frequent that you have to try to connect a second or third time. It
 * never happened to us that the communication would not happen with
 * 5 attempts. For this reason, the count is hard coded in the \c send()
 * function. Feel free to increase it if you get that problem once in
 * a while.
 *
 * \note
 * Since version 1.6, when you use <code>new eFax(false)</code> the
 * http_request detects errors that it reports with a different
 * exception. This allows the system to avoid waiting a long time
 * when a fatal error happens. You may want to catch errors of
 * type http_request_exception to make sure that your software does
 * not just fail on those errors. In most cases those will happen
 * at time of development and if something goes wrong.
 *
 * \section errors Error Handling
 *
 * Most functions throw an eFaxException when an error occurs.
 *
 * Exceptions are raised if some parameter was not yet defined and
 * you try to create the message to send a fax and when calling the
 * \c eFax::send() function.
 *
 * Also, exceptions are raised if the user name or password
 * were not defined before calling the \c eFax::parse_disposition()
 * or \c eFax::parse_inbound_message().
 *
 * Similarly, exceptions are raised if calling one of the get
 * functions before \c eFax::parse_disposition() or
 * \c eFax::parse_inbound_message() were called.
 *
 * Notice that calling \c eFax::set_fax_id() does NOT set the fax
 * identifier of the disposition. In other words, calling
 * \c eFax::get_result_fax_id() after \c eFax::set_fax_id() without
 * calling \c eFax::parse_disposition() or
 * \c eFax::parse_inbound_message() still generates an
 * exception.
 *
 * Although it should not be necessary, since no exceptions should ever
 * occur when the eFax class is properly used, it is possible to catch
 * the eFaxException in this way:
 *
 * \code
 *    try {
 *        ... // deal with eFax object
 *    }
 *    catch(eFaxException $e)
 *    {
 *        ... // handle exception
 *    }
 * \endcode
 *
 * \subsection new_version_exceptions When using the new http_request class
 *
 * Note that the new http_request class may throw the http_request_exception.
 * If raised, these exceptions will reach your software. Some are like
 * the eFaxException: they only happen when a parameter is invalid and fixing
 * your code will fix the problem. Others would happen in the event an invalid
 * reply was sent by the eFax server. So neither should be raised, although
 * to strengthen your code you probably want to catch these exceptions, just
 * in case. These will only happen when you call the eFax::send() function
 * so you may just protect that one call:
 *
 * \code
 *    try {
 *        $efax->send(); // deal with eFax object
 *    }
 *    catch(http_request_exception $e)
 *    {
 *        ... // handle exception
 *    }
 * \endcode
 *
 * \subsection old_version_exceptions When using the PEAR HttpRequest library
 *
 * Note that the HttpRequest also throws some exceptions. Please
 * read the HttpRequest reference manual for more information.
 *
 * http://www.php.net/manual/en/class.httprequest.php
 *
 * According to the documentation of the send() function the
 * following exceptions may be raised by the library:
 *
 * \code
 *    HttpRuntimeException,
 *    HttpRequestException,
 *    HttpMalformedHeaderException,
 *    HttpEncodingException
 * \endcode
 *
 * Note that the eFax::send() function already catches the
 * HttpInvalidParamException so you will never get that
 * one from the outside of the eFax class.
 *
 * \code
 *    try {
 *        $efax->send(); // deal with eFax object
 *    }
 *    catch(HttpRequestException $e)
 *    {
 *        ... // handle exception
 *    }
 * \endcode
 *
 * \par
 * Back to \ref summary
 *
 * \section changes Changes between versions
 *
 * The following shows what changes between versions. In general, what
 * is listed is what will affect you in some way.
 *
 * \subsection version1_7 Changes in 1.7
 *
 * \li Clean up the response which includes invalid characters and can
 * prevent the LoadXML() from working right.
 *
 * \li Fixed the test in the transmission_control_tags() of the duplicate_id
 * parameter; if false then we must have the fax_id.
 *
 * \li Added support for the disposition language offered by eFax.
 *
 * \li Added support for the new file types. Also changed the case support,
 * you can now pass the type with any case. It will be forced to lowercase
 * before sending to the eFax server.
 *
 * \subsection version1_6 Changes in 1.6
 *
 * \li Added a new class called http_request that we can use instead of the
 * PEAR HttpRequest class. This means you do not have to install PEAR at
 * all and you can still use PHP eFax.
 *
 * \li Applied a fix to the response parser, we now trim the response first
 * and then check that it does start with "<?xml" before parsing it as XML.
 *
 * \li Added a test to check the send() operation of the PHP 5.x version of
 * PHP eFax. This requires an SSL aware server where the fax-server.php code
 * can be installed and run. It is expected to run with Apache2.
 *
 * \li Updated the documentation.
 *
 * \subsection version1_5 Changes in 1.5
 *
 * \li Some clean up work. The code is the same as in version 1.4.
 *
 * \subsection version1_4 Changes in 1.4
 *
 * \li Added options to the HttpRequest so SSL v3 is forced. Without it and
 * a newer system (such as Intrepid, Ubuntu 8.10) you could get an error saying
 * that the request was invalid because it was empty.
 *
 * \li Added an option so redirects are followed (up to 3 of them)
 *
 * \li Fixed a few small bugs found as we wrote a new test. You are unlikely
 * to have been affected by any of those bugs.
 *
 * \li Enhanced the documentation.
 *
 * \li Include a separate file to support PHP 4.4.0+
 *
 * \subsection version1_3 Changes in 1.3
 *
 * \li Added the "Post Successful" message from within the parse_inbound_message()
 * function. It is sent only if the login and password are accepted and the
 * control information is valid (proper control date).
 *
 * \li Added a version definition in the package so your code can check for the
 * version if need be. (PHP_EFAX_VERSION)
 *
 * \subsection version1_2 Changes in 1.2
 *
 * \li Fixed the set_user_password() function that would checked \c $user_name
 * instead of \c $user_password.
 *
 * \li Enhanced the documentation for Fedora users.
 *
 * \li Added more comments about the need for a valid HTTPS certificate.
 *
 * \subsection version1_1 Changes in 1.1
 *
 * \li Added support for inbound faxes with the addition of the
 * \c eFax::parse_inbound_message() function and some eFax::get_result...()
 * functions. Updated the documentation accordingly.
 *
 * \li Added the eFaxBarcode class to support reading barcodes from received
 * faxes.
 *
 * \li Fixed the completion date computation. The previous version was
 * affected by your timezone. Now, it properly returns a UTC time.
 *
 * \li Fixed the \c eFax::set_account_id() function so invalid characters
 * are automatically removed. Added \c eFax::set_raw_account_id() just in
 * case you need to setup an account identifier with what is supposed to
 * be viewed as otherwise invalid characters.
 *
 * \li Fixed some of the existing documentation to clarify a few points.
 *
 * \li \c eFax::get_result_recipient_csid() was renamed \c eFax::get_result_csid()
 * since it is now used for the notifications of sent faxes and the received
 * faxes. In the later case we are the recipient and thus calling the
 * CSID the recipient was not correct.
 *
 * \li \c eFax::get_result_pages_sent() was renamed \c eFax::get_result_pages()
 * since it is now used for the notifications of sent faxes and the received
 * faxes. In the later case, it would need to be "pages received" otherwise.
 *
 * \par
 * Back to \ref summary
 *
 * \section copyright Copyright (c) 2007-2013 Made to Order Software Corp.
 *
 *    All Rights Reserved.
 *
 *    This software and its associated documentation contains
 *    proprietary, confidential and trade secret information
 *    of Made to Order Software Corp. and except as provided by
 *    written agreement with Made to Order Software Corp.
 *
 *    a) no part may be disclosed, distributed, reproduced,
 *       transmitted, transcribed, stored in a retrieval system,
 *       adapted or translated in any form or by any means
 *       electronic, mechanical, magnetic, optical, chemical,
 *       manual or otherwise,
 *
 *    and
 *
 *    b) the recipient is not entitled to discover through reverse
 *       engineering or reverse compiling or other such techniques
 *       or processes the trade secrets contained therein or in the
 *       documentation.
 *
 * \par
 * Back to \ref summary
 */

/** \brief The eFax PHP version.
 *
 * This name definition, PHP_EFAX_VERSION, is the string representing
 * the version of the eFax PHP library.
 *
 * You can use that definition to make sure that you have a compatible
 * version of the library for your software to run properly.
 */
define('PHP_EFAX_VERSION', '1.7');

/** \brief An exception used by the eFax class.
 *
 * The eFax class may throw this exception whenever an invalid
 * parameter is found. This is done when you set a variable with
 * an invalid value or when you attempt to generate the fax request
 * without having set all the necessary parameters (the required
 * parameters need to be set before calling \c eFax::message().)
 *
 * eFaxException is an extension of the PHP Exception class.
 *
 * The class is empty.
 */
class eFaxException extends Exception
{
    // No extensions, but gives our exceptions a special name
    // so they can be caught easily.
};


/** \brief A barcode object.
 *
 * Whenever you receive a fax that includes one or more barcodes
 * and you have the Barcode support from eFax, the
 * \c eFax::parse_inbound_message() function reads them and saves them
 * in a set of eFaxBarcode objects.
 *
 * Note that barcode technology has many redundancies which makes
 * them very robust even with relatively poor transmission and
 * an area of 1 square inch can define 500 Mb of text (7 bits per
 * byte.)
 *
 * \sa eFax::parse_inbound_message()
 * \sa eFax::get_result_barcodes()
 */
class eFaxBarcode
{
    /** \brief Build a barcode object.
     *
     * This function parses the specified barcode control node.
     *
     * The function reads the Key, AdditionalInfo, ReadSequence,
     * ReadDirection, Symbology, Location, PageNumber, and the
     * Start & End Points.
     *
     * \param[in] $barcode    The XML node with the BarcodeControl tag.
     */
    function eFaxBarcode($barcode)
    {
        // Get key
        $node_list = $barcode->getElementsByTagName("Key");
        if($node_list->length == 1)
        {
            $this->key = $node_list->item(0)->textContent;
        }

        // The rest of the info is under AdditionalInfo
        $node_list = $barcode->getElementsByTagName("AdditionalInfo");
        if($node_list->length != 1)
        {
            // we're done!
            return;
        }
        $additional_info = $node_list->item(0);

        // Get the code bar number within this page
        $node_list = $additional_info->getElementsByTagName("ReadSequence");
        if($node_list->length == 1)
        {
            $this->sequence = $node_list->item(0)->textContent;
        }

        // Get the read direction
        $node_list = $additional_info->getElementsByTagName("ReadDirection");
        if($node_list->length == 1)
        {
            $this->direction = $node_list->item(0)->textContent;
        }

        // Get the symbology that was used in that barcode
        $node_list = $additional_info->getElementsByTagName("Symbology");
        if($node_list->length == 1)
        {
            $this->symbology = $node_list->item(0)->textContent;
        }

        // Get the location of the code bar
        $node_list = $additional_info->getElementsByTagName("CodeLocation");
        if($node_list->length != 1)
        {
            return;
        }
        $code_location = $node_list->item(0);

        // The page where the barcode was detected
        $node_list = $code_location->getElementsByTagName("PageNumber");
        if($node_list->length == 1)
        {
            $this->page = $node_list->item(0)->textContent;
        }

        // The exact (X, Y) coordinates where the barcode is located
        $node_list = $code_location->getElementsByTagName("PageCoordinates");
        if($node_list->length != 1)
        {
            return;
        }
        $page_coordinates = $node_list->item(0);

        // Get the start points
        $node_list = $page_coordinates->getElementsByTagName("StartEdge");
        if($node_list->length == 1)
        {
            $start_edge = $node_list->item(0);

            $node_list = $start_edge->getElementsByTagName("XStartPointA");
            if($node_list->length == 1)
            {
                $this->x_start_a = $node_list->item(0)->textContent;
            }
            $node_list = $start_edge->getElementsByTagName("YStartPointA");
            if($node_list->length == 1)
            {
                $this->y_start_a = $node_list->item(0)->textContent;
            }
            $node_list = $start_edge->getElementsByTagName("XStartPointB");
            if($node_list->length == 1)
            {
                $this->x_start_b = $node_list->item(0)->textContent;
            }
            $node_list = $start_edge->getElementsByTagName("YStartPointB");
            if($node_list->length == 1)
            {
                $this->y_start_b = $node_list->item(0)->textContent;
            }
        }

        // Get the end points
        $node_list = $page_coordinates->getElementsByTagName("EndEdge");
        if($node_list->length == 1)
        {
            $end_edge = $node_list->item(0);

            $node_list = $end_edge->getElementsByTagName("XEndPointA");
            if($node_list->length == 1)
            {
                $this->x_end_a = $node_list->item(0)->textContent;
            }
            $node_list = $end_edge->getElementsByTagName("YEndPointA");
            if($node_list->length == 1)
            {
                $this->y_end_a = $node_list->item(0)->textContent;
            }
            $node_list = $end_edge->getElementsByTagName("XEndPointB");
            if($node_list->length == 1)
            {
                $this->x_end_b = $node_list->item(0)->textContent;
            }
            $node_list = $end_edge->getElementsByTagName("YEndPointB");
            if($node_list->length == 1)
            {
                $this->y_end_b = $node_list->item(0)->textContent;
            }
        }
    }

    /** \brief Get the barcode key.
     *
     * This function is used to retrieve the barcode key. The key is
     * the actual value of the barcode. In most cases, this is the
     * only function you need to call (assuming you can have only
     * one barcode per fax.)
     *
     * \return The barcode key or null.
     */
    function get_key()
    {
        return $this->key;
    }

    /** \brief Get the page number.
     *
     * This number represents the page on which the barcode was
     * found. It starts at 1 and grows incrementally.
     *
     * Note that there can be several barcodes on a single page.
     * To know which barcode it is, you may need to use the
     * sequence number or the coordinates.
     *
     * \return The page number on which the barcode was found or null.
     *
     * \sa eFaxBarcode::get_sequence()
     */
    function get_page()
    {
        return $this->page;
    }

    /** \brief Get the number of the barcode on the page.
     *
     * Each barcode on a single page is given a different sequence
     * number. This can be used to know which barcode is being
     * worked on.
     *
     * The sequence starts at 1 (TBD) and grows incrementally.
     *
     * \return The sequence number of this barcode or null.
     *
     * \sa eFaxBarcode::get_page()
     */
    function get_sequence()
    {
        return $this->sequence;
    }

    /** \brief Get the direction of the barcode.
     *
     * The barcode can be found going in any direction (left to
     * right, bottom to top, etc.) This function returns that
     * information.
     *
     * The direction is defined as a string and can be any
     * of the following:
     *
     * \li "2-Dimensional"
     * \li "Left/Right"
     * \li "Top/Bottom"
     * \li "Right/Left"
     * \li "Bottom/Top"
     *
     * \return A string with the direction or null.
     */
    function get_direction()
    {
        return $this->direction;
    }

    /** \brief Get the symbology or protocol used.
     *
     * This function returns the name of the symbology or
     * protocol used to generate the barcode. The names
     * are not defined in the eFax Developer documentation.
     *
     * \return The symbology used for this barcode or null.
     */
    function get_symbology()
    {
        return $this->symbology;
    }

    /** \brief Get the location points.
     *
     * This function is used to get the start and end points of the
     * two barcode areas detected and defining this barcode.
     *
     * The coordinates may be defined in inches. However, the documentation
     * does not say. You will need to test with a few faxes to make sure
     * that they represent what you think they are.
     *
     * \note
     * If the points were not defined for that barcode, then this function
     * sets them to null. Any one coordinate can be set to null.
     *
     * \param[out] $x_start_a    A reference to a variable to hold the horizontal start point A
     * \param[out] $y_start_a    A reference to a variable to hold the vertical start point A
     * \param[out] $x_start_b    A reference to a variable to hold the horizontal start point B
     * \param[out] $y_start_b    A reference to a variable to hold the vertical start point B
     * \param[out] $x_end_a      A reference to a variable to hold the horizontal end point A
     * \param[out] $y_end_a      A reference to a variable to hold the vertical end point A
     * \param[out] $x_end_b      A reference to a variable to hold the horizontal end point B
     * \param[out] $y_end_b      A reference to a variable to hold the vertical end point B
     */
    function get_points(&$x_start_a, &$y_start_a, &$x_start_b, &$y_start_b,
                        &$x_end_a,   &$y_end_a,   &$x_end_b,   &$y_end_b)
    {
        $x_start_a = $this->x_start_a;
        $y_start_a = $this->y_start_a;
        $x_start_b = $this->x_start_b;
        $y_start_b = $this->y_start_b;
        $x_end_a   = $this->x_end_a;
        $y_end_a   = $this->y_end_a;
        $x_end_b   = $this->x_end_b;
        $y_end_b   = $this->y_end_b;
    }

    private $key;            // the value of the barcode (interpreted from the picture)
    private $page;            // the page on which the code was found
    private $sequence;        // this barcode number on the given $page
    private $direction;        // 2D, left/right, top/bottom, right/left, bottom/top
    private $symbology;        // symbology or protocol used to generate  the barcode
    private $x_start_a;        // where the barcode starts on the page (in pixels?)
    private $y_start_a;        // where the barcode starts on the page (in pixels?)
    private $x_start_b;        // where the barcode starts on the page (in pixels?)
    private $y_start_b;        // where the barcode starts on the page (in pixels?)
    private $x_end_a;        // where the barcode ends on the page (in pixels?)
    private $y_end_a;        // where the barcode ends on the page (in pixels?)
    private $x_end_b;        // where the barcode ends on the page (in pixels?)
    private $y_end_b;        // where the barcode ends on the page (in pixels?)
};


/** \brief The PHP script to send and receive faxes over the Internet.
 *
 * This class is based on the developer documents provided by
 * eFax (%http://www.efax.com).
 *
 * It will generate an XML file that is 100% compatible with
 * the eFax specification. It supports all the data types and
 * all the different modes available in the API.
 *
 * If you know how much it costs to send one page, and you
 * receive the disposition, you will be able to compute exactly
 * how much sending the fax did cost:
 *
 * \code
 * $fax_charge = $efax->get_result_pages() * $cost_per_page * $number_of_recipients;
 * \endcode
 *
 * Before calling \c eFax::send() you will need to call \c eFax::message().
 * Before calling \c eFax::message() you will need to at least call
 * the following functions to setup your eFax object:
 *
 * \code
 * $efax->set_account_id(...);
 * $efax->set_user_name(...);
 * $efax->set_user_password(...);
 * $efax->add_file(...);
 * $efax->add_recipient(...);
 * \endcode
 *
 * \sa
 *    eFax::set_account_id()
 *    eFax::set_user_name()
 *    eFax::set_user_password()
 *    eFax::add_file()
 *    eFax::add_recipient()
 */
class eFax
{
    /// \brief Received when sending the fax is a success.
    const RESPOND_SUCCESS = 1;
    /// \brief Received when sending the fax is a failure.
    const RESPOND_ERROR   = 2;

    /** \brief Initializes the eFax object.
     *
     * By default, the output URL is the eFax URL expected by the eFax Developers.
     * Namely, this is:
     *
     * %https://secure.efaxdeveloper.com/EFax_WebFax.serv
     *
     * The outbound encoding is set to application/x-www-form-urlencoded.
     *
     * The fax resolution is set to STANDARD.
     *
     * The disposition level is set to both: success and error.
     *
     * The status is set to 0 as if a response indicated a failure.
     *
     * \param[in] $use_pear_http_request  If true (the default) use the PEAR HttpRequest object (requires http.so), otherwise use the http_request.php class.
     */
    function eFax($use_pear_http_request = true)
    {
        $this->use_pear_http_request = $use_pear_http_request;

        $this->outbound_url = "https://secure.efaxdeveloper.com/EFax_WebFax.serv";
        $this->outbound_encoding = "application/x-www-form-urlencoded";

        $this->resolution = "STANDARD";
        $this->disposition_level = eFax::RESPOND_SUCCESS | eFax::RESPOND_ERROR;

        $this->status = 0;
    }

    /** \brief Send the message to eFax.
     *
     * This function sends the specified message to eFax. The message
     * can be generated using the \c eFax::message() function (actually, it is
     * strongly recommended that you do so unless you have another
     * tool to generate such request reliably.)
     *
     * IMPORTANT NOTE: You need to use the HTTPS protocol and for that
     * you must have an HTTPS website with a valid certificate. Although
     * just sending a fax does not require an HTTPS server, you will need
     * to support connecting to an HTTPS server. And if you want to
     * handle the disposition via your Web Server (instead of just getting
     * an email), then you do not have a choice. You must have a valid
     * SSL certificate.
     *
     * \note
     * If your system does not include HttpRequest, then you need to
     * install it with the following commands. The php5-dev gives you
     * the phpize that pecl needs. The pecl install the actual HTTP
     * support. It needs to use a C compiler to recompile the module.
     *
     * \code
     * sudo apt-get install php5-dev
     * sudo pecl install pecl_http
     * \endcode
     *
     * Also, as mentioned at the end of a successful compile, add
     *
     * \code
     * extension=http.so
     * \endcode
     *
     * in your php.ini file.
     *
     * The phpize function is available in php5-dev (apt-get install php5-dev).
     * You may also need the curl library development environment. Get the
     * libcurl3-openssl-dev, for instance.
     *
     * \note
     * This function sets up options for the HttpRequest. This includes
     * a redirect (3 hops) and SSL version of 3. This works from our server
     * but if you do not support SSL version 3, go ahead and edit this
     * function and try with version 2. You could also remove the verify
     * option, but frankly, if eFax does not maintain their certificate,
     * we all have a problem!
     *
     * \exception eFaxException is thrown if some parameter is invalid in the
     * input or the response data. If somehow the transmission fails, the
     * function returns false instead. However, the HttpRequest may throw some
     * exception that will not be caught here.
     *
     * \param[in] $msg The fax message (i.e. the XML message to forward.)
     *
     * \return true if the transmission succeeded with a positive response;
     *            false if the transmission does not occur
     *
     * \sa eFax::message()
     * \sa eFax::parse_response()
     */
    function send($msg)
    {
        if(strlen($this->account_id) < 5)
        {
            throw new eFaxException("an account identifier is required to send a fax.");
        }

        // if the Http request object throws before the send()
        // returns then that's our response...
        $this->response = "not sent";

        // initialize the request
        if($this->use_pear_http_request)
        {
            $request = new HttpRequest($this->outbound_url, HttpRequest::METH_POST);
        }
        else
        {
            // include our reimplementation of the PEAR HttpRequest
            // (much simpler than the PEAR version though!)
            require_once('http_request.php');

            $request = new http_request($this->outbound_url);
        }
        $request->setOptions(array(
                    "redirect" => "3",
                    "ssl" => array("version" => "3", "verifyhost" => "1")
                ));
        $request->addHeaders(array(
                    "Cache-Control" => "no-cache, must-revalidate",
                    "Content-type" => $this->outbound_encoding
                ));
        $request->addPostFields(array(
                    "id" => $this->account_id,    // the login + password are defined in the XML data
                    "respond" => "xml",
                    "xml" => $msg                // this does the urlencode() as expected by eFax
                ));

        // send the request and wait for the immediate XML response
        $response = false;
        $count = 5;
        do
        {
            $repeat = false;
            try
            {
                $response = $request->send();
            }
            catch(HttpInvalidParamException $e)
            {
                // this exception happens whenever the eFax server times out
                // (we may want to check the exception a little closer, but
                // I'm not too sure how to do that in there...)
                $repeat = true;
                --$count;
                sleep(1);
                $this->response = "not sent (Caught an HttpInvalidParamException exception: "
                    . $e->getMessage() . ")";
            }
        }
        while($repeat && $count > 0);

        if(!$response)
        {
            // there was no response!
            return false;
        }
        $this->response = $response->getBody();

        return $this->parse_response($this->response);
    }

    /** \brief Parse the send request immediate response.
     *
     * This function is used to parse the immediate response of sending a
     * fax request to eFax. This is used to confirm that eFax did indeed
     * receive the request and will be able to process it (i.e. that we
     * sent a valid eFax XML document.)
     *
     * \exception eFaxException is thrown if the XML does not seem correct.
     *
     * \param[in] $response The body of the HTTPMessage returned by \c HttpRequest::send().
     *
     * \return true if the request is a success, false otherwise
     *
     * \sa eFax::send()
     */
    private function parse_response($response)
    {
        $response = trim($response);
        if(!$response)
        {
            throw new eFaxException("parse_response() cannot be called with an empty response.");
        }
        $p = strpos($response, '<?xml');
        if($p === FALSE)
        {
            throw new eFaxException("response does not include an <?xml tag");
        }
        $q = strpos($response, '</OutboundResponse', $p);
        if($q === FALSE)
        {
            throw new eFaxException("response does not include the </OutboundResponse> tag");
        }
        // we add the '>' in case there were spaces after the tag name
        $response = substr($response, $p, $q - $p + 18) . '>';

        $xml = new DOMDocument;
        $xml->loadXML($response);

        $node_list = $xml->getElementsByTagName("StatusCode");
        if($node_list->length != 1)
        {
            throw new eFaxException("response does not include a StatusCode tag");
        }
        $this->status = $node_list->item(0)->nodeValue;

        // in case of an error, get the level and message
        if($this->status != 1)
        {
            $node_list = $xml->getElementsByTagName("ErrorLevel");
            if($node_list->length == 1)
            {
                $this->error_level = $node_list->item(0)->nodeValue;
            }
            $node_list = $xml->getElementsByTagName("ErrorMessage");
            if($node_list->length == 1)
            {
                $this->error_description = $node_list->item(0)->nodeValue;
            }
        }

        // if we sent an identifier, verify that it is equal
        if(!is_null($this->fax_id))
        {
            // there must be exactly 1 TransmissionID tag in the response
            $node_list = $xml->getElementsByTagName("TransmissionID");
            if($node_list->length != 1)
            {
                throw new eFaxException("response does not include a TransmissionID tag");
            }
            else
            {
                $id = $node_list->item(0)->nodeValue;
                if($id != $this->fax_id && $this->status == 1)
                {
                    throw new eFaxException("response TransmissionID ($id) does not match the identifier sent ({$this->fax_id})");
                }
            }
        }

        $node_list = $xml->getElementsByTagName("DOCID");
        if($node_list->length != 1)
        {
            throw new eFaxException("response does not include a DOCID tag");
        }
        $this->docid = $node_list->item(0)->nodeValue;

        return $this->status == 1;
    }

    /** \brief Function used to parse the disposition.
     *
     * This function transforms the disposition XML message into a set
     * of internal variables that can then be queried using get functions.
     *
     * The disposition includes the user name and password. These will
     * be checked against the user name and password defined in this
     * eFax object. If there is a mismatch, then the function returns
     * false and stops.
     *
     * Please, see the \ref disposition section for an example of handling a
     * disposition.
     *
     * At this time, it is necessary to use \c stripslashes()
     * before one can pass the XML data to this parse function.
     *
     * \code
     * ...
     * $msg = stripslashes($_POST["xml"]);
     * $efax->parse_disposition($msg);
     * ...
     * echo "Post Successful\n";
     * ...
     * \endcode
     *
     * Note that the "Post Successful" reply can be sent at any time. You
     * have to judge where is the best position for the message in your
     * code. It is likely a good idea to do it after you successfully saved
     * the information about the fax disposition, but eFax does not really
     * send you the data again either way. (It will email you about the
     * failure though.)
     *
     * \note
     * The function returns false if anything in this message represents
     * a failure of some kind. This does not means that the fax was not
     * partially transmitted.
     *
     * \exception eFaxException is raised if the XML message is not a
     * valid outbound disposition file or the user and password where
     * not specified.
     *
     * \param[in] $msg The message to parse
     *
     * \return true if the message represents a successful fax transmission;
     *            false in all other cases
     *
     * \sa eFax::set_user_name()
     * \sa eFax::set_user_password()
     */
    function parse_disposition($msg)
    {
        if(!$this->user_name || !$this->user_password)
        {
            throw new eFaxException("parsing of a disposition message requires a user name and password");
        }
        if(!$msg)
        {
            throw new eFaxException("parsing of a disposition message requires a non-empty message");
        }

        $xml = new DOMDocument;
        $xml->loadXML($msg);

        $node_list = $xml->getElementsByTagName("OutboundDisposition");
        if($node_list->length != 1)
        {
            throw new eFaxException("disposition message does not include an OutboundDisposition tag");
        }
        $disposition = $node_list->item(0);

        // verify the login/password info (should we throw on these if erroneous?)
        // if($disposition->getAttribute("UserName") != $this->user_name)
        // {
        //     $this->error_level = "System";
        //     $this->error_description = "Invalid login name.";
        //     return false;
        // }
        // if($disposition->getAttribute("Password") != $this->user_password)
        // {
        //     $this->error_level = "System";
        //     $this->error_description = "Invalid password.";
        //     return false;
        // }

        $this->result_fax_id          = $disposition->getAttribute("TransmissionID");
        $this->result_docid           = $disposition->getAttribute("DOCID");
        $this->result_fax_number      = $disposition->getAttribute("FaxNumber");
        $this->result_completion_date = $disposition->getAttribute("CompletionDate");
        $this->result_fax_status      = $disposition->getAttribute("FaxStatus");
        $this->result_csid            = $disposition->getAttribute("RecipientCSID");
        $this->result_duration        = $disposition->getAttribute("Duration");
        $this->result_pages           = $disposition->getAttribute("PagesSent");
        $this->result_retries         = $disposition->getAttribute("NumberOfRetries");

        return true;
    }

    /** \brief Parse an Inbound Fax.
     *
     * This function is called whenever you receive an Inbound Fax from
     * eFax Developer. It will send the necessary "Post Successful" response
     * if the header of the message is parsed successfully. This means the
     * login, password, and date of the message were valid.
     *
     * The message includes the user name and password defined in the
     * Inbound Settings section. These will be checked against the user name
     * and password defined in this eFax object with the set_user_name() and
     * set_user_password() functions. If there is a mismatch, then the
     * function returns false and stops.
     *
     * If the function returns true, then the data defined in the XML
     * file is now available for you to query from your eFax object.
     *
     * The message is expected to be received via a POST and will be in
     * the \c $_POST['xml'] variable. Yet, the data will include backslashes
     * and you must clean it before calling the parse function as in:
     *
     * \code
     * ...
     * $msg = stripslashes($_POST["xml"]);
     * $efax->parse_inbound_message($msg);
     * ...
     * \endcode
     *
     * \note
     * This function returns false if the message is not valid. This means
     * it is not valid XML, the user or password are wrong or the main tag
     * is not InboundPostRequest. In all other cases, the function returns
     * true meaning that the request is valid, not that the fax was properly
     * received. You need to call the eFax::get_result_fax_status() to check whether
     * the fax was successfully received (0) or not (!=0).
     *
     * \exception eFaxException is raised if the XML message is not a valid
     * inbound request file. The minimum requirements checked are the following
     * valid tags: InboundPostRequest (root tag), AccessControl (to ensure user),
     * UserName (the login name), Password (your password), and FaxControl (the
     * actual fax data, although all the sub-tags are not required.)
     * The other tags are not considered so critical and as such only
     * generate an error when missing.
     *
     * \bug
     * Because the function will quickly mark the received message as valid,
     * it is strongly suggest that you save the XML message (i.e. write to a
     * log or save in your database). That way, if the treatment of the message
     * fails at a later time, you still have a copy of the message that
     * generated that error. Especially, you may want to mark the message as
     * "in process", and once you are done processing 100%, mark the message
     * as done (or even remove that copy since it includes your login and
     * password in clear...)
     *
     * \param[in] $msg   The raw XML data sent to you by eFax Developer via a POST request.
     *
     * \return true if the message is valid, false in all other cases; note
     * that in the worst case scenario the function may instead raise an
     * exception.
     *
     * \sa eFax::set_user_name()
     * \sa eFax::set_user_password()
     * \sa eFax::get_result_request_date()
     * \sa eFax::get_result_request_type()
     * \sa eFax::get_result_completion_date()
     */
    function parse_inbound_message($msg)
    {
        // just in case, clean a previous inbound request
        $this->clear_inbound();

        $xml = new DOMDocument;
        $xml->loadXML($msg);

        $node_list = $xml->getElementsByTagName("InboundPostRequest");
        if($node_list->length != 1)
        {
            throw new eFaxException("inbound request message does not include an InboundPostRequest tag");
        }
        $request = $node_list->item(0);

        // User & Password (Access Control)
        if(!$this->parse_inbound_access_control($request))
        {
            return false;
        }

        // Date, Time & Type (Request Control)
        if(!$this->parse_inbound_request_control($request))
        {
            return false;
        }

        // The rest of the data is in the FaxControl tag
        $node_list = $request->getElementsByTagName("FaxControl");
        if($node_list->length != 1)
        {
            throw new eFaxException("inbound request message does not include an InboundPostRequest/FaxControl tag");
        }
        $fax_control = $node_list->item(0);

        // Get the fax main info:
        //
        //        Account, Date, Time, Fax Name, File Type,
        //        # of Pages, CSID, ANI, Status, MCFID
        if(!$this->parse_inbound_fax_control($fax_control))
        {
            return false;
        }

        // Get the user defined fields
        if(!$this->parse_inbound_user_fields($fax_control))
        {
            return false;
        }

        // Get the detected barcode information
        if(!$this->parse_inbound_barcodes($fax_control))
        {
            return false;
        }

        // Get the fax pages
        if(!$this->parse_inbound_pages($fax_control))
        {
            return false;
        }

        return true;
    }

    /** \brief Internal function used to clean the Inbound data.
     *
     * This function is used to clean the Inbound data from a previous
     * inbound request. This is used by the \c eFax::parse_inbound_message()
     * to avoid possible problems in case the function was to be called
     * multiple times.
     */
    private function clear_inbound()
    {
        unset($this->inbound_date);                // RequestDate
        unset($this->inbound_type);                // RequestType
        unset($this->result_fax_id);            // AccountID (this is your eFax phone number)
        unset($this->result_completion_date);    // DateReceived
        unset($this->inbound_fax_name);            // FaxName
        unset($this->inbound_file_type);        // FileType
        unset($this->result_pages);                // PageCount
        unset($this->result_csid);                // CSID
        unset($this->result_fax_number);        // ANI (sender fax number if available)
        unset($this->result_fax_status);        // Status
        unset($this->result_docid);                // MCFID
        unset($this->inbound_user_fields);        // UserFields
        unset($this->inbound_barcodes);            // Barcodes
        $this->files = array();                    // FileContents or PageContents
    }

    /** \brief Check the username and password.
     *
     * This function checks the AccessControl/UserName and the
     * AccessControl/Password tags for validity.
     *
     * If the tags are missing, the function throws an exception.
     *
     * If the user name or password are not valid, the function
     * returns false.
     *
     * \exception eFaxException is raised whenever one
     * of the AccessControl, AccessControl/UserName or
     * AccessControl/Password tags are missing.
     *
     * \param[in] $request  The XML request node
     *
     * \return false if the user name or password do not match.
     */
    private function parse_inbound_access_control($request)
    {
        if(!$this->user_name || !$this->user_password)
        {
            throw new eFaxException("parsing of an inbound message requires a user name and password");
        }

        $node_list = $request->getElementsByTagName("AccessControl");
        if($node_list->length != 1)
        {
            throw new eFaxException("inbound request message does not include an InboundPostRequest/AccessControl tag");
        }
        $access_control = $node_list->item(0);

        // NOTE: the eFax Guide about Inbound requests says UserName is not required.
        $node_list = $access_control->getElementsByTagName("UserName");
        if($node_list->length != 1)
        {
            throw new eFaxException("inbound request message does not include an InboundPostRequest/AccessControl/UserName tag");
        }
        $user_name = $node_list->item(0);

        // NOTE: the eFax Guide about Inbound requests says Password is not required.
        $node_list = $access_control->getElementsByTagName("Password");
        if($node_list->length != 1)
        {
            throw new eFaxException("inbound request message does not include an InboundPostRequest/AccessControl/Password tag");
        }
        $password = $node_list->item(0);

        if($user_name->textContent != $this->user_name)
        {
            $this->error_level = "System";
            $this->error_description = "Invalid login name.";
            return false;
        }

        if($password->textContent != $this->user_password)
        {
            $this->error_level = "System";
            $this->error_description = "Invalid password.";
            return false;
        }

        return true;
    }

    /** \brief Get the request control date, time and type.
     *
     * This function parses the RequestControl tag.
     *
     * These tags are not considered critical and if missing, it is simply
     * ignored. The date and type variables will not be set.
     *
     * If there is a date and time but the format is not valid,
     * an error is set and the function returns false.
     *
     * \param[in] $request  The XML root node that includes the RequestControl tag.
     *
     * \return true if no error occurred.
     *
     * \sa eFax::get_result_request_date()
     * \sa eFax::get_result_request_type()
     */
    private function parse_inbound_request_control($request)
    {
        $this->inbound_date = -1;
        $this->inbound_type = 'undefined';

        // Get the date/time and request type info
        $node_list = $request->getElementsByTagName("RequestControl");
        if($node_list->length == 1)
        {
            $request_control = $node_list->item(0);

            // Check the date and time
            $node_list = $request_control->getElementsByTagName("RequestDate");
            if($node_list->length == 1)
            {
                // Date/Time format: MM/DD/YYYY HH:MM:SS
                $date = $node_list->item(0);
                if(preg_match(
                        '/([0-9][0-9])\/([0-9][0-9])\/([0-9][0-9][0-9][0-9]) +([0-9][0-9]):([0-9][0-9]):([0-9][0-9])/',
                        $date->textContent, $date_info) == 1)
                {
                    // the UTC Unix timestamp
                    $this->inbound_date = gmmktime($date_info[4], $date_info[5], $date_info[6],
                                                $date_info[1], $date_info[2], $date_info[3])
                                                        + 8 * 60 * 60;
                }
                else
                {
                    $this->error_level = "System";
                    $this->error_description = "Invalid date and time in RequestDate.";
                    return false;
                }
            }

            // Check the request type
            $node_list = $request_control->getElementsByTagName("RequestType");
            if($node_list->length == 1)
            {
                $request_type = $node_list->item(0);
                $this->inbound_type = $request_type->textContent;
            }
        }

        // if we get here, consider the request as successful
        echo "Post Successful\n";

        return true;
    }

    /** \brief Extract the main fax control information.
     *
     * This function retrieves the basic fax control information.
     *
     * This includes:
     *
     * \li Date & Time &mdash;
     * The date and time when the data was received by eFax.
     * See \c eFax::get_result_completion_date().
     *
     * \li Fax Name &mdash;
     * The name of the fax as defined by the sender (?).
     * See \c eFax::get_result_fax_name().
     *
     * \li Account Identifier &mdash;
     * Your account fax phone number.
     * See \c eFax::get_result_fax_id().
     *
     * \li Type of files &mdash;
     * When the request includes pages, then these will include
     * files of this type. At this time, you may receive PDF or
     * TIFF files. (And type will be set to 'pdf' or 'tif'.)
     * You cannot directly retrieve the file type. Instead, you
     * retrieve the files and the type is included.
     *
     * \li Number of pages &mdash;
     * The number of pages that are included in this message.
     * This should be the total number of pages sent to you.
     * However, if some pages could not make it, then this will
     * be less.
     * See \c eFax::get_result_pages().
     *
     * \li CSID &mdash;
     * This is the sender station identifier. Often, this is not
     * available.
     * See \c eFax::get_result_csid().
     *
     * \li ANI &mdash;
     * The fax number of the person who sent you this fax. This
     * is the 'caller id'. It may not be available.
     * See \c eFax::get_result_fax_number().
     *
     * \li Status &mdash;
     * The status of the fax. This may be 0 for success or any
     * other number for an error. If not zero, a string can be
     * retrieved calling \c eFax::get_result_error_message() with
     * the error code.
     * See \c eFax::get_result_fax_status().
     *
     * \li MCFID &mdash;
     * This number is the fax identifier as defined by the eFax
     * interface. It is expected to be unique among all the
     * faxes sent or received by eFax.
     * See \c eFax::get_result_docid().
     *
     * \param[in] $fax_control  The XML node InboundPostRequest/FaxControl
     *
     * \return true if the function succeeds, false if an error was detected
     *
     * \sa \c eFax::get_result_error_message()
     */
    private function parse_inbound_fax_control($fax_control)
    {
        // Account Identifier (i.e. eFax phone number)
        $node_list = $fax_control->getElementsByTagName("AccountID");
        if($node_list->length == 1)
        {
            $account_id = $node_list->item(0);
            $this->result_fax_id = $account_id->textContent;
        }
        else
        {
            $this->result_fax_id = 'n.a.';
        }

        // Date & Time when the fax was received by eFax
        $node_list = $fax_control->getElementsByTagName("DateReceived");
        if($node_list->length == 1)
        {
            $date_received = $node_list->item(0);
            if(preg_match(
                    '/([0-9][0-9])\/([0-9][0-9])\/([0-9][0-9][0-9][0-9]) +([0-9][0-9]):([0-9][0-9]):([0-9][0-9])/',
                    $date_received->textContent, $date_info) == 1)
            {
                // we expect yyyy-mm-dd hh:mm:ss in $this->result_completion_date
                // (see get_result_completion_date() function)
                $this->result_completion_date = sprintf("%04d-%02d-%02d %02d:%02d:%02d",
                        $date_info[3], $date_info[1], $date_info[2],
                        $date_info[4], $date_info[5], $date_info[6]);
            }
            else
            {
                $this->error_level = "System";
                $this->error_description = "Invalid date and time in DateReceived.";
                return false;
            }
        }
        else
        {
            $this->result_completion_date = '';
        }

        // Name of the fax as defined in eFax Developer and the client
        $node_list = $fax_control->getElementsByTagName("FaxName");
        if($node_list->length == 1)
        {
            $fax_name = $node_list->item(0);
            $this->inbound_fax_name = $fax_name->textContent;
        }
        else
        {
            $this->inbound_fax_name = '';
        }

        // The type of files in this fax (used to call the add_file() function)
        // NOTE: we do not give the user a direct access to the filetype, instead
        //         they have to get entries from the $this->files array that include
        //         the type as well as the content of the file.
        $node_list = $fax_control->getElementsByTagName("FileType");
        if($node_list->length == 1)
        {
            $file_type = $node_list->item(0);
            $this->inbound_file_type = strtolower($file_type->textContent);
        }
        else
        {
            // from what I understand, this is the default...
            // (or should we generate an error here?!)
            $this->inbound_file_type = 'pdf';
        }

        // Status
        $node_list = $fax_control->getElementsByTagName("Status");
        if($node_list->length == 1)
        {
            $status = $node_list->item(0);
            $this->result_fax_status = $status->textContent;
        }
        else
        {
            $this->result_fax_status = 0;
        }

        // The number of pages received
        $node_list = $fax_control->getElementsByTagName("PageCount");
        if($node_list->length == 1)
        {
            $page_count = $node_list->item(0);
            $this->result_pages = $page_count->textContent;
        }
        else
        {
            // assume there is at least one page unless the status != 0
            // (or should we generate an error here?!)
            $this->result_pages = $this->result_fax_status == 0 ? 1 : 0;
        }

        // CSID
        $node_list = $fax_control->getElementsByTagName("CSID");
        if($node_list->length == 1)
        {
            $csid = $node_list->item(0);
            $this->result_csid = $csid->textContent;
        }
        else
        {
            $this->result_csid = '';
        }

        // ANI
        $node_list = $fax_control->getElementsByTagName("ANI");
        if($node_list->length == 1)
        {
            $ani = $node_list->item(0);
            $this->result_fax_number = $ani->textContent;
        }
        else
        {
            $this->result_fax_number = 'Unknown';
        }

        // MCFID, this is equivalent to the document identifier
        $node_list = $fax_control->getElementsByTagName("MCFID");
        if($node_list->length == 1)
        {
            $mcfid = $node_list->item(0);
            $this->result_docid = $mcfid->textContent;
        }
        else
        {
            $this->result_docid = 'n.a.';
        }

        return true;
    }

    /** \brief Read the user fields.
     *
     * This function reads all the user fields from the fax control
     * node and put them in an array. The key of the array is the
     * name of the field and the content is the value.
     *
     * The fields can be retrieved with the \c eFax::get_result_user_fields()
     * function.
     *
     * \note
     * It is not unlikely that no user fields were defined. These are
     * probably not very useful in most cases since they are not
     * dynamic.
     *
     * \bug
     * A field named FALSE or 0 or some other value that can end up
     * looking like \c false will be skipped.
     *
     * \param[in] $fax_control   The FaxControl node
     *
     * \return true if nothing was wrong (including no user fields,)
     *            false otherwise
     *
     * \sa eFax::get_result_user_fields()
     */
    private function parse_inbound_user_fields($fax_control)
    {
        $this->inbound_user_fields = array();

        // Defined in the user field control tag
        $node_list = $fax_control->getElementsByTagName("UserFieldControl");
        if($node_list->length != 1)
        {
            return true;
        }
        $user_field_control = $node_list->item(0);

        // we ignore the UserFieldsRead which won't give us anything

        // Get the node that contains the array
        $node_list = $user_field_control->getElementsByTagName("UserFields");
        if($node_list->length != 1)
        {
            // this is probably an error, but who cares?
            return true;
        }
        $user_fields = $node_list->item(0);

        // Get the array itself
        $node_list = $user_fields->getElementsByTagName("UserField");
        $max = $node_list->length;
        for($idx = 0; $idx < $max; ++$idx)
        {
            $user_field = $node_list->item($idx);

            // Get name
            $node_child = $user_field->getElementsByTagName("FieldName");
            if($node_child->length != 1)
            {
                continue;
            }
            $field_name = $node_child->item(0);
            if(!$field_name->textContent)
            {
                // We skip empty field names, that should never happen though.
                // (note that if the field is named 'FALSE' or '0', then we may lose it too...)
                continue;
            }

            // Get value
            unset($node_child);
            $node_child = $user_field->getElementsByTagName("FieldValue");
            if($node_child->length != 1)
            {
                continue;
            }
            $field_value = $node_child->item(0);

            // Save the info
            $this->inbound_user_fields[$field_name->textContent] = $field_value->textContent;
        }

        return true;
    }

    /** \brief Retrieve the barcodes from the inbound message.
     *
     * This function goes through the nodes defining barcodes that eFax
     * found in the fax pages and saves them in an array.
     *
     * Barcodes are not available in all accounts. If your account was
     * not setup to support barcodes, then they will never be detected.
     *
     * \param[in] $fax_control   The FaxControl node
     *
     * \return true if nothing was wrong (including no user fields,)
     *            false otherwise
     *
     * \sa class eFaxBarcode
     * \sa eFax::get_result_barcodes()
     */
    private function parse_inbound_barcodes($fax_control)
    {
        // by default, there's none
        $this->inbound_barcodes = array();

        // Defined in the user field control tag
        $node_list = $fax_control->getElementsByTagName("BarcodeControl");
        if($node_list->length != 1)
        {
            return true;
        }
        $barcode_control = $node_list->item(0);

        // first we ignore the BarcodesRead which won't give us anything

        // Get the node that contains the array
        $node_list = $barcode_control->getElementsByTagName("Barcodes");
        if($node_list->length != 1)
        {
            // this is probably an error, but we skip it silently
            return true;
        }
        $barcodes = $node_list->item(0);

        // Get the array itself
        $node_list = $barcodes->getElementsByTagName("Barcode");
        $max = $node_list->length;
        for($idx = 0; $idx < $max; ++$idx)
        {
            $barcode = $node_list->item($idx);
            $this->inbound_barcodes[$idx] = new eFaxBarcode($barcode);
        }

        return true;
    }

    /** \brief Retrieve the fax pages.
     *
     * This function retrieves the pages from the fax. This includes
     * the content, type, and the page number when available.
     *
     * The result is saved in the \c $files array and can be retrieved
     * with the \c eFax::get_result_files() function.
     *
     * The function works whether or not the pages are being split.
     * The result in the eFax object is the same (outside of the fact
     * that there will be a single document when not split.)
     *
     * \param[in] $fax_control   The FaxControl node.
     *
     * \return true if nothing was wrong (including no pages,)
     *            false otherwise
     *
     * \sa eFax::get_result_files()
     */
    private function parse_inbound_pages($fax_control)
    {
        $this->files = array();

        // First check for file contents, if it exists, then we're
        // done (the file is being split) otherwise, we check the
        // page content control tag instead.
        $node_list = $fax_control->getElementsByTagName("FileContents");
        if($node_list->length == 1)
        {
            // Save the data in our array of files
            $this->files[] = array(
                    "type" => $this->inbound_file_type,
                    "contents" => base64_decode($node_list->item(0)->textContent)
                );
            return true;
        }

        $node_list = $fax_control->getElementsByTagName("PageContentControl");
        if($node_list->length != 1)
        {
            // no page data?!
            return true;
        }
        $page_content_control = $node_list->item(0);

        $node_list = $page_content_control->getElementsByTagName("Pages");
        if($node_list->length != 1)
        {
            // no page data?!
            return true;
        }
        $pages = $node_list->item(0);

        $node_list = $pages->getElementsByTagName("Page");
        $max = $node_list->length;
        for($idx = 0; $idx < $max; ++$idx)
        {
            $page = $node_list->item($idx);
            $node_child = $page->getElementsByTagName("PageNumber");
            if($node_child->length != 1)
            {
                continue;
            }
            $page_number = $node_child->item(0);

            $node_child = $page->getElementsByTagName("PageContents");
            if($node_child->length != 1)
            {
                continue;
            }
            $page_contents = $node_child->item(0);

            $this->files[] = array(
                    "type" => $this->inbound_file_type,
                    "page" => $page_number->textContent,
                    "contents" => base64_decode($page_contents->textContent)
                );
        }

        return true;
    }

    /** \brief Create the XML message.
     *
     * This function generates the XML message that is to be sent to eFax.
     *
     * \return The XML message
     */
    function message()
    {
        // Start
        $result = "<?xml version=\"1.0\"?" . "><OutboundRequest>";

        // Access Control
        $result .= "<AccessControl>" . $this->access_control_tags() . "</AccessControl>";

        // Transmission
        $result .= "<Transmission>" . $this->transmission_tags() . "</Transmission>";

        // Done.
        $result .= "</OutboundRequest>";

        return $result;
    }

    /** \brief Generate the access control tags.
     *
     * This function generates the UserName and Password tags.
     *
     * \exception eFaxException is raised if the user name or password
     * parameters were not defined.
     *
     * \return A string with the user name and password tags.
     */
    private function access_control_tags()
    {
        if(is_null($this->user_name) || is_null($this->user_password))
        {
            throw new eFaxException("user and password must be defined in access_control_tags().");
        }

        return "<UserName>{$this->user_name}</UserName>"
            . "<Password>{$this->user_password}</Password>";
    }

    /** \brief Generate the Transmission tags
     *
     * This function generates all the required and optional transmission
     * flags necessary to create a valid transmission tag in the eFax
     * request.
     *
     * \return A string with all the transmission tags.
     *
     * \sa eFax::transmission_control_tags()
     * \sa eFax::disposition_control_tags()
     * \sa eFax::recipients_tags()
     * \sa eFax::files_tags()
     */
    private function transmission_tags()
    {
        // Transmission Control
        $result = $this->transmission_control_tags();

        // Disposition Control
        $result .= $this->disposition_control_tags();

        // Recipients
        $result .= $this->recipients_tags();

        // Files
        $result .= $this->files_tags();

        return $result;
    }

    /** \brief Generate the transmission control tags.
     *
     * This function generates the transmission tags. Many are
     * optional. If still undefined, then this function do not
     * generate the corresponding tag. It expects that eFax will
     * do the right thing if the tag is not defined. In general,
     * it will be undefined by default. You can also set some
     * options to the value 'null'.
     *
     * This function generates the transmission identifier,
     * no duplicates, resolution, priority, self-busy and
     * fax header tags.
     *
     * \exception eFaxException is raised if duplicate identifiers
     * are forbidding and no identifier was defined. Note that since
     * we do not have any database or similar access, we cannot
     * guarantee that the identifier is unique.
     *
     * \return A string with all the transmission tags defined.
     *
     * \sa eFax::transmission_tags()
     */
    private function transmission_control_tags()
    {
        if($this->duplicate_id === false && is_null($this->fax_id))
        {
            throw new eFaxException("a fax identifier is required when duplicate identifiers are forbidden in transmission_control_tags().");
        }

        $result = "<TransmissionControl>";
            if(!is_null($this->fax_id))
            {
                $result .= "<TransmissionID>{$this->fax_id}</TransmissionID>";
            }
            if(!is_null($this->duplicate_id))
            {
                $result .= "<NoDuplicates>" . ($this->duplicate_id ? "DISABLE" : "ENABLE") . "</NoDuplicates>";
            }
            $result .= "<Resolution>{$this->resolution}</Resolution>";
            if(!is_null($this->priority))
            {
                $result .= "<Priority>{$this->priority}</Priority>";
            }
            if(!is_null($this->self_busy))
            {
                $result .= "<SelfBusy>" . ($this->self_busy ? "ENABLE" : "DISABLE") . "</SelfBusy>";
            }
            if(!is_null($this->fax_header))
            {
                $result .= "<FaxHeader>{$this->fax_header}</FaxHeader>";
            }
        $result .= "</TransmissionControl>";

        return $result;
    }

    /** \brief Generates all the disposition control tags.
     *
     * This function generates the disposition control tags.
     *
     * These include the return URL, the level (when a reply is
     * expected), the method and senders email addresses.
     *
     * \return A string with all the disposition control tags.
     *
     * \sa eFax::transmission_tags()
     */
    private function disposition_control_tags()
    {
        $result = "<DispositionControl>";
            if(!is_null($this->disposition_url))
            {
                $result .= "<DispositionURL>{$this->disposition_url}</DispositionURL>";
            }
            $result .= "<DispositionLevel>";
                switch($this->disposition_level)
                {
                case 0:
                    $result .= "NONE";
                    break;

                case eFax::RESPOND_SUCCESS:
                    $result .= "SUCCESS";
                    break;

                case eFax::RESPOND_ERROR:
                    $result .= "ERROR";
                    break;

                case eFax::RESPOND_SUCCESS | eFax::RESPOND_ERROR:
                    $result .= "BOTH";
                    break;

                }
            $result .= "</DispositionLevel>";
            if(!is_null($this->disposition_method))
            {
                $result .= "<DispositionMethod>{$this->disposition_method}</DispositionMethod>";
            }
            if(!is_null($this->disposition_language))
            {
                $result .= "<DispositionLanguage>{$this->disposition_language}</DispositionLanguage>";
            }
            if((is_null($this->disposition_method) || $this->disposition_method == "EMAIL")
            && count($this->disposition_emails) > 0)
            {
                $result .= "<DispositionEmails>";
                foreach($this->disposition_emails as $email)
                {
                    $result .= "<DispositionEmail>";
                        if(!is_null($email["name"]))
                        {
                            $result .= "<DispositionRecipient>{$email["name"]}</DispositionRecipient>";
                        }
                        $result .= "<DispositionAddress>{$email["email"]}</DispositionAddress>";
                    $result .= "</DispositionEmail>";
                }
                $result .= "</DispositionEmails>";
            }
        $result .= "</DispositionControl>";

        return $result;
    }

    /** \brief Generate the recipient tags.
     *
     * This function generates the array of recipients with their
     * name, company name and fax phone number.
     *
     * \exception eFaxException is raised if the \c eFax::add_recipient()
     * function was not called before hand.
     *
     * \return A string with all the recipient tags
     *
     * \sa eFax::add_recipient()
     */
    private function recipients_tags()
    {
        if(count($this->recipients) == 0)
        {
            throw new eFaxException("to send a fax, we need at least one fax phone number in recipients_tags().");
        }

        $result = "<Recipients>";
        foreach($this->recipients as $recipient)
        {
            $result .= "<Recipient>";
            if(!is_null($recipient["name"]))
            {
                $result .= "<RecipientName>{$recipient["name"]}</RecipientName>";
            }
            if(!is_null($recipient["company"]))
            {
                $result .= "<RecipientCompany>{$recipient["company"]}</RecipientCompany>";
            }
                $result .= "<RecipientFax>{$recipient["fax"]}</RecipientFax>";
            $result .= "</Recipient>";
        }
        $result .= "</Recipients>";

        return $result;
    }

    /** \brief Generate the file tags.
     *
     * This function goes through the array of files that have been
     * added with the \c eFax::add_file() function call and generate the
     * corresponding set of tags to produce an eFax compatible
     * XML description.
     *
     * \exception eFaxException is raised if no files were defined in
     * this eFax object. This happens if \c eFax::add_file() is never called.
     *
     * \return The set of Files tags.
     *
     * \sa eFax::add_file()
     */
    private function files_tags()
    {
        if(count($this->files) == 0)
        {
            throw new eFaxException("to send a fax, we need at least one file in files_tags().");
        }

        $result = "<Files>";
        foreach($this->files as $file)
        {
            $result .= "<File>";
                $result .= "<FileContents>" . base64_encode($file["contents"]) . "</FileContents>";
                $result .= "<FileType>{$file["type"]}</FileType>";
            $result .= "</File>";
        }
        $result .= "</Files>";

        return $result;
    }

    /** \brief Set the URL used to send the fax.
     *
     * This function sets the URL used to send the fax. The eFax class
     * sets that variable to a default value that is the URL defined by
     * eFax as their fax server.
     *
     * \exception eFaxException is raised whenever the parameter is not
     * a string of at least 12 characters.
     *
     * \param[in] $url The new URL to use to send the fax
     */
    function set_outbound_url($url)
    {
        if(!is_string($url) || strlen($url) < 12)
        {
            throw new eFaxException("invalid parameter calling set_outbound_url().");
        }
        $this->outbound_url = $url;
    }

    /** \brief Set the account identifier.
     *
     * This function is used to set the eFax account identifier. The
     * identifier is your eFax Developer fax number.
     *
     * The account identifier is required if you need to send a fax.
     * It is not necessary if you just need to parse a disposition.
     *
     * This is put in the \c $_POST['id'] variable of the request to the
     * eFax Developer website.
     *
     * \note
     * Only digits must be presented to the eFax Developer interface.
     * This function will automatically remove parenthesis, pluses,
     * dashes and spaces if any. Also, the number is not expected to
     * include a leading 1. If present, it is removed.
     *
     * \code
     * "+1 (916) 988-1450" becomes "9169881450"
     * "916-988-1450" becomes "9169881450"
     * \endcode
     *
     * \exception eFaxException is raised if \p $account_id is an array
     * or an object.
     *
     * \param[in] $account_id The eFax account identifier
     *
     * \sa eFax::set_raw_account_id()
     * \sa eFax::set_user_name()
     * \sa eFax::set_user_password()
     */
    function set_account_id($account_id)
    {
        if(is_null($account_id) || is_array($account_id) || is_object($account_id))
        {
            throw new eFaxException("invalid parameter calling set_account_id().");
        }
        $id = str_replace(array(' ', '+', '-', '(', ')'), array('', '', '', '', ''), $account_id);
        if($id[0] == '1' && strlen($id) == 11)
        {
            // drop the leading 1, eFax developer does not want it.
            $id = substr($id, 1);
        }
        $this->account_id = $id;
    }

    /** \brief Set the account identifier.
     *
     * This function is used to set the eFax account identifier. The
     * identifier is your eFax Developer fax number.
     *
     * The account identifier is required if you need to send a fax.
     * It is not necessary if you just need to parse a disposition.
     *
     * This function uses the \p $account_id as is. Please, use the
     * \c eFax::set_account_id() function to make sure that the identifier
     * does not include invalid characters.
     *
     * \note
     * This function was added so special identifiers that are otherwise
     * tempered with by the \c eFax::set_account_id() function can be used.
     *
     * \exception eFaxException is raised if \p $account_id is an array
     * or an object.
     *
     * \param[in] $account_id    The eFax account identifier.
     *
     * \sa eFax::set_account_id()
     * \sa eFax::set_user_name()
     * \sa eFax::set_user_password()
     */
    function set_raw_account_id($account_id)
    {
        if(is_null($account_id) || is_array($account_id) || is_object($account_id))
        {
            throw new eFaxException("invalid parameter calling set_account_id().");
        }
        $this->account_id = $account_id;
    }

    /** \brief Set the user name.
     *
     * This function saves your user name (name used to login.)
     *
     * The user name is required. Without a user name you cannot login
     * and the request fails.
     *
     * For outbound messages, the user name is saved in:
     *
     * \code
     * OutboundRequest/AccessControl/UserName
     * \endcode
     *
     * For inbound messages, the user name is expected in:
     *
     * \code
     * InboundPostReques/AccessControl/UserName
     * \endcode
     *
     * \note
     * The outbound user name to use here is the user name that you use to
     * login in your eFax Developer account here:
     *
     * \par
     * https://secure.efaxdeveloper.com/EFax_UnifiedLogin.serv
     *
     * \note
     * The inbound user name is defined in the Inbound Settings of your
     * eFax account. It can be set to the same user name as your outbound
     * user name, yet we suggest that you choose a different user name.
     *
     * \bug
     * If your user name includes any blank character (space, new line, return,
     * tabs, etc.), it is not impossible that it will fail because the data is
     * being transferred in an XML file.
     *
     * \exception eFaxException is raised if the \p $user_name is not
     * a valid string. This means it is not null, not an array, not
     * an object and has a length of at most 20 characters.
     *
     * \param[in] $user_name   The login name used to connect to eFax.
     *
     * \sa eFax::set_account_id()
     * \sa eFax::set_raw_account_id()
     * \sa eFax::set_user_password()
     */
    function set_user_name($user_name)
    {
        if(is_null($user_name) || is_array($user_name) || is_object($user_name)
        || strlen($user_name) > 20)
        {
            throw new eFaxException("invalid parameter calling set_user_name().");
        }
        $this->user_name = $user_name;
    }

    /** \brief Set the user password.
     *
     * This function saves your user password.
     *
     * The password is required. Without a password you cannot login
     * and the request fails.
     *
     * For outbound messages, the password is saved in:
     *
     * \code
     * OutboundRequest/AccessControl/Password
     * \endcode
     *
     * For inbound messages, the password is expected in:
     *
     * \code
     * InboundPostReques/AccessControl/Password
     * \endcode
     *
     * \note
     * The outbound password to use here is the password that you use to
     * login in your eFax Developer account here:
     *
     * \par
     * https://secure.efaxdeveloper.com/EFax_UnifiedLogin.serv
     *
     * \note
     * The inbound password is defined in the Inbound Settings of your
     * eFax account. It can be set to the same password as your outbound
     * password, yet we suggest that you choose a different password.
     *
     * \bug
     * If your password includes any blank character (space, new line, return,
     * tabs, etc.), it is not impossible that it will fail because the data is
     * being transferred in an XML file.
     *
     * \exception eFaxException is raised if the \p $user_password is not
     * a valid string. This means it is not null, not an array, not
     * an object and has a length of at most 20 characters.
     *
     * \param[in] $user_password The password used to connect to eFax
     *
     * \sa eFax::set_account_id()
     * \sa eFax::set_raw_account_id()
     * \sa eFax::set_user_name()
     */
    function set_user_password($user_password)
    {
        if(is_null($user_password) || is_array($user_password) || is_object($user_password)
        || strlen($user_password) > 20)
        {
            throw new eFaxException("invalid parameter to set_user_password().");
        }
        $this->user_password = $user_password;
    }

    /** \brief Set this fax request identifier.
     *
     * This function saves the request identifier used for this fax.
     * For instance, if the fax represents an order, you can use
     * the order number as this fax identifier.
     *
     * This identifier is optional. If none are defined, then none
     * are sent.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/TransmissionControl/TransmissionID
     * \endcode
     *
     * \exception eFaxException is raised if the parameter fax_id
     * is not null, a string or a numeric, or if the length is more
     * than 15 characters.
     *
     * \param[in] fax_id The identifier used to identify this fax
     */
    function set_fax_id($fax_id)
    {
        if((!is_null($fax_id) && !is_string($fax_id) && !is_numeric($fax_id))
        || strlen("" . $fax_id) > 15)
        {
            throw new eFaxException("invalid parameter to set_fax_id().");
        }
        $this->fax_id = $fax_id;
    }

    /** \brief Set whether fax identifiers can be duplicated.
     *
     * This function can be used to determine whether the same fax
     * identifier can be used multiple times (in several different
     * requests.)
     *
     * This identifier is optional. If this option is not set (i.e. null,)
     * then identifiers can be duplicated.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/TransmissionControl/NoDuplicates
     * \endcode
     *
     * \note
     * The value 'true' means that you can duplicate the identifiers.
     * The value 'false' means that you cannot duplicate the identifiers.
     * Note that this is inverted from the NoDuplicates definition which
     * is because the name starts with "No".
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not true, false or null.
     *
     * \param[in] $duplicate_id One of true, false or null.
     */
    function set_duplicate_id($duplicate_id)
    {
        if(is_null($duplicate_id) || is_bool($duplicate_id))
        {
            $this->duplicate_id = $duplicate_id;
        }
        else
        {
            throw new eFaxException("invalid parameter to set_duplicate_id().");
        }
    }

    /** \brief Set the resolution used for transmission.
     *
     * This function is used to set the transmission resolution.
     * The resolution can be set to "STANDARD" or "FINE". Note
     * that in general, eFax charges more for the "FINE" option.
     *
     * The resolution is required. It is set to "STANDARD" by
     * default so you do not need to set it if you want to send
     * a standard fax.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/TransmissionControl/Resolution
     * \endcode
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not one of "STANDARD" or "FINE".
     *
     * \param[in] $resolution "STANDARD" or "FINE"
     */
    function set_resolution($resolution)
    {
        $resolution = strtoupper($resolution);
        if($resolution == "STANDARD" || $resolution == "FINE")
        {
            $this->resolution = $resolution;
        }
        else
        {
            throw new eFaxException("invalid parameter to set_resolution().");
        }
    }

    /** \brief Set the priority for this transmission.
     *
     * This function sets the priority of the transmission to one
     * of NORMAL or HIGH.
     *
     * This parameter is optional. The default is NORMAL.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/TransmissionControl/Priority
     * \endcode
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not one of "NORMAL" or "HIGH".
     *
     * \param[in] $priority "NORMAL" or "HIGH"
     */
    function set_priority($priority)
    {
        if(is_string($priority))
        {
            $priority = strtoupper($priority);
        }
        if(is_null($priority) || $priority === "NORMAL" || $priority === "HIGH")
        {
            $this->priority = $priority;
        }
        else
        {
            throw new eFaxException("invalid parameter to set_priority().");
        }
    }

    /** \brief Queue faxes sent to the same number.
     *
     * This function defines whether the phone number can accept multiple
     * connections at once or not. If you do not know, it is recommended
     * that you set this value to true (which is the default).
     *
     * SelfBusy is optional. If this option is not set, then faxes will
     * be queued instead of sent when eFax is already sending a fax at
     * the same number.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/TransmissionControl/SelfBusy
     * \endcode
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not true, false or null.
     *
     * \param[in] $self_busy One of true, false or null.
     */
    function set_self_busy($self_busy)
    {
        if(is_null($self_busy) || is_bool($self_busy))
        {
            $this->self_busy = $self_busy;
        }
        else
        {
            throw new eFaxException("invalid parameter to set_self_busy().");
        }
    }

    /** \brief Defines the fax header.
     *
     * This function can be used to define the fax header.
     *
     * eFax will provide a default if not specified.
     *
     * The fax header is a string that can include \@\<name> and
     * \%\<name> dynamic parameters. The \@\<name> parameters are
     * usually specific to one fax. When the \%\<name> parameters
     * are specific to each page of a fax.
     *
     * The available dynamic fax header variables are:
     *
     * \@DATEx where x is a digit defining the format of the eFax
     * server date (i.e. Pacific Time). \c y represents the year, \c m the
     * month in digits, \c x the month in letters, \c d the day. All formats
     * imply a leading 0 to years, months and days.
     *
     * \code
     *    \@DATE0        yyyymmdd
     *    \@DATE1        mm/dd/yy
     *    \@DATE2        dd/mm/yy
     *    \@DATE3        dd/xx/yy
     *    \@DATE4        mm/dd/yyyy
     *    \@DATE5        dd xxx yyyy
     *    \@DATE6        xxxxx dd, yyyy
     *    \@DATE7        yy mm dd
     *    \@DATE8        yy-mm-dd
     *    \@DATE9        yymmdd
     * \endcode
     *
     * \@TIMEx where x is a digit defining the format of the eFax
     * server time (i.e. Pacific Time.) h represents the hour, m the
     * minute, s the second, xx the AM/PM letters.
     *
     * \code
     *    \@TIME1        hh:mm
     *    \@TIME2        hh:mm:ss
     *    \@TIME3        hh:mmxx
     *    \@TIME4        hhmm
     * \endcode
     *
     * \@ROUTETO{n} is replaced by the name of the recipient name and company.
     * The value 'n' represents the maximum number of characters that
     * will be printed in the header (i.e. "\@DATE3 \@TIME2 \@ROUTETO{20}"
     * would print the date, time and up to 20 letters of the recipient
     * name and recipient company name.) That name is looked up in the XML
     * data:
     *
     * \code
     * OutboundRequest/Transmission/Recipients/Recipient/RecipientName
     * OutboundRequest/Transmission/Recipients/Recipient/RecipientCompany
     * \endcode
     *
     * \@RCVRFAX is replaced by the fax number of the recipient. This is
     * taken from the XML data:
     *
     * \code
     * OutboundRequest/Transmission/Recipients/Recipient/RecipientFax
     * \endcode
     *
     * \@SPAGES is the total number of pages for this fax including the
     * cover sheet if there is one.
     *
     * \%P is the current page number.
     *
     * \%nf is used to defines the size of the font. 'n' must be replaced
     * with a digit (0 through 3, i.e. \%2f) to indicate the size. 0 is the
     * default and is the largest font. 3 is the smallest font. This tag
     * can appear as many times as required in the header.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/TransmissionControl/FaxHeader
     * \endcode
     *
     * \note
     * Set this variable to " " (one space) if you do not want to have a header.
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not a string or null or if the string is over 80 characters.
     *
     * \bug
     * At this time, the input string is not checked for correctness.
     *
     * \param[in] $fax_header A string with the header information or null.
     */
    function set_fax_header($fax_header)
    {
        if(is_null($fax_header) || is_string($fax_header))
        {
            if(is_string($fax_header) && strlen($fax_header) > 80)
            {
                throw new eFaxException("string too long in set_fax_header()--limit is 80 characters.");
            }
            $this->fax_header = $fax_header;
        }
        else
        {
            throw new eFaxException("invalid parameter to set_fax_header().");
        }
    }

    /** \brief Defines the return URL to signal disposition of the request.
     *
     * This function is used to define the URL where the results of the
     * request will be returned.
     *
     * Your eFax account lets you define a default URL. This option lets
     * you define the URL dynamically. However, be careful since the URL
     * is limited to only 100 characters.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/DispositionControl/DispositionURL
     * \endcode
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not a string or null or if the string is over 100 characters.
     *
     * \bug
     * At this time, the input string is not checked for correctness.
     * (i.e. a valid URL.)
     *
     * \param[in] $disposition_url A string with the header information or null.
     */
    function set_disposition_url($disposition_url)
    {
        if(is_null($disposition_url) || is_string($disposition_url))
        {
            if(is_string($disposition_url) && strlen($disposition_url) > 100)
            {
                throw new eFaxException("string too long in set_disposition_url()--limit is 100 characters.");
            }
            $this->disposition_url = $disposition_url;
        }
        else
        {
            throw new eFaxException("invalid parameter to set_disposition_url().");
        }
    }

    /** \brief Defines the type of reply.
     *
     * This function is used to define the type of reply this request
     * will generate.
     *
     * By default, this class will force replies for both successful and
     * erroneous request. It is strongly encouraged to keep it that way.
     * The eFax default is NONE (i.e. zero--no reply whatsoever.)
     *
     * It is possible to request no response at all.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/DispositionControl/DispositionLevel
     * \endcode
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not one of 0, eFax::RESPOND_ERROR, eFax::RESPOND_SUCCESS
     * or a mix of these flags.
     *
     * \param[in] $disposition_level Logical OR of 0, eFax::RESPOND_ERROR and eFax::RESPOND_SUCCESS
     */
    function set_disposition_level($disposition_level)
    {
        if(is_numeric($disposition_level))
        {
            if(($disposition_level & ~(eFax::RESPOND_ERROR | eFax::RESPOND_SUCCESS)) != 0)
            {
                throw new eFaxException("invalid flags set in set_disposition_level().");
            }
            $this->disposition_level = $disposition_level;
        }
        else
        {
            throw new eFaxException("invalid parameter to set_disposition_level().");
        }
    }

    /** \brief Defines the method to use to send the reply.
     *
     * This function defines the method used to send back the reply.
     * The reply can be sent using either a POST or an EMAIL.
     *
     * This parameter is optional. The default is POST. Note that
     * more often than not, emails are not secure.
     *
     * IMPORTANT NOTE: The POST method requires you to have a secure
     * web server (HTTPS protocol) with a valid certificate (one
     * that eFax Developer computer can check automatically.)
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/DispositionControl/DispositionMethod
     * \endcode
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not one of null, "POST", "EMAIL" or "NONE".
     *
     * \param[in] $method One of null, "POST", "EMAIL" or "NONE".
     */
    function set_disposition_method($method)
    {
        if(is_string($method))
        {
            $method = strtoupper($method);
        }
        if(is_null($method) || $method == "POST"
        || $method == "EMAIL" || $method == "NONE")
        {
            $this->disposition_method = $method;
        }
        else
        {
            throw new eFaxException("invalid parameter to set_disposition_method().");
        }
    }

    /** \brief Defines the disposition language.
     *
     * This function defines the language to be used in the disposition
     * message. By default (if undefined) the language is English.
     *
     * Currently supported languages are:
     *
     * \li en -- English
     * \li de -- German
     * \li es -- Spanish
     * \li fr -- French
     * \li it -- Italian
     * \li nl -- Dutch
     * \li pl -- Polish
     * \li pt -- Portuguese
     *
     * You may set the language to null to get the default.
     *
     * \exception eFaxException is thrown whenever the parameter
     * is not one of the supported languages.
     *
     * \param[in] $language One of the 2 letter name of the supported languages.
     */
    function set_disposition_language($language)
    {
        if(is_string($language))
        {
            $language = strtolower($language);
        }
        if(is_null($language))
        {
            $this->disposition_language = null;
        }
        else
        {
            switch ($language)
            {
            case "en":
            case "de":
            case "es":
            case "fr":
            case "it":
            case "nl":
            case "pl":
            case "pt":
                $this->disposition_language = $language;
                break;

            default:
                throw new eFaxException("invalid parameter to set_disposition_language().");

            }
        }
    }

    /** \brief Add an email address for the response.
     *
     * This function adds one email address where the response will be
     * sent. As many email addresses as you want can be included.
     *
     * The default is no email addresses. However, your eFax setup may
     * include an email address that is used by default whenever the
     * disposition method is set to EMAIL.
     *
     * When you add an email address, the recipient name is optional.
     * This function is not capable of separating the recipient name
     * from the email address. You must do that before calling this
     * function.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/DispositionControl/DispositionEmails
     * OutboundRequest/Transmission/DispositionControl/DispositionEmails/DispositionEmail
     * OutboundRequest/Transmission/DispositionControl/DispositionEmails/DispositionEmail/DispositionRecipient
     * OutboundRequest/Transmission/DispositionControl/DispositionEmails/DispositionEmail/DispositionAddress
     * \endcode
     *
     * \bug
     * If the disposition method is not EMAIL then these emails will
     * NOT be included in the output XML message. The system does not
     * generate an error or anything like that in such circumstances.
     *
     * \bug
     * The email address is not checked. You are responsible to ensure
     * its validity.
     *
     * \bug
     * If the same email address is added multiple times, then the
     * disposition will be sent multiple times.
     *
     * \exception eFaxException is thrown whenever the parameters
     * are not null or strings.
     *
     * \param[in] $name The name of the recipient (can be null)
     * \param[in] $email The email of the recipient (cannot be null)
     */
    function add_disposition_email($name, $email)
    {
        if((is_null($name) || is_string($name)) && is_string($email))
        {
            // should we check for double definitions?
            $this->disposition_emails[] = array(
                        "name" => $name,
                        "email" => $email
                    );
        }
        else
        {
            throw new eFaxException("invalid parameter to add_disposition_email().");
        }
    }

    /** \brief Add a recipient name, company and fax number.
     *
     * This function adds one recipient to the list of recipients of this
     * fax. The name and company name of the recipient are optional. The
     * fax number is mandatory.
     *
     * International fax numbers are dialed from the USA and must start
     * with 011 and the country code character. The format of the fax phone
     * number is "[-+ ()0-9]{5,25}". All characters other than digits are
     * ignored. At least 5 characters and at most 25 are necessary.
     *
     * The request identifier is saved in:
     *
     * \code
     * OutboundRequest/Transmission/Recipients
     * OutboundRequest/Transmission/Recipients/Recipient
     * OutboundRequest/Transmission/Recipients/Recipient/RecipientName
     * OutboundRequest/Transmission/Recipients/Recipient/RecipientCompany
     * OutboundRequest/Transmission/Recipients/Recipient/RecipientFax
     * \endcode
     *
     * \exception eFaxException is thrown whenever the parameters
     * are not null, numeric or strings, a name is more than 50 characters
     * or the fax number is less than 5 or more than 25 characters.
     *
     * \param[in] $name The name of the fax recipient (can be null)
     * \param[in] $company The name of the fax recipient company (can be null)
     * \param[in] $fax The fax number where the fax is being sent (cannot be null)
     */
    function add_recipient($name, $company, $fax)
    {
        // TODO: should we remove the first character if it is 1?
        // (as in +1 (916) 988-1450) Since we do not support local
        // numbers, it should work.
        $fax = str_replace(array(' ', '+', '-', '(', ')'), array('', '', '', '', ''), $fax);

        if((!is_null($name) && strlen($name) > 50)
        || (!is_null($company) && strlen($company) > 50)
        || is_null($fax) || strlen($fax) < 5 || strlen($fax) > 25)
        {
            throw new eFaxException("invalid parameter to add_recipient().");
        }

        $this->recipients[] = array(
                    "name" => $name,
                    "company" => $company,
                    "fax" => $fax
                );
    }

    /** \brief Add a file: the content of the fax.
     *
     * This function adds one file to the content of the fax. There can be
     * as many files as necessary to print out your entire fax. Each file
     * can have a different format. The format must be correct for the data
     * or the fax will fail.
     *
     * The data parameter is the actual content of the file. This function
     * will NOT read the content of a file on disk or otherwise. The function
     * will encode the content for you (base64). You can use the file_get_contents()
     * function to read a file from disk and pass it to this function as a
     * string.
     *
     * \code
     * $efax->add_file('pdf', file_get_contents('myfile.pdf'));
     * \endcode
     *
     * Supported file formats:
     *
     * \code
     * doc        MS-Word
     * docx        MS-Word (new XML format)
     * gif        Graphics Interchange Format (a GIF image)
     * jpg or jpeg    Joint Photographic Experts Group (a JPEG image)
     * htm or html    HTML file
     * pdf        Postscript Description Format file
     * ppt        PowerPoint
     * pptx        PowerPoint (new XML format)
     * rtf        Rich Text Format
     * snp        Microsoft Access Report Snapshots
     * tif or tiff    Tag Interchange File Format (a TIFF image)
     * txt        Text only file
     * xls        Excel spreadsheet
     * xlsx        Excel spreadsheet (new XML format)
     * \endcode
     *
     * \exception eFaxException is thrown if the file type is not one supported
     * by eFax. Note that the names can be either all lowercase or all uppercase.
     *
     * \param[in] $type One of: doc, xls, tif, pdf, txt, html, htm or rtf.
     * \param[in] $contents The contents of the file (NO file is read from disk!)
     */
    function add_file($type, $contents)
    {
        $type = strtolower($type);
        switch($type)
        {
        case "doc":
        case "docx":
        case "gif":
        case "jpg": case "jpeg":
        case "html": case "htm":
        case "pdf":
        case "png":
        case "ppt":
        case "pptx":
        case "rtf":
        case "snp":
        case "tif": case "tiff":
        case "txt":
        case "xls": case "xlsx":
            break;

        default:
            throw new eFaxException("invalid \x24type parameter '$type' to add_file().");

        }
        // we do the base64 when generating the XML since it take more
        // space that we do not need to waste here
        $this->files[] = array(
                    "type" => $type,
                    "contents" => $contents
                );
    }

    /** \brief The response of the last \c eFax::send() command.
     *
     * This function returns the raw response of the last \c eFax::send()
     * command.
     *
     * \note
     * If the \c eFax::send() does not send anything, then the response
     * will be "not sent" when it happens before the HTTP POST
     * or the empty string if no reply is received from the
     * \c eFax::send().
     *
     * \return The response of the eFax server from the last \c eFax::send()
     */
    function get_response()
    {
        return $this->response;
    }

    /** \brief Get the status of the last call to \c eFax::send().
     *
     * This function returns the status that the last call to \c eFax::send()
     * generated. By default, the status is set to 0 meaning that
     * \c eFax::send() was never called. Once \c eFax::send() returns with no exception,
     * then status will be set to either 1 (success) or 2 (failure).
     *
     * When the status is 2, the \c eFax::get_error_level() and \c eFax::get_error_description()
     * functions can be used to retrieve more information about the error.
     *
     * \return The \c eFax::send() command status on exit.
     */
    function get_status()
    {
        return $this->status;
    }

    /** \brief Return the document identifier after a \c eFax::send().
     *
     * This function returns the document identifier found in the
     * outbound response message. This can be reused later to
     * request status information and when the final disposition
     * is sent to us.
     *
     * \return The document identifier.
     */
    function get_docid()
    {
        return $this->docid;
    }

    /** \brief Get the last error level.
     *
     * This function returns the level of the last error that occurred
     * after a request was sent to eFax.
     *
     * \return The error level; one of User or System.
     */
    function get_error_level()
    {
        return $this->error_level;
    }

    /** \brief Get the last error description.
     *
     * This function returns the description of the last error that occurred
     * after a request was sent to eFax. This is a string of undefined length.
     * It is intended for display and will be in English.
     *
     * \return The error description in plain English.
     */
    function get_error_description()
    {
        return $this->error_description;
    }

    /** \brief Return the fax identifier.
     *
     * This function returns the fax identifier found in the
     * disposition message or the fax request message. It is
     * extracted from the message with a call to \c eFax::parse_disposition()
     * or \c eFax::parse_inbound_message().
     *
     * The disposition has a fax identifier only if you called the
     * \c eFax::set_fax_id() before sending a fax with \c eFax::send(). This is that
     * identifier that you probably want to make unique.
     *
     * The fax request message sets the fax identifier to your account
     * identifier (InboundPostRequest/FaxControl/AccountID). If you
     * are handling multiple eFax accounts, it can be quite handy to
     * check this identifier to know which number was used.
     *
     * \note
     * If the fax identifier is not available in the FaxControl, then
     * the special string 'n.a.' is returned. Note that this could be
     * viewed as an error since the AccountID tag is marked as mandatory.
     *
     * \exception eFaxException when you call this function before a
     * successful call to \c eFax::parse_disposition() or \c eFax::parse_inbound_message().
     *
     * \return The fax identifier, an empty string or null.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     * \sa eFax::set_fax_id()
     * \sa eFax::send()
     */
    function get_result_fax_id()
    {
        if(is_null($this->result_fax_id))
        {
            throw new eFaxException("get_result_fax_id() called before parse_disposition() or parse_inbound_message()");
        }
        return $this->result_fax_id;
    }

    /** \brief Return the document identifier from a disposition or inbound request.
     *
     * This function returns the document identifier found in the
     * disposition message or inbound request.
     *
     * This identifier is automatically assigned by eFax to every
     * single fax sent via eFax. This is the same as the identifier
     * returned in the outbound response when you sent a fax.
     *
     * \note
     * Also not actually written anywhere in the eFax documents, this
     * identifier is most certainly unique among all the faxes you will
     * ever send or receive.
     *
     * \exception eFaxException when you call this function before a
     * successful call to \c eFax::parse_disposition() or \c eFax::parse_inbound_message().
     *
     * \return The document identifier.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_docid()
    {
        if(is_null($this->result_docid))
        {
            throw new eFaxException("get_result_docid() called before parse_disposition() or parse_inbound_message()");
        }
        return $this->result_docid;
    }

    /** \brief Return the fax number at the other end.
     *
     * This function returns the fax number at the other end.
     * It may be found in the disposition message or the inbound
     * fax request.
     *
     * Note that you will receive one disposition per fax number defined
     * in the outbound request and each one will have the same identifiers
     * (fax identifier and document identifier.) Thus, if you need to
     * distinguish each disposition, it is necessary to also check the
     * fax number. (Although it may have been blocked and thus be undefined.)
     *
     * \exception eFaxException when you call this function before a
     * successful call to \c eFax::parse_disposition() or \c eFax::parse_inbound_message().
     *
     * \return The fax number used in link with this disposition, may be
     * empty or 'Unknown' or some other label when it cannot be determined.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_fax_number()
    {
        if(is_null($this->result_fax_number))
        {
            throw new eFaxException("get_result_fax_number() called before parse_disposition() or parse_inbound_message()");
        }
        return $this->result_fax_number;
    }

    /** \brief Return the date and time when the transmission ended.
     *
     * This function returns a Unix timestamp with the date and time when
     * the transmission of the fax ended. The timestamp is defined
     * in UTC.
     *
     * The \c eFax::parse_inbound_message() may or may not return a completion
     * date field. If so, this function returns -1.
     *
     * \exception eFaxException is raised when you call this function before a
     * successful call to \c eFax::parse_disposition() or \c eFax::parse_inbound_message().
     *
     * \bug
     * This value may not be set. Please, check whether the returned
     * reference is -1 before using the Unix timestamp.
     *
     * \return The Unix timestamp when the fax was 100% sent or received
     *            or -1 when not available
     */
    function get_result_completion_date()
    {
        if(is_null($this->result_completion_date))
        {
            throw new eFaxException("get_result_completion_date() called before parse_disposition() or parse_inbound_message()");
        }
        if(!$this->result_completion_date)
        {
            return -1;
        }

        //           111111111
        // 0123456789012345678
        // yyyy-mm-dd hh:mm:ss
        $year = substr($this->result_completion_date,  0, 4);
        $mon  = substr($this->result_completion_date,  5, 2);
        $day  = substr($this->result_completion_date,  8, 2);
        $hour = substr($this->result_completion_date, 11, 2);
        $min  = substr($this->result_completion_date, 14, 2);
        $sec  = substr($this->result_completion_date, 17, 2);

        // create timestamp and add 8 hours
        // (at this time, it isn't clear whether DST affects this date...)
        return gmmktime($hour, $min, $sec, $mon, $day, $year) + 8 * 60 * 60;
    }

    /** \brief Return the disposition or fax request status.
     *
     * This function returns the status of the fax transmission from the
     * message parsed with a call to \c eFax::parse_disposition() or
     * \c eFax::parse_inbound_message().
     *
     * The status is zero (0) when the fax was successfully sent or
     * received. The status is an error number otherwise. Use the
     * \c eFax::get_result_error_message() function to retrieve the corresponding
     * error string.
     *
     * \exception eFaxException when you call this function before a
     * successful call to \c eFax::parse_disposition() or \c eFax::parse_inbound_message().
     *
     * \return The fax disposition or fax request status.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     * \sa eFax::get_result_error_message()
     */
    function get_result_fax_status()
    {
        if(is_null($this->result_fax_status))
        {
            throw new eFaxException("get_result_fax_status() called before parse_disposition() or parse_inbound_message()");
        }
        return $this->result_fax_status;
    }

    /** \brief Return the recipient or sender fax machine identification.
     *
     * This function returns some identification of the recipient (in
     * a disposition) or the sender (in a fax request) as defined by
     * the destination fax machine. This is not always available since
     * many fax machines do not support this feature.
     *
     * \exception eFaxException when you call this function before a
     * successful call to \c eFax::parse_disposition() or \c eFax::parse_inbound_message().
     *
     * \return The recipient fax machine identification, may be an empty string.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_csid()
    {
        if(is_null($this->result_csid))
        {
            throw new eFaxException("get_result_csid() called before parse_disposition() or parse_inbound_message()");
        }
        return $this->result_csid;
    }

    /** \brief Return the duration of the fax transmission.
     *
     * This function returns the number of seconds that the fax
     * transmission took. The time is rounded up to the closest
     * second (i.e. if the time represents 0.1 second, then 1
     * is returned.)
     *
     * \note
     * The result is given to use in minutes. We multiply that
     * number by 60. Note also that the result is a floating point
     * so you may get a number that is not a multiple of 60.
     * (i.e. 0.8 minutes represent 48 seconds.)
     *
     * \exception eFaxException when you call this function before a
     * successful call to \c eFax::parse_disposition().
     *
     * \return The time the transmission of the fax took in seconds.
     */
    function get_result_duration()
    {
        if(is_null($this->result_duration))
        {
            throw new eFaxException("get_result_duration() called before parse_disposition()");
        }
        // we want to return seconds like a Unix timestamp
        return ceil($this->result_duration * 60);
    }

    /** \brief Return the number of pages sent.
     *
     * This function returns the number of pages that have been sent.
     * If the fax transmission failed, this number indicates the number
     * of pages that have successfully been sent.
     *
     * \exception eFaxException when you call this function before a
     * successful call to \c eFax::parse_disposition() or \c eFax::parse_inbound_message().
     *
     * \return The number of pages that have successfully been sent or received.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_pages()
    {
        if(is_null($this->result_pages))
        {
            throw new eFaxException("get_result_pages() called before parse_disposition() or parse_inbound_message()");
        }
        return $this->result_pages;
    }

    /** \brief Return the number of times eFax had to dial to process the fax.
     *
     * This function returns the number of times eFax dial the fax number
     * in order to transmit the documents. A first attempt success is represented
     * by a 1 here.
     *
     * \exception eFaxException when you call this function before a
     * successful call to \c eFax::parse_disposition().
     *
     * \return The number of attempts made to send all the document pages.
     *
     * \sa eFax::parse_disposition()
     */
    function get_result_retries()
    {
        if(is_null($this->result_retries))
        {
            throw new eFaxException("get_result_retries() called before parse_disposition()");
        }
        return $this->result_retries;
    }

    /** \brief Return the error message from a status code.
     *
     * This function converts a status code in an error message in
     * English. The input status code parameter can be set to null
     * in which case the internal result status code is used from the
     * last call to the \c eFax::parse_disposition() function.
     *
     * If the status code is not known, then the function returns
     * the special error "unknown status code".
     *
     * If the function is called with null and no disposition was
     * parsed yet, the function returns "no disposition message
     * has been parsed".
     *
     * \param[in] $status The status code to convert or null
     *
     * \return The error message that corresponds to the \p $status code
     *
     * \sa eFax::parse_disposition()
     */
    function get_result_error_message($status = null)
    {
        if(is_null($status))
        {
            $status = $this->result_fax_status;
            if(is_null($status))
            {
                return "no disposition message has been parsed";
            }
        }
        $result = $this->err_messages[$status];
        if(is_null($result))
        {
            return "unknown status code";
        }
        // return the message
        return $result[1];
    }

    /** \brief Return the error class from a status code.
     *
     * This function converts a status code in an error class.
     * The status code can be set to null in which case the
     * result status code is used.
     *
     * If the status code is not known, then the function returns
     * the special class "?".
     *
     * When there was no error, the special class " " is returned
     * (a space.)
     *
     * \param[in] $status The status code to convert or null
     *
     * \return The error message that corresponds to the \p $status
     */
    function get_result_error_class($status = null)
    {
        if(is_null($status))
        {
            $status = $this->result_fax_status;
            if(is_null($status))
            {
                return "?";
            }
        }
        $result = $this->err_messages[$status];
        if(is_null($result))
        {
            return "?";
        }
        // return the class
        return $result[0];
    }

    /** \brief Get the date and time when the Inbound fax was received.
     *
     * This function returns the date and time in the form of a Unix
     * timestamp of when the XML request was sent from eFax Developer.
     *
     * If you want to know when the fax was received, use the
     * \c eFax::get_result_completion_date() function instead.
     *
     * \note
     * This variable member is set only after a valid call to
     * \c eFax::parse_inbound_message().
     *
     * \exception eFaxException is raised if the \c eFax::parse_inbound_message()
     * function was not called before hand.
     *
     * \bug
     * This value may not be set. Please, check whether the returned
     * reference is -1 before using the Unix timestamp.
     *
     * \return The Unix timestamp when the XML request was sent to your server.
     *
     * \sa eFax::get_result_completion_date()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_request_date()
    {
        if(is_null($this->inbound_date))
        {
            throw new eFaxException("get_result_request_date() called before parse_inbound_message()");
        }
        return $this->inbound_date;
    }

    /** \brief Get the type of inbound fax request.
     *
     * The eFax Developer system can send a fax on receipt (New Inbound)
     * and it can resend a fax that it received earlier (Manual Repost).
     *
     * This function let you check the type of fax you are receiving.
     *
     * \exception eFaxException is raised if the \c eFax::parse_inbound_message()
     * function was not called before hand.
     *
     * \return One of "New Inbound" or "Manual Repost", unless it is
     *            undefined in which case null is returned.
     */
    function get_result_request_type()
    {
        if(is_null($this->inbound_type))
        {
            throw new eFaxException("get_result_request_type() called before parse_inbound_message()");
        }
        return $this->inbound_type;
    }

    /** \brief Retrieve the name of the fax received.
     *
     * This function returns the name of the of fax as defined in the
     * eFax request.
     *
     * \exception eFaxException is raised if the \c eFax::parse_inbound_message()
     * function was not called before hand.
     *
     * \return The name of the fax or an empty string if not available.
     *
     * \sa eFax::parse_inbound_message()
     */
    function get_result_fax_name()
    {
        if(is_null($this->inbound_fax_name))
        {
            throw new eFaxException("get_result_fax_name() called before parse_inbound_message()");
        }
        return $this->inbound_fax_name;
    }

    /** \brief Retrieve the array of user fields.
     *
     * This function returns the array of user fields as found in the
     * inbound message.
     *
     * The keys used for the array are the field names and the content
     * of each entry is the field value. You can parse the entire array
     * with the following code:
     *
     * \code
     *    ...
     *    $fields = $efax->get_result_user_fields();
     *    foreach($fields as $name => $value)
     *    {
     *        // WARNING: the following echo is not 100% safe since the field
     *        //            name and value could be tainted
     *        echo $name, " = '", $value, "'";
     *    }
     *    ...
     * \endcode
     *
     * \exception eFaxException is raised if this function is called
     * before the \c eFax::parse_inbound_message() function.
     *
     * \bug
     * The name and value are not being modified from what they are in
     * the XML message. This means they could include anything such as
     * PHP code or SQL statements.
     *
     * \return The array of field names and values, the array may be empty.
     *
     * \sa eFax::parse_inbound_user_fields()
     */
    function get_result_user_fields()
    {
        if(is_null($this->inbound_user_fields))
        {
            throw new eFaxException("get_result_user_fields() called before parse_inbound_message()");
        }
        return $this->inbound_user_fields;
    }

    /** \brief Retrieve the array of barcode keys.
     *
     * This function retrieves the array of barcode objects as found
     * in the inbound message.
     *
     * The array is not keyed (i.e. uses numeric indices). The order
     * of the barcode objects in the array should be the same as the
     * order in which they were found in the source message. However,
     * it is strongly advised that you use the page and sequence
     * information to know for sure where a barcode is from.
     *
     * \code
     *    ...
     *    $barcodes = $efax->get_result_barcodes();
     *    foreach($barcodes as $barcode)
     *    {
     *        // WARNING: the following echo is not 100% safe since the barcode
     *        //            data could be tainted
     *        echo $barcode->get_page(), "/", $barcode->get_sequence(), " = ", $barcode->get_key();
     *    }
     *    ...
     * \endcode
     *
     * \bug
     * The values are not being modified from what they are in
     * the XML message. This means they could include anything such as
     * PHP code or SQL statements.
     *
     * \exception eFaxException is thrown whenever this function is called
     * before a successful call to the \c eFax::parse_inbound_message().
     *
     * \return The array of barcode objects, the array may be empty.
     *
     * \sa class eFaxBarcode
     * \sa eFax::parse_inbound_barcodes()
     */
    function get_result_barcodes()
    {
        if(is_null($this->inbound_barcodes))
        {
            throw new eFaxException("get_result_barcodes() called before parse_inbound_message()");
        }
        return $this->inbound_barcodes;
    }

    /** \brief Retrieve the fax pages.
     *
     * This function returns a list of the pages received in an inbound
     * fax or those added with the \c eFax::add_file() function call. This includes
     * the content, the type of the data and the page number when available.
     *
     * The data comes from the \c $files variable member. It is an array of arrays
     * The following sample of code shows how one can save the data in a set of
     * files.
     *
     * \code
     *    ...
     *    $files = $efax->get_result_files();
     *    $idx = 1;
     *    foreach($files as $f)
     *    {
     *        if($f['type'] == 'tif')
     *        {
     *            $output = fopen("tiff/fax$idx.tif", "w");
     *            if($output)
     *            {
     *                fwrite($output, $f['contents']);
     *                fclose($output);
     *            }
     *            else ... // handle file open error
     *        }
     *        else if($f['type'] == 'pdf')
     *        {
     *            $output = fopen("pdf/fax$idx.pdf", "w");
     *            if($output)
     *            {
     *                fwrite($output, $f['contents']);
     *                fclose($output);
     *            }
     *            else ... // handle file open error
     *        }
     *        else ... // handle unknown type error
     *        ++$idx;
     *    }
     *    ...
     * \endcode
     *
     * The \c $file array includes a set of arrays, possibly none.
     * These sub-arrays have two or three indices defined as:
     *
     * \li 'type' -- the type of file, the inbound faxes can either be tif images or PDF documents
     * \li 'contents' -- the contents of the file, this is already decoded so it is the raw data
     * \li 'page' -- when available, the page number that this file represents
     *
     * \return A reference to the \c $files array.
     *
     * \sa eFax::parse_inbound_pages()
     */
    function get_result_files()
    {
        return $this->files;
    }

    // those are defaults that usually do not need to be modified
    private $outbound_url;
    private $outbound_encoding;

    // these are variables that are set by the user before sending the request
    private $use_pear_http_request;    // if true, use the PEAR http.so extension, otherwise use http_request.php
    private $account_id;            // this is the eFax fax phone number
    private $user_name;                // your eFax user name (required)
    private $user_password;            // your eFax password (required)
    private $fax_id;                // the transmission identifier (optional)
    private $duplicate_id;            // boolean representing ENABLE (default) or DISABLE (optional)
    private $resolution;            // STANDARD (default) or FINE (required)
    private $priority;                // NORMAL (default) or HIGH (optional)
    private $self_busy;                // boolean representing ENABLE (default) or DISABLE (optional)
    private $fax_header;            // null (default) or a string with the fax header info (optional)
    private $disposition_url;        // URL where the results are reported (optional)
    private $disposition_level;        // flags ERROR and SUCCESS (required)
    private $disposition_method;    // POST or EMAIL (optional)
    private $disposition_language;    // one of the supported languages (en, de, es, etc.) or null
    private $disposition_emails = array();    // list of emails & name pairs (at least 1 if method is EMAIL)
    private $recipients = array();    // list of recipient name, company and fax number (at least 1)
    private $files = array();        // list of files and their type (at least 1)

    private $response;                // the whole raw response
    private $status;                // status result of the last send() call
    private $docid;                    // each XML sent is marked with a unique identifier
    private $error_level;            // in case an error occurred when sending the request
    private $error_description;        // the description of the error when sending the request

    private $result_fax_id;            // the fax identifier (useful to match the sender/receiver)
    private $result_docid;            // the document identifier from eFax
    private $result_fax_number;        // the recipient fax number
    private $result_completion_date;// the completion date in PST (yyyy-mm-dd hh:mm:ss)
    private $result_fax_status;        // status 0 (success) or an error code
    private $result_csid;            // receiving/sending fax station identifier if available
    private $result_duration;        // transmission duration in minutes
    private $result_pages;            // number of pages sent or received
    private $result_retries;        // number of times the fax # was dialed

    private $inbound_date;            // the time and date when the inbound fax was received
    private $inbound_type;            // the type of inbound request
    private $inbound_fax_name;        // fax name given to this fax by eFax Developer as changed by client
    private $inbound_file_type;        // the type used whenever adding a file from the inbound content (add_file)
    private $inbound_user_fields;    // the array of user fields

    private $err_messages = array(
             0 => array(' ', 'no error occurred'),
            60 => array('Z', 'failure to update control file'),
            61 => array('Z', 'failure to invoke pre-processor'),
            62 => array('Z', 'failure to invoke post processor'),
            63 => array('Z', 'phone number failed to pass phone mask'),
            64 => array('Z', 'unable to find or load user profile'),
            65 => array('Z', 'unable to find cover sheet'),
            66 => array('Z', 'transmission stopped on abnormal termination'),
            67 => array('Z', 'unspecified error in .FS file command'),
            68 => array('Z', 'error in file list no files to send'),
            69 => array('Z', 'receive rejected for mail stop match'),
            70 => array('Z', 'not attempted after earlier failure'),
            71 => array('Z', 'internal error no fax board'),
            72 => array('Z', 'failed by FFMERGE'),
            73 => array('Z', 'unable to create attempt record'),
            74 => array('Z', 'error or hangup during voice OB call'),
            75 => array('Z', 'phone failed on global DNS lookup'),
            76 => array('Z', 'phone failed on user DNS lookup'),
            77 => array('Z', 'missing phone number'),
            78 => array('Z', 'phone number too long'),
            79 => array('Z', 'auto job launch on timeout cancelled'),
            80 => array('Z', 'exception while checking DNS'),
            81 => array('Z', 'no user profile for pre-process'),
            82 => array('Z', 'FXCVRT.DLL not found/loaded for pre-process'),
            83 => array('Z', 'FXCVRT.DLL failed to convert source to target'),
            84 => array('Z', 'Timeout accessing DNS_DOMN.NDX'),
            90 => array('Z', 'e-mail failure'),
            91 => array('Z', 'unspecified receive failure'),
           100 => array('Z', 'out of memory to process FS file'),
           101 => array('Z', 'invalid preprocess specification'),
           102 => array('Z', 'invalid postprocess specification'),
           103 => array('Z', 'invalid $fax_origin command'),
           104 => array('Z', 'invalid number of parameters on FS command'),
           105 => array('Z', 'unrecognized command in FS file'),
           108 => array('Z', '$fax_filename file not found'),
           110 => array('Z', 'invalid mailbox for outbound poll'),
           119 => array('Z', 'invalid fax send date'),
           120 => array('Z', 'invalid e-mail parameters'),
           121 => array('Z', 'invalid retry parameters'),
           122 => array('Z', 'invalid document conversion options'),
           123 => array('Z', 'DNS file missing'),
           124 => array('Z', 'FS include file error'),
           130 => array('Z', 'Cannot stop wait for inbound call on selected line'),
           131 => array('Z', 'FS file found for completed job'),
           132 => array('Z', 'IIF sequence not finished with correct state'),
           141 => array('Z', 'no local DNS server found'),
           142 => array('Z', 'no e-mail subject body or attachment'),
           143 => array('B', 'target domain name invalid'),
           144 => array('A', 'domain name lookup timed out'),
           145 => array('A', 'unspecified DNS lookup error'),
           146 => array('A', 'SMTP send timed out'),
           147 => array('A', 'unspecified SMTP send error'),
           148 => array('Z', 'Exception while processing e-mail'),
           149 => array('Z', 'Missing body or alternate body file'),
           150 => array('Z', 'Missing attachment file'),
           152 => array('Z', 'No e-mail sender name given'),
           153 => array('Z', 'No attachment(s) for MHTML'),
           154 => array('Z', 'Remote Host not found'),
           155 => array('Z', 'Connection refused'),
           258 => array('D', 'No dial tone detected'),
           259 => array('D', 'No loop current detected'),
           260 => array('D', 'Local phone in use (country-specific)'),
           261 => array('D', 'Busy trunk line detected'),
           265 => array('D', 'T1 time slot busy'),
           266 => array('C', 'Ringing detected during dialing'),
           267 => array('C', 'No wink'),
           268 => array('C', 'ISDN invalid dial string'),
           269 => array('C', 'Redial failed (Japan)'),
           301 => array('B', 'Normal busy: remote end busy (off hook)'),
           302 => array('B', 'Normal busy: remote end busy (off hook)'),
           303 => array('B', 'Fast busy: telephone co trunk lines busy'),
           304 => array('C', 'Calling while already connected'),
           305 => array('C', 'Unexpected confirmation tone'),
           308 => array('A', 'Ringing (single tone)'),
           309 => array('A', 'Ringing (double tone)'),
           316 => array('A', 'Answer detected probable human'),
           317 => array('A', 'Remote answered call'),
           318 => array('A', 'Dialtone remains after dial sequence'),
           324 => array('A', 'Silence (no CNG detected)'),
           325 => array('A', 'Ringing timed out (no answer)'),
           326 => array('C', 'Group 2 fax detected (cannot transmit)'),
           327 => array('B', 'Special Info. Tone: invalid # or svc'),
           328 => array('D', 'Possible dead line after dial'),
           329 => array('B', 'Special Info. Tone: invalid #'),
           330 => array('B', 'Special Info. Tone: reorder tone'),
           331 => array('B', 'Special Info. Tone: no circuit'),
           332 => array('C', 'CNG fax tone detected'),
           333 => array('C', 'Remote fax went off hook (digital only)'),
           334 => array('C', 'Special call progress result'),
           339 => array('C', 'Fax answer tone detected (CED)'),
           340 => array('C', 'Unknown call progress result'),
           349 => array('C', 'ISDN call collision'),
           421 => array('C', 'Service not available'),
           450 => array('D', 'Mailbox not available'),
           451 => array('A', 'Server error'),
           452 => array('A', 'Server insufficient storage'),
           500 => array('Z', 'SMTP command unrecognized (syntax error)'),
           501 => array('Z', 'SMTP syntax error in command arguments'),
           502 => array('Z', 'SMTP command not implemented'),
           503 => array('Z', 'SMTP commands in bad sequence'),
           504 => array('Z', 'SMTP command parameter not implemented'),
           550 => array('E', 'Mailbox unavailable (e.g. mailbox not found)'),
           551 => array('F', 'User not local (try <forward address>)'),
           552 => array('G', 'Mailbox has exceeded storage allocation'),
           553 => array('H', 'Mailbox name invalid'),
           554 => array('Z', 'SMTP transaction failed'),
          4007 => array('C', 'Unknown dial error (line not connected)'),
          4009 => array('E', 'Unknown error'),
          4010 => array('E', 'Error on infopkt file'),
          4011 => array('E', 'No error not all pages sent'),
          4012 => array('Z', 'Unable to start fax receive'),
          4013 => array('F', 'Unable to get remote info'),
          4014 => array('F', 'Unable to train'),
          4015 => array('Z', 'Unable to open file for receive'),
          4016 => array('E', 'Unable to process retry action'),
          4017 => array('Z', 'Receive decline on sender CSID'),
          4018 => array('B', 'Timeout on wait for call complete'),
          4020 => array('F', 'One or more pages was confirmed as RTN'),
          4021 => array('F', 'One or more pages not MCF RTP or RTN'),
          4101 => array('F', 'Ring detect without successful handshake'),
          4102 => array('E', 'Call aborted'),
          4103 => array('C', 'No loop current / A/B bits (hang up in send)'),
          4104 => array('B', 'ISDN disconnect'),
          4111 => array('F', 'No answer T.30 T1 timeout'),
          4120 => array('F', 'Unspecified transmit Phase B error'),
          4121 => array('F', 'Remote cannot receive or send'),
          4122 => array('F', 'COMREC error'),
          4123 => array('F', 'COMREC invalid command receivedd'),
          4124 => array('F', 'RSPREC error'),
          4125 => array('F', 'DCS sent 3 times without response'),
          4126 => array('F', 'DIS/DTC recd 3 times; DCS not recognized'),
          4127 => array('F', 'Failure to train'),
          4128 => array('F', 'RSPREC invalid response received'),
          4129 => array('F', 'DCN received in COMREC'),
          4130 => array('F', 'DCN received in RSPREC'),
          4133 => array('F', 'Incompatible fax formats (eg page width)'),
          4134 => array('F', 'Invalid DMA count specified for transmit'),
          4135 => array('F', 'BFT specified but no ECM enabled on xmit'),
          4136 => array('F', 'BFT specified but not supported by rcvr'),
          4140 => array('F', 'No response to RR after 3 tries'),
          4141 => array('F', 'No response to CTC or response not CTR'),
          4142 => array('F', 'T5 timout since receiving RNR'),
          4143 => array('F', 'Do not continue after receiving ERR'),
          4144 => array('F', 'ERR response to EOR-EOP or EOR-PRI-EOP'),
          4151 => array('F', 'RSPREC error'),
          4152 => array('F', 'No response to MPS repeated 3 times'),
          4153 => array('F', 'Invalid response to MPS'),
          4154 => array('F', 'No response to EOP repeated 3 times'),
          4155 => array('F', 'Invalid response to EOP'),
          4156 => array('F', 'No response to EOM repeated 3 times'),
          4157 => array('F', 'Invalid response to EOM'),
          4160 => array('F', 'DCN received in RSPREC'),
          4161 => array('F', 'No response after 3 tries for PPS-NULL'),
          4162 => array('F', 'No response after 3 tries for PPS-MPS'),
          4163 => array('F', 'No response after 3 tries for PPS-EOP'),
          4164 => array('F', 'No response after 3 tries for PPS-EOMM'),
          4165 => array('F', 'No response after 3 tries for EOR-NULL'),
          4166 => array('F', 'No response after 3 tries for EOR-MPS'),
          4167 => array('F', 'No response after 3 tries for EOR-EOP'),
          4168 => array('F', 'No response after 3 tries for EOR-EOM'),
          4340 => array('F', 'No interrupt acknowledge time-out'),
          4341 => array('F', 'Comm fault loop current still present'),
          4342 => array('F', 'T.30 holdup time-out'),
          4343 => array('F', 'DCN received from host in receive holdup'),
          4600 => array('Z', 'Error interrupt problem with card'),
          4601 => array('Z', 'Unexpected overrun'),
          4602 => array('E', 'Unexpected 03 or 7F interrupt'),
          4603 => array('Z', 'IOCTL error'),
          4604 => array('Z', 'OVerlay DLOAD error'),
          4605 => array('F', 'Max Timeout'),
          5000 => array('Z', 'Unclassified API error'),
          5001 => array('Z', 'File I/O error'),
          5002 => array('Z', 'Bad file format'),
          5003 => array('Z', 'Board does not have required capability'),
          5004 => array('F', 'Channel not in correct state'),
          5005 => array('Z', 'Bad API parameter value'),
          5006 => array('Z', 'Memory allocation error'),
          5007 => array('Z', 'Channel not in required state'),
          5008 => array('F', 'Dialling attempted too soon'),
          6000 => array('C', '(no cause available)'),
          6001 => array('Z', 'UNASSIGNED_NUMBER'),
          6002 => array('B', 'NO_ROUTE'),
          6003 => array('C', '(no cause available)'),
          6006 => array('C', 'CHANNEL_UNACCEPTABLE'),
          6016 => array('B', 'NORMAL_CLEARING'),
          6017 => array('B', 'USER_BUSY'),
          6018 => array('A', 'NO_USER_RESPONDING'),
          6021 => array('B', 'CALL_REJECTED'),
          6022 => array('C', 'NUMBER_CHANGED'),
          6027 => array('B', 'DEST_OUT_OF_ORDER'),
          6028 => array('C', 'INVALID_NUMBER_FORMAT'),
          6029 => array('C', 'FACILITY_REJECTED'),
          6030 => array('C', 'RESP_TO_STAT_ENQ'),
          6031 => array('C', 'UNSPECIFIED_CAUSE'),
          6034 => array('B', 'NO_CIRCUIT_AVAILABLE'),
          6038 => array('B', 'NETWORK_OUT_OF_ORDER'),
          6041 => array('B', 'TEMPORARY_FAILURE'),
          6042 => array('B', 'NETWORK_CONGESTION'),
          6043 => array('C', 'ACCESS_INFO_DISCARDED'),
          6044 => array('E', 'REQ_CHANNEL_NOT_AVAIL'),
          6045 => array('C', 'PRE_EMPTED'),
          6050 => array('B', 'FACILITY_NOT_SUBSCRIBED'),
          6052 => array('Z', 'OUTGOING_CALL_BARRED'),
          6054 => array('Z', 'INCOMING_CALL_BARRED'),
          6058 => array('B', 'BEAR_CAP_NOT_AVAIL'),
          6063 => array('B', 'SERVICE_NOT_AVAIL'),
          6065 => array('B', 'CAP_NOT_IMPLEMENTED'),
          6066 => array('B', 'CHAN_NOT_IMPLEMENTED'),
          6069 => array('Z', 'FACILITY_NOT_IMPLEMENT'),
          6081 => array('E', 'INVALID_CALL_REF'),
          6082 => array('E', 'CHAN_DOES_NOT_EXIST'),
          6088 => array('Z', 'INCOMPATIBLE_DEST'),
          6095 => array('E', 'INVALID_MSG_UNSPEC'),
          6096 => array('E', 'MANDATORY_IE_MISSING'),
          6097 => array('E', 'NONEXISTENT_MSG'),
          6098 => array('E', 'WRONG_MESSAGE'),
          6099 => array('E', 'BAD_INFO_ELEM'),
          6100 => array('E', 'INVALID_ELEM_CONTENTS'),
          6101 => array('E', 'WRONG_MSG_FOR_STATE'),
          6102 => array('E', 'TIMER_EXPIRY'),
          6103 => array('E', 'MANDATORY_IE_LEN_ERR'),
          6111 => array('E', 'PROTOCOL_ERROR'),
          6127 => array('B', 'INTERWORKING_UNSPEC'),
          6401 => array('A', 'Alerting message but timed out before connect'),
          6402 => array('A', 'Setup acknowledged but timed out before connect'),
          6403 => array('A', 'Progress message but timed out before connect'),
          6404 => array('E', 'Layer 2 - D-Channel went down'),
          6405 => array('E', 'Wait for complete was terminated unexpectedly'),
          6406 => array('E', 'Disconnect message occurred after connect message'),
          6407 => array('E', 'Outgoing call attempted but no response from network'),
          6408 => array('E', 'Special code used internally')
    );
};

// vim: ts=4 sw=4
?>
