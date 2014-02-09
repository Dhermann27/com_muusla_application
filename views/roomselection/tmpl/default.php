<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
      rel="stylesheet" />
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery.imagemapster.min.js"></script>
   <form id="muusaApp" method="post">
      <?php if($this->msg) {?>
      <div class="ui-state-highlight ui-corner-all">
         <p style="margin-top: 1em;">
            <span class="ui-icon ui-icon-info"
               style="float: left; margin: 1em;"></span> You have
            successfully chosen your room! Click the links above to
            review your bill or alter your workshop preferences.
         </p>
      </div>
      <?php }?>
      <div align="center">
         <?php if($this->reg == null || $this->reg[2] == "0") {?>
         <div class="ui-state-error ui-corner-all">
            <p style="margin-top: 1em;">
               <span class="ui-icon ui-icon-alert"
                  style="float: left; margin: 1em;"></span>
               <?php if($this->reg == null || $this->reg[1] == "0") {?>
               You have not yet registered for MUUSA in
               <?php echo $this->year[0]; ?>
               . In order to choose a room, you will first need to
               complete your registration form and pay any registration
               and workshop fees due.
               <?php } else {?>
               Although you have successfully preregistered for MUUSA
               <?php echo $this->year[0]; ?>
               , you have not yet completed your registration form. In
               order to choose a room, you will first need to complete
               your registration form and pay any registration and
               workshop fees due remaining after your preregistration
               deposit has been applied.
               <?php } ?>
               <br />
               <button id="nextRegister">Proceed to Registration Form</button>
            </p>
         </div>
         <?php } else if($this->reg[4] == "0") {?>
         <div class="ui-state-error ui-corner-all">
            <p style="margin-top: 1em;">
               <span class="ui-icon ui-icon-alert"
                  style="float: left; margin: 1em;"></span> Although you
               have successfully registered for MUUSA
               <?php echo $this->year[0]; ?>
               , you have not yet paid your registration and workshop
               fees. Please recheck the Statement &amp; Payment tab of
               the Registration Form for the link to remit payment via
               PayPal. You will need a zero balance before being allowed
               to select a room. <i>Unfortunately, this also applies if
                  you are participating in the MUUSA scholarship process</i>.<br />
               <button id="nextRegister">Proceed to Registration Form</button>
            </p>
         </div>
         <?php } else {?>
         <?php if($this->year[1] == "0" || $this->reg[1] == "1") {?>
         <h5>
            Instructions: Please choose the unoccupied room that you
            would like your family to occupy for
            <?php echo $this->year[0]; ?>
            . Families with campers in Meyer, Burt or Young Adult
            (18-20) programs do not need to select housing for their
            youth, as this will be assigned automatically. Campers
            requiring handicapped-accessible rooms or roommates will be
            assigned by the Registrar per their selections on the
            Registration Form.
         </h5>
         <?php } else {?>
         <div class="ui-state-highlight ui-corner-all">
            <p style="margin-top: 1em;">
               <span class="ui-icon ui-icon-info"
                  style="float: left; margin: 1em;"></span> Priority
               Registration is only available to those that
               preregistered for
               <?php echo $this->year[0];?>
               . Please contact the Registrar if you believe that this
               is in error, or return 30 days after registration begins
               to choose your room. Feel free to browse this tool in <i>read-only
                  mode</i> until then.
            </p>
         </div>

         <?php }?>
         <img id="roomselection"
            src="<?php echo JURI::root(true);?>/images/muusa/rooms.jpg"
            usemap="#rooms" />
         <map name="rooms">
            <?php
            $areas = array();
            foreach($this->rooms as $room) {
               $selected = "false";
               $selectable = "true";
               $deselectable = "true";
               $fillcolor = "daa520";
               $roomname = $room->buildingid < 1007 ? ", Room $room->roomnbr" : "";
               $roomname .= $room->connected ? ($room->buildingid==1000 ? "<br /><i>Double Privacy Door with Room $room->connected</i>" : "<br /><i>Shares common area with Room $room->connected</i>") : "";
               if($this->reg[3] == $room->id) {
                  $selected = "true";
                  $roomname .= "<br /><strong>Your Current Selection</strong>";
                  if($room->capacity < 10) {
                     $roomname .= "<br />Please note that changing from this room will make it available to other campers.<br /><i>This cannot be undone!</i>";
                  }
               } else if($room->occupants || $room->locked) {
                  if($room->capacity < 10) {
                     $selected = "true";
                     $deselectable = "false";
                     $fillcolor = "2f2f2f";
                  }
                  if($room->occupants) {
                     $roomname .= $room->occupants == "1" ?"<br /><strong>Private Occupants</strong>" : "<br />Current Occupants:<br /><strong>$room->occupants</strong>";
                  } else {
                     $roomname .= $room->locked == "1" ?"<br /><strong>Locked by Preregistered Campers</strong>" : "<br />Locked by:<br /><strong>$room->locked</strong>";
                  }
               }
               if($this->year[1] == "1" && $this->reg[1] == "0") {
                  $selectable = "false";
               }
               echo "<area shape='rect' data-key='$room->id' coords='$room->xcoord, $room->ycoord, " . ($room->xcoord+$room->pixelsize) . ", " . ($room->ycoord+$room->pixelsize) . "' href='#' />\n";
               array_push($areas, "{ key: '$room->id', toolTip: '$room->buildingname$roomname', selected: $selected, isSelectable: $selectable, isDeselectable: $deselectable, render_select: { fillColor: '$fillcolor' } }");
            }?>
         </map>
         <?php 
         if($this->year[1] == "0" || $this->reg[1] == "1") {?>
         <div align="center">
            <strong>Privacy Setting</strong>: <select
               name="yearattending-is_private-0" class="ui-corner-all">
               <option value="0">
                  Show other
                  <?php echo $this->year[0];?>
                  MUUSA Campers where I'll be staying.
               </option>
               <option value="1"
               <?php echo $this->reg[5]==1 ? " selected" : "";?>>I
                  will share where I'll be staying with other MUUSA
                  Campers myself.</option>
            </select> <input id="roomid" type="hidden"
               name="yearattending-roomid-0"
               value="<?php echo $this->reg[3];?>" />
         </div>
         <div align="right">
            <button class="save">Save Room Selection</button>
         </div>
         <?php }?>
         <script lang="text/javascript">
         jQuery(document).ready(function ($) {
             $("#muusaApp .save").button().click(function (event) {
                 $("#muusaApp").submit();
                 event.preventDefault();
                 return false;
             });
             $("#roomselection").mapster({
                 render_highlight: {
                     fillColor: '67b021',
                     strokeWidth: 2
                 },
                 render_select: {
                     fillColor: 'daa520'
                 },
                 fadeInterval: 100,
                 mapKey: 'data-key',
                 toolTipContainer: '<div class="ui-state-highlight ui-corner-all" style="padding: 5px 10px 5px 10px;"></div>',
                 showToolTip: true,
                 areas: [ <?php echo implode(",\n", $areas); ?> ],
                 onClick: unselectAll
             });
         });

         function unselectAll(data) {
             jQuery("#roomid").val(data.key);
             jQuery('area').filter(function () {
                 return jQuery(this).attr("data-key") != data.key && jQuery("#roomselection").mapster('get_options', jQuery(this).attr("data-key")).isDeselectable;
             }).mapster('set', false);
         }
         </script>
         <?php }?>
      </div>

   </form>
</div>
