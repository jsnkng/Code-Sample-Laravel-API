<?php
/** \file efax.php4
 *
 * \brief eFax class implementation
 *
 *    Implementation of the efax class used to create faxes that
 *    are compatible with eFax Developer.
 *
 * \section copyright Copyright (c) 2007-2010 Made to Order Software Corp.
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

// PHP 4 does not automatically include those
require_once('./HTTP_Request.php');
require_once('XML/Parser.php');
require_once('XML/Parser/Simple.php');

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
 * IMPORTANT NOTE: The secure HTTPS API requires YOU to have a
 * secure website with a valid certificate. The certificate must
 * be validated by an entity such as godaddy.com or verysign.com
 * (there are hundreds of companies offering certificates now a
 * day.) Without a valid certificate, the response from eFax
 * Developer will NOT work. This is beyond what we can do for you
 * with PHP eFax.
 *
 * \par
 * Back to \ref summary
 *
 * \section product How do I get my own copy of this product?
 *
 * The source code for this class is available for sale on our
 * m2osw.com website.
 *
 * Click on <a href="http://www.m2osw.com/products" target="_blank">Products</a>
 * at the top, search for PHP eFax, and
 * <a href="https://secure.m2osw.com/cart.phtml?set=php_efax1_0" target="_blank">add
 * it to your cart</a>. Then simply go through our checkout process.
 * A few seconds after we receive your payment, you will gain
 * access to the download area where you will be able to download
 * the PHP eFax package.
 *
 * \par
 * Back to \ref summary
 *
 * \section require Requirements
 *
 * The class requires the availability of HttpRequest.
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
 * If it is not already installed on your \b Debian system use the
 * following commands to retrieve and recompile a version
 * on your system:
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
 * For \b FedoraCore and other RPM based systems, use \c yum instead
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
 *    $efax = new eFax;
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
 * The disposition will be sent to the disposition URL. In our
 * previous example it is:
 *
 * %https://secure.m2osw.com/fax-disposition.php
 *
 * \code
 *    // Get the XML message
 *    $xml = stripslashes($_POST["xml"]);
 *
 *    $efax = new eFax;
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
 * This HTTP POST message includes an XML file that PHP eFax will
 * parse for you. See the \c eFax::parse_inbound_message(] function
 * for additional information.
 *
 * Then you can use different get functions to retrieve the resulting
 * information.
 *
 * The request will be sent to the URL you define in your account.
 * At that URL, you will have a PHP file that includes what follows:
 *
 * \par Warning
 * The user name and password for the inbound eFax processing are
 * defined in the inbound settings screen. These can be made the
 * same as the outbound user name and password, although I would
 * suggest you use a different user namd and a different password
 * to increase your security level.
 *
 * \code
 *    ... -- some initialization code such as require_once('efax.php');
 *
 *    // Get the XML message
 *    $xml = stripslashes($_POST["xml"]);
 *
 *    $efax = new eFax;
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
 * \par
 * IMPORTANT: Do not save the XML in clear on your hard drive unless you know for
 * sure that it is safe. If you want to save it for storage or archival, think
 * about removing the login and password first.
 *
 * Similarly, whenever you create an XML file to be sent to eFax Developer, you need
 * to incorporate the Login and Password in clear in that XML document. So watch
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
 * trust are correct (was created with guenine tools.) If those
 * fail too, then there is another problem.
 *
 * The debugging is somewhat difficult because eFax Developer does not
 * give you much info about what goes right and what goes wrong. Note,
 * however that they keep a copy of the outbound response on their server.
 * So if somehow you do not get that response, you can at least see what
 * they were going to tell you.
 *
 * The main reason why you would not receive the response is because you
 * did not specify a URL with the HTTPS protocol (secure). Or because your
 * certificate cannot be verified by eFax Developer.
 *
 * Note that some eFax errors will not happen. For instance, the
 * "Account identifier argument was not passed" error will be prevented
 * by eFax which checks that you specified the identifier before it
 * forwards the XML packet.
 *
 * Other failures are possible. For instance, the fax number may not be
 * valid.
 *
 * Internally, the eFax class will automatically retry sending your
 * document up to 5 times. If after 5 times it cannot connect to
 * the eFax Developer server, then it returns with FALSE. In this case,
 * you will NOT get any other error from eFax Developer since they do
 * not even know you wanted to contact them. Failure to connect happen
 * often at times when they receive a large number of faxes. It is
 * frequent that you have to try to connect a second or third time. It
 * never happened to us that the communication would not happen with
 * 5 attempts. For this reason, the count is hard coded in the \c send()
 * function. Feel free to increase it if you get that problem once in
 * a while.
 *
 * \section errors Error Handling
 *
 * PHP 4.x does not support exceptions. Thus, many functions will
 * return false whenever an error is detected, whether the error is
 * caused by the expected function behavior failing (i.e. an HTTP
 * connection failing) or an invalid parameter.
 *
 * http://www.php.net/manual/en/function.http-request.php
 *
 * \par
 * Back to \ref summary
 *
 * \section changes Changes between versions
 *
 * The following shows what changes between versions. In general, what
 * is listed is what will affect you in some way.
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
 * to have been afected by any of those bugs.
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
 * \li Enhanced the documentation for FedoraCore users.
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
 * \section copyright Copyright (c) 2007-2010 Made to Order Software Corp.
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
    /** \brief Initialize a barcode object.
     *
     * This function initializes a barcode control node.
     *
     * The XML parser reads the Key, AdditionalInfo, ReadSequence,
     * ReadDirection, Symbology, Location, PageNumber, and the
     * Start & End Points saved in this object.
     */
    function eFaxBarcode()
    {
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
     * \li "2-Dimentional"
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

    /// \brief The value of the barcode (interpreted from the picture)
    var $key;
    /// \brief The page on which the code was found
    var $page;
    /// \brief This barcode number on the given $page
    var $sequence;
    /// \brief 2D, left/right, top/bottom, right/left, bottom/top
    var $direction;
    /// \brief Symbology or protocol used to generate  the barcode
    var $symbology;
    /// \brief Where the barcode starts on the page (in pixels?)
    var $x_start_a;
    /// \brief Where the barcode starts on the page (in pixels?)
    var $y_start_a;
    /// \brief Where the barcode starts on the page (in pixels?)
    var $x_start_b;
    /// \brief Where the barcode starts on the page (in pixels?)
    var $y_start_b;
    /// \brief Where the barcode ends on the page (in pixels?)
    var $x_end_a;
    /// \brief Where the barcode ends on the page (in pixels?)
    var $y_end_a;
    /// \brief Where the barcode ends on the page (in pixels?)
    var $x_end_b;
    /// \brief Where the barcode ends on the page (in pixels?)
    var $y_end_b;
};


/** \brief A class to handle XML parsing.
 *
 * This class is composed of functions that are used to
 * capture the data of input XML files.
 *
 * \private
 */
class XML_Parser_eFax extends XML_Parser_Simple
{
    /** \brief Initialize the eFax XML object
     *
     * This function initializes the eFax XML object.
     */
    function XML_Parser_eFax()
    {
        $this->XML_Parser_Simple();
        $this->clear();
    }

