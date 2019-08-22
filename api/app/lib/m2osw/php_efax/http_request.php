<?php
/** \file http_request.php
 *
 * \brief http_request class implementation
 *
 *    Implementation of the http_request class used to send HTTP
 *    requests to a website. This is a replacement of the HttpRequest
 *    that was available in PEAR before.
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

/** \brief Exception used in case of a timeout.
 *
 * This may look like a strange name for a timeout exception, but that's
 * somehow what they used in the PEAR HttpRequest module. To be backward
 * compatible with that one, I use the same exception.
 */
class HttpInvalidParamException extends Exception
{
    /** \brief Initialize the exception.
     *
     * This function ensures that the HttpInvalidParamException is
     * full initialized as expected by the user.
     *
     * \param[in] $message  The message of this exception (the error).
     * \param[in] $code  The error code representing this exception (not used internally).
     * \param[in] $previous  The previous exception level (not used internally).
     */
    function __construct($message = "", $code = 0, $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }
};

/** \brief Exception used when detecting an invalid parameter
 *
 * Unfortunately the old scheme used an exception from the PEAR HttpRequest
 * that used the name "invalid param exception" even though it could be
 * thrown when the system timed out.
 *
 * We have a new exception though that we use when we detect an invalid
 * parameter so that way we do not catch it in eFax and thus the caller
 * gets the information.
 */
class http_request_exception extends Exception
{
    /** \brief Initalize the http_request_exception object.
     *
     * This constructor ensures that the http_request_exception is fully
     * initialized.
     *
     * \param[in] $message  The message of this exception (the error).
     * \param[in] $code  The error code representing this exception (not used internally).
     * \param[in] $previous  The previous exception level (not used internally).
     */
    function __construct($message = "", $code = 0, $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }
};

/** \brief The response of a succesfull request.
 *
 * When a request succeeds the http_request object returns an
 * http_response object with the result read from the eFax server.
 */
class http_response
{
    /** \brief Initialize the HTTP response object.
     *
     * This function saves the different parameters in their required
     * fields and return the response.
     *
     * \param[in] $body  The response body.
     */
    function http_response($body)
    {
        $this->body = $body;
    }

    /** \brief Retrieve the body of the HTTP response.
     *
     * This function retrieves the body of the HTTP response.
     *
     * \return The body data received in the response of our request.
     */
    function getBody()
    {
        return $this->body;
    }

    private $body;
};


/** \brief Object to send one request.
 *
 * This class can be used to send ONE request to an e-Fax server.
 * The request may fail in which case an exception is raised. In
 * most cases it is possible to retry to get it to work. The
 * servers may not always respond because too many requests are
 * received all at once and thus your server does not connect.
 * This is normal behavior.
 *
 * Note that one http_request object can be used to send at most
 * ONE request. To send another request, create a new object or
 * you are likely to get surprises.
 *
 * The main function is the send() function, but you should always
 * call the addPostFields() before sending anything. The options
 * currently support the 'redirect' option. If not defined the
 * default is 3.
 */
class http_request
{
    /** \brief Initialize the HTTP request object.
     *
     * This object is in replacement of the PEAR HttpRequest object that
     * require a compiled library which has been discontinued.
     *
     * To further initialize the class, call the setOptions() and addPostFields().
     * The addHeaders() function is a stub so it is not necessary to call it.
     * Since SSL is enforced, only the number of redirects is taken in account
     * from the options.
     *
     * \param[in] $url  The URL used to send the e-Fax request.
     */
    function http_request($url)
    {
        $this->url = $url;
        $this->options = array();
        $this->post_fields = array();
    }

    /** \brief Set options.
     *
     * This function sets the options of the class. Note that each time you
     * call this function all the options are reset by the new ones that
     * you specify when calling.
     *
     * The options are defined in an array with the index defined as the
     * name of the option and the content as the value of the option.
     * For example:
     *
     * \code
     * $request->setOptions(array('redirect', 3));
     * \endcode
     *
     * \param[in] $options  The options to save in this object.
     */
    function setOptions($options)
    {
        $this->options = $options;
    }

