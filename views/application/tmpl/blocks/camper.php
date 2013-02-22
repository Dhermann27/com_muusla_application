<?php defined('_JEXEC') or die('Restricted access');
/**
 * camper.php
 * XHTML Block containing the camper information block
 *
 * @param   object  $camper  The database camper object
 *
 */
$user =& JFactory::getUser();
?>
<tbody id="$camper->camperid" class="camperBody<?php echo $camper->camperid > 100 && $camper->camperid < 1000 ? " hidden" : "";?>">
   <tr valign="bottom">
      <td width="25%"><select
         name="campers-sexcd-<?php echo $camper->camperid;?>"
         class="notzero ui-corner-all">
            <option value="0">Gender</option>
            <option value="M"
            <?php echo $camper->sexcd == "M" ? " selected" : "";?>>Male</option>
            <option value="F"
            <?php echo $camper->sexcd == "F" ? " selected" : "";?>>Female</option>
      </select> <input type="hidden"
         name="campers-camperid-<?php echo $camper->camperid;?>"
         value="<?php echo $camperid;?>" />
      </td>
      <td colspan="3" width="75%" align="right">
         <button class="help info">Show Attending Help</button> <select
         name="campers-attending-<?php echo $camper->camperid;?>"
         class="attending ui-corner-all">
            <option value="7" selected="selected">Attending</option>
            <option value="0">Not Attending</option>
      </select>
      </td>
   </tr>
   <tr class="hidden" valign="top">
      <td><h4>What does "Attending" mean?</h4></td>
      <td colspan="3">
         <p>Use this option if you have a person who is in your family,
            but is not attending MUUSA this year. This person will not
            be charged any fees and will not appear on the roster.</p>
         <p>If this person is not in your family or is registering
            themselves this year, please use the Contact Us link above
            to send a message to the Registrar letting us know.</p>
      </td>
   </tr>
   <tr>
      <td>First Name</td>
      <td colspan="3"><input type="text"
         name="campers-firstname-<?php echo $camper->camperid;?>"
         maxlength="30"
         class="inputtext firstname notempty ui-corner-all"
         value="<?php echo $camper->firstname;?>" />
      </td>
   </tr>
   <tr>
      <td>Last Name</td>
      <td colspan="3"><input type="text"
         name="campers-lastname-<?php echo $camper->camperid;?>"
         maxlength="30"
         class="inputtext lastname notempty ui-corner-all"
         value="<?php echo $camper->lastname;?>" />
      </td>
   </tr>
   <?php
   if($camper->camperid == 100 || $camper->camperid >= 1000) {
      ?>
   <tr>
      <td>
         <button class="help info right">Show E-mail Address Help</button>
         Email Address
      </td>
      <?php 
      echo "								<td colspan='3'>$user->email\n";
      echo "                                 <input type='hidden' name='campers-email-$camper->camperid' value='$user->email' />\n";
      echo "                              </td>\n";
      ?>
   </tr>
   <tr class="hidden" valign="top">
      <td><h4>Why can't I change my email address?</h4></td>
      <td colspan="3">
         <p>We need the same email as your http://muusa.org account.
            This lets us connect our camper database to the website user
            database.</p>
      </td>
   </tr>
   <?php
   } else {
      ?>
   <tr>
      <td>Email Address</td>
      <td colspan="3"><input type="text"
         name="campers-email-<?php echo $camper->camperid;?>"
         value="<?php echo $camper->email;?>"
         class="inputtextshort ui-corner-all" /></td>
   </tr>
   <?php
   }
   if(count($camper->phonenbrs) == 0) {
      $nbrindex = 0;
      $phonenumber = $this->emptyPhonenumber;
      include 'phonenumber.php';
   } else {
      foreach($camper->phonenbrs as $nbrindex => $phonenumber) {
         include 'phonenumber.php';
      }
   }
   $nbrindex = -1;
   $phonenumber = $this->emptyPhonenumber;
   include 'phonenumber.php';
   ?>
   <tr>
      <td>Birthday</td>
      <td><input type="text" maxlength="10"
         name="campers-birthdate-<?php echo $camper->camperid;?>"
         class="birthday validday ui-corner-all"
         value="<?php echo $camper->birthday;?>" />
      </td>
      <td align="right">Grade Entering in Fall <?php echo substr($this->year, -4)?>
      </td>
      <td><select name="campers-grade-<?php echo $camper->camperid;?>"
         class="grade ui-corner-all">
            <?php 						
            echo "                        <option value='13'>Not Applicable</option>\n";
            $selected = isset($camper->grade) && $camper->grade <= 0 ? " selected" : "";
            echo "                        <option value='0'$selected>Kindergarten or Earlier</option>\n";
            for($i=1; $i<13; $i++) {
               $selected = min($camper->grade, 13) == $i ? " selected" : "";
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
            <h4>Click here, then drag in order of preference from right
               to left.</h4>
            <div>
               <div class="right">
                  <h5>Room Type List</h5>
                  <ul class="connected connectedRoomtype roomtype-no">
                     <?php 
                     foreach($this->buildings as $buildingid => $building) {
                        if(!$camper->roomtypes || !in_array($buildingid, $camper->roomtypes)) {
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
                  <ul class="connected connectedRoomtype roomtype-yes">
                     <?php
                     if($camper->roomtypes) {
                        foreach($camper->roomtypes as $roomtype) {
                           echo "                  <li value='$roomtype' class='ui-state-default'>\n";
                           // echo "                     <button class='help link right'>Show " . $this->buildings[$roomtype]->name . " Information</button>\n";
                           echo "                     " . $this->buildings[$roomtype]["name"] . "\n";
                           echo "                  </li>\n";
                        }
                     }
                     ?>
                     <li class="ui-state-default">No Preference</li>
                  </ul>
               </div>
               <button class="roomtypeSave clearboth right">Save
                  Preferences</button>
            </div>
         </div>
      </td>
   </tr>
   <?php
   if(count($camper->roommates) == 0) {
      $mateindex = 0;
      $name = "";
      include 'roommate.php';
   } else {
      foreach($camper->roommates as $mateindex => $name) {
         include 'roommate.php';
      }
   }
   $mateindex = -1;
   $name = "";
   include 'roommate.php';
   ?>
   <tr>
      <td>Accessibility</td>
      <td colspan="2" valign="middle">Do you require a room accessible
         by the disabled?</td>
      <td><select
         name="campers-is_handicap-<?php echo $camper->camperid;?>"
         class="ui-corner-all">
            <option value="1"
            <?php echo $camper->is_handicap == "1" ? " selected" : "";?>>Yes</option>
            <option value="0"
            <?php echo $camper->is_handicap == "1" ? "" : " selected";?>>No</option>
      </select>
      </td>
   </tr>
   <tr>
      <td>Food Options</td>
      <td colspan="2" valign="middle">Which option best describes your
         eating restrictions?</td>
      <td><select
         name="campers-foodoptionid-<?php echo $camper->camperid;?>"
         class="ui-corner-all">
            <?php
            foreach ($this->foodoptions as $foodoption) {
               $selected = $camper->foodoptionid == $foodoption->foodoptionid ? " selected" : "";
               echo "                  <option value='$foodoption->foodoptionid'$selected>$foodoption->name</option>\n";
            }
            ?>
      </select>
      </td>
   </tr>
   <tr>
      <td>Smoking Preference</td>
      <td colspan="2" valign="middle">What is your smoking preference,
         if assigned a roommate?</td>
      <td><select
         name="campers-smokingoptionid-<?php echo $camper->camperid;?>"
         class="ui-corner-all">
            <?php
            foreach ($this->smokingoptions as $smokingoption) {
               $selected = $camper->smokingoptionid == $foodoption->smokingoptionid ? " selected" : "";
               echo "                  <option value='$smokingoption->smokingoptionid'$selected>$smokingoption->name</option>\n";
            }
            ?>
      </select>
      </td>
   </tr>
   <tr>
      <td>
         <button class="help info right">Show Sponsor Help</button>
         Sponsor
      </td>
      <td colspan="3"><input type="text" maxlength="30"
         name="campers-sponsor-<?php echo $camper->camperid;?>"
         class="inputtext ui-corner-all"
         value="<?php echo $camper->sponsor;?>" />
      </td>
   </tr>
   <tr class="hidden" valign="top">
      <td><h4>When is a sponsor required?</h4></td>
      <td colspan="3">
         <p>A sponsor is required if the camper will be under the age of
            18 on the first day of camp and a parent or legal guardian
            is not attending for the entire length of time that the
            camper will be on YMCA property. A sponsor is asked to
            attend the informational meetings in the parents' stead, and
            if the camper is asked to leave for any reason, the sponsor
            will be required to assist the camper home.</p>
         <p>If you are having difficulty finding a sponsor, please let
            us know using the Contact Us form above. Oftentimes, we have
            adults in your area who are willing to volunteer, and may
            also be willing to offer transportation.</p>
      </td>
   </tr>
   <tr>
      <td>Church Affiliation</td>
      <td colspan="3"><?php
      echo "                     <select name='campers-churchid-$camper->camperid' class='ui-corner-all'>\n";
      echo "                     <option value='0'>No Affiliation</option>\n;";
      foreach ($this->churches as $church) {
         $selected = $camper->churchid == $church->churchid ? " selected" : "";
         echo "                  <option value='$church->churchid'$selected>$church->statecd - $church->city: $church->name</option>\n";
      }
      echo "                  </select></td>\n";
      ?>
      </td>
   </tr>
   <?php
   if($index == -1) {
      ?>
   <tr>
      <td colspan="4" align="right">
         <button class="removeCamper">Remove This Camper</button>
      </td>
   </tr>
   <?php
   }
   ?>
   <tr>
      <td colspan="4"><hr /></td>
   </tr>
</tbody>