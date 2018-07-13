<?php
/**
 * Mail Sender Function
 * @param string $sender
 * @param string $recipient
 * @param string $subject
 * @param string $content
 * @return boolean
 */
function mail_sender($sender, $recipient, $subject, $content)
{
  
  $sanitize_sender = sanitize_email($sender);
  
  // Define Headers
  $email_headers = 'From '. $sanitize_sender . "\r\n" .
                   'Reply-To: '. $sanitize_sender . "\r\n" .
                   'Return-Path: '. $sanitize_sender . "\r\n".
                   'MIME-Version: 1.0'. "\r\n".
                   'Content-Type: text/html; charset=utf-8'."\r\n".
                   'X-Mailer: PHP/' . phpversion(). "\r\n" .
                   'X-Priority: 1'. "\r\n".
                   'X-Sender:'.$sanitize_sender."\r\n";
   
    if (filter_var($recipient, FILTER_SANITIZE_EMAIL)) {
        
        if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            
            $send_mail = mail($recipient, $subject, $content, $email_headers);
            
            if ($send_mail) return true;
            
        }
        
    }
  
    return false;
    
}