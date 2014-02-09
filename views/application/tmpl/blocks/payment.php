<?php defined('_JEXEC') or die('Restricted access');
/**
 * payment.php
 * XHTML Block containing the camper information block
 *
 * @param   object  $year     The fiscal year of the charges and credits
 * @param   object  $charges  The database charges list for one year
 * @param   object  $credits  The database credits list for one year
 *
 */
$serverCharges = array("1000", "1001", "1002", "1003", "1004", "1016", "1021");
?>
<table id="payments<?php echo $year;?>">
   <tr>
      <td width="20%"><strong>Charge Type</strong></td>
      <td width="15%" align="right"><strong>Amount</strong>
      </td>
      <td width="15%" align="center"><strong>Date</strong></td>
      <td width="50%"><strong>Memo</strong></td>
   </tr>
   <?php
   $total = 0.0;
   $housing = false;
   if($charges) {
      foreach($charges as $charge) {
         if($year != $this->year["year"] || !$this->editcamper || in_array($charge->chargetypeid, $serverCharges)) {
            $housing = $housing || $charge->chargetypeid == 1000;
            echo "           <tr>\n";
            $total += (float)preg_replace("/,/", "",  $charge->amount);
            echo "                   <td class='chargetype' nowrap='nowrap'>$charge->chargetypename</td>\n";
            echo "                   <td class='amount' align='right'>\$$charge->amount</td>\n";
            echo "                   <td class='date' align='center'>$charge->timestamp</td>\n";
            echo "                   <td class='memo'>$charge->memo</td>\n";
            echo "                </tr>\n";
         } else {
            include 'charge.php';
         }
      }
   }
   if(!$this->editcamper) {?>
   <tr>
      <td class="chargetype">Donation <input type="hidden"
         name="charge-chargetypeid-0" value="1008" />
      </td>
      <td align="right"><input type="text" id="donation"
         name="charge-amount-0"
         class="inputtexttiny onlymoney recalc ui-corner-all" />
      </td>
      <td colspan='2' class="memo padleft">Please consider at least a
         $10.00 donation to the MUUSA Scholarship fund. <input
         type="hidden" name="charge-timestamp-0"
         value="<?php echo date("m/d/Y");?>" /> <input type="hidden"
         name="charge-memo-0" value="Thank you for your donation" />
      </td>
   </tr>
   <?php
   } else if($year == $this->year["year"]) {
      $charge = new stdClass;
      $charge->id = 0;
      include 'charge.php';
   }
   echo "           <tr align='right'>\n";
   echo "              <td><strong>Amount Due Now:</strong></td>\n";
   echo "              <td id='amountNow'>$" . number_format($total, 2, '.', '') . "</td>\n";
   echo "              <td colspan='2'>&nbsp;</td>\n";
   echo "           </tr>\n";
   if($housing) {
      echo "           <tr align='right'>\n";
      echo "              <td><strong>Amount Due Upon Arrival:</strong></td>\n";
      echo "              <td id='amountArrival'>$" . number_format($total, 2, '.', '') . "</td>\n";
      echo "              <td colspan='2'>&nbsp;</td>\n";
      echo "           </tr>\n";
   } else if(!$this->editcamper) {
      echo "           <tr>\n";
      echo "              <td colspan='4'><i>Does not include your remaining housing fees, which are due on the first day of camp</i>.</td>\n";
      echo "           </tr>\n";
   }
   ?>
</table>
<?php if(!$this->editcamper) {?>
<table>
   <tr valign="bottom">
      <td colspan="2" align="center"><div>
            Make checks payable to: <strong>MUUSA, Inc.</strong><br />
            Mail check by May 31,
            <?php echo $this->year["year"]?>
            to<br /> MUUSA, Inc.<br />616 W. Fulton #705<br />Chicago,
            IL 60661<br /> <br />
         </div></td>
      <td align="center" style="border-left: 2px dashed black"><div>
            <img src="images/muusa/secure-paypal-logo.png"
               alt="PayPal - The safer, easier way to pay online!" />
         </div>
         <table>
            <tr align="center">
               <td class="small"><a
                  href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>#" onclick="switchNextRow(jQuery(this));">+
                     Pay Another Amount </a>
               </td>
            </tr>
            <tr align="center" class="hidden">
               <td><input type="text" id="paypalAmt"
                  class="inputtexttiny ui-corner-all"
                  name="paypal-amount"
                  value="<?php echo number_format(max($total, 0.0), 2, '.', '');?>" />
               </td>
            </tr>
         </table>
         <button id="finishPaypal">Pay Now via PayPal</button>
      </td>
      <td align="right">
         <button id="finishWorkshop">Save Changes</button>
      </td>
   </tr>
</table>
<?php }?>