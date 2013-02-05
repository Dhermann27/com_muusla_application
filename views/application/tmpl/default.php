<?php defined('_JEXEC') or die('Restricted access');
$user =& JFactory::getUser();
?>
<div
   id="ja-content">
   <div class="componentheading">MUUSA Registation Form</div>
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
      rel="stylesheet" />
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/jquery-ui-1.10.0.custom.css"
      rel="stylesheet" />
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery-1.9.0.js"></script>
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery-ui-1.10.0.custom.js"></script>
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
                     maxlength="30" class="inputtext ui-corner-all"
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
                     maxlength="30" class="inputtext ui-corner-all"
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
                     maxlength="30" class="inputtextshort ui-corner-all"
                     value="<?php echo $this->family->city?>" />
                  </td>
               </tr>
               <tr>
                  <td>State</td>
                  <td><select
                     name="family-statecd-<?php echo $familyid;?>"
                     class="ui-corner-all">
                        <option>Choose a State</option>
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
                     maxlength="10" class="inputtextshort ui-corner-all"
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
               <?php $camperid = $this->campers[0] ? $this->campers[0]->camperid : 0;?>
               <tbody <?php if($camperid != 0) echo "id='$camperid' "?>
                  class="camperBody">
                  <tr valign="bottom">
                     <td width="25%"><select
                        name="campers-sexcd-<?php echo $camperid;?>"
                        class="ui-corner-all">
                           <option value="0">Gender</option>
                           <option value="M"
                           <?php echo $this->campers[0]->sexcd == "M" ? " selected" : "";?>>Male</option>
                           <option value="F"
                           <?php echo $this->campers[0]->sexcd == "F" ? " selected" : "";?>>Female</option>
                     </select> <input type="hidden"
                        name="campers-camperid-<?php echo $camperid;?>"
                        value="<?php echo $camperid;?>" />
                     </td>
                     <td colspan="3" width="75%" align="right">
                        <button class="help info">Show Attending Help</button>
                        <select
                        name="campers-attending-<?php echo $camperid;?>"
                        class="attending ui-corner-all">
                           <option value="7" selected="selected">Attending</option>
                           <option value="0">Not Attending</option>
                     </select>
                     </td>
                  </tr>
                  <tr class="hidden" valign="top">
                     <td><h4>What does "Attending" mean?</h4></td>
                     <td colspan="3">
                        <p>Use this option if you have a person who is
                           in your family, but is not attending MUUSA
                           this year. This person will not be charged
                           any fees and will not appear on the roster.</p>
                        <p>If this person is not in your family or is
                           registering themselves this year, please use
                           the Contact Us link above to send a message
                           to the Registrar letting us know.</p>
                     </td>
                  </tr>
                  <tr>
                     <td>First Name</td>
                     <td colspan="3"><input type="text"
                        name="campers-firstname-<?php echo $camperid;?>"
                        maxlength="30"
                        class="inputtext firstname ui-corner-all"
                        value="<?php echo $this->campers[0]->firstname;?>" />
                     </td>
                  </tr>
                  <tr>
                     <td>Last Name</td>
                     <td colspan="3"><input type="text"
                        name="campers-lastname-<?php echo $camperid;?>"
                        maxlength="30"
                        class="inputtext lastname ui-corner-all"
                        value="<?php echo $this->campers[0]->lastname;?>" />
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <button class="help info right">Show E-mail
                           Address Help</button> Email Address
                     </td>
                     <?php 
                     echo "								<td colspan='3'>$user->email\n";
                     echo "                                 <input type='hidden' name='campers-email-$camperid' value='$user->email' />\n";
                     echo "                              </td>\n";
                     ?>
                  </tr>
                  <tr class="hidden" valign="top">
                     <td><h4>Why can't I change my email address?</h4></td>
                     <td colspan="3">
                        <p>We need the same email as your
                           http://muusa.org account. This lets us
                           connect our camper database to the website
                           user database.</p>
                     </td>
                  </tr>
                  <?php
                  if(count($this->campers[0]->phonenbrs) == 0) {
                     $index = 0;
                     $phonenumber = $this->emptyPhonenumber;
                     include 'blocks/phonenumber.php';
                  } else {
                     foreach($this->campers[0]->phonenbrs as $index => $phonenumber) {
                        include 'blocks/phonenumber.php';
                     }
                  }
                  $index = -1;
                  $phonenumber = $this->emptyPhonenumber;
                  include 'blocks/phonenumber.php';
                  ?>
                  <tr>
                     <td>Birthday</td>
                     <td><input type="text" maxlength="10"
                        name="campers-birthdate-<?php echo $camperid;?>"
                        class="birthday ui-corner-all"
                        value="<?php echo $this->campers[0]->birthday;?>" />
                     </td>
                     <td align="right">Grade Entering in Fall <?php echo substr($this->year, -4)?>
                     </td>
                     <td><select
                        name="campers-grade-<?php echo $camperid;?>"
                        class="grade ui-corner-all">
                           <?php 						
                           echo "                        <option value='13'>Not Applicable</option>\n";
                           echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
                           for($i=1; $i<13; $i++) {
                              $selected = min($this->campers[0]->grade, 13) == $i ? " selected" : "";
                              echo "                        <option value='$i'$selected>$i</option>\n";
                           }
                           ?>
                     </select>
                     </td>
                  </tr>
                  <tr>
                     <td>Room Type Preferences</td>
                     <td colspan="3">
                        <div class="roomtypes">
                           <h4>Click here, then drag in order of
                              preference from right to left.</h4>
                           <div>
                              <div class="right">
                                 <h5>Room Type List</h5>
                                 <ul
                                    class="connected connectedRoomtype roomtype-no">
                                    <?php 
                                    foreach($this->buildings as $buildingid => $building) {
                                       if(!in_array($buildingid, $this->campers[0]->roomtypes)) {
                                          echo "                  <li value='$buildingid' class='ui-state-default'>\n";
                                          // echo "                     <button class='help link right'>Show $building->name Information</button>\n";
                                          echo "                     " . $building["name"] . "\n";
                                          echo "                  </li>\n";
                                       }
                                    }
                                    ?>
                                 </ul>
                              </div>
                              <div>
                                 <h5>Desired Room Type</h5>
                                 <ul
                                    class="connected connectedRoomtype roomtype-yes">
                                    <?php
                                    foreach($this->campers[0]->roomtypes as $roomtype) {
                                       echo "                  <li value='$roomtype' class='ui-state-default'>\n";
                                       // echo "                     <button class='help link right'>Show " . $this->buildings[$roomtype]->name . " Information</button>\n";
                                       echo "                     " . $this->buildings[$roomtype]["name"] . "\n";
                                       echo "                  </li>\n";
                                    }
                                    ?>
                                    <li class="ui-state-default">No
                                       Preference</li>
                                 </ul>
                              </div>
                              <button
                                 class="roomtypeSave clearboth right">Save
                                 Preferences</button>
                           </div>
                        </div>
                     </td>
                  </tr>
                  <?php
                  if(count($this->campers[0]->roommates) == 0) {
                     $index = 0;
                     $name = "";
                     include 'blocks/roommate.php';
                  } else {
                     foreach($this->campers[0]->roommates as $index => $name) {
                        include 'blocks/roommate.php';
                     }
                  }
                  $index = -1;
                  $name = "";
                  include 'blocks/roommate.php';
                  ?>
                  <tr>
                     <td>Accessibility</td>
                     <td colspan="2" valign="middle">Do you require a
                        room accessible by the disabled?</td>
                     <td><select
                        name="campers-is_handicap-<?php echo $camperid;?>"
                        class="ui-corner-all">
                           <option value="1"
                           <?php echo $this->campers[0]->is_handicap == "1" ? " selected" : "";?>>Yes</option>
                           <option value="0"
                           <?php echo $this->campers[0]->is_handicap == "1" ? "" : " selected";?>>No</option>
                     </select>
                     </td>
                  </tr>
                  <tr>
                     <td>Food Options</td>
                     <td colspan="2" valign="middle">Which option best
                        describes your eating restrictions?</td>
                     <td><select
                        name="campers-foodoptionid-<?php echo $camperid;?>"
                        class="ui-corner-all">
                           <?php
                           foreach ($this->foodoptions as $foodoption) {
                              $selected = $this->campers[0]->foodoptionid == $foodoption->foodoptionid ? " selected" : "";
                              echo "                  <option value='$foodoption->foodoptionid'$selected>$foodoption->name</option>\n";
                           }
                           ?>
                     </select>
                     </td>
                  </tr>
                  <tr>
                     <td>Smoking Preference</td>
                     <td colspan="2" valign="middle">What is your
                        smoking preference, if assigned a roommate?</td>
                     <td><select
                        name="campers-smokingoptionid-<?php echo $camperid;?>"
                        class="ui-corner-all">
                           <?php
                           foreach ($this->smokingoptions as $smokingoption) {
                              $selected = $this->campers[0]->smokingoptionid == $foodoption->smokingoptionid ? " selected" : "";
                              echo "                  <option value='$smokingoption->smokingoptionid'$selected>$smokingoption->name</option>\n";
                           }
                           ?>
                     </select>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <button class="help info right">Show Sponsor
                           Help</button> Sponsor
                     </td>
                     <td colspan="3"><input type="text" maxlength="30"
                        name="campers-sponsor-<?php echo $camperid;?>"
                        class="inputtext ui-corner-all"
                        value="<?php echo $this->campers[0]->sponsor;?>" />
                     </td>
                  </tr>
                  <tr class="hidden" valign="top">
                     <td><h4>When is a sponsor required?</h4></td>
                     <td colspan="3">
                        <p>A sponsor is required if the camper will be
                           under the age of 18 on the first day of camp
                           and a parent or legal guardian is not
                           attending for the entire length of time that
                           the camper will be on YMCA property. A
                           sponsor is asked to attend the informational
                           meetings in the parents' stead, and if the
                           camper is asked to leave for any reason, the
                           sponsor will be required to assist the camper
                           home.</p>
                        <p>If you are having difficulty finding a
                           sponsor, please let us know using the Contact
                           Us form above. Oftentimes, we have adults in
                           the area who are willing to volunteer, and
                           may also be willing to offer transportation.</p>
                     </td>
                  </tr>
                  <tr>
                     <td>Church Affiliation</td>
                     <td colspan="3"><?php
                     echo "                     <select name='campers-churchid-$camperid' class='ui-corner-all'>\n";
                     echo "                     <option value='0'>No Affiliation</option>\n;";
                     foreach ($this->churches as $church) {
                        $selected = $this->campers[0]->churchid == $church->churchid ? " selected" : "";
                        echo "                  <option value='$church->churchid'$selected>$church->statecd - $church->city: $church->name</option>\n";
                     }
                     echo "                  </select></td>\n";
                     ?>
                     </td>
                  </tr>
                  <!-- 						<tr> -->
                  <!-- 							<td colspan="4"> -->
                  <!-- 								<button id="removeCamper">Remove This Camper</button> -->
                  <!-- 								<hr /> -->
                  <!-- 							</td> -->
                  <!-- 						</tr> -->
               </tbody>
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
               <h4>Camper Name</h4>
               <div class="workshopTimes">
                  <?php 
                  foreach($this->times as $timeid => $time) {
                     echo "					   <h5>" . $time["name"] . "</h5>\n";
                     echo "                     <div>\n";
                     echo "                        <div class='right'>\n";
                     echo "                           <h6>Available Workshops</h6>\n";
                     echo "                           <ul class='connected connectedWorkshop workshop-no'>\n";
                     if($time["shops"]) {
                        foreach($time["shops"] as $shop) {
                           echo "                              <li value='$shop->eventid' class='ui-state-default'>\n";
                           // echo "                              <button class='help link right'>Show $building->name Information</button>\n";
                           echo "                                 $shop->name ($shop->days)\n";
                           echo "                              </li>\n";
                        }
                     }
                     echo "                           </ul>\n";
                     echo "                        </div>\n";
                     echo "                        <div class='desired'>\n";
                     echo "                           <h6 class='$timeid'>Desired Workshops (in order of preference)</h6>\n";
                     echo "                           <ul class='connected connectedWorkshop workshop-yes'>\n";
                     echo "                           </ul>\n";
                     echo "                        </div>\n";
                     echo "                     </div>\n";
                  }
                  ?>
                  <h5>Volunteer Opportunities</h5>
                  <div>
                     <div class="right">
                        <h6>Available Volunteer Positions</h6>
                        <ul
                           class="connected connectedWorkshop workshop-no">
                           <?php
                           foreach($this->positions as $position) {
                              echo "                              <li class='ui-state-default'>\n";
                              // echo "                              <button class='help link right'>Show $building->name Information</button>\n";
                              echo "                                 $position->name\n";
                              echo "                                 <input type='hidden' name='volunteers-eventids-$camper->camperid' value='$position->positionid' />\n";
                              echo "                              </li>\n";
                           }
                           ?>
                        </ul>
                     </div>
                     <div class="volunteers">
                        <h6>Desired Roles</h6>
                        <ul
                           class="connected connectedWorkshop workshop-yes">
                        </ul>
                     </div>
                  </div>
               </div>
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
					      echo "                <td class='memo'><i>$credit->name</i></td>\n";
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
                     class="inputtexttiny ui-corner-all" /></td>
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
                  e-mail address. Please contact the registrar by phone
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
   <!-- <div id='nastygram' -->
   <!-- 	style='position: absolute; display: none; top: 25px; left: 33%; width: -->
   <!-- 	33%; border: 2px dashed black; background: white; padding: 10px;'> -->
   <!-- <h4><i>All returning campers should have their information prepopulated -->
   <!-- into this form. If you do not see this, please login using the username -->
   <!-- found on the bottom of the mailing label of your paper brochure, Logout -->
   <!-- and use the Forgot Your Username? tool in the lower left, or use the -->
   <!-- Contact Us tool above to send the webmaster an e-mail about the -->
   <!-- discrepancy. Thank you.</i></h4> -->
   <!-- <p align="center"><input type="button" -->
   <!-- 	onclick='document.getElementById("nastygram").style.display = "none";' -->
   <!-- 	value='Close' /> -->
   <!-- </div> -->
   <!-- <form name='application' -->
   <!-- 	action="index.php?option=com_muusla_application&task=detail&view=application&Itemid=72" -->
   <!-- 	method="post"> -->
   <!-- <table class="blog" cellpadding="0" cellspacing="0"> -->
   //
   <?php
	// if($this->camper) {
	// 	$camper = $this->camper;
	// 	echo "      <input type='hidden' name='hohid' value='$camper->camperid' />\n";
	// }
	// if(!$camper->camperid) {
	// 	$camper->camperid = 0;
	// 	echo "<script language='JavaScript'>setTimeout(\"document.getElementById('nastygram').style.display = 'block';\", 3000);</script>\n";
	// }
	// $user =& JFactory::getUser();
	// echo "      <input type='hidden' name='campers-camperid-$camper->camperid' value='$camper->camperid' />\n";
	// echo "      <tr>\n";
