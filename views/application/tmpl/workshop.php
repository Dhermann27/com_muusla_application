<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
      rel="stylesheet" />
   <script
      src='<?php echo JURI::root(true);?>/components/com_muusla_application/js/workshop.js'></script>
   <form id="muusaApp" method="post">
      <?php if($this->editcamper) {
         echo "<input type='hidden' id='editcamper' name='editcamper' value='$this->editcamper' />\n";
         echo "<div style='float: right;'><button id='toAssignRoom' class='btn-danger'>Do not Save<br />Proceed to Assign Room</button></div>\n";
         echo "<div><button id='toSelection' class='btn'>Return<br />to Camper Selection</button></div>\n";
      }?>
      <table class="blog">
         <tr>
            <td valign='top'>
               <div>
                  <div class='contentpaneopen'>
                     <h2 class='contentheading'>Workshop Selections</h2>
                     Workshops are filled on a first-come, first-served
                     basis. They will be filled in order of your listed
                     preference unless the workshop is full. Register
                     early for the best chance of getting into your
                     first choice workshops. You may enroll in multiple
                     workshops for the same timeslot if they are offered
                     on different days. List all in your choices.
                  </div>
                  <div align="center">
                     <h5>To select your workshops, click and drag them
                        from the right to the left side, then put them
                        in order of preference.</h5>
                     <table>
                        <tr align="center" valign="middle">
                           <td width="33%"
                              style="background-color: #327e04; color: #fff; padding: 0px;"
                              class="ui-corner-left">Plenty of Room</td>
                           <td width="33%"
                              style="background-color: #e3a345; color: #fff; padding: 0px;">Filling
                              Up Fast!</td>
                           <td width="33%"
                              style="background-color: #cd0a0a; color: #fff; padding: 0px;"
                              class="ui-corner-right">Waiting List
                              Available</td>
                        </tr>
                     </table>
                  </div>
                  <?php if($this->msg) {
                     if($this->editcamper) {?>
                  <h3>Workshop Assignment Successful.</h3>
                  <?php } else {?>
                  <div class="ui-state-highlight ui-corner-all">
                     <p style="margin: 1em;">
                        <i class="fa fa-check-square-o fa-3x"></i> You
                        have successfully signed up for workshops! If
                        your balance is paid, proceed to the Room
                        Selection tool. <i>Note: only possible during
                           the Priority Registration period to those
                           that preregistered.</i>
                        <button id="toHousing" class="btn">Proceed to
                           Room Selection</button>
                     </p>
                  </div>
                  <p>&nbsp;</p>
                  <?php }
                  }
                  if(count($this->regcampers) == 0) {?>
                  <div
                     class="padtop ui-state-error ui-corner-all hidden spaceleft">
                     <p style="margin: 1em;">
                        <span class="ui-icon ui-icon-alert"
                           style="float: left; margin: 1em;"></span> You
                        have not completed your registration process.
                        Please click "Register Online" above to first
                        register you and your family, then return here
                        to register for workshops.
                     </p>
                  </div>
                  <?php }?>
                  <div class="padtop article-content">
                     <div class="workshopSelection">
                        <?php
                        if(count($this->regcampers) > 0) {
                           foreach($this->regcampers as $camper) {
                              include 'blocks/workshop.php';
                           }
                        }?>
                     </div>
                     <div class="padtop" align="right">
                        <input type="hidden" name="deleteMe" value="1" />
                        <button id="submitWorkshops">Save Workshop
                           Preferences</button>
                     </div>
                  </div>
                  <?php
                  foreach($this->times as $timeid => $time) {
                     foreach($time["shops"] as $eventid => $shop) {
                        echo "      <div id='room-$eventid' class='dialog-message' title='" . $shop["name"] . "'>\n";
                        echo "         " . $shop["introtext"] . "\n";
                        echo "      </div>\n";
                     }
                  }
                  ?>
               </div>
            </td>
         </tr>
      </table>
   </form>
</div>