    /** \brief Clear data read previously.
     *
     * This function clears all the data that you
     * read in an XML file. This gives you the option
     * to read another file with the same XML parser.
     */
    function clear()
    {
        $this->status_code = null;
        $this->error_level = "System";
        $this->error_message = "";
        $this->transmission_id = null;
        $this->docid = null;
        $this->outbound_disposition_attribs = null;
        $this->inbound_post_request = false;
        $this->user_name = null;
        $this->user_password = null;
        $this->request_date = null;
        $this->request_type = null;
        $this->account_id = null;
        $this->date_received = null;
        $this->fax_name = null;
        $this->file_type = null;
        $this->page_count = null;
        $this->csid = null;
        $this->ani = null;
        $this->mcfid = null;
        $this->user_fields = array();
        $this->user_field_name = null;
        $this->user_field_value = null;
        $this->barcodes = array();
        $this->key = null;
        $this->read_sequence = null;
        $this->read_direction = null;
        $this->symbology = null;
        $this->page_number = null;
        $this->x_start_point_a = null;
        $this->y_start_point_a = null;
        $this->x_start_point_b = null;
        $this->y_start_point_b = null;
        $this->x_end_point_a = null;
        $this->y_end_point_a = null;
        $this->x_end_point_b = null;
        $this->y_end_point_b = null;
        $this->file_contents = null;
        $this->pages = array();
        $this->page_contents = null;
    }

