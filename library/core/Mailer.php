<?php 
/**
 * Mailer Class
 * Send e-mail via mail php function  
 * 
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Mailer
{

	/**
	 * 1st destination
	 * send e-mail
	 * @var string
	 */
	private $_to;

	/**
	 * 2nd destination
	 * send e-mail
	 * @var string
	 */
	private $_cc;

	/**
	 * 3rd destination
	 * send e-mail
	 * @var string
	 */
	private $_bcc;

	/**
	 * email's sender
	 * @var string
	 */
	private $_from;

	/**
	 * email's subject
	 * @var string
	 */
	private $_subject;

	/**
	 * set properties
	 * for sending text message
	 * @var string
	 */
	private $_sendText;

	/**
	 * set properties
	 * text email message body
	 * @var string
	 */
	private $_textBody;

	/**
	 * set properties
	 * send email as HTML
	 * @var string
	 */
	private $_sendHTML;

	/**
	 * set properties
	 * text HTML message body
	 * @var string
	 */
	private $_HTMLBody;

	/**
	 * Initialize the message parts with blank or default values
	 */
	public function __construct()
	{
		$this->_to   = '';
		$this->_cc   = '';
		$this->_bcc  = '';
		$this->_from = '';
		$this->_subject = '';
		$this->_sendText = true;
		$this->_textBody = '';
		$this->_sendHTML = false;
		$this->_HTMLBody = '';

	}

	/**
	 * set send to
	 * @param string $value
	 */
	public function setSendTo($value)
	{
		$this->_to = $value;
	}

	/**
	 * set send CC
	 * @param string $value
	 */
	public function setSendCc($value)
	{
		$this->_cc = $value;
	}

	/**
	 * set send BCC
	 * @param string $value
	 */
	public function setSendBcc($value)
	{
		$this->_bcc = $value;
	}

	/**
	 * set email's sender
	 * @param string $value
	 */
	public function setFrom($value)
	{
		$this->_from = $value;
	}

	/**
	 * set email's subject
	 * @param string $value
	 */
	public function setSubject($value)
	{
		$this->_subject = $value;
	}

	/**
	 * set whether to send email as text
	 * @param string $value
	 */
	public function setSendText($value)
	{
		$this->_sendText = $value;
	}

	/**
	 * set text email message body
	 * @param string $value
	 */
	public function setTextBody($value)
	{
		$this->_sendText = true;
		$this->_textBody = $value;
	}

	/**
	 * set whether to send email as HTML
	 * @param string $value
	 */
	public function setSendHTML($value)
	{
		$this->_sendHTML = $value;
	}

	/**
	 * set text HTML message body
	 * @param string $value
	 */
	public function setHTMLBody($value)
	{
		$this->_sendHTML = true;
		$this->_HTMLBody = $value;
	}

	/**
	 * Send 
	 * sending email
	 * 
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @param string $headers
	 * @return boolean
	 */
	public function send($to = null, $subject = null, $message = null, $headers = null)
	{
		$success = false;

		if (!is_null($to) && !is_null($subject) && !is_null($message))
		{
			$success = mail($to, $subject, $message, $headers);
			return $success;
		}
		else
		{
			$headers = array();
				
			$eol = PHP_EOL;
				
			if (!empty($this->_from))
			{
				$headers[] = 'From: ' . $this->_from;
			}

			if (!empty($this->_cc))
			{
				$headers[] = 'CC: ' . $this->_cc;
			}

			if (!empty($this->_bcc))
			{
				$headers[] = 'BCC: ' . $this->_bcc;
			}

			if ($this->_sendText && !$this->_sendHTML)
			{
				$message = $this->_textBody;
			}

			elseif (!$this->_sendText && $this->_sendHTML)
			{
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-Type: text/html; charset="utf-8"';
				$headers[] = 'From: <'.APP_EMAIL.'>';
				$headers[] = 'Reply-To: '.APP_EMAIL;

				$message = $this->_HTMLBody;
				
			}
			//Multipart Message in MIME format
			elseif ($this->_sendText && $this->_sendHTML)
			{

				$headers[] = 'MIME-Version: 1.0';
				$headers[] = "From: <".APP_EMAIL.">";
				$headers[] = "Reply-To:".APP_EMAIL;

				$message .= 'Content-Type: text/plain; charset="utf-8"';
				$message .= 'Content-Transfer-Encoding: 7bit';
				$message .= $this->_textBody . "\n";

				$message .= 'Content-Type: text/html; charset="utf-8"' . "\n";
				$message .= 'Content-Transfer-Encoding: 7bit' . "\n";
				$message .= $this->_HTMLBody . "\n";

			}

			$success = mail($this->_to, $this->_subject, $message, implode($eol, $headers));
				
			return $success;

		}
		
	}

}