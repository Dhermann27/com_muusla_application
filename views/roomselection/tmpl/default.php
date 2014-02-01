<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
      rel="stylesheet" />
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery.imagemapster.min.js"></script>
   <form id="muusaApp" method="post">
      <div align="center">
         <h5>
            <?php if($this->year[1] == "0" || $this->reg[0] != 0) {?>
            Instructions: Please choose the unoccupied that you would
            like your family to occupy for
            <?php echo $this->year[0]; ?>
            . Those requiring handicapped-accessible rooms or roommates
            will be assigned by the Registrar per their selections on
            the Registration Form.
            <?php } else {?>
            Priority Registration is only available to those that
            preregistered for
            <?php echo $this->year[0];?>
            . Please contact the Registrar if you believe that this is
            in error, or return 30 days after registration begins to
            choose your room.
            <?php }?>
         </h5>
         <img id="roomselection"
            src="<?php echo JURI::root(true);?>/images/muusa/rooms.png"
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
               if($this->reg[1] == $room->id) {
                  $selected = "true";
                  $roomname .= "<br /><strong>Your Room From " . ($this->year[0]-1) . "</strong></br><i>Please note that changing from this room will make it available to other campers!</i>";
               } else if($room->occupants) {
                  if($room->capacity < 10) {
                     $selected = "true";
                     $deselectable = "false";
                     $fillcolor = "2f2f2f";
                  }
                  $roomname .= $room->occupants == "1" ?"<br /><strong>Private Occupants</strong>" : "<br />Current Occupants:<br /><strong>$room->occupants</strong>";
               }
               if($this->year[1] == "1" && $this->reg[0] == 0) {
                  $selectable = "false";
               }
               echo "<area shape='rect' data-key='$room->id' coords='$room->xcoord, $room->ycoord, " . ($room->xcoord+$room->pixelsize) . ", " . ($room->ycoord+$room->pixelsize) . "' href='#' />\n";
               array_push($areas, "{ key: '$room->id', toolTip: '$room->buildingname$roomname', selected: $selected, isSelectable: $selectable, isDeselectable: $deselectable, render_select: { fillColor: '$fillcolor' } }");
            }?>
         </map>
         <?php 
         if($this->year[1] == "0" || $this->reg[0] != 0) {?>
         <div align="center">
            <strong>Privacy Setting</strong>: <select
               name="yearattending-is_private-0" class="ui-corner-all">
               <option value="0">
                  Share my room selection with other
                  <?php echo $this->year[0];?>
                  MUUSA campers.
               </option>
               <option value="1">
                  Keep my room selection private from other
                  <?php echo $this->year[0];?>
                  MUUSA campers.
               </option>
            </select> <input id="roomid" type="hidden"
               name="yearattending-roomid-0"
               value="<?php echo $this->reg[1];?>" />
         </div>
         <div align="right">
            <button class="save">Save Room Selection</button>
         </div>
         <?php }?>
         <script lang="text/javascript">
                  jQuery("#roomselection").mapster({
                          fillOpacity: 0.75,
                		    render_highlight: {
                		        fillColor: '67b021',
                		        stroke: false
                		    },
                		    render_select: {
                		        fillColor: 'daa520',
                		        stroke: false
                		    },
                		    fadeInterval: 100,
                		    mapKey: 'data-key',
                		    toolTipContainer: '<div class="ui-state-highlight ui-corner-all" style="padding: 5px 10px 5px 10px;"></div>',
                		    showToolTip: true,
                		    areas: [
                		    <?php echo implode(",\n", $areas); ?>
                		    ],
                		    onClick: unselectAll
                		    });
      		      function unselectAll(data) {
          		      jQuery("#roomid").val(data.key);
          		      jQuery('area').filter(function() {
              		      return jQuery(this).attr("data-key") != data.key && jQuery("#roomselection").mapster('get_options', jQuery(this).attr("data-key")).isDeselectable; 
          		      }).mapster('set', false);
      		      }
                  jQuery(document).ready(function ($) {
              	    $("#muusaApp .save").button().click(function (event) {
              	    	$("#muusaApp").submit();
              	        event.preventDefault();
              	        return false;
              	    });
                  });
         </script>
      </div>

   </form>
</div>