    /** \brief Handle one element.
     *
     * This function dispatches the elements to their
     * respective function (as intended by the 'func'
     * method, but that does not work with the
     * XML_Parser_Simple class.)
     *
     * The sub-functions only receive the attributes
     * array and the data since the name is already
     * known (it is present in the function name.)
     *
     * \param[in] $name The name of the element (tag)
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement($name, $attribs, $data)
    {
        $func = "handleElement_" . $name;
        if(method_exists($this, $func))
        {
            call_user_func(array(&$this, $func), $attribs, $data);
        }
    }

    /** \brief Save the status code.
     *
     * This function saves the status code. The $data is expected
     * to be 1 or 2.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_StatusCode($attribs, $data)
    {
        $this->status_code = $data;
    }

    /** \brief Save the error level.
     *
     * This function saves the error level.
     *
     * The error level is expected to be "User" or "System".
     *
     * If no error is received, this parameter will be set
     * to "System" by default.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_ErrorLevel($attribs, $data)
    {
        $this->error_level = $data;
    }

    /** \brief Save the error message.
     *
     * This function saves the error message.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_ErrorMessage($attribs, $data)
    {
        $this->error_message = $data;
    }

    /** \brief Save the transmission identifier.
     *
     * This function saves the transmission identifier.
     * This is the same string we send as the transmission
     * identifier. What we call the fax identifier in the
     * eFax class.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_TransmissionID($attribs, $data)
    {
        $this->transmission_id = $data;
    }

    /** \brief Save the document identifier.
     *
     * This function saves the document identifier.
     * This number is unique for each document sent to eFax
     * Developer.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_DOCID($attribs, $data)
    {
        $this->docid = $data;
    }

    /** \brief Save the outbound disposition attributes.
     *
     * This function saves the attributes (an associative
     * array of attributes and their value) in the parser.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_OutboundDisposition($attribs, $data)
    {
        $this->outbound_disposition_attribs = $attribs;
    }

    /** \brief Mark that the inbound post request tag was found.
     *
     * The InboundPostRequest is the root tag of the post request
     * that we receive when an incoming fax arrived. We simply mark
     * that it arrived so we can make sure that it exists.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_InboundPostRequest($attribs, $data)
    {
        $this->inbound_post_request = true;
    }

    /** \brief Save the user name.
     *
     * The InboundPostRequest includes the user name and password.
     *
     * This handler captures the user name and saves it in the
     * XML parser.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_UserName($attribs, $data)
    {
        $this->user_name = $data;
    }

    /** \brief Save the password.
     *
     * The InboundPostRequest includes the user name and password.
     *
     * This handler captures the password and saves it in the
     * XML parser.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_Password($attribs, $data)
    {
        $this->user_password = $data;
    }

    /** \brief Save the request date.
     *
     * The InboundPostRequest includes a request date and time
     * stamp saved by this function.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_RequestDate($attribs, $data)
    {
        $this->request_date = $data;
    }

    /** \brief Save the request type.
     *
     * The InboundPostRequest includes a request type.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_RequestType($attribs, $data)
    {
        $this->request_type = $data;
    }

    /** \brief Save the account identifier.
     *
     * The InboundPostRequest includes your eFax phone number
     * and this function saves it as account_id.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_AccountID($attribs, $data)
    {
        $this->account_id = $data;
    }

    /** \brief Save the date when the fax was received.
     *
     * The InboundPostRequest includes the date when eFax
     * received this very fax. This should always be smaller
     * or equal to the request date since eFax does not send
     * your any request if they don't receive a fax first.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_DateReceived($attribs, $data)
    {
        $this->date_received = $data;
    }

    /** \brief Save the name of the fax when defined.
     *
     * The InboundPostRequest may include a name for the fax
     * being sent. This function saves it.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_FaxName($attribs, $data)
    {
        $this->fax_name = $data;
    }

    /** \brief Save the type of the file.
     *
     * The InboundPostRequest includes a type for the incoming
     * fax data. This can be PDF (pdf) or TIFF (tif).
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_FileType($attribs, $data)
    {
        $this->file_type = $data;
    }

    /** \brief Save the status of the transaction.
     *
     * The InboundPostRequest includes a status about the incoming
     * fax.
     *
     * \note
     * I used the $this->status_code for the status since it is
     * already defined and not otherwise defined in the inbound
     * post request XML schema.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_Status($attribs, $data)
    {
        $this->status_code = $data;
    }

    /** \brief Save the number of pages in the transaction.
     *
     * The InboundPostRequest XML includes the number of
     * pages received by eFax.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_PageCount($attribs, $data)
    {
        $this->page_count = $data;
    }

    /** \brief Save the CSID from the transaction.
     *
     * The InboundPostRequest XML may include the CSID of
     * the sender. This is a string identifying the sender
     * (usually his fax number.)
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_CSID($attribs, $data)
    {
        $this->csid = $data;
    }

    /** \brief Save the ANI from the transaction.
     *
     * The InboundPostRequest XML may include the ANI of
     * the sender. This is the sender caller identifier
     * (usually his fax number.)
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_ANI($attribs, $data)
    {
        $this->ani = $data;
    }

    /** \brief Save the MCFID from the transaction.
     *
     * The InboundPostRequest XML may include the MCFID of
     * the sender. This is a unique fax number.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_MCFID($attribs, $data)
    {
        $this->mcfid = $data;
    }

    /** \brief Reset the user field name and value.
     *
     * The InboundPostRequest XML may include user defined
     * fields. These have a name and a value. Each name is
     * expected to be unique. It is assumed that the name
     * and the value can appear in any order. For this
     * reason, we save it when the end UserField tag is
     * reached.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_UserField($attribs, $data)
    {
        if(is_null($this->user_field_name) || is_null($this->user_field_value))
        {
            return;
        }
        $this->user_fields[$this->user_field_name] = $this->user_field_value;
        $this->user_field_name = null;
        $this->user_field_value = null;
    }

    /** \brief Save the user field name.
     *
     * The InboundPostRequest XML may include user defined
     * fields. This function saves the name of the current
     * user field.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_FieldName($attribs, $data)
    {
        $this->user_field_name = $data;
    }

    /** \brief Save the user field value.
     *
     * The InboundPostRequest XML may include user defined
     * fields. This function saves the value of the current
     * user field.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_FieldValue($attribs, $data)
    {
        $this->user_field_value = $data;
    }

    /** \brief Save one barcode object.
     *
     * The InboundPostRequest XML may include barcodes that
     * eFax detected in the fax being processed. This function
     * saves the next barcode object in the array of barcodes.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_Barcode($attribs, $data)
    {
        // we expect at least a key
        if(is_null($this->key))
        {
            return;
        }
        $barcode = new eFaxBarcode;
        $barcode->key = $this->key;
        $barcode->page = $this->page_number;
        $barcode->sequence = $this->read_sequence;
        $barcode->direction = $this->read_direction;
        $barcode->symbology = $this->symbology;
        $barcode->x_start_a = $this->x_start_point_a;
        $barcode->y_start_a = $this->y_start_point_a;
        $barcode->x_start_b = $this->x_start_point_b;
        $barcode->y_start_b = $this->y_start_point_b;
        $barcode->x_end_a = $this->x_end_point_a;
        $barcode->y_end_a = $this->y_end_point_a;
        $barcode->x_end_b = $this->x_end_point_b;
        $barcode->y_end_b = $this->y_end_point_b;
        $this->barcodes[] = $barcode;

        // clear all to make sure we don't get "wrong defaults"
        // for the next barcode
        $this->key = null;
        $this->page_number = null;
        $this->read_sequence = null;
        $this->read_direction = null;
        $this->symbology = null;
        $this->x_start_point_a = null;
        $this->y_start_point_a = null;
        $this->x_start_point_b = null;
        $this->y_start_point_b = null;
        $this->x_end_point_a = null;
        $this->y_end_point_a = null;
        $this->x_end_point_b = null;
        $this->y_end_point_b = null;
    }

    /** \brief Save the barcode key.
     *
     * The InboundPostRequest XML may include a barcode.
     * The code, whenever possible is automatically detected
     * and transcribed by eFax. The result is saved in the
     * barcode key.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_Key($attribs, $data)
    {
        $this->key = $data;
    }

    /** \brief Save the read sequence number.
     *
     * The InboundPostRequest XML may include a barcode. Each
     * barcode is read in order as defined by eFax. The order
     * is saved in the ReadSequence tag.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_ReadSequence($attribs, $data)
    {
        $this->read_sequence = $data;
    }

    /** \brief Save the barcode direction.
     *
     * The InboundPostRequest XML may include barcodes.
     * Each barcode can be horizontal or vertical, left
     * to right.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_ReadDirection($attribs, $data)
    {
        $this->read_direction = $data;
    }

    /** \brief Save the barcode symbology.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax supports different type of barcodes and that
     * information is saved in this field.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_Symbology($attribs, $data)
    {
        $this->symbology = $data;
    }

    /** \brief Save the page number of this document or barcode.
     *
     * While reading the fax pages, this number is set to
     * the current page.
     *
     * The InboundPostRequest XML may also include barcodes.
     * In that case the page number represents the page on
     * which the barcode was found.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_PageNumber($attribs, $data)
    {
        $this->page_number = $data;
    }

    /** \brief Save the position of the barcode.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax saves the x/y coordinates where the barcodes
     * are found in the pages.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_XStartPointA($attribs, $data)
    {
        $this->x_start_point_a = $data;
    }

    /** \brief Save the position of the barcode.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax saves the x/y coordinates where the barcodes
     * are found in the pages.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_YStartPointA($attribs, $data)
    {
        $this->y_start_point_a = $data;
    }

    /** \brief Save the position of the barcode.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax saves the x/y coordinates where the barcodes
     * are found in the pages.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_XStartPointB($attribs, $data)
    {
        $this->x_start_point_b = $data;
    }

    /** \brief Save the position of the barcode.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax saves the x/y coordinates where the barcodes
     * are found in the pages.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_YStartPointB($attribs, $data)
    {
        $this->y_start_point_b = $data;
    }

    /** \brief Save the position of the barcode.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax saves the x/y coordinates where the barcodes
     * are found in the pages.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_XEndPointA($attribs, $data)
    {
        $this->x_end_point_a = $data;
    }

    /** \brief Save the position of the barcode.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax saves the x/y coordinates where the barcodes
     * are found in the pages.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_YEndPointA($attribs, $data)
    {
        $this->y_end_point_a = $data;
    }

    /** \brief Save the position of the barcode.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax saves the x/y coordinates where the barcodes
     * are found in the pages.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_XEndPointB($attribs, $data)
    {
        $this->x_end_point_b = $data;
    }

    /** \brief Save the position of the barcode.
     *
     * The InboundPostRequest XML may include barcodes.
     * eFax saves the x/y coordinates where the barcodes
     * are found in the pages.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_YEndPointB($attribs, $data)
    {
        $this->y_end_point_b = $data;
    }

    /** \brief Save the file contents.
     *
     * It is possible to setup your eFax account to receive one
     * file with all the pages at once. In that case, you get
     * the FileContents tag and no pages. The file is considered
     * complete.
     *
     * \note
     * The data is base64 encoded, but we do not decode here.
     * It is done later.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_FileContents($attribs, $data)
    {
        $this->file_contents = $data;
    }

    /** \brief Save one page of the incoming fax.
     *
     * The InboundPostRequest can be used in two different ways:
     *
     * 1. One large file with all the pages, or
     *
     * 2. One file per page.
     *
     * This function saves one page at a time.
     *
     * \note
     * The data is base64 encoded, but we do not decode here.
     * It is done later.
     *
     * \note
     * The PageNumber is the same tag as the one found in the
     * Barcode definitions. Since it cannot be used for both
     * at the same time, we know that when we enter this function
     * this is the page number of the current page and not the
     * one for a barcode.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_Page($attribs, $data)
    {
        $this->pages[$this->page_number] = $this->page_contents;

        // Reset to make sure we do not get duplicates in other
        // pages.
        $this->page_number = null;
        $this->page_contents = null;
    }

    /** \brief Save the page contents.
     *
     * It is possible to setup your eFax account to receive one
     * file per page. In that case, you get a PageContents tag
     * one by one for each page.
     *
     * \note
     * The data is base64 encoded, but we do not decode here.
     * It is done later.
     *
     * \param[in] $attribs The attributes in an associative array
     * \param[in] $data The data found in that element
     *
     * \private
     */
    function handleElement_PageContents($attribs, $data)
    {
        $this->page_contents = $data;
    }

