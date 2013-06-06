<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
      rel="stylesheet" />
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/jquery-ui-1.10.0.custom.min.css"
      rel="stylesheet" />
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery-1.9.1.min.js"></script>
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery-ui-1.10.0.custom.min.js"></script>
   <script
      src='<?php echo JURI::root(true);?>/components/com_muusla_application/js/workshop.js'></script>
   <form
      action="http://<? echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>"
      method="post">
      <?php if($this->editcamper) {
         echo "<input type='hidden' name='editcamper' value='$this->editcamper' />\n";
         //echo "<div style='float: right;'><button id='forwardRoom'>Do not Save<br />Proceed to Room Selection</button></div>\n";
         echo "<div><button id='backDetails'>Return<br />to Camper Details</button></div>\n";
      }?>
      <table class="blog" cellpadding="0" cellspacing="0">
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
                  <?php if($this->msg) {?>
                  <div class="ui-state-highlight ui-corner-all">
                     <p style="margin: 1em;">
                        <span class="ui-icon ui-icon-info"
                           style="float: left; margin: 1em;"></span> You
                        have successfully signed up for workshops! Be
                        sure to pay your balance to be assigned housing
                        by clicking on the Register Online link above.<br />
                     </p>
                  </div>
                  <p>&nbsp;</p>
                  <?php }
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
                  <div id="muusaApp" class='article-content'>
                     <div class="workshopSelection">
                        <?php
                        if(count($this->regcampers) > 0) {
                           $scholarshipNum = 0;
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
