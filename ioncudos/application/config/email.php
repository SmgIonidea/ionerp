<?php
 $config['protocol']    = 'smtp';
 $config['smtp_host']    = '74.125.68.109';
// $config['smtp_host']    = 'smtp.gmail.com';
 $config['smtp_port']    = '465';
 $config['smtp_crypto'] = 'ssl';
 $config['smtp_timeout'] = '10';
 $config['smtp_user']    = 'ionscpdev@gmail.com';
 $config['smtp_pass']    = 'ionscpdev';
 $config['charset']    = 'utf-8';
 $config['wordwrap']    = 'false';
 $config['newline']    = "\r\n";
 $config['mailtype'] = 'html'; // or html
 $config['validation'] = TRUE; // bool whether to validate email or not      

 $config['from_email'] = 'ionscpdev@gmail.com';
 $config['from_name'] = 'Ioncudos';
 $config['subject'] = 'NBA/SAR Report';
 $config['message'] = 'Please find the NBA/SAR report attached';
 $config['export_path'] = './uploads/nba_reports/';

/*
 * Code has been moved to the controller email_scheduler.
 * In Controller/scheduler/email_scheduler/index.
 * Purpose: For defining multiple email Ids. after a count of 500 Emails the Email Id Changes to Next Email Id and continues to send the Emails.
 */
?>