    /// \brief Whether it succeeded (1) or not (2)
    var $status_code;
    /// \brief The error level: warning, error, fatal
    var $error_level;
    /// \brief The error message from eFax Developer
    var $error_message;
    /// \brief The identifier coming back (i.e. $efax->fax_id)
    var $transmission_id;
    /// \brief The unique document identifier
    var $docid;
    /// \brief The associative array of attributes found in the outbound disposition
    var $outbound_disposition_attribs;
    /// \brief Boolean flag to know whether the InboundPostRequest tag exists
    var $inbound_post_request;
    /// \brief The name of the user, used to log into your system
    var $user_name;
    /// \brief The user password, used to log into your system
    var $user_password;
    /// \brief The date and time when the request was created and sent
    var $request_date;
    /// \brief The type of inbound request
    var $request_type;
    /// \brief The account identifier (your eFax phone number)
    var $account_id;
    /// \brief The date when the fax was received by eFax
    var $date_received;
    /// \brief The name of this fax when available
    var $fax_name;
    /// \brief The type of file being transmitted, PDF (pdf) or TIFF (tif)
    var $file_type;
    /// \brief The number of pages in this fax transmission
    var $page_count;
    /// \brief The CSID of the sender
    var $csid;
    /// \brief The ANI of the sender
    var $ani;
    /// \brief The MCFID for this transmission
    var $mcfid;
    /// \brief An associative array of user fields
    var $user_fields;
    /// \brief The last field name found
    var $user_field_name;
    /// \brief The last field value found
    var $user_field_value;
    /// \brief An array of barcodes found in the XML file
    var $barcodes;
    /// \brief The barcode key
    var $key;
    /// \brief The order in which the barcode were read
    var $read_sequence;
    /// \brief The direction in which the barcode was drawn
    var $read_direction;
    /// \brief The symbology used for this barcode
    var $symbology;
    /// \brief The current fax page or the barcode page
    var $page_number;
    /// \brief A coordinate defining the location of the barcode in the page
    var $x_start_point_a;
    /// \brief A coordinate defining the location of the barcode in the page
    var $y_start_point_a;
    /// \brief A coordinate defining the location of the barcode in the page
    var $x_start_point_b;
    /// \brief A coordinate defining the location of the barcode in the page
    var $y_start_point_b;
    /// \brief A coordinate defining the location of the barcode in the page
    var $x_end_point_a;
    /// \brief A coordinate defining the location of the barcode in the page
    var $y_end_point_a;
    /// \brief A coordinate defining the location of the barcode in the page
    var $x_end_point_b;
    /// \brief A coordinate defining the location of the barcode in the page
    var $y_end_point_b;
    /// \brief When defined, this variable represents the whole fax
    var $file_contents;
    /// \brief An array of pages, the page number is used as the index
    var $pages;
    /// \brief The content of the current page
    var $page_contents;
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
    var $RESPOND_SUCCESS = 1;
    /// \brief Received when sending the fax is a failure.
    var $RESPOND_ERROR   = 2;

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
     */
    function eFax()
    {
        $this->outbound_url = "https://secure.efaxdeveloper.com/EFax_WebFax.serv";
        $this->outbound_encoding = "application/x-www-form-urlencoded";

        $this->resolution = "STANDARD";
        $this->disposition_level = $this->RESPOND_SUCCESS | $this->RESPOND_ERROR;

        $this->status = 0;
    }

    /** \brief Send the message to eFax.
     *
     * This function sends the specified message to eFax. The message
     * can be generated using the \c eFax::message() function (actually, it is
     * strongly recommanded that you do so unless you have another
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
     * \param[in] $msg The fax message (i.e. the XML message to forward.)
     *
     * \return true if the transmission succeeded with a positive response;
     * false if the transmission does not occur or the setup is not
     * correct (i.e. no valid account identifier defined.)
     *
     * \sa eFax::message()
     * \sa eFax::parse_response()
     */
    function send($msg)
    {
        if(strlen($this->account_id) < 5)
        {
            return false;
        }

        // if the Http request object throws before the send()
        // returns then that's our response...
        $this->response = "not sent";

        // initialize the request
        $request = new HTTP_Request($this->outbound_url);
        $request->setMethod("POST");
        $request->addHeader("Cache-Control", "no-cache, must-revalidate");
        $request->addHeader("Content-type", $this->outbound_encoding);
        $request->addPostData("id", $this->account_id); // the login + password are defined in the XML data
        $request->addPostData("respond", "xml");
        $request->addPostData("xml", $msg); // this does the urlencode() as expected by eFax

        // send the resquest and wait for the immediate XML response
        $count = 5;
        do
        {
            $repeat = false;
            $response = $request->sendRequest();
            if(!PEAR::isError($response))
            {
                break;
            }
            // this exception happens whenever the eFax server times out
            // (we may want to check the exception a little closer, but
            // I'm not too sure how to do that in there...)
            $repeat = true;
            --$count;
            sleep(1);
            $this->response = "not sent (Got PEAR Error: "
                    . $response->getMessage() . ")";
        }
        while($repeat && $count > 0);

        if(!$response)
        {
            // there was no response!
            return false;
        }
        $this->response = $request->getResponseBody();
        if($this->response === false)
        {
            $this->response = "no response";
            return false;
        }

        return $this->parse_response($this->response);
    }

