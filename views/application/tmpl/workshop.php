<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content"><script type='text/javascript'
	language='Javascript'
	src='components/com_muusla_application/js/application.js'></script>
<div class="componentheading">MUUSA 2012 Registation Form</div>
<form name='application'
	action="index.php?option=com_muusla_application&task=payment&view=application&Itemid=72"
	method="post">
<table class="blog" cellpadding="0" cellspacing="0">
<?php
echo "      <tr>\n";
echo "         <td valign='top'>\n";
echo "            <div>\n";
echo "            <div class='contentpaneopen'>\n";
echo "               <h2 class='contentheading'>Workshop Selections</h2>\n";
echo "               Workshops are filled on a first-come, first-served basis.  They will be filled in order of your listed preference unless the workshop is full.\n";
echo "               Register early for the best chance of getting into your first choice workshops. List all your workshop choices, in order of preference, by using the Add and Move Up/Down buttons.\n";
echo "            </div>\n";
echo "            <div class='article-content'>\n";
echo "            <table width='100%'>\n";
foreach($this->campers as $camper) {
	if($camper->notattending == "1") {
		continue;
	}
	echo "               <tr>\n";
	echo "                  <td colspan='4'><h4>$camper->fullname</h4>\n";
	echo "                  <input type='hidden' name='selected-0-$camper->camperid' value='LEAD' /></td>\n";
	echo "               </tr>\n";
	$choicenbrs = array();
	if($camper->choices) {
		foreach($camper->choices as $choice) {
			array_push($choicenbrs, $choice->eventid);
		}
	}
	if($camper->workshop) {
		echo "               <tr>\n";
		echo "                  <td colspan='4'><i>$camper->workshop</i></td>\n";
		echo "               </tr>\n";
	} else {
		$count = 1;
		foreach($this->workshops as $timename => $shops) {
			$available = "";
			$selected = array();
			foreach($shops as $shop) {
				if(!$camper->choices || array_search($shop->eventid, $choicenbrs) === false) {
					$available .= "                     <option value='$shop->eventid'>$shop->shopname ($shop->days)</option>\n";
				} else {
					$eventlead = $shop->eventid;
					if($camper->choices[array_search($shop->eventid, $choicenbrs)]->is_leader) {
						$shop->shopname .= " (Leader)";
						$eventlead = "LEAD";
					}
					$selected[$shop->eventid] = "                     <option value='$eventlead'>$shop->shopname ($shop->days)</option>\n";
				}
			}
			echo "               <tr>\n";
			echo "                  <td colspan='4'>$timename</td>\n";
			echo "               </tr>\n";
			echo "               <tr>\n";
			echo "                  <td width='40%'><select name='available-$count-$camper->camperid[]' multiple='multiple' size='" . max(min(count($shops),5),2) . "' style='width: 100%;'>\n";
			echo $available;
			echo "                     </select></td>\n";
			echo "                  <td width='10%'><input type='button' value='Add >>' onclick='listbox_moveto(\"$count-$camper->camperid\")' ondblclick='listbox_moveto(\"$count-$camper->camperid\")' style='width: 100%; font-size: x-small;' /><br />\n";
			echo "                     <input type='button' value='<< Remove' onclick='listbox_movefrom(\"$count-$camper->camperid\")' ondblclick='listbox_movefrom(\"$count-$camper->camperid\")' style='width: 100%; font-size: x-small;' /></td>\n";
			echo "                  <td width='40%'><select name='selected-$count-$camper->camperid[]' multiple='multiple' size='" . max(min(count($shops),5),2) . "' style='width: 100%;'>\n";
			if($camper->choices) {
				foreach($camper->choices as $choice) {
					echo $selected[$choice->eventid];
				}
			}
			echo "                     </select></td>\n";
			echo "                  <td width='10%'><input type='button' value='Move Up' onclick='listbox_up(\"$count-$camper->camperid\")' style='width: 100%; font-size: x-small;' /><br />\n";
			echo "                     <input type='button' value='Move Down' onclick='listbox_down(\"" . $count++ . "-$camper->camperid\")' style='width: 100%; font-size: x-small;' /></td>\n";
			echo "               </tr>\n";
		}
	}
	echo "               <tr>\n";
	echo "                  <td colspan='4'>MUUSA Excursions:\n";
	echo "                  These additional options must be signed up for ahead of time and the fees, which are due with registration, are non-refundable (unless cancelled by MUUSA).</td>\n";
	echo "               </tr>\n";
	$checked = "";
	if(array_search(1028, $choicenbrs) !== false) {
		$checked = " checked";
	}
	echo "               <tr>\n";
	echo "                  <td colspan='4'><input type='checkbox' name='selected-997-$camper->camperid' value='1028'$checked/>\n";
	echo "                  St. Louis City Museum, Saturday June 30th 2:00 PM - Midnight ($6).</td>\n";
	echo "               </tr>\n";
	$checked = "";
	if(array_search(1029, $choicenbrs) !== false) {
		$checked = " checked";
	}
	echo "               <tr>\n";
	echo "                  <td colspan='2'><input type='checkbox' name='selected-998-$camper->camperid' value='1029'$checked/>\n";
	echo "                  River Float Trip, Tuesday 9:50 AM - 5:00 PM ($35).</td>\n";
	$checked = "";
	if(array_search(1030, $choicenbrs) !== false) {
		$checked = " checked";
	}
	echo "                  <td colspan='2'><input type='checkbox' name='selected-999-$camper->camperid' value='1030'$checked/>\n";
	echo "                  Onondaga Cave State Park, Wednesday 1:00 PM - 5:00 PM ($25).</td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='4'><hr /></td>\n";
	echo "               </tr>\n";
}
echo "               <tr><td colspan='4' align='center'><p>&nbsp;</p>Interested in Trout Lodge activities such as horseback riding\n";
echo "                  or ropes courses? Sign up on the first day of camp; plenty of slots will be available.</td></tr>\n";
echo "            </table>\n";
echo "            </div>\n";
echo "            <span class='article_separator'>&nbsp;</span>\n";
echo "            </div>\n";
echo "            <p align='center'><input type='button' value='Continue' class='Button' onclick='selectSubmit()' /></form></p>\n";
echo "            <span class='article_separator'>&nbsp;</span>\n";
echo "         </td>\n";
echo "      </tr>\n";
?>
</table>

</div>
