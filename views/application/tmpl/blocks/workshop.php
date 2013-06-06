<?php defined('_JEXEC') or die('Restricted access');
/**
 * workshop.php
 * XHTML Block containing the workshop selection block
 *
 * @param   object  $index           -1 for hidden
 * @param   object  $camper          The database camper object if the camper exists
 * @param   object  $scholarshipNum  The dummy number for scholarship IDs
 *
 */
$user =& JFactory::getUser();
?>
<span id="<?php echo $camper->fiscalyearid;?>" class="camper">
   <h4>
      <?php echo $camper->firstname . " " . $camper->lastname;?>
   </h4>
   <div class="workshopTimes">
      <?php
      $validtimes = $this->times;
      if($camper->shops != 1) {
         $validtimes = array(1020 => $validtimes[1020]);
         echo "					   <p><strong>Automatically enrolled in $camper->programname programming.</strong></p>\n";
      }
      foreach($validtimes as $timeid => $time) {
         echo "					   <h5>" . $time["name"];
         if($timeid != 1020) {
            echo " (" . $time["start"] . " - " . $time["end"] . ")";
         }
         echo "</h5>\n";
         echo "                     <div>\n";
         echo "                        <div class='right'>\n";
         echo "                           <h6>Available Workshops</h6>\n";
         echo "                           <ul class='connected connectedWorkshop workshop-no'>\n";
         if(count($time["shops"]) > 0) {
            foreach($time["shops"] as $eventid => $shop) {
               if(!$camper->attendees || !in_array($eventid, $camper->attendees)) {
                  echo "                              <li value='$eventid' class='ui-state-default'>\n";
                  if($shop["introtext"]) {
                     echo "                     <button class='help link right'>Show " . $shop["name"] . " Information</button>\n";
                  }
                  echo "                                 " . $shop["name"] . " (" . $shop["days"] . ")\n";
                  echo "                              </li>\n";
               }
            }
         }
         echo "                           </ul>\n";
         echo "                        </div>\n";
         echo "                        <div class='desired'>\n";
         echo "                           <h6 class='$timeid'>Desired Workshops (in order of preference)</h6>\n";
         echo "                           <ul class='connected connectedWorkshop workshop-yes'>\n";
         if(count($camper->attendees) > 0) {
            foreach($camper->attendees as $eventid) {
               if(array_key_exists($eventid, $time["shops"])) {
                  echo "                              <li value='$eventid' class='ui-state-default'>\n";
                  if($time["shops"][$eventid]["introtext"]) {
                     echo "                     <button class='help link right'>Show " . $time["shops"][$eventid]["name"] . " Information</button>\n";
                  }
                  echo "                                 " . $time["shops"][$eventid]["name"] . " (" . $time["shops"][$eventid]["days"] . ")\n";
                  echo "                              </li>\n";
               }
            }
         }
         echo "                           </ul>\n";
         echo "                        </div>\n";
         echo "                     </div>\n";
      }
      ?>
      <h5>Volunteer Opportunities</h5>
      <div>
         <div class="right">
            <h6>Available Volunteer Positions</h6>
            <ul class="connected connectedWorkshop workshop-no">
               <?php
               foreach($this->positions as $positionid => $position) {
                  if(!$camper->volunteers || !in_array($positionid, $camper->volunteers)) {
                     echo "                              <li value='$positionid' class='ui-state-default'>\n";
                     echo "                                 " . $position["name"] . "\n";
                     echo "                              </li>\n";
                  }
               }
               ?>
            </ul>
         </div>
         <div class="volunteers">
            <h6 class="0">Desired Roles</h6>
            <ul class="connected connectedWorkshop workshop-yes">
               <?php
               if($camper->volunteers) {
                  foreach($camper->volunteers as $positionid) {
                     echo "                              <li value='$positionid' class='ui-state-default'>\n";
                     echo "                                 " . $this->positions[$positionid]["name"] . "\n";
                     echo "                              </li>\n";
                  }
               }
               ?>
            </ul>
         </div>
      </div>
      <h5>Paid Positions</h5>
      <div class="paidpositionlist">
         <div class="right">
            <h6>Available Paid Positions</h6>
            <ul class="connected connectedWorkshop workshop-no">
               <?php
               foreach($this->paidpositions as $positionid => $position) {
                  if(!$camper->staff || !in_array($positionid, $camper->staff)) {
                     echo "                              <li value='$positionid' class='ui-state-default'>\n";
                     echo "                                 " . $position["name"] . "\n";
                     echo "                              </li>\n";
                  }
               }
               ?>
            </ul>
         </div>
         <div class="paid">
            <h6 class="0">Assigned Positions</h6>
            <ul class="connected connectedWorkshop workshop-yes">
               <?php
               if($camper->staff) {
                  foreach($camper->staff as $positionid) {
                     echo "                              <li value='$positionid' class='ui-state-default'>\n";
                     echo "                                 " . $this->paidpositions[$positionid]["name"] . "\n";
                     echo "                              </li>\n";
                  }
               }
               ?>
            </ul>
         </div>
      </div>
      <h5>Scholarships</h5>
      <div class="scholarshiplist">
         <table>
            <tr>
               <?php $muusaid = $camper->scholarshipMuusa->scholarshipid > 0 ? $camper->scholarshipMuusa->scholarshipid : $scholarshipNum++;?>
               <td><strong>MUUSA Scholarship</strong></td>
               <td>Registration Percentage: <input type="text"
                  name="scholarships-registration_pct-<?php echo $muusaid;?>"
                  maxlength="7"
                  class="inputtext inputtexttiny ui-corner-all"
                  value="<?php echo $camper->scholarshipMuusa->registration_pct * 100;?>" />
                  %
               </td>
               <td>Housing Percentage: <input type="text"
                  name="scholarships-housing_pct-<?php echo $muusaid;?>"
                  maxlength="3"
                  class="inputtext inputtexttiny ui-corner-all"
                  value="<?php echo $camper->scholarshipMuusa->housing_pct * 100;?>" />
                  % <input type="hidden"
                  name="scholarships-fiscalyearid-<?php echo $muusaid;?>"
                  value="<?php echo $camper->fiscalyearid;?>" /> <input
                  type="hidden"
                  name="scholarships-is_muusa-<?php echo $muusaid;?>"
                  value="1" />
               </td>
            </tr>
            <?php $ymcaid = $camper->scholarshipYmca->scholarshipid > 0 ? $camper->scholarshipYmca->scholarshipid : $scholarshipNum++;?>
            <tr>
               <td><strong>YMCA Scholarship</strong></td>
               <td>Registration Percentage: <input type="text"
                  name="scholarships-registration_pct-<?php echo $ymcaid;?>"
                  maxlength="7"
                  class="inputtext inputtexttiny ui-corner-all"
                  value="<?php echo $camper->scholarshipYmca->registration_pct * 100;?>" />
                  %
               </td>
               <td>Housing Percentage: <input type="text"
                  name="scholarships-housing_pct-<?php echo $ymcaid;?>"
                  maxlength="3"
                  class="inputtext inputtexttiny ui-corner-all"
                  value="<?php echo $camper->scholarshipYmca->housing_pct * 100;?>" />
                  % <input type="hidden"
                  name="scholarships-fiscalyearid-<?php echo $ymcaid;?>"
                  value="<?php echo $camper->fiscalyearid;?>" /><input
                  type="hidden"
                  name="scholarships-is_muusa-<?php echo $ymcaid;?>"
                  value="0" />
               </td>
            </tr>
         </table>
      </div>
   </div>
</span>
