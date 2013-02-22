<?php defined('_JEXEC') or die('Restricted access');
/**
 * workshop.php
 * XHTML Block containing the workshop selection block
 *
 * @param   object  $index  -1 for hidden
 * @param   object  $camper     The database camper object if the camper exists
 *
 */
$cprid = $camper->camperid > 0 ? "-" . $camper->camperid : "";
$user =& JFactory::getUser();
if($index == -1) {
   echo "<span id='workshopDummy' class='hidden'>\n";
} elseif ($index == -2) {
   echo "<span id='childDummy' class='hidden'>\n";
}
?>

<h4>
   <?php echo $camper->firstname . " " . $camper->lastname;?>
</h4>
<div class="workshopTimes">
   <?php 
   $validtimes = $this->times;
   if((isset($camper->grade) && $camper->grade < 13) || $index == -2) {
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
               // echo "                              <button class='help link right'>Show $building->name Information</button>\n";
               echo "                                 " . $shop["name"] . " (" . $shop["days"] . ")\n";
               echo "                              </li>\n";
            }
         }
      }
      echo "                           </ul>\n";
      echo "                        </div>\n";
      echo "                        <div class='desired'>\n";
      echo "                           <h6 class='$cprid-$timeid'>Desired Workshops (in order of preference)</h6>\n";
      echo "                           <ul class='connected connectedWorkshop workshop-yes'>\n";
      if(count($camper->attendees) > 0) {
         foreach($camper->attendees as $eventid) {
            if(array_key_exists($eventid, $time["shops"])) {
               echo "                              <li value='$eventid' class='ui-state-default'>\n";
               // echo "                              <button class='help link right'>Show $building->name Information</button>\n";
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
         <h6 class="<?php echo $cprid?>-0">Desired Roles</h6>
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
</div>
<?php
if($index < 0) {
   echo "</span>\n";
}
?>