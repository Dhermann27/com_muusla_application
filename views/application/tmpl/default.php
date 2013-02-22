<?php defined('_JEXEC') or die('Restricted access');
$user =& JFactory::getUser();
?>
<div id="ja-content">
   <div class="componentheading">MUUSA Registation Form</div>
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
      rel="stylesheet" />
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/jquery-ui-1.10.0.custom.css"
      rel="stylesheet" />
   <script
      src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
   <script
      src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery-ui-1.10.0.custom.min.js"></script>
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery.scrollTo-1.4.3.1-min.js"></script>
   <script
      src='<?php echo JURI::root(true);?>/components/com_muusla_application/js/application.js'></script>
   <script>var thisyear = <?php echo substr($this->year, -4)?>;</script>
   <form action="<? echo $_SERVER['PHP_SELF'];?>" method="post">
      <div id="muusaApp">
         <?php $familyid = $this->family->familyid ? $this->family->familyid : 0;?>
         <ul>
            <li><a href="#appFamily">Family Information</a></li>
            <li><a href="#appCamper">Camper Listing</a></li>
            <li><a href="#appWorkshop">Workshop Selection</a></li>
            <li><a href="#appPayment">Statement &amp; Payment</a></li>
         </ul>
         <div id="appFamily">
            <table>
               <tr>
                  <td width="25%">
                     <button class="help info right">Show Family Name
                        Help</button> Family Name <input type="hidden"
                     name="family-familyid-<?php echo $familyid?>"
                     value="<?php echo $familyid?>" />
                  </td>
                  <td width="75%"><input type="text"
                     name="family-familyname-<?php echo $familyid;?>"
                     maxlength="30"
                     class="inputtext notempty ui-corner-all"
                     value="<?php echo $this->family->familyname;?>" />
                  </td>
               </tr>
               <tr class="hidden" valign="top">
                  <td><h4>What is a Family Name?</h4></td>
                  <td>
                     <p>Family Name is the display name for your family
                        as a whole. It will appear on your mailing label
                        and be the only entry in the roster,
                        alphabetized by the first letter of this name.</p>
                     <p>Examples:</p>
                     <ul>
                        <li>Most people will choose their family's last
                           name: "Washington".</li>
                        <li>Families with different surnames might
                           choose: "Lincoln / Todd".</li>
                        <li>Please do not add "The" or make your
                           family's name plural: "The Obamas".</li>
                     </ul>
                     <p>If you would like a separate entry in the roster
                        for any member of your family, please register
                        them separately. There is no additional cost for
                        this option.</p>
                  </td>
               </tr>
               <tr>
                  <td>Address Line #1</td>
                  <td><input type="text"
                     name="family-address1-<?php echo $familyid;?>"
                     maxlength="30"
                     class="inputtext notempty ui-corner-all"
                     value="<?php echo $this->family->address1?>" />
                  </td>
               </tr>
               <tr>
                  <td>Address Line #2</td>
                  <td><input type="text"
                     name="family-address2-<?php echo $familyid;?>"
                     maxlength="30" class="inputtext ui-corner-all"
                     value="<?php echo $this->family->address2?>" />
                  </td>
               </tr>
               <tr>
                  <td>City</td>
                  <td><input type="text"
                     name="family-city-<?php echo $familyid;?>"
                     maxlength="30"
                     class="inputtextshort notempty ui-corner-all"
                     value="<?php echo $this->family->city?>" />
                  </td>
               </tr>
               <tr>
                  <td>State</td>
                  <td><select
                     name="family-statecd-<?php echo $familyid;?>"
                     class="notzero ui-corner-all">
                        <option value="0">Choose a State</option>
                        <?php
                        foreach($this->states as $state) {
                           $selected = $this->family->statecd == $state->statecd ? " selected" : "";
                           echo "               <option value='$state->statecd'$selected>$state->name</option>\n";
                        }
                        ?>
                  </select>
                  </td>
               </tr>
               <tr>
                  <td>Zip Code</td>
                  <td><input type="text"
                     name="family-zipcd-<?php echo $familyid;?>"
                     maxlength="10"
                     class="inputtextshort onlydigits notempty ui-corner-all"
                     value="<?php echo $this->family->zipcd?>" />
                  </td>
               </tr>
               <tr>
                  <td>Country</td>
                  <td><input type="text"
                     name="family-country-<?php echo $familyid;?>"
                     maxlength="30" class="inputtextshort ui-corner-all"
                     value="<?php echo $this->family->country?>" />
                  </td>
               </tr>
               <tr>
                  <td>&nbsp;</td>
                  <td colspan="2" align="right"><a id="nextCamper">Next
                        Page</a>
                  </td>
               </tr>
            </table>
         </div>
         <div id="appCamper">
            <table>
               <?php
               if(count($this->campers)== 0) {
                  $index = 0;
                  //$phonenumber = $this->emptyPhonenumber;
                  include 'blocks/camper.php';
               } else {
                  foreach($this->campers as $index => $camper) {
                     include 'blocks/camper.php';
                  }
               }
               $index = -1;
               $camper = new stdClass;
               include 'blocks/camper.php';
               ?>
               <tfoot>
                  <tr>
                     <td colspan="2">
                        <button id="addCamper">Add Another Camper</button>
                     </td>
                     <td colspan="2" align="right">
                        <button id="nextWorkshop">Next Page</button>
                     </td>
                  </tr>
               </tfoot>
            </table>
         </div>
         <div id="appWorkshop">
            <div class="workshopSelection">
               <?php
               if(count($this->campers) > 0) {
                  foreach($this->campers as $index => $camper) {
                     include 'blocks/workshop.php';
                  }
               }
               $index = -1;
               $camper = new stdClass;
               include 'blocks/workshop.php';
               $index = -2;
               $camper = new stdClass;
               include 'blocks/workshop.php';
               ?>
            </div>
            <div align="right">
               <button id="nextPayment">Next Page</button>
            </div>
         </div>
         <div id="appPayment">
            <script>
				<?php
				   echo "               var campDate = new Date('$this->year');\n";
				   echo "				var feeTable = { fees: [\n				";
				   $fees = array();
				   foreach($this->programs as $program) {
				   	  array_push($fees, "{ 'name': '$program->name', 'agemax': $program->agemax, 'agemin': $program->agemin, 'grademax': $program->grademax, 'grademin': $program->grademin, 'fee': $program->registration_fee }");
				   }
				   echo implode(",\n				   ", $fees);
				   echo " ] };\n"; 
				?>
				</script>
            <div id="noattending"
               class="padtop ui-state-error ui-corner-all hidden spaceleft">
               <p>
                  <span class="space left ui-icon ui-icon-alert"></span>
                  No campers of age are marked as "Attending" in the
                  Camper Listing tab. Please return to that tab and
                  choose "Attending" from the drop-down in the
                  upper-right.
               </p>
            </div>
            <table width="98%" align="center">
               <tr align="center">
                  <td width="20%">Charge Type</td>
                  <td width="15%">Amount</td>
                  <td width="15%">Date</td>
                  <td width="50%">Memo</td>
               </tr>
               <?php
               $total = 0.0;
               if($this->charges) {
                  foreach($this->charges as $charge) {
                     echo "           <tr>\n";
                     $total += (float)preg_replace("/,/", "",  $charge->amount);
                     echo "                   <td class='chargetype'>$charge->name</td>\n";
                     echo "                   <td class='amount' align='right'>\$" . $charge->amount . "</td>\n";
                     echo "                   <td class='date' align='center'>$charge->timestamp</td>\n";
                     echo "                   <td class='memo'>$charge->memo</td>\n";
                     echo "                </tr>\n";
                  }
               }
               if($this->credits) {
                  foreach($this->credits as $credit) {
                     echo "           <tr>\n";
                     echo "               <td class='chargetype'>Credit</td>\n";
                     $total -= (float)preg_replace("/,/", "",  $credit->housing_amount+$credit->registration_amount);
                     echo "                <td class='amount' align='right'>\$-" . number_format($credit->housing_amount+$credit->registration_amount, 2) . "</td>\n";
                     echo "                <td>&nbsp;</td>\n";
                     echo "                <td class='memo'><i>$credit->positionname</i></td>\n";
                     echo "           </tr>\n";
                  }
               }
               ?>
               <tr id="paymentDummy" class="hidden">
                  <td class="chargetype"></td>
                  <td class="amount" align="right"></td>
                  <td class="date" align="center"></td>
                  <td class="memo padleft"></td>
               </tr>
               <tr>
                  <td class="chargetype">Donation</td>
                  <td align="right"><input type="text" id="donation"
                     name="charges-amount-0" maxlength="9"
                     class="inputtexttiny onlymoney ui-corner-all" /></td>
                  <td colspan='2' class="memo padleft">Please consider
                     at least a $10.00 donation to the MUUSA Scholarship
                     fund.</td>
               </tr>
               <?php
               echo "           <tr align='right'>\n";
               echo "              <td><strong>Amount Due Now:</strong></td>\n";
               echo "              <td id='amountNow'>$" . number_format($total, 2, '.', '') . "</td>\n";
               echo "              <td colspan='2'>&nbsp;</td>\n";
               echo "           </tr>\n";
               if($this->room != 0) {
                  echo "           <tr align='right'>\n";
                  echo "              <td><strong>Amount Due Upon Arrival:</strong></td>\n";
                  echo "              <td id='amountArrival'>$" . number_format($total, 2, '.', '') . "</td>\n";
                  echo "              <td colspan='2'>&nbsp;</td>\n";
                  echo "           </tr>\n";
               }
               ?>
               <tr>
                  <td colspan="4" align="right">
                     <button id="nextFinish">Complete Registration</button>
                  </td>
               </tr>
            </table>
            <div class="padtop ui-state-highlight ui-corner-all">
               <p>
                  <span class="space left ui-icon ui-icon-info"></span>
                  If you paid a pre-registration deposit or are
                  expecting a staff position credit but do not see it
                  here, it has been associated with a different name or
                  e-mail address. Please contact the Registrar by phone
                  or using the Contact Us link above.
               </p>
            </div>
         </div>
      </div>
      <div id="room-guest" class="dialog-message" title="Guest Room">
         <ul>
            <li>1st through 3rd floors of Trout Lodge</li>
            <li>60 air-conditioned guest rooms</li>
            <li>2 queen beds, rollaway bed, a bathroom with separate
               vanity area, table and chairs</li>
            <li>Walkout balcony or patio with view of the lake</li>
            <li>Limited number of refrigerators available at the front
               desk for those with medical needs. Please notify the
               Registrar if you need a refrigerator.</li>
            <li><i>Handicapped persons will have top priority for
                  handicapped-accessible rooms located on the 1st floor.</i>
            </li>
         </ul>
      </div>
      <div id="room-loft" class="dialog-message" title="Loft Suite">
         <ul>
            <li>4th and 5th floors of Trout Lodge</li>
            <li>19 air-conditioned loft rooms with two levels and stairs</li>
            <li>Guests enter the suite on the upper level</li>
            <li><i>Upper Level</i>: sofa, roll-away bed, sink and
               vanity, table and chairs, small refrigerator</li>
            <li>Stairs in the loft suite lead down to the lower level</li>
            <li><i>Lower Level</i>: 2 queen beds, a bathroom with
               separate vanity area, table and chairs</li>
            <li>Walkout balcony with view of the lake</li>
            <li>Loft suites are recommended for 3 or more adults
               choosing to share housing</li>
         </ul>
      </div>
   </form>
</div>
