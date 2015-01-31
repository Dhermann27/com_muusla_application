<?php defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
?>
<div id="ja-content">
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
      rel="stylesheet" />
   <script
      src='<?php echo JURI::root(true);?>/components/com_muusla_application/js/application.js'></script>
   <script>var thisyear = <?php echo $this->year["year"]?>;</script>
   <?php if($this->msg) {?>
   <?php if($this->editcamper) {?>
   <h3>Camper Details Save Successful.</h3>
   <?php } else {?>
   <div class="ui-state-highlight ui-corner-all">
      <p style="margin-top: 1em;">
         <span class="ui-icon ui-icon-info"
            style="float: left; margin: 1em;"></span> You have
         successfully preregistered for camp!
         <!-- Be sure to pay your balance
         to be assigned housing and register for your workshops by
         clicking here. br />
         button id="nextWorkshop">Proceed to Workshop Selection/button> -->
      </p>
   </div>
   <?php }?>
   <p>&nbsp;</p>
   <?php } elseif ($this->redirectAmt) {?>
   <div id="paypalRedirect" title="PayPal">
      <span class="fa-stack fa-2x" style="float: left;"> <i
         class="fa fa-arrow-circle-left fa-stack-1x"></i> <i
         class="fa fa-ban fa-stack-2x" style="color: indianred;"></i>
      </span> <strong>Redirecting to PayPal. After you complete your
         transtion, <i>do not hit the Back button on your web browser</i>.
         The website will redirect you back to muusa.org automatically.
      </strong>
      <form id="paypalForm" name="_xclick"
         action="https://www.paypal.com/cgi-bin/webscr" method="post">
         <input type="hidden" name="amount"
            value="<?php echo $this->redirectAmt?>" /> <input
            type="hidden" name="custom"
            value="<?php echo $this->campers[0]->id;?>" /> <input
            type="hidden" name="cmd" value="_xclick"> <input
            type="hidden" name="business"
            value="muusaregistar@gmail.com"> <input type="hidden"
            name="currency_code" value="USD"> <input type="hidden"
            name="item_name"
            value="Midwest Unitarian Universalist Summer Assembly"> <img
            alt="" border="0"
            src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"
            width="1" height="1" />
      </form>
   </div>
   <?php }?>
   <form id="muusaApp" method="post">
      <?php if($this->editcamper) {
         echo "<input type='hidden' id='editcamper' name='editcamper' value='$this->editcamper' />\n";
         echo "<div style='float: right;'><button id='toWorkshop'>Do not Save<br />Proceed to Workshop Selection</button></div>\n";
         echo "<div><button id='toSelection'>Return<br />to Camper Selection</button></div>\n";
      }
      $familyid = $this->family->id ? $this->family->id : 0;?>
      <ul>
         <li><a
            href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>#appFamily">Household
               Information</a></li>
         <li><a
            href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>#appCamper">Camper
               List</a></li>
         <li><a
            href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>#appPayment">Statement
               &amp; Payment</a></li>
      </ul>
      <div id="appFamily">
         <table>
            <tr>
               <td width="25%">
                  <button class="myhelp myinfo right">Show Family Name Help</button>
                  Family Name <input type="hidden"
                  name="family-id-<?php echo $familyid?>"
                  value="<?php echo $familyid?>" />
               </td>
               <td width="75%"><input type="text"
                  name="family-name-<?php echo $familyid;?>"
                  maxlength="30"
                  class="inputtext notempty ui-corner-all"
                  value="<?php echo $this->family->name;?>" />
               </td>
            </tr>
            <tr class="myhidden" valign="top">
               <td><h4>What is a Family Name?</h4></td>
               <td>
                  <p>Family Name is the display name for your family as
                     a whole. It will appear on your mailing label and
                     be the only entry in the roster, alphabetized by
                     the first letter of this name.</p>
                  <p>Examples:</p>
                  <ul>
                     <li>Most people will choose their family's last
                        name: "Washington".</li>
                     <li>Families with different surnames might choose:
                        "Lincoln / Todd".</li>
                     <li>Please do not add "The" or make your family's
                        name plural: "The Obamas".</li>
                  </ul>
                  <p>If you would like a separate entry in the roster
                     for any member of your family, please register them
                     separately. There is no additional cost for this
                     option.</p>
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
               <td><select name="family-statecd-<?php echo $familyid;?>"
                  class="notzero ui-corner-all">
                     <option value="0">Choose a State</option>
                     <?php
                     foreach($this->states as $state) {
                        $selected = $this->family->statecd == $state->code ? " selected" : "";
                        echo "               <option value='$state->code'$selected>$state->name</option>\n";
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
                  class="inputtextshort validzip notempty ui-corner-all"
                  value="<?php echo $this->family->zipcd?>" />
               </td>
            </tr>
            <tr>
               <td>Country</td>
               <td><input type="text"
                  name="family-country-<?php echo $familyid;?>"
                  maxlength="30" class="inputtextshort ui-corner-all"
                  value="<?php echo $this->family->country != "" ? $this->family->country : "USA";?>" />
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
         <p>
            <strong>Please fill out the following form for each camper.</strong>
         </p>
         <table>
            <?php
            if(count($this->campers)== 0) {
               $camper = new stdClass;
               $camper->camperid = 1;
               include 'blocks/camper.php';
            } else {
               foreach($this->campers as $index => $camper) {
                  include 'blocks/camper.php';
               }
            }
            ?>
            <tfoot>
               <tr>
                  <td colspan="2">
                     <button id="addCamper">Add Another Camper</button>
                     <button id="removeCamper">Remove Last Camper</button>
                  </td>
                  <td colspan="2" align="right">
                     <button id="nextPayment">Next Page</button>
                  </td>
               </tr>
            </tfoot>
         </table>
      </div>
      <div id="appPayment">
         <script>
				<?php
				   echo "               var campDate = new Date('" . $this->year["date"] . "');\n";
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
               <span class="space left ui-icon ui-icon-alert"></span> No
               campers of age are marked as "Attending" in the Camper
               Listing tab. Please return to that tab and choose
               "Attending" from the drop-down in the upper-right.
            </p>
         </div>
         <?php if($this->charges) {
            $multiyear = count($this->charges) > 1 || !$this->charges[$this->year["year"]];
            if($multiyear) {
               echo "<div class='paymentYears'>\n";
            }
            if(!$this->charges[$this->year["year"]]) {
               $year = $this->year["year"];
               echo "<h4>$year</h4>\n";
               echo "<div>\n";
               include 'blocks/payment.php';
               echo "</div>\n";
            }
            foreach($this->charges as $year => $charges) {
               if($multiyear) {
                  echo "<h4>$year</h4>\n";
                  echo "<div>\n";
               }
               include 'blocks/payment.php';
               if($multiyear) {
                  echo "</div>\n";
               }
            }
            if($multiyear) {
               echo "</div>\n";
            }
         } else {
            $year = $this->year["year"];
            include 'blocks/payment.php';
         }
         if($this->editcamper) {?>
         <br />
         <div class="right">
            <button id="finishWorkshop">
               <?php echo $this->sumdays > 0 ? "Save Changes" : "Finish Registration";?>
            </button>
         </div>
         <div align="center">
            <button id="registerAll">
               Register Entire Family for
               <?php echo $this->year["year"]?>
            </button>
         </div>
         <?php }?>
      </div>
   </form>
</div>
