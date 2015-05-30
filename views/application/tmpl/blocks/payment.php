<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );
/**
 * payment.php
 * XHTML Block containing the camper information block
 *
 * @param object $year
 *        	The fiscal year of the charges and credits
 * @param object $charges
 *        	The database charges list for one year
 * @param object $credits
 *        	The database credits list for one year
 *        	
 */
?>
<table id="payments<?php echo $year;?>">
	<tr>
		<td width="20%"><strong>Charge Type</strong></td>
		<td width="15%" align="right"><strong>Amount</strong></td>
		<td width="15%" align="center"><strong>Date</strong></td>
		<td width="50%"><strong>Memo</strong></td>
	</tr>
   <?php
			$total = 0.0;
			$housing = false;
			if (count ( $charges ) > 0) {
				foreach ( $charges as $charge ) {
					if ($year != $this->year ["year"] || ! $this->editcamper || $charge->id == 0) {
						$housing = $housing || $charge->chargetypeid == 1000;
						echo "           <tr>\n";
						$total += ( float ) preg_replace ( "/,/", "", $charge->amount );
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
			if (! $this->editcamper) {
				?>
   <tr>
		<td class="chargetype">Donation <input type="hidden"
			name="charge-chargetypeid-0" value="1008" />
		</td>
		<td align="right"><input type="text" id="donation"
			name="charge-amount-0"
			class="inputtexttiny onlymoney recalc ui-corner-all" /></td>
		<td colspan='2' class="memo padleft">Please consider at least a $10.00
			donation to the MUUSA Scholarship fund. <input type="hidden"
			name="charge-timestamp-0" value="<?php echo date("m/d/Y");?>" /> <input
			type="hidden" name="charge-memo-0"
			value="Thank you for your donation" />
		</td>
	</tr>
   <?php
			} else if ($year == $this->year ["year"]) {
				$charge = new stdClass ();
				$charge->id = 0;
				include 'charge.php';
			}
			?>
   <tr align="right">
		<td><strong>Amount Due Now:</strong></td>
		<td id="amountNow">$<?php echo number_format($total, 2, '.', '');?>
      </td>
		<td colspan="2"></td>
	</tr>
   <?php if($housing) {?>
   <tr align="right">
		<td><strong>Amount Due Upon Arrival:</strong></td>
		<td id="amountArrival">$<?php echo number_format($total, 2, '.', '');?>
      </td>
		<td colspan="2">&nbsp;</td>
	</tr>
   <?php } else if(!$this->editcamper) {?>
   <tr>
		<td colspan="4"><i>Does not include your remaining housing fees, which
				are due on the first day of camp</i>.</td>
	</tr>
   <?php }?>
</table>
<?php if(!$this->editcamper) {?>
<table>
	<tr valign="bottom">
		<td colspan="2" align="center"><div>
				Make checks payable to: <strong>MUUSA, Inc.</strong><br />
            Mail check by May 31,
            <?php echo $this->year["year"]?>
            to<br /> MUUSA, Inc.<br />5105 Indian Shores Place<br />Wimauma,
				FL 33598<br /> <br />
			</div></td>
		<td align="center" style="border-left: 2px dashed black"><?php if($this->year["date"]) {?>
         <div>
				<img src="images/muusa/secure-paypal-logo.png"
					alt="PayPal - The safer, easier way to pay online!" />
			</div> <input type="text" id="paypalAmt"
			class="inputtexttiny onlymoney ui-corner-left" name="paypal-amount"
			value="<?php echo number_format(max($total, 0.0), 2, '.', '');?>" />
			<button id="finishPaypal" class="btn">Pay Now via PayPal</button> <?php } else {?>
         We will be accepting<br />Visa or Mastercard payments<br />on
         the first day of camp. <?php }?>
      </td>
		<td align="right">
			<button id="finishWorkshop" class="btn">
            <?php echo $this->sumdays > 0 ? "Save Changes" : "Finish Registration";?>
         </button>
		</td>
	</tr>
</table>
<?php }?>
