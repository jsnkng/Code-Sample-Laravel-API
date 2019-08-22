
  Thank you for getting PHP eFax from Made to Order Software.

  To make it work, simply copy the efax.php or efax.php4 file
  on your server. The other files are not necessary to run PHP
  eFax. You are welcome to change the efax.php[4] code to your
  requirements. Also you should not need to. It is a
  class and it is supposed to work as is from the start.

  To see how to implement your own send command, notification
  and inbound fax, please, check our documentation. Start
  with the doc/html/index.html file. The documentation is
  also available online at:

    http://www.m2osw.com/efax_docs

  By default, the documentation is generated hidding the
  private functions and variable members. Variable members
  are not documented, however, all functions are. So if you
  want to get a document with all the functions documented,
  get Doxygen, load the efax.doxy file and set the
  EXTRACT_PRIVATE variable to YES:

    EXTRACT_PRIVATE        = YES

  If you have any question, feel free to contact us. You can
  use our website contact form or email us at contact@m2osw.com
  or call us at 916 988 1450.

  For more information about the PHP eFax licensing, please,
  check out the LICENSE.txt file

  If you want to change the receiving code (one of the inbound
  parse functions) then you can use the unit tests to verify
  that your code behaves as expected. To do so, you should
  just have to run the PHP files under tests/efax-tests/*.php.
  The bin/efax_test shell script can be used for that purpose
  under a Unix system.