    /** \brief Parse the send request immediate response.
     *
     * This function is used to parse the immediate response of sending a
     * fax request to eFax. This is used to confirm that eFax did indeed
     * receive the request and will be able to process it (i.e. that we
     * sent a valid eFax XML document.)
     *
     * \param[in] $response The body of the HTTPMessage returned by
     * \c HttpRequest::send().
     *
     * \return true if the request is a success, false otherwise;
     * the function already returns false if the \p $response
     * parameter is not set properly.
     *
     * \sa eFax::send()
     *
     * \private
     */
    function parse_response($response)
    {
        if(!$response)
        {
            return false;
        }

        $xml = new XML_Parser_eFax;
        $xml->setInputString($response);
        $xml->parse(); // fills the different data fields automatically

        if(is_null($xml->status_code))
        {
            return false;
        }
        $this->status = $xml->status_code;
        if($this->status == 2)
        {
            $this->error_level = $xml->error_level;
            $this->error_description = $xml->error_message;
        }

        // if we sent an identifier, verify that it is equal
        if(!is_null($this->fax_id))
        {
            if(is_null($xml->transmission_id))
            {
                return false;
            }
            if($this->status == 1 && $xml->transmission_id != $this->fax_id)
            {
                return false;
            }
        }

        if(is_null($xml->docid))
        {
            return false;
        }
        $this->docid = $xml->docid;

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
     * \endcode
     *
     * \note
     * The function returns false if anything in this message represents
     * a failure of some kind. This does not means that the fax was not
     * partially transmitted.
     *
     * \param[in] $msg The message to parse
     *
     * \return true if the message represents a successful fax transmission;
     * false in all other cases including when an invalid parameter was
     * defined.
     *
     * \sa eFax::set_user_name()
     * \sa eFax::set_user_password()
     */
    function parse_disposition($msg)
    {
        if(!$this->user_name || !$this->user_password || !$msg)
        {
            return false;
        }

        $xml = new XML_Parser_eFax;
        $xml->setInputString($msg);
        $xml->parse();

        if(!is_array($xml->outbound_disposition_attribs))
        {
            return false;
        }

        $disposition = $xml->outbound_disposition_attribs;

        // verify the login/password info (should we throw on these if erroneous?)
        if($disposition["USERNAME"] != $this->user_name)
        {
            $this->error_level = "System";
            $this->error_description = "Invalid login name.";
            return false;
        }
        if($disposition["PASSWORD"] != $this->user_password)
        {
            $this->error_level = "System";
            $this->error_description = "Invalid password.";
            return false;
        }

        $this->result_fax_id          = $disposition["TRANSMISSIONID"];
        $this->result_docid           = $disposition["DOCID"];
        $this->result_fax_number      = $disposition["FAXNUMBER"];
        $this->result_completion_date = $disposition["COMPLETIONDATE"];
        $this->result_fax_status      = $disposition["FAXSTATUS"];
        $this->result_csid            = $disposition["RECIPIENTCSID"];
        $this->result_duration        = $disposition["DURATION"];
        $this->result_pages           = $disposition["PAGESSENT"];
        $this->result_retries         = $disposition["NUMBEROFRETRIES"];

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
     * the \c $_POST["xml"] variable. Yet, the data will include backslashes
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
     * \param[in] $msg   The raw XML data sent to you by eFax Developer
     * via a POST request.
     *
     * \return true if the message is valid, false in all other cases.
     * The function may also return false when a parameter is invalid.
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

        $xml = new XML_Parser_eFax;
        $xml->setInputString($msg);
        $xml->parse();

        // make sure there is an inbound post request
        // (although this XML Parser accepts any one tag
        // with that name... in other words it doesn't need
        // to be the root tag for this test to pass!)
        if(!$xml->inbound_post_request)
        {
            return false;
        }

        // User & Password (Access Control)
        if(!$this->parse_inbound_access_control($xml))
        {
            return false;
        }

        // Date, Time & Type (Request Control)
        if(!$this->parse_inbound_request_control($xml))
        {
            return false;
        }

        // Get the fax main info:
        //
        //        Account, Date, Time, Fax Name, File Type,
        //        # of Pages, CSID, ANI, Status, MCFID
        if(!$this->parse_inbound_fax_control($xml))
        {
            return false;
        }

        // Get the user defined fields
        if(!$this->parse_inbound_user_fields($xml))
        {
            return false;
        }

        // Get the detected barcode information
        if(!$this->parse_inbound_barcodes($xml))
        {
            return false;
        }

        // Get the fax pages
        if(!$this->parse_inbound_pages($xml))
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
    function clear_inbound()
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
     * If the tags are missing, the function returns false.
     *
     * If the user name or password are not valid, the function
     * returns false and sets the error level and description.
     *
     * \param[in] $xml  The XML parser
     *
     * \return false if the user name or password do not match
     * or an invalid parameter is detected. true if the name
     * and password are valid.
     *
     * \private
     */
    function parse_inbound_access_control($xml)
    {
        if(!$this->user_name || !$this->user_password)
        {
            return false;
        }
        if(is_null($xml->user_name) || is_null($xml->user_password))
        {
            return false;
        }

        if($xml->user_name != $this->user_name)
        {
            $this->error_level = "System";
            $this->error_description = "Invalid login name.";
            return false;
        }

        if($xml->user_password != $this->user_password)
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
     * \param[in] $xml  The XML object that includes the RequestControl tag.
     *
     * \return true if no error occured, false otherwise.
     *
     * \sa eFax::get_result_request_date()
     * \sa eFax::get_result_request_type()
     *
     * \private
     */
    function parse_inbound_request_control($xml)
    {
        $this->inbound_date = -1;
        $this->inbound_type = 'undefined';

        // Check the date and time
        if($xml->request_date)
        {
            // Date/Time format: MM/DD/YYYY HH:MM:SS
            if(preg_match(
                    '/([0-9][0-9])\/([0-9][0-9])\/([0-9][0-9][0-9][0-9]) +([0-9][0-9]):([0-9][0-9]):([0-9][0-9])/',
                    $xml->request_date, $date_info) == 1)
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
        if($xml->request_type)
        {
            $this->inbound_type = $xml->request_type;
        }

        // if we get here, consider the request as successful
        echo "Post Successful\n";

        return true;
    }

    /** \brief Extract the main fax control information.
     *
     * This function retrives the basic fax control information.
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
     * \param[in] $xml  The XML parser with the InboundPostRequest/FaxControl info
     *
     * \return true if the function succeeds, false if an error was detected
     *
     * \sa \c eFax::get_result_error_message()
     *
     * \private
     */
    function parse_inbound_fax_control($xml)
    {
        // Account Identifier (i.e. eFax phone number)
        if($xml->account_id)
        {
            $this->result_fax_id = $xml->account_id;
        }
        else
        {
            $this->result_fax_id = 'n.a.';
        }

        // Date & Time when the fax was received by eFax
        if($xml->date_received)
        {
            if(preg_match(
                    '/([0-9][0-9])\/([0-9][0-9])\/([0-9][0-9][0-9][0-9]) +([0-9][0-9]):([0-9][0-9]):([0-9][0-9])/',
                    $xml->date_received, $date_info) == 1)
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
        if($xml->fax_name)
        {
            $this->inbound_fax_name = $xml->fax_name;
        }
        else
        {
            $this->inbound_fax_name = '';
        }

        // The type of files in this fax (used to call the add_file() function)
        // NOTE: we do not give the user a direct access to the filetype, instead
        //         they have to get entries from the $this->files array that include
        //         the type as well as the content of the file.
        if($xml->file_type)
        {
            $this->inbound_file_type = strtolower($xml->file_type);
        }
        else
        {
            // from what I understand, this is the default...
            // (or should we generate an error here?!)
            $this->inbound_file_type = 'pdf';
        }

        // Status
        if($xml->status_code)
        {
            $this->result_fax_status = $xml->status_code;
        }
        else
        {
            $this->result_fax_status = 0;
        }

        // The number of pages received
        if($xml->page_count)
        {
            $this->result_pages = $xml->page_count;
        }
        else
        {
            // assume there is at least one page unless the status != 0
            // (or should we generate an error here?!)
            $this->result_pages = $this->result_fax_status == 0 ? 1 : 0;
        }

        // CSID
        if($xml->csid)
        {
            $this->result_csid = $xml->csid;
        }
        else
        {
            $this->result_csid = '';
        }

        // ANI
        if($xml->ani)
        {
            $this->result_fax_number = $xml->ani;
        }
        else
        {
            $this->result_fax_number = 'Unknown';
        }

        // MCFID, this is equivalent to the document identifier
        if($xml->mcfid)
        {
            $this->result_docid = $xml->mcfid;
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
     * false otherwise
     *
     * \sa eFax::get_result_user_fields()
     *
     * \private
     */
    function parse_inbound_user_fields($xml)
    {
        // in PHP4 the user fields were processed while parsing the XML
        $this->inbound_user_fields = $xml->user_fields;

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
     * false otherwise
     *
     * \sa class eFaxBarcode
     * \sa eFax::get_result_barcodes()
     *
     * \private
     */
    function parse_inbound_barcodes($xml)
    {
        // the parsing is directly done in the XML parser
        $this->inbound_barcodes = $xml->barcodes;

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
     *
     * \private
     */
    function parse_inbound_pages($xml)
    {
        $this->files = array();

        // First check for file contents, if it exists, then we're
        // done (the file is being split) otherwise, we check the
        // page content control tag instead.
        if($xml->file_contents)
        {
            // Save the data in our array of files
            $this->files[] = array(
                    "type" => $this->inbound_file_type,
                    "contents" => base64_decode($xml->file_contents)
                );
            return true;
        }

        foreach($xml->pages as $key => $value) {
            $this->files[] = array(
                    "type" => $this->inbound_file_type,
                    "page" => $key,
                    "contents" => base64_decode($value)
                );
        }

        return true;
    }

    /** \brief Create the XML message.
     *
     * This function generates the XML message that is to be sent to eFax.
     *
     * Please, make sure you test whether you get a string or the
     * value false. If one of the parameters is not set properly
     * the function fails and returns false.
     *
     * \return The XML message or false if one of the parameters
     * was not properly defined.
     *
     * \sa eFax::access_control_tags()
     * \sa eFax::transmission_tags()
     */
    function message()
    {
        $a = $this->access_control_tags();
        if($a === false)
        {
            return false;
        }
        $t = $this->transmission_tags();
        if($t === false)
        {
            return false;
        }

        // Start
        $result = "<?xml version=\"1.0\"?><OutboundRequest>";

        // Access Control
        $result .= "<AccessControl>" . $a . "</AccessControl>";

        // Transmission
        $result .= "<Transmission>" . $t . "</Transmission>";

        // Done.
        $result .= "</OutboundRequest>";

        return $result;
    }

    /** \brief Generate the access control tags.
     *
     * This function generates the UserName and Password tags.
     *
     * \return A string with the user name and password tags.
     * The function returns false if the user name or password
     * are not properly defined.
     *
     * \private
     */
    function access_control_tags()
    {
        if(is_null($this->user_name) || is_null($this->user_password))
        {
            return false;
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
     * This function returns false if one of the required
     * parameter was not defined.
     *
     * \sa eFax::transmission_control_tags()
     * \sa eFax::disposition_control_tags()
     * \sa eFax::recipients_tags()
     * \sa eFax::files_tags()
     *
     * \private
     */
    function transmission_tags()
    {
        // Transmission Control
        $r = $this->transmission_control_tags();
        if($r === false)
        {
            return false;
        }
        $result = $r;

        // Disposition Control
        $r = $this->disposition_control_tags();
        if($r === false)
        {
            return false;
        }
        $result .= $r;

        // Recipients
        $r = $this->recipients_tags();
        if($r === false)
        {
            return false;
        }
        $result .= $r;

        // Files
        $r = $this->files_tags();
        if($r === false)
        {
            return false;
        }
        $result .= $r;

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
     * This function returns false if the fax identifier is not
     * defined and the duplicate identifier was set to true.
     *
     * \private
     */
    function transmission_control_tags()
    {
        if($this->duplicate_id === true && is_null($this->fax_id))
        {
            return false;
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
     *
     * \private
     */
    function disposition_control_tags()
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

                case $this->RESPOND_SUCCESS:
                    $result .= "SUCCESS";
                    break;

                case $this->RESPOND_ERROR:
                    $result .= "ERROR";
                    break;

                case $this->RESPOND_SUCCESS | $this->RESPOND_ERROR:
                    $result .= "BOTH";
                    break;

                }
            $result .= "</DispositionLevel>";
            if(!is_null($this->disposition_method))
            {
                $result .= "<DispositionMethod>{$this->disposition_method}</DispositionMethod>";
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

    /** \brief Genearte the recipient tags.
     *
     * This function generates the array of recipients with their
     * name, company name and fax phone number.
     *
     * \return A string with all the recipient tags.
     * This function returns false if the \c eFax::add_recipient()
     * was never called.
     *
     * \sa eFax::add_recipient()
     *
     * \private
     */
    function recipients_tags()
    {
        if(count($this->recipients) == 0)
        {
            return false;
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
     * \return The set of Files tags. This functions returns false
     * if the \c eFax::add_file() function was never called.
     *
     * \sa eFax::add_file()
     *
     * \private
     */
    function files_tags()
    {
        if(count($this->files) == 0)
        {
            return false;
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
     * \param[in] $url The new URL to use to send the fax
     *
     * \return false if the URL is not at least 12 characters or is not
     * a string, true otherwise
     */
    function set_outbound_url($url)
    {
        if(!is_string($url) || strlen($url) < 12)
        {
            return false;
        }
        $this->outbound_url = $url;
        return true;
    }

    /** \brief Set the account idenfitier.
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
     * \return false if the $account_id parameter is not valid, true otherwise
     *
     * \sa eFax::set_raw_account_id()
     * \sa eFax::set_user_name()
     * \sa eFax::set_user_password()
     */
    function set_account_id($account_id)
    {
        if(is_null($account_id) || is_array($account_id) || is_object($account_id))
        {
            return false;
        }
        $id = str_replace(array(' ', '+', '-', '(', ')'), array('', '', '', '', ''), $account_id);
        if($id[0] == '1' && strlen($id) == 11)
        {
            // drop the leading 1, eFax developer does not want it.
            $id = substr($id, 1);
        }
        $this->account_id = $id;
        return true;
    }

    /** \brief Set the account idenfitier.
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
     * \param[in] $account_id    The eFax account identifier.
     *
     * \return false if the \p $account_id parameter is not valid, true otherwise
     *
     * \sa eFax::set_account_id()
     * \sa eFax::set_user_name()
     * \sa eFax::set_user_password()
     */
    function set_raw_account_id($account_id)
    {
        if(is_null($account_id) || is_array($account_id) || is_object($account_id))
        {
            return false;
        }
        $this->account_id = $account_id;
        return true;
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
     * being transfered in an XML file.
     *
     * \param[in] $user_name   The login name used to connect to eFax.
     *
     * \return false if the \p $user_name variable is not valid (not a string
     * of at most 20 characters,) true otherwise
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
            return false;
        }
        $this->user_name = $user_name;
        return true;
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
     * being transfered in an XML file.
     *
     * \param[in] $user_password The password used to connect to eFax
     *
     * \return false if the \p $user_password parameter is not valid (which
     * means it is not a string of at most 20 characters,) true otherwise
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
            return false;
        }
        $this->user_password = $user_password;
        return true;
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
     * \param[in] fax_id The identifier used to identify this fax or null
     *
     * return false if the fax identifier is not valid (i.e. it needs
     * to be a valid string or number of at most 15 characters or null);
     * true otherwise
     */
    function set_fax_id($fax_id)
    {
        if((!is_null($fax_id) && !is_string($fax_id) && !is_numeric($fax_id))
        || strlen("" . $fax_id) > 15)
        {
            return false;
        }
        $this->fax_id = $fax_id;
        return true;
    }

    /** \brief Set whether fax identifiers can be duplicated.
     *
     * This function can be used to determine whether the same fax
     * identifier can be used multiple times (in several different
     * requests.)
     *
     * This identifier is optional. If this option is not set, then
     * identifiers can be duplicated.
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
     * \param[in] $duplicate_id One of true, false or null.
     *
     * \return true if the \p $duplicate_id paramter is accepted;
     * false otherwise
     */
    function set_duplicate_id($duplicate_id)
    {
        if(is_null($duplicate_id) || is_bool($duplicate_id))
        {
            $this->duplicate_id = $duplicate_id;
            return true;
        }
        else
        {
            return false;
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
     * \param[in] $resolution "STANDARD" or "FINE"
     *
     * \return true when the \p $resolution paramter is one of
     * "STANDARD" or "FINE"; otherwise return false
     */
    function set_resolution($resolution)
    {
        if(is_string($resolution))
        {
            $resolution = strtoupper($resolution);
        }
        if($resolution === "STANDARD" || $resolution === "FINE")
        {
            $this->resolution = $resolution;
            return true;
        }
        else
        {
            return false;
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
     * \param[in] $priority "NORMAL" or "HIGH"
     *
     * \return true when the \p $priority parameter is accepted
     * (i.e. is NORMAL, HIGH or null); false otherwise
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
            return true;
        }
        else
        {
            return false;
        }
    }

    /** \brief Queue faxes sent to the same number.
     *
     * This function defines whether the phone number can accept multiple
     * connections at once or not. If you do not know, it is recommanded
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
            return true;
        }
        else
        {
            return false;
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
     * month in digits, \c x the mon in letters, \c d the day. All formats
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
     * \bug
     * At this time, the input string is not checked for correctness.
     *
     * \param[in] $fax_header A string with the header information or null.
     *
     * \return true if the new \p $fax_header parameter is accepted
     * (it must be a string of 80 character or less or null);
     * false otherwise
     */
    function set_fax_header($fax_header)
    {
        if(is_null($fax_header) || is_string($fax_header))
        {
            if(is_string($fax_header) && strlen($fax_header) > 80)
            {
                return false;
            }
            $this->fax_header = $fax_header;
            return true;
        }
        else
        {
            return false;
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
     * \bug
     * At this time, the input string is not checked for correctness.
     * (i.e. a valid URL.)
     *
     * \param[in] $disposition_url A string with the header information or null.
     *
     * \return true when the function saves the disposition URL; false
     * if the \p $disposition_url parameter is invalid (i.e. too large
     * or not a valid string or null.)
     */
    function set_disposition_url($disposition_url)
    {
        if(is_null($disposition_url) || is_string($disposition_url))
        {
            if(is_string($disposition_url) && strlen($disposition_url) > 100)
            {
                return false;
            }
            $this->disposition_url = $disposition_url;
            return true;
        }
        else
        {
            return false;
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
     * \param[in] $disposition_level Logical OR of 0,
     * $efax->RESPOND_ERROR and $efax->RESPOND_SUCCESS
     *
     * \return true when the function accepts the \p $disposition_level
     * parameter; false otherwise
     */
    function set_disposition_level($disposition_level)
    {
        if(is_numeric($disposition_level))
        {
            if(($disposition_level & ~($this->RESPOND_ERROR | $this->RESPOND_SUCCESS)) != 0)
            {
                return false;
            }
            $this->disposition_level = $disposition_level;
            return true;
        }
        else
        {
            return false;
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
            return true;
        }
        else
        {
            return false;
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
     * If the same email is added multiple times, it will be sent
     * multiple times.
     *
     * \exception eFaxException is thrown whenever the parameters
     * are not null or strings.
     *
     * \param[in] $name The name of the recipient (can be null)
     * \param[in] $email The email of the recipient (cannot be null)
     *
     * \return true if the disposition email was added, false if
     * one of the parameters is illegal and no disposition email
     * was saved.
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
            return true;
        }
        else
        {
            return false;
        }
    }

    /** \brief Add a recipient name, company and fax number.
     *
     * This function adds one recipient to the list of recipients of this
     * fax. The name and company name of the recipient are optional. The
     * fax number is mandatory.
     *
     * Internation fax numbers are dialed from the USA and must start
     * with 011 and the country code character. The format of the fax phone
     * number is "[-+ ()0-9]{5,25}". All characters other than digits are
     * ignored. At least 5 characters and at most 25 are necessary.
     *
     * The name and company parameters can be at most 50 characters.
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
     * \param[in] $name The name of the fax recipient (can be null)
     * \param[in] $company The name of the fax recipient company (can be null)
     * \param[in] $fax The fax number where the fax is being sent (cannot be null)
     *
     * \return false if the \p $name, \p $company or \p $fax parameters
     * are not valid, true otherwise
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
            return false;
        }

        $this->recipients[] = array(
                    "name" => $name,
                    "company" => $company,
                    "fax" => $fax
                );
        return true;
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
     * will encode the content for you (base64).
     *
     * The format must be written all in lowercase or all in uppercase
     * (i.e. doc and DOC will work, Doc won't.)
     *
     * Supported file formats:
     *
     * \code
     * doc    MS-Windows
     * xls    Excel spreadsheet
     * tif    Tag Interchange File Format (a TIFF image)
     * pdf    Postscript Description Format file
     * txt    Text only file
     * htm or html    HTML file
     * rtf    Rich Text Format
     * \endcode
     *
     * \param[in] $type One of: doc, xls, tif, pdf, txt, html, htm or rtf.
     * \param[in] $contents The contents of the file (NO file is read from disk!)
     *
     * \return true when the file was added, false otherwise.
     */
    function add_file($type, $contents)
    {
        switch($type)
        {
        case "doc":  case "DOC":
        case "xls":  case "XLS":
        case "tif":  case "TIF":
        case "pdf":  case "PDF":
        case "txt":  case "TXT":
        case "html": case "HTML":
        case "htm":  case "HTM":
        case "rtf":  case "RTF":
            break;

        default:
            return false;

        }
        // we do the base64 when generating the XML since it take more
        // space that we do not need to waste here
        $this->files[] = array(
                    "type" => strtolower($type),
                    "contents" => $contents
                );
        return true;
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
     * This function returns the level of the last error that occured
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
     * This function returns the description of the last error that occured
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
     * When the function is called without first sending a fax, it
     * returns false meaning that it failed.
     *
     * \note
     * If the fax identifier is not available in the FaxControl, then
     * the special string 'n.a.' is returned. Note that this could be
     * viewed as an error since the AccountID tag is marked as mandatory.
     *
     * \return The fax identifier, an empty string, null or false.
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
            return false;
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
     * \return The document identifier or false if no identifier is defined.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_docid()
    {
        if(is_null($this->result_docid))
        {
            return false;
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
     * \return The fax number used in link with this disposition, may be
     * empty or 'Unknown' or some other label when it cannot be determined.
     * The function returns false is no disposition was received.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_fax_number()
    {
        if(is_null($this->result_fax_number))
        {
            return false;
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
     * \bug
     * This value may not be set. Please, check whether the returned
     * reference is -1 before using the Unix timestamp.
     *
     * \return The Unix timestamp when the fax was 100% sent or received
     *            or -1 when not available. The function returns false if
     *            called before a disposition was received.
     */
    function get_result_completion_date()
    {
        if(is_null($this->result_completion_date))
        {
            return false;
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
     * \return The fax disposition or fax request status. The
     * function returns false if the \c eFax::parse_disposition()
     * or \c eFax::parse_inbound_message() functions were never called.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     * \sa eFax::get_result_error_message()
     */
    function get_result_fax_status()
    {
        if(is_null($this->result_fax_status))
        {
            return false;
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
     * \return The recipient fax machine identification, may be an empty
     * string. The function returns false if the \c eFax::parse_disposition()
     * or \c eFax::parse_inbound_message() functions were never called.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_csid()
    {
        if(is_null($this->result_csid))
        {
            return false;
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
     * \return The time the transmission of the fax took in seconds.
     * The function returns false if the \c eFax::parse_disposition()
     * function was never called.
     */
    function get_result_duration()
    {
        if(is_null($this->result_duration))
        {
            return false;
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
     * \return The number of pages that have successfully been sent
     * or received. The function returns false if the
     * \c eFax::parse_disposition() or \c eFax::parse_inbound_message()
     * functions were never called.
     *
     * \sa eFax::parse_disposition()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_pages()
    {
        if(is_null($this->result_pages))
        {
            return false;
        }
        return $this->result_pages;
    }

    /** \brief Return the number of times eFax had to dial to process the fax.
     *
     * This function returns the number of times eFax dial the fax number
     * in order to transmit the documents. A first attempt success is represented
     * by a 1 here.
     *
     * \return The number of attemps made to send all the document pages.
     * This function returns false if the \c eFax::parse_disposition()
     * function was never called.
     *
     * \sa eFax::parse_disposition()
     */
    function get_result_retries()
    {
        if(is_null($this->result_retries))
        {
            return false;
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
     * \return The Unix timestamp when the XML request was sent
     * to your server. The function returns false if the
     * \c eFax::parse_inbound_message() was never called.
     *
     * \sa eFax::get_result_completion_date()
     * \sa eFax::parse_inbound_message()
     */
    function get_result_request_date()
    {
        if(is_null($this->inbound_date))
        {
            return false;
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
     * \return One of "New Inbound" or "Manual Repost", unless it is
     * undefined in which case null is returned. This function returns
     * false if the \c eFax::parse_inbound_message() function was never
     * called.
     */
    function get_result_request_type()
    {
        if(is_null($this->inbound_type))
        {
            return false;
        }
        return $this->inbound_type;
    }

    /** \brief Retrieve the name of the fax received.
     *
     * This function returns the name of the of fax as defined in the
     * eFax request.
     *
     * \return The name of the fax or an empty string if not available.
     * This function returns false if the \c eFax::parse_inbound_message()
     * function was never called.
     *
     * \sa eFax::parse_inbound_message()
     */
    function get_result_fax_name()
    {
        if(is_null($this->inbound_fax_name))
        {
            return false;
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
     * \bug
     * The name and value are not being modified from what they are in
     * the XML message. This means they could include anything such as
     * PHP code or SQL statements.
     *
     * \return The array of field names and values, the array may be empty.
     * This function returns false if the \c eFax::parse_inbound_message()
     * function was never called.
     *
     * \sa eFax::parse_inbound_user_fields()
     */
    function get_result_user_fields()
    {
        if(is_null($this->inbound_user_fields))
        {
            return false;
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
     * it is strongly adviced that you use the page and sequence
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
     * \return The array of barcode objects, the array may be empty.
     * This function returns false if the \c eFax::parse_inbound_message()
     * function was never called.
     *
     * \sa class eFaxBarcode
     * \sa eFax::parse_inbound_barcodes()
     */
    function get_result_barcodes()
    {
        if(is_null($this->inbound_barcodes))
        {
            return false;
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
    /// \brief The URL used to send the request
    var $outbound_url;
    /// \brief The encoding used for the request, should always be UTF-8
    var $outbound_encoding;

    // these are variables that are set by the user before sending the request
    /// \brief This is the eFax fax phone number
    var $account_id;
    /// \brief Your eFax user name (required)
    var $user_name;
    /// \brief Your eFax password (required)
    var $user_password;
    /// \brief The transmission identifier (optional)
    var $fax_id;
    /// \brief Boolean representing ENABLE (default) or DISABLE (optional)
    var $duplicate_id;
    /// \brief STANDARD (default) or FINE (required)
    var $resolution;
    /// \brief NORMAL (default) or HIGH (optional)
    var $priority;
    /// \brief Boolean representing ENABLE (default) or DISABLE (optional)
    var $self_busy;
    /// \brief Null (default) or a string with the fax header info (optional)
    var $fax_header;
    /// \brief uRL where the results are reported (optional)
    var $disposition_url;
    /// \brief Flags ERROR and SUCCESS (required)
    var $disposition_level;
    /// \brief POST or EMAIL (optional)
    var $disposition_method;
    /// \brief List of emails & name pairs (at least 1 if method is EMAIL)
    var $disposition_emails = array();
    /// \brief List of recipient name, company and fax number (at least 1)
    var $recipients = array();
    /// \brief List of files and their type (at least 1)
    var $files = array();

    /// \brief The whole raw response
    var $response;
    /// \brief Status result of the last send() call
    var $status;
    /// \brief Each XML sent is marked with a unique identifier
    var $docid;
    /// \brief In case an error occured when sending the request
    var $error_level;
    /// \brief The description of the error when sending the request
    var $error_description;

    /// \brief The fax identifier (useful to match the sender/receiver)
    var $result_fax_id;
    /// \brief The document identifier from eFax
    var $result_docid;
    /// \brief The recipient fax number
    var $result_fax_number;
    /// \brief The completion date in PST (yyyy-mm-dd hh:mm:ss)
    var $result_completion_date;
    /// \brief Status 0 (success) or an error code
    var $result_fax_status;
    /// \brief Receiving/sending fax station identifier if available
    var $result_csid;
    /// \brief Transmission duration in minutes
    var $result_duration;
    /// \brief Number of pages sent or received
    var $result_pages;
    /// \brief Number of times the fax # was dialed
    var $result_retries;

    /// \brief The time and date when the inbound fax was received
    var $inbound_date;
    /// \brief The type of inbound request
    var $inbound_type;
    /// \brief Fax name given to this fax by eFax Developer as changed by client
    var $inbound_fax_name;
    /// \brief The type used whenever adding a file from the inbound content (add_file)
    var $inbound_file_type;
    /// \brief The array of user fields
    var $inbound_user_fields;

    /// \brief The eFax error messages
    var $err_messages = array(
             0 => array(' ', 'no error occured'),
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
