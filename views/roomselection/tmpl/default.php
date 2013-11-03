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
      src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery.imagemapster.min.js"></script>
   <form
      action="http://<? echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>"
      method="post">
      <div align="center">
         <img id="roomselection"
            src="<?php echo JURI::root(true);?>/images/muusa/rooms.png"
            usemap="#rooms" />
         <map name="rooms">
            <area shape="rect" coords="143,58,213,128" href="#"
               title="Camp Lakewood Cabins" />
            <area shape="rect" coords="356,349,426,419" href="#"
               title="Tent Camping" />
            <area shape="rect" coords="417,723,487,793" href="#"
               title="Commuter" />
            <?php foreach(range(121, 246, 31) as $yco) { 
               foreach(range(387, 502, 38) as $xco) {
                  echo "<area shape='rect' coords='$xco, $yco, " . ($xco+25) . ", " . ($yco+25) . "' href='#' title='Lakeview Cabin' />\n";
               }
            }
            foreach(range(236, 383, 31) as $yco) {
               foreach(range(113, 252, 38) as $xco) {
                  echo "<area shape='rect' coords='$xco, $yco, " . ($xco+25) . ", " . ($yco+25) . "' href='#' title='Forestview Cabin' />\n";
               }
            }
            foreach(range(211, 481, 30.5) as $yco) {
               foreach(range(769, 870, 38) as $xco) {
                  echo "<area shape='rect' coords='$xco, $yco, " . ($xco+25) . ", " . ($yco+25) . "' href='#' title='Trout Lodge Guest Suite' />\n";
               }
            }
            foreach(range(516, 869, 30.5) as $yco) {
               foreach(range(769, 870, 38) as $xco) {
                  echo "<area shape='rect' coords='$xco, $yco, " . ($xco+25) . ", " . ($yco+25) . "' href='#' title='Trout Lodge Guest Suite' />\n";
               }
            }
            foreach(range(211, 481, 30.5) as $yco) {
               echo "<area shape='rect' coords='670, $yco, 695, " . ($yco+25) . "' href='#' title='Trout Lodge Loft Suite' />\n";
            }
            foreach(range(547, 869, 30.5) as $yco) {
               echo "<area shape='rect' coords='670, $yco, 695, " . ($yco+25) . "' href='#' title='Trout Lodge Loft Suite' />\n";
            }?>
         </map>
         <div>
            <a href="javascript:populate();">Test Data</a>
         </div>
         <script lang="text/javascript">
                  $("#roomselection").mapster();
                  //$("area").tooltip({hide: {duration: 200}, show: {duration: 200}, track: true});
                  function populate() {
                      $("area").get().sort(function() { 
                    	  return Math.round(Math.random())-0.5
                      }).slice(0,25).css("background-color", "black");
                  }
         </script>
      </div>
   </form>
</div>
