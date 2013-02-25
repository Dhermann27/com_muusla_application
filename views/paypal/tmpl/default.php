<?php defined('_JEXEC') or die('Restricted access'); ?>
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
   src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery.scrollTo-1.4.3.1-min.js"></script>
<script>
   jQuery(document).ready(
			function($) {
				$("#nextWorkshop").button().click(function() {
					window.location.href = "http://muusa.org/index.php?Itemid=222";
					return false;
				});
			});
</script>
<div id="ja-content">
   <div class="componentheading">Welcome Back to muusa.org</div>
   <table class="blog" cellpadding="0" cellspacing="0">
      <tr>
         <td valign="top">
            <div>
               <div class="contentpaneopen">
                  <?php
                  if($this->amount) {
                     $msg = "successful";
                  } else {
                     $msg = "failed";
                  }
                  echo "		   <h2 class='contentheading'>Payment $msg!</h2>\n";
                  echo "		   </div>\n";
                  echo "		   <div class='article-content'>\n";
                  if($msg == "successful") {?>
                  <div class="ui-state-highlight ui-corner-all">
                     <p style="margin: 2em;">
                        <span class="ui-icon ui-icon-info"
                           style="float: left; margin: 1em;"></span>
                        Thank you,
                        <?php echo $this->name;?>
                        ! MUUSA received your payment for $
                        <?php echo number_format($this->amount,2)?>
                        . That payment will be reflected on your bill.
                        Be sure to register for your workshops by
                        clicking here. <br />
                        <button id="nextWorkshop">Proceed to Workshop
                           Selection</button>
                     </p>
                  </div>
                  <?php }?>
               </div>
               <span class="article_separator">&nbsp;</span>
            </div>

         </td>
      </tr>
   </table>
</div>
