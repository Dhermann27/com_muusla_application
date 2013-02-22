<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <script type='text/javascript' language='Javascript'
      src='components/com_muusla_application/js/application.js'></script>
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
      rel="stylesheet" />
   <link type="text/css"
      href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/jquery-ui-1.10.0.custom.css"
      rel="stylesheet" />
   <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
   <script src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery-ui-1.10.0.custom.min.js"></script>
   <script
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery.scrollTo-1.4.3.1-min.js"></script>
   <script
      src='<?php echo JURI::root(true);?>/components/com_muusla_application/js/workshop.js'></script>
   <form action="<? echo $_SERVER['PHP_SELF'];?>" method="post">
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
                     first choice workshops.
                  </div>
                  <div class='article-content'>
                     <?php
                     foreach($this->campers as $camper) {
                        if(count($this->campers) > 0) {
                           foreach($this->campers as $index => $camper) {
                              include 'blocks/workshop.php';
                           }
                        }
                     }?>
                  </div>
               </div>
            </td>
         </tr>
      </table>
   </form>
</div>
