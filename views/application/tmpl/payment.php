<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
<div class="componentheading">MUUSA 2012 Registation Form</div>
<table class="blog" cellpadding="0" cellspacing="0">
<?php
echo "      <tr>\n";
echo "         <td valign='top'>\n";
echo "            <div>\n";
echo "            <div class='contentpaneopen'>\n";
echo "               <h2 class='contentheading'>Balance Sheet</h2>\n";
echo "            </div>\n";
echo "            <div class='article-content'>\n";
if($this->hohid == "") {
	echo "            <b>Your information could not be loaded at this time. If you have not registered, please do so by clicking the Registration Form link at the top of your screen.</b>\n";
} else {
	echo "            <table width='98%' align='center'>\n";
	echo "               <tr align='center'>\n";
	echo "                  <td>Charge Type</td>\n";
	echo "                  <td>Amount</td>\n";
	echo "                  <td>Date</td>\n";
	echo "                  <td>Memo</td>\n";
	echo "               </tr>\n";
	$total = 0.0;
	foreach($this->charges as $charge) {
		echo "           <tr>\n";
		$total += (float)preg_replace("/,/", "",  $charge->amount);
		echo "                   <td>$charge->name</td>\n";
		echo "                   <td align='right'>\$" . $charge->amount . "</td>\n";
		echo "                   <td align='center'>$charge->timestamp</td>\n";
		echo "                   <td><i>$charge->memo</i></td>\n";
		echo "                </tr>\n";
	}
	foreach($this->credits as $credit) {
		echo "           <tr>\n";
		echo "               <td>Credit</td>\n";
		$total -= (float)preg_replace("/,/", "",  $credit->housing_amount+$credit->registration_amount);
		echo "                <td align='right'>\$-" . number_format($credit->housing_amount+$credit->registration_amount, 2) . "</td>\n";
		echo "                <td>&nbsp;</td>\n";
		echo "                <td><i>$credit->name</i></td>\n";
		echo "           </tr>\n";
	}
	echo "           <tr>\n";
	echo "              <td colspan='3' align='right'>Amount Due From Camper:</td>\n";
	echo "              <td><h3>$" . number_format($total, 2, '.', '') . "</h3></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td colspan='4' align='center'><i>If you paid a pre-registration deposit or are expecting a staff position credit but do not see it here, \n";
	echo "                 it has been associated with a different name or e-mail address. Please contact the registrar by phone or using the Contact Us link above</i>.</td>\n";
	echo "           </tr>\n";
	if(strtotime("now") < strtotime("27 June 2012")) {
		echo "           <tr>\n";
		echo "              <td colspan='4' align='center'>To complete your application, please submit the above payment \n";
		echo "                 using either of these methods. Your registration will not be fully process until payment is received.</td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td colspan='2'><p>Mail a check, payable to \"MUUSA Inc.\", to the following address:</p>\n";
		echo "                 <p>MUUSA, Inc.<br />6501 Amber Crest<br />Indianapolis, IN 46220-5005</p></td>\n";
		echo "              <td colspan='2' align='center'>\n";
		echo "                 <b>Now accepting online payments via Paypal!</b><br />\n";
		echo "                 <b>Please allow several days for Paypal payments to appear on our site.<br />\n";
		echo "                 <form action='https://www.paypal.com/cgi-bin/webscr' method='post'>\n";
		echo "                    <input type='hidden' name='business' value='MUUSATreasurer@gmail.com'>\n";
		echo "                    <input type='hidden' name='cmd' value='_xclick'>\n";
		echo "                    <input type='hidden' name='item_name' value='Application Fees'>\n";
		echo "                    <input type='hidden' name='amount' value='" . number_format($total, 2, '.', '') . "'>\n";
		echo "                    <input type='hidden' name='currency_code' value='USD'>\n";
		echo "                    <input type='image' name='submit' border='0' src='https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif' alt='PayPal - The safer, easier way to pay online'>\n";
		echo "                    <img alt='' border='0' width='1' height='1' src='https://www.paypal.com/en_US/i/scr/pixel.gif' />\n";
		echo "                 </form>\n";
		echo "              </td>\n";
		echo "           </tr>\n";
	} else {
		echo "           <tr>\n";
		echo "              <td colspan='4' align='center'>To complete your application, please bring the above payment \n";
		echo "                 by check or your credit card to camp. We will process your payment during registration.</td>\n";
		echo "           </tr>\n";
	}
	echo "         </table>\n";
}
echo "         </div>\n";
echo "         <span class='article_separator'>&nbsp;</span>\n";
echo "         </div>\n";
echo "      </td>\n";
echo "   </tr>\n";
?>
</table>
</div>
