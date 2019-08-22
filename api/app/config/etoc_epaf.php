<?php
return array(

    //****** debug flag true = development mode, false = production mode. pediatrician fax and rep emails are controlled by this.
   // 'debug' => false,
    'debug' => true,
    'environment_name' => 'configuration',
    //****** email address if not set and debug is false then app will send the notifivations to the rep email
     'email' => 'Rep Test email would be Rep if Production <cdmprinceton@gmail.com>',
    // 'email' => '',
    //cc not in production but would be the cc address
   // 'cc' => 'cdmprinceton@gmail.com',

    //****** email address where rep emails for zip codes that dont exist will be delivered
    'rep_email_no_zip' => 'Representative Jane Doe No Zip Code <cdmprinceton@gmail.com>',

    //****** pediatrician number if not set and debug is false then app will use pediatrician number from form
    'toc_ped_fax' => '6092285521',

    //outgoing fax number
    'toc_outgoing_fax' => '6092285521',
    'paf_outgoing_fax' => '6092285521',


    //rx crossroad number
    'toc_rxc_fax' => '6092285521',
    'paf_rxc_fax' => '6092285521',

    //efax disposition endpoints
    'toc_fax_disposition' => 'https://synagis-toc-dev.med.cdm210.com/api/post_etoc_fax_disposition',
    'paf_fax_disposition' => 'https://synagis-toc-dev.med.cdm210.com/api/post_epaf_fax_disposition',

    //CWC servlet endpoint
    'cwc_url' => 'https://stage.cradlewithcare.com/etoc_epaf/Registration?action=register',


    //toc testing flags true will disable the features
    'toc_testing_fax' => false,
    'toc_testing_cwc' => false,
    'toc_testing_email' => false,
    'toc_testing_db' => false,

    //toc application type
    'toc_application_type' => 'synagistoc',

    //toc pdfs
    'toc_usr_pdf' => app_path().'/views/pdfs/eTOC/eTOCUser.pdf',
    'toc_usr_pdf_sp' => app_path().'/views/pdfs/eTOC/eTOCUser_sp.pdf',
    'toc_ped_pdf' => app_path().'/views/pdfs/eTOC/eTOCPediatrician.pdf',
    'toc_rxc_pdf' => app_path().'/views/pdfs/eTOC/eTOCRxCrossroads.pdf',

    //paf testing flags true will disable the features
    'paf_testing_fax' => false,
    'paf_testing_cwc' => false,
    'paf_testing_db' => false,

    //paf application type
    'paf_application_type' => 'synagispaf',

    //paf pdfs
    'paf_usr_pdf' => app_path().'/views/pdfs/ePAF/ePAFUser.pdf',
    'paf_usr_pdf_sp' => app_path().'/views/pdfs/ePAF/ePAFUser_sp.pdf',
    'paf_rxc_pdf' => app_path().'/views/pdfs/ePAF/ePAFRxCrossroads.pdf',


    //mailgun
    'mgKey' => 'key-7-s8f3dunycclj-f7s4wvf3mvyk-7wd7',
    'mgDomain' => 'etoc-medimmune.com',



);