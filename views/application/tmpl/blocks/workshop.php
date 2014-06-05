<?php defined('_JEXEC') or die('Restricted access');
/**
 * workshop.php
 * XHTML Block containing the workshop selection block
 *
 * @param   object  $index           -1 for hidden
 * @param   object  $camper          The database camper object if the camper exists
 *
 */
$user =& JFactory::getUser();
?>
<span id="<?php echo $camper->yearattendingid;?>" class="camper">
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
                  $style = "";
                  if ($shop["enrollment"] >= .75) {
                     $style = " style='background: " . ($shop["enrollment"] >= 1 ? "#cd0a0a" : "#e3a345") . ";'";
                     $style .= " title='" . ($shop["enrollment"] >= 1 ? "Waiting list available" : "Filling up fast") . "'";
                  }
                  echo "                              <li value='$eventid' class='ui-state-default' title='Check'$style>\n";
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
                  $style = "";
                  if ($shop["enrollment"] >= .75) {
                     $style = " style='background: " . ($shop["enrollment"] >= 1 ? "#cd0a0a" : "#e3a345") . ";'";
                     $style .= " title='" . ($shop["enrollment"] >= 1 ? "Waiting list available" : "Filling up fast") . "'";
                  }
                  echo "                              <li value='$eventid' class='ui-state-default'$style>\n";
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
               foreach($this->volunteerpositions as $positionid => $position) {
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
                     echo "                                 " . $this->volunteerpositions[$positionid]["name"] . "\n";
                     echo "                              </li>\n";
                  }
               }
               ?>
            </ul>
         </div>
      </div>
   </div>
</span>
