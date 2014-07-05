<?php
/**
 * @package		muusla_application
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_application buildings Component
 *
 * @package		muusla_application
 */
class muusla_applicationViewpaypal extends JView
{
   function display($tpl = null) {
      $model =& $this->getModel();

      if(JRequest::getVar('tx') == "") {
         echo "FAILURE";
      } else {
         $req = 'cmd=_notify-synch';

         $auth_token = "K3UT0rL4aqQDqrKuuAIZxKj0hcnFl69d-g1giuC7xq6c3TRAOPfrcz9NZ1i";
         $req .= "&tx=" . JRequest::getVar('tx') . "&at=" . $auth_token;

         $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
         $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
         $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
         $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

         if (!$fp) {
            echo "HTTP ERROR";
         } else {
            fputs ($fp, $header . $req);
            // read the body data
            $res = '';
            $headerdone = false;
            while (!feof($fp)) {
               $line = fgets ($fp, 1024);
               if (strcmp($line, "\r\n") == 0) {
                  // read the header
                  $headerdone = true;
               }
               else if ($headerdone)
               {
                  // header has been read. now read the contents
                  $res .= $line;
               }
            }

            // parse the data
            $lines = explode("\n", $res);
            $keyarray = array();
            if (strcmp ($lines[0], "SUCCESS") == 0) {
               for ($i=1; $i<count($lines);$i++){
                  list($key,$val) = explode("=", $lines[$i]);
                  $keyarray[urldecode($key)] = urldecode($val);
               }
               // check the payment_status is Completed
               // check that txn_id has not been previously processed
               // check that receiver_email is your Primary PayPal email
               // check that payment_amount/payment_currency are correct
               // process payment

               $name = $keyarray['first_name'] . " " . $keyarray['last_name'];
               $amount = $keyarray['payment_gross'];
               $this->assignRef('name', $name);
               $this->assignRef('amount', $amount);
               $model->insertCharge(-$amount, $keyarray['custom'], $keyarray['txn_id']);
            }
            else if (strcmp ($lines[0], "FAIL") == 0) {
               //echo "log for manual investigation";
            }
         }
         fclose ($fp);
      }

      parent::display($tpl);
   }

}
?>