// echo "         <td valign='top'>\n";
// echo "            <div>\n";
// echo "            <div class='contentpaneopen'>\n";
// echo "               <h2 class='contentheading'>Your Application</h2>\n";
// echo "            </div>\n";
// echo "            <div class='article-content'>\n";
// if($camper->hohid != 0 && !$camper->not_attending) {
// 	echo "            <h4><font color='indianred'>The primary camper on your account has not registered yet. In order to complete your registration, please have your the primary camper complete his or her registration, or use the Contact Us form above to have the Webmaster change your primary to you.</font></h4>\n";
// }
// if($camper->room != null) {
// 	echo "            <h4><font color='indianred'>Your room has been assigned. If you need to change any information about your registration, please use the Contact Us link above and send a message to the Registrar.</font></h4>\n";
// 	$dis = " disabled";
// }
// echo "            <table width='100%'>\n";
// echo "            <tbody class='camper'>\n";
// echo "               <tr>\n";
// if($camper->sexcd == "M") {
// 	$sexcdm = "selected";
// } elseif($camper->sexcd == "F") {
// 	$sexcdf = "selected";
// }
// echo "                  <td><select name='campers-sexcd-$camper->camperid'$dis>\n";
// echo "                     <option value='0'>Gender</option>\n";
// echo "                     <option value='M'$sexcdm>Male</option>\n";
// echo "                     <option value='F'$sexcdf>Female</option>\n";
// echo "                  </select><font color='red'> *</font></td>\n";
// echo "                  <td align='right'>First Name: </td>\n";
// echo "                  <td><input type='text' name='campers-firstname-$camper->camperid' value='$camper->firstname' size='20' maxlength='20'$dis /> <font color='red'>*</font></td>\n";
// echo "                  <td align='right'>Last Name: </td>\n";
// echo "                  <td><input type='text' name='campers-lastname-$camper->camperid' value='$camper->lastname' size='25' maxlength='30'$dis /> <font color='red'>*</font></td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'>Address Line 1: </td>\n";
// echo "                  <td colspan='3'><input type='text' name='campers-address1-$camper->camperid' value='$camper->address1' size='50' maxlength='50'$dis /> <font color='red'>*</font></td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'>Address Line 2: </td>\n";
// echo "                  <td colspan='3'><input type='text' name='campers-address2-$camper->camperid' value='$camper->address2' size='50' maxlength='50'$dis /></td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'>City: </td>\n";
// echo "                  <td colspan='3'><input type='text' name='campers-city-$camper->camperid' value='$camper->city' size='50' maxlength='30'$dis /> <font color='red'>*</font></td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'>State: </td>\n";
// echo "                  <td colspan='3'><select name='campers-statecd-$camper->camperid'$dis>\n";
// echo "                     <option value='0'>Select a State</option>\n";
// echo "                  </select> <font color='red'>*</font>\n";
// echo "                  Zip: <input type='text' name='campers-zipcd-$camper->camperid' value='$camper->zipcd' size='10' maxlength='10'$dis /> <font color='red'>*</font>\n";
// echo "                  Country: <input type='text' name='campers-country-$camper->camperid' value='$camper->country' ize='10' maxlength='40'$dis /></td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'>Email Address: </td>\n";
// echo "                  <td colspan='3'>$user->email\n";
// echo "                     <input type='hidden' name='campers-email-$camper->camperid' value='$user->email' /></td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_ecomm-$camper->camperid' value='1'$camper->is_ecomm/></td>\n";
// echo "                  <td colspan='3'>Check this box if you prefer to receive electronic communication from MUUSA via the above e-mail address.</td>\n";
// echo "               </tr>\n";
// foreach($this->camper->phonenbrs as $phonenumber) {
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>Phone Number: </td>\n";
// 	echo "                  <td colspan='3'><select name='phonenumbers-phonetypeid-$phonenumber->phonenbrid'>\n";
// 	foreach($this->phonetypes as $phonetype) {
// 		if($phonenumber->phonetypeid == $phonetype->phonetypeid) {
// 			$selected = " selected";
// 		} else {
// 			$selected = "";
// 		}
// 		echo "                     <option value='$phonetype->phonetypeid'$selected>$phonetype->name</option>\n";
// 	}
// 	echo "                     </select>\n";
// 	echo "                     <input type='hidden' name='phonenumbers-camperid-$phonenumber->phonenbrid' value='$camper->camperid' />\n";
// 	echo "                     <input type='text' name='phonenumbers-phonenbr-$phonenumber->phonenbrid' value='$phonenumber->phonenbr' size='20' />\n";
// 	if($phonenumber->phonenbrid >= 1000) {
// 		echo "                     <input type='checkbox' name='phonenumbers-delete-$phonenumber->phonenbrid' value='delete' /> Delete this phone number\n";
// 	}
// 	echo "                  </td>\n";
// 	echo "               </tr>\n";
// }
// if($camper->birthdate) {
// 	$grade = $camper->age + $camper->gradeoffset;
// }
// if(!$camper->birthdate || $camper->hohid == "0" || $camper->age > 17) {
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>Birthdate (MM/DD/YYYY): </td>\n";
// 	echo "                  <td><input type='text' name='campers-birthdate-$camper->camperid' value='$camper->birthdate' size='20' maxlength='10'$dis /> <font color='red'>*</font></td>\n";
// 	echo "                  <td colspan='2'>Grade Entering in Fall 2012: \n";
// 	echo "                     <select name='campers-grade-$camper->camperid'$dis>\n";
// 	echo "                        <option value='0'>Not Applicable</option>\n";
// 	echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
// 	for($i=1; $i<13; $i++) {
// 		if($grade == $i) {
// 			$selected = " selected";
// 		} else {
// 			$selected = "";
// 		}
// 		echo "                        <option value='$i'$selected>$i</option>\n";
// 	}
// 	echo "                     </select>\n";
// 	echo "                  </td>";
// 	echo "               </tr>\n";
// } else {
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>Birthdate: </td>\n";
// 	echo "                  <td>$camper->birthdate\n";
// 	echo "                     <input type='hidden' name='campers-birthdate-$camper->camperid' value='$camper->birthdate' /></td>\n";
// 	echo "                  <td colspan='2'>Grade Entering in Fall 2012: \n";
// 	echo "                     <input type='hidden' name='campers-grade-$camper->camperid' value='$camper->grade' />\n";
// 	echo "                     <select name='dummy' disabled>\n";
// 	echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
// 	for($i=1; $i<13; $i++) {
// 		if($grade == $i) {
// 			$selected = " selected";
// 		} else {
// 			$selected = "";
// 		}
// 		echo "                        <option value='$i'$selected>$i</option>\n";
// 	}
// 	echo "                     </select>\n";
// 	echo "                  </td>";
// 	echo "               </tr>\n";
// }
// echo "               <tr>\n";
// echo "                  <td>&nbsp;</td>\n";
// echo "                  <td colspan='4'>If you would like to help MUUSA, please consider volunteering for up to three positions.\n";
// echo "                     Keep in mind that these are purely volunteer positions and offer no compensation. The program coordinator \n";
// echo "                     will contact you. Hold the CTRL key to select multiple items.</td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'>Volunteer Interest:\n";
// echo "                  <td colspan='3'><select name='volunteers-positionid-$camper->camperid[]' size='5' multiple />\n";
// foreach($this->positions as $position) {
// 	$selected = "";
// 	if(count($this->volunteers) > 0) {
// 		foreach($this->volunteers as $volunteer) {
// 			if($volunteer->camperid == $camper->camperid && $volunteer->positionid == $position->positionid) {
// 				$selected = " selected";
// 			}
// 		}
// 	}
// 	echo "                        <option value='$position->positionid'$selected>$position->name</option>\n";
// }
// echo "                  </select></td>\n";
// echo "               </tr>\n";
// if(!$camper->birthdate || $camper->hohid == "0") {
// 	echo "               <tr>\n";
// 	echo "                  <td>&nbsp;</td>\n";
// 	echo "                  <td colspan='4'>Campers who choose their previous year's housing type as their first preference will, if at all possible, be given the same room as well.</td>\n";
// 	echo "               </tr>\n";
// 	for($i=1; $i<4; $i++) {
// 		eval("\$roompref = \$camper->roomprefid$i;");
// 		echo "               <tr>\n";
// 		echo "                  <td colspan='2' align='right'>Room Preference #$i:</td>\n";
// 		echo "                  <td colspan='3'><select name='campers-roomprefid$i-$camper->camperid'>\n";
// 		echo "                     <option value='0'>No Preference</option>\n";
// 		foreach ($this->buildings as $building) {
// 			if($building->buildingid == $roompref) {
// 				$selected = " selected";
// 			} else {
// 				$selected = "";
// 			}
// 			echo "                        <option value='$building->buildingid'$selected>$building->name</option>\n";
// 		}
// 		echo "                  </select></td>\n";
// 		echo "               </tr>\n";
// 	}
// 	echo "               <tr>\n";
// 	echo "                  <td>&nbsp;</td>\n";
// 	echo "                  <td colspan='4'>Singles without roommates will be housed with other campers \n";
// 	echo "                      having similar preferences. Pairs will only be housed with other pairs by request.</td>\n";
// 	echo "               </tr>\n";
// 	for($i=1; $i<4; $i++) {
// 		eval("\$matepref = \$camper->matepref$i;");
// 		echo "               <tr>\n";
// 		echo "                  <td colspan='2' align='right'>Roommate Preference #$i: </td>\n";
// 		echo "                  <td colspan='3'><input type='text' name='campers-matepref$i-$camper->camperid' size='50' maxlength='50' value='$matepref' /></td>\n";
// 		echo "               </tr>\n";
// 	}
// }
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_handicap-$camper->camperid' value='1'$camper->is_handicap/></td>\n";
// echo "                  <td colspan='3'>Check this box if you require lodging in a handicap-accessible room.</td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'>\n";
// echo "                  <input type='checkbox' name='campers-is_ymca-$camper->camperid' value='1'$camper->is_ymca/></td>\n";
// echo "                  <td colspan='3'>Check this box if any members of your family are applying for YMCA scholarships.</td>\n";
// echo "               </tr>\n";
// echo "               <tr>\n";
// echo "                  <td colspan='2' align='right'>Food Option: </td>\n";
// echo "                  <td colspan='3'><select name='campers-foodoptionid-$camper->camperid'>\n";
// foreach ($this->foodoptions as $foodoption) {
// 	if($foodoption->foodoptionid == $camper->foodoptionid) {
// 		$selected = " selected";
// 	} else {
// 		$selected = "";
// 	}
// 	echo "                  <option value='$foodoption->foodoptionid'$selected>$foodoption->name</option>\n";
// }
// echo "                  </select></td>\n";
// echo "               </tr>\n";
// if(!$camper->birthdate || $camper->hohid == "0") {
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>If you are under 18 and your parent or legal guardian is not attending MUUSA, please enter your sponsor here:</td>\n";
// 	echo "                  <td colspan='3'><input type='text' value='$camper->sponsor' name='campers-sponsor-$camper->camperid' size='40' maxlength='50' />\n";
// 	echo "               </tr>\n";
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>Church Affiliation: </td>\n";
// 	echo "                  <td colspan='3'><select name='campers-churchid-$camper->camperid'>\n";
// 	echo "                     <option value='0'>No Affiliation</option>\n;";
// 	foreach ($this->churches as $church) {
// 		if($church->churchid == $camper->churchid) {
// 			$selected = " selected";
// 		} else {
// 			$selected = "";
// 		}
// 		echo "                  <option value='$church->churchid'$selected>$church->statecd - $church->city: $church->name</option>\n";
// 	}
// 	echo "                  </select></td>\n";
// 	echo "               </tr>\n";
// } else {
// 	echo "               <input type='hidden' name='campers-churchid-$camper->camperid' value='$camper->churchid' />\n";
// }
// echo "            </tbody>\n";
// for($i=1; $i<7 && (!$camper->birthdate || $camper->hohid == "0") && ($camper->room == null || $i<=count($this->children)); $i++) {
// 	if($this->children[($i-1)]) {
// 		$child = $this->children[($i-1)];
// 		$childid = $child->camperid;
// 	} else {
// 		$child = new stdClass;
// 		$child->phonenbrs = array();
// 		for($j=0;$j<3;$j++) {
// 			$emptyNbr = new stdClass;
// 			$emptyNbr->phonenbrid = $i*10+$j;
// 			$emptyNbr->phonetypeid = 0;
// 			$emptyNbr->phonenbr = "";
// 			array_push($child->phonenbrs, $emptyNbr);
// 		}
// 		$childid = $i;
// 	}
// 	echo "            <tbody class='camper'>\n";
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='5'><hr /></td>\n";
// 	echo "               </tr>\n";
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='5'><b>Spouse / Dependent #$i</b>\n";
// 	echo "                  </td>\n";
// 	echo "               </tr>\n";
// 	echo "               <tr>\n";
// 	if($child->sexcd == "M") {
// 		$sexcdm = " selected";
// 		$sexcdf = "";
// 	} elseif($child->sexcd == "F") {
// 		$sexcdm = "";
// 		$sexcdf = " selected";
// 	} else {
// 		$sexcdm = "";
// 		$sexcdf = "";
// 	}
// 	echo "                  <td><select name='campers-sexcd-$childid'$dis>\n";
// 	echo "                     <option value='0'>Gender</option>\n";
// 	echo "                     <option value='M' $sexcdm>Male</option>\n";
// 	echo "                     <option value='F' $sexcdf>Female</option>\n";
// 	echo "                  </select></td>\n";
// 	echo "                  <td align='right'>First Name: </td>\n";
// 	echo "                  <td><input type='text' name='campers-firstname-$childid' value='$child->firstname' size='25' maxlength='20'$dis /></td>\n";
// 	echo "                  <td align='right'>Last Name: </td>\n";
// 	echo "                  <td><input type='text' name='campers-lastname-$childid' value='$child->lastname' size='25' maxlength='30'$dis />\n";
// 	echo "                     <input type='hidden' name='campers-hohid-$childid' value='$camper->camperid' /></td>\n";
// 	echo "               </tr>\n";
// 	if($childid >= 1000) {
// 		echo "               <tr>\n";
// 		echo "                  <td colspan='2' align='right'>\n";
// 		echo "                     <input type='checkbox' value='1' name='campers-notattending-$childid'$child->not_attending/></td>\n";
// 		echo "                     <td colspan='3'>Check this box if this person will not be attending this year.</td>\n";
// 		echo "               </tr>\n";
// 	}
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>Email Address: (MUUSA will not contact this address)</td>\n";
// 	echo "                  <td colspan='3'><input type='text' value='$child->email' name='campers-email-$childid' size='50' maxlength='30' />\n";
// 	echo "               </tr>\n";
// 	foreach($child->phonenbrs as $phonenumber) {
// 		echo "               <tr>\n";
// 		echo "                  <td colspan='2' align='right'>Phone Number: </td>\n";
// 		echo "                  <td colspan='3'><select name='phonenumbers-phonetypeid-$phonenumber->phonenbrid'>\n";
// 		foreach($this->phonetypes as $phonetype) {
// 			if($phonenumber->phonetypeid == $phonetype->phonetypeid) {
// 				$selected = " selected";
// 			} else {
// 				$selected = "";
// 			}
// 			echo "                     <option value='$phonetype->phonetypeid'$selected>$phonetype->name</option>\n";
// 		}
// 		echo "                     </select>\n";
// 		echo "                     <input type='hidden' name='phonenumbers-camperid-$phonenumber->phonenbrid' value='$childid' />\n";
// 		echo "                     <input type='text' name='phonenumbers-phonenbr-$phonenumber->phonenbrid' value='$phonenumber->phonenbr' size='20' />\n";
// 		if($phonenumber->phonenbrid >= 1000) {
// 			echo "                     <input type='checkbox' name='phonenumbers-delete-$phonenumber->phonenbrid' value='delete' /> Delete this phone number\n";
// 		}
// 		echo "                  </td>\n";
// 		echo "               </tr>\n";
// 	}
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>Birthdate (MM/DD/YYYY): </td>\n";
// 	echo "                  <td><input type='text' name='campers-birthdate-$childid' value='$child->birthdate' size='20' maxlength='10'$dis /></td>\n";
// 	$grade = "";
// 	if($child->birthdate) {
// 		$grade = $child->age + $child->gradeoffset;
// 	}
// 	echo "                  <td colspan='2'>Grade Entering in Fall 2012: \n";
// 	echo "                     <select name='campers-grade-$childid'$dis>\n";
// 	echo "                        <option value='0'>Not Applicable</option>\n";
// 	echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
// 	for($j=1; $j<13; $j++) {
// 		if($grade == $j) {
// 			$selected = " selected";
// 		} else {
// 			$selected = "";
// 		}
// 		echo "                        <option value='$j'$selected>$j</option>\n";
// 	}
// 	echo "                     </select>\n";
// 	echo "                  </td>";
// 	echo "               </tr>\n";
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>Volunteer Interest:\n";
// 	echo "                  <td colspan='3'><select name='volunteers-positionid-$childid" . "[]' size='5' multiple />\n";
// 	foreach($this->positions as $position) {
// 		$selected = "";
// 		if(count($this->volunteers) > 0) {
// 			foreach($this->volunteers as $volunteer) {
// 				if($volunteer->camperid == $childid && $volunteer->positionid == $position->positionid) {
// 					$selected = " selected";
// 				}
// 			}
// 		}
// 		echo "                        <option value='$position->positionid'$selected>$position->name</option>\n";
// 	}
// 	echo "                  </select></td>\n";
// 	echo "               </tr>\n";
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_handicap-$childid' value='1'$child->is_handicap/></td>\n";
// 	echo "                  <td colspan='3'>Check this box if this member of your family requires access to a handicap-accessible room.</td>\n";
// 	echo "               </tr>\n";
// 	echo "               <tr>\n";
// 	echo "                  <td colspan='2' align='right'>Food Option: </td>\n";
// 	echo "                  <td colspan='3'><select name='campers-foodoptionid-$childid'>\n";
// 	foreach ($this->foodoptions as $foodoption) {
// 		if($foodoption->foodoptionid == $child->foodoptionid) {
// 			$selected = " selected";
// 		} else {
// 			$selected = "";
// 		}
// 		echo "                  <option value='$foodoption->foodoptionid'$selected>$foodoption->name</option>\n";
// 	}
// 	echo "                  </select></td>\n";
// 	echo "               </tr>\n";
// 	echo "            </tbody>\n";
// }
// echo "            </table>\n";
// echo "            </div>\n";
// echo "            <span class='article_separator'>&nbsp;</span>\n";
// echo "            </div>\n";
// if($camper->hohid == 0 || $camper->not_attending) {
// 	echo "            <p align='center'>Click \"Continue\" to proceed to workshop selection.<br />\n";
// 	echo "            <input type='button' value='Continue' onclick='checkForm();' /></form></p>\n";
// }
// echo "            <span class='article_separator'>&nbsp;</span>\n";
// echo "         </td>\n";
// echo "      </tr>\n";
// ?>
   <!-- </table> -->
</div>