    /** \brief Add one or more headers to the object.
     *
     * This function is an empty stub. Headers are always forced in the send()
     * function.
     *
     * \param[in] $headers  Ignored.
     */
    function addHeaders($headers)
    {
    }

    /** \brief Add a set of fields to the request.
     *
     * This function saves fields in the request. Note that it is not
     * currently possible to remove existing fields. This function is
     * cumulative so multiple calls to it adds the new fields (although
     * it will overwrite old fields if the new array includes the same
     * name.)
     *
     * Be careful as the name of fields is viewed as case sensitive at
     * this level. So 'Id' and 'id' are two distinct fields.
     *
     * \code
     * $request->addPostFields(array('id' => 1234));
     * $request->addPostFields(array('username' => 'Charles',
     *                               'password' => 'Henri'));
     * \endcode
     *
     * \param[in] $post_fields  The fields to post in the send() request.
     */
    function addPostFields($post_fields)
    {
        $this->post_fields += $post_fields;
    }

    /** \brief Send the request to the server.
     *
     * This function sends the request to the output server. The server
     * was defined when creating the object (the constructor takes a
     * $url).
     *
     * The function returns an http_response object if the request
     * goes through without errors (reply with "HTTP/1.1 200 OK".)
     * The function supports redirects so on 301 the server information
     * is updated with the Location header and a new request sent.
     * This is ALWAYS done with SSL.
     *
     * \exception http_request_exception is raised whenever an
     * invalid parameter is detected or we received an invalid
     * reply.
     *
     * \exception HttpInvalidParamException is raised whenever the
     * socket times out. This exception is used to be compatible
     * with the PEAR HttpRequest class.
     *
     * \return An http_response object
     */
    function send()
    {
        // transform the fields in a string that can be sent as
        // is in the request (i.e. URL encode the name and value
        // and concatenate with & as the separator.)
        foreach($this->post_fields as $name => $value)
        {
            $f[] = urlencode($name) . '=' . urlencode($value);
        }
        $fields = implode('&', $f);

        $url = $this->url;
        $redirect = false;
        $max_redirect = isset($this->options['redirect']) && is_numeric($this->options['redirect']) ? $this->options['redirect'] : 3;
        $max_redirect = $max_redirect < 1 ? 1 : $max_redirect;
        for($i = 0; $i < $max_redirect; ++$i)
        {
            $redirect = false;

            // define the different server information as required
            $server = parse_url($url);
            // 1. verify scheme
            $server['scheme'] = strtolower($server['scheme']);
            if($server['scheme'] != 'https'
            && $server['scheme'] != 'ssl')
            {
                throw new http_request_exception("Unsupported Internet Scheme; we only support HTTPS, and SSL for the scheme");
            }
            // fsockopen() wants 'ssl' to open a secure SSL connections
            // we force that below anyway; we refuse HTTP though

            // 2. determine host we need to put in the HTTP header
            $host = $server['host'];
            if(empty($server['port']))
            {
                // the default must be 443 because we only connect to HTTPS servers
                $server['port'] = 443;
            }
            if($server['port'] != 80 && $server['port'] != 443)
            {
                // must add port if no one of the defaults
                $host .= ':' . $server['port'];
            }

            // 3. send the request
            $post = "POST " . $server['path'] . (empty($server['query']) ? '' : '?' . $server['query']) . " HTTP/1.1\r\n"
                . "Host: " . $host . "\r\n"
                . "User-Agent: PHP eFax\r\n" // you may want to change the agent to your name
                . "Accept: text/plain;q=0.9,*/*;q=0.8\r\n"
                . "Connection: Close\r\n"
                . "Cache-Control: no-cache, must-revalidate\r\n"
                . "Content-Type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($fields) . "\r\n"
                . "\r\n"
                . $fields . "\r\n";
            $errno = 0;
            $errstr = '';
            $s = fsockopen('ssl://' . $server['host'], $server['port'], $errno, $errstr, 60);
            if(!$s)
            {
                // error
                return false;
            }
            // timeout in 1 min. maximum
            stream_set_timeout($s, 60);
            fwrite($s, $post);

            // 4. read the response
            stream_set_blocking($s, true);
            stream_set_timeout($s, 60);
            $started_on = time();
            $timed_out = false;
            $response = '';
            while(!feof($s))
            {
                // according to the feof() documentation this can happen
                // because the feof() can hang for a while (until timeout
                // is reached) although so far it always happens with the
                // fgets below
                if(time() - $started_on >= 60)
                {
                    $timed_out = true;
                    break;
                }
                stream_set_timeout($s, 60);
                $response .= fgets($s);
                $status = stream_get_meta_data($s);
                if($status['timed_out'])
                {
                    $timed_out = true;
                    break;
                }
                if(feof($s))
                {
                    break;
                }
                if(time() - $started_on >= 60)
                {
                    $timed_out = true;
                    break;
                }
            }
            fclose($s);

            if($timed_out)
            {
//echo date('r'), " timed out!\n";
                throw new HttpInvalidParamException("The transaction timed out");
            }

            // 5. parse the response
            $lines = explode("\n", $response);
            $body = '';
            $state = 0;
            $code = 0;
            foreach($lines as $line)
            {
                // get next line and remove any \r\n or spaces
                $l = trim($line);
                switch($state)
                {
                case 0: // response (first line such as HTTP/1.1 200 OK)
                    $scheme = strtolower(substr($l, 0, 9));
                    if($scheme != 'http/1.0 '
                    && $scheme != 'http/1.1 ')
                    {
                        throw new http_request_exception("Invalid scheme in response");
                    }
                    $code = substr($l, 9, 3);
                    if(!is_numeric($code))
                    {
                        throw new http_request_exception("Invalid code in response, not a number");
                    }
                    switch($code)
                    {
                    case 200: // if necessary, add 201 and 202
                        // it worked!
                        break;

                    case 301:
                    case 302:
                        // server wants a redirect
                        // change to 301 because we do not need to
                        // distinguish the type of redirect
                        $code = 301;
                        break;

                    default: // special handling of 401 and 404?
                        // some error or at least unsupported code
                        throw new http_request_exception("Unsupported code in response (" . $code . ")");

                    }
                    $state = 1;
                    break;

                case 1: // headers (such as Location: http://www.m2osw.com)
                    // we ignore the other headers, but 301 we want to
                    // handle so we support redirects
                    if($code == 301)
                    {
                        $header = explode(':', $l, 2);
                        if(count($header) == 2 && strtolower($header[0]) == 'location')
                        {
                            // found the redirect
                            $url = trim($header[1]);
                            $redirect = true;
                            break;
                        }
                    }
                    if(!$l)
                    {
                        if($code == 301)
                        {
                            throw new http_request_exception("Got a 301 or 302 and no Location field in the answer");
                        }
                        // end of header detected
                        $state = 2;
                    }
                    break;

                case 2: // body (anything, really, for efax we expect ASCII only though)
                    // in case of the body we keep the lines as is
                    // (we even put the "\n" back in)
                    $body .= $line . "\n";
                    break;

                }
                if($redirect)
                {
                    break;
                }
            }

            if(!$redirect)
            {
                break;
            }
        }
        if($redirect)
        {
            throw new http_request_exception("Too many or forbidden redirects");
        }

//echo "Response: [$body]\n";
        return new http_response($body);
    }

    /// The server information to send requests
    private $url;
    /// Options to tweak the behavior, only support 'redirect' at this time
    private $options;
    /// The array of fields to send in the request
    private $post_fields;
};

// vim: ts=4 sw=4
?>
