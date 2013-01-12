<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
<div class="componentheading">MUUSA 2012 Registation Form</div>
<script type='text/javascript' language='Javascript'
	src='components/com_muusla_application/js/application.js'></script>
<div id='nastygram'
	style='position: absolute; display: none; top: 25px; left: 33%; width: 33%; border: 2px dashed black; background: white; padding: 10px;'>
<h4><i>All returning campers should have their information prepopulated
into this form. If you do not see this, please login using the username
found on the bottom of the mailing label of your paper brochure, Logout
and use the Forgot Your Username? tool in the lower left, or use the
Contact Us tool above to send the webmaster an e-mail about the
discrepancy. Thank you.</i></h4>
<p align="center"><input type="button"
	onclick='document.getElementById("nastygram").style.display = "none";'
	value='Close' /></p>
</div>
<form name='application'
	action="index.php?option=com_muusla_application&task=detail&view=application&Itemid=72"
	method="post">
<table class="blog" cellpadding="0" cellspacing="0">
<?php
if($this->camper) {
	$camper = $this->camper;
	echo "      <input type='hidden' name='hohid' value='$camper->camperid' />\n";
}
if(!$camper->camperid) {
	$camper->camperid = 0;
	echo "<script language='JavaScript'>setTimeout(\"document.getElementById('nastygram').style.display = 'block';\", 3000);</script>\n";
}
$user =& JFactory::getUser();
echo "      <input type='hidden' name='campers-camperid-$camper->camperid' value='$camper->camperid' />\n";
echo "      <tr>\n";
echo "         <td valign='top'>\n";
echo "            <div>\n";
echo "            <div class='contentpaneopen'>\n";
echo "               <h2 class='contentheading'>Your Application</h2>\n";
echo "            </div>\n";
echo "            <div class='article-content'>\n";
if($camper->hohid != 0 && !$camper->not_attending) {
	echo "            <h4><font color='indianred'>The primary camper on your account has not registered yet. In order to complete your registration, please have your the primary camper complete his or her registration, or use the Contact Us form above to have the Webmaster change your primary to you.</font></h4>\n";
}
if($camper->room != null) {
	echo "            <h4><font color='indianred'>Your room has been assigned. If you need to change any information about your registration, please use the Contact Us link above and send a message to the Registrar.</font></h4>\n";
	$dis = " disabled";
}
echo "            <table width='100%'>\n";
echo "            <tbody class='camper'>\n";
echo "               <tr>\n";
if($camper->sexcd == "M") {
	$sexcdm = "selected";
} elseif($camper->sexcd == "F") {
	$sexcdf = "selected";
}
echo "                  <td><select name='campers-sexcd-$camper->camperid'$dis>\n";
echo "                     <option value='0'>Gender</option>\n";
echo "                     <option value='M'$sexcdm>Male</option>\n";
echo "                     <option value='F'$sexcdf>Female</option>\n";
echo "                  </select><font color='red'> *</font></td>\n";
echo "                  <td align='right'>First Name: </td>\n";
echo "                  <td><input type='text' name='campers-firstname-$camper->camperid' value='$camper->firstname' size='20' maxlength='20'$dis /> <font color='red'>*</font></td>\n";
echo "                  <td align='right'>Last Name: </td>\n";
echo "                  <td><input type='text' name='campers-lastname-$camper->camperid' value='$camper->lastname' size='25' maxlength='30'$dis /> <font color='red'>*</font></td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'>Address Line 1: </td>\n";
echo "                  <td colspan='3'><input type='text' name='campers-address1-$camper->camperid' value='$camper->address1' size='50' maxlength='50'$dis /> <font color='red'>*</font></td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'>Address Line 2: </td>\n";
echo "                  <td colspan='3'><input type='text' name='campers-address2-$camper->camperid' value='$camper->address2' size='50' maxlength='50'$dis /></td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'>City: </td>\n";
echo "                  <td colspan='3'><input type='text' name='campers-city-$camper->camperid' value='$camper->city' size='50' maxlength='30'$dis /> <font color='red'>*</font></td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'>State: </td>\n";
echo "                  <td colspan='3'><select name='campers-statecd-$camper->camperid'$dis>\n";
echo "                     <option value='0'>Select a State</option>\n";
foreach($this->states as $state) {
	if($camper->statecd == $state->statecd) {
		$selected = " selected";
	} else {
		$selected = "";
	}
	echo "                     <option value='$state->statecd'$selected>$state->name</option>\n";
}
echo "                  </select> <font color='red'>*</font>\n";
echo "                  Zip: <input type='text' name='campers-zipcd-$camper->camperid' value='$camper->zipcd' size='10' maxlength='10'$dis /> <font color='red'>*</font>\n";
echo "                  Country: <input type='text' name='campers-country-$camper->camperid' value='$camper->country' ize='10' maxlength='40'$dis /></td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'>Email Address: </td>\n";
echo "                  <td colspan='3'>$user->email\n";
echo "                     <input type='hidden' name='campers-email-$camper->camperid' value='$user->email' /></td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_ecomm-$camper->camperid' value='1'$camper->is_ecomm/></td>\n";
echo "                  <td colspan='3'>Check this box if you prefer to receive electronic communication from MUUSA via the above e-mail address.</td>\n";
echo "               </tr>\n";
foreach($this->camper->phonenbrs as $phonenumber) {
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Phone Number: </td>\n";
	echo "                  <td colspan='3'><select name='phonenumbers-phonetypeid-$phonenumber->phonenbrid'>\n";
	foreach($this->phonetypes as $phonetype) {
		if($phonenumber->phonetypeid == $phonetype->phonetypeid) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                     <option value='$phonetype->phonetypeid'$selected>$phonetype->name</option>\n";
	}
	echo "                     </select>\n";
	echo "                     <input type='hidden' name='phonenumbers-camperid-$phonenumber->phonenbrid' value='$camper->camperid' />\n";
	echo "                     <input type='text' name='phonenumbers-phonenbr-$phonenumber->phonenbrid' value='$phonenumber->phonenbr' size='20' />\n";
	if($phonenumber->phonenbrid >= 1000) {
		echo "                     <input type='checkbox' name='phonenumbers-delete-$phonenumber->phonenbrid' value='delete' /> Delete this phone number\n";
	}
	echo "                  </td>\n";
	echo "               </tr>\n";
}
if($camper->birthdate) {
	$grade = $camper->age + $camper->gradeoffset;
}
if(!$camper->birthdate || $camper->hohid == "0" || $camper->age > 17) {
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Birthdate (MM/DD/YYYY): </td>\n";
	echo "                  <td><input type='text' name='campers-birthdate-$camper->camperid' value='$camper->birthdate' size='20' maxlength='10'$dis /> <font color='red'>*</font></td>\n";
	echo "                  <td colspan='2'>Grade Entering in Fall 2012: \n";
	echo "                     <select name='campers-grade-$camper->camperid'$dis>\n";
	echo "                        <option value='0'>Not Applicable</option>\n";
	echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
	for($i=1; $i<13; $i++) {
		if($grade == $i) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                        <option value='$i'$selected>$i</option>\n";
	}
	echo "                     </select>\n";
	echo "                  </td>";
	echo "               </tr>\n";
} else {
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Birthdate: </td>\n";
	echo "                  <td>$camper->birthdate\n";
	echo "                     <input type='hidden' name='campers-birthdate-$camper->camperid' value='$camper->birthdate' /></td>\n";
	echo "                  <td colspan='2'>Grade Entering in Fall 2012: \n";
	echo "                     <input type='hidden' name='campers-grade-$camper->camperid' value='$camper->grade' />\n";
	echo "                     <select name='dummy' disabled>\n";
	echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
	for($i=1; $i<13; $i++) {
		if($grade == $i) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                        <option value='$i'$selected>$i</option>\n";
	}
	echo "                     </select>\n";
	echo "                  </td>";
	echo "               </tr>\n";
}
echo "               <tr>\n";
echo "                  <td>&nbsp;</td>\n";
echo "                  <td colspan='4'>If you would like to help MUUSA, please consider volunteering for up to three positions.\n";
echo "                     Keep in mind that these are purely volunteer positions and offer no compensation. The program coordinator \n";
echo "                     will contact you. Hold the CTRL key to select multiple items.</td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'>Volunteer Interest:\n";
echo "                  <td colspan='3'><select name='volunteers-positionid-$camper->camperid[]' size='5' multiple />\n";
foreach($this->positions as $position) {
	$selected = "";
	if(count($this->volunteers) > 0) {
		foreach($this->volunteers as $volunteer) {
			if($volunteer->camperid == $camper->camperid && $volunteer->positionid == $position->positionid) {
				$selected = " selected";
			}
		}
	}
	echo "                        <option value='$position->positionid'$selected>$position->name</option>\n";
}
echo "                  </select></td>\n";
echo "               </tr>\n";
if(!$camper->birthdate || $camper->hohid == "0") {
	echo "               <tr>\n";
	echo "                  <td>&nbsp;</td>\n";
	echo "                  <td colspan='4'>Campers who choose their previous year's housing type as their first preference will, if at all possible, be given the same room as well.</td>\n";
	echo "               </tr>\n";
	for($i=1; $i<4; $i++) {
		eval("\$roompref = \$camper->roomprefid$i;");
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'>Room Preference #$i:</td>\n";
		echo "                  <td colspan='3'><select name='campers-roomprefid$i-$camper->camperid'>\n";
		echo "                     <option value='0'>No Preference</option>\n";
		foreach ($this->buildings as $building) {
			if($building->buildingid == $roompref) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			echo "                        <option value='$building->buildingid'$selected>$building->name</option>\n";
		}
		echo "                  </select></td>\n";
		echo "               </tr>\n";
	}
	echo "               <tr>\n";
	echo "                  <td>&nbsp;</td>\n";
	echo "                  <td colspan='4'>Singles without roommates will be housed with other campers \n";
	echo "                      having similar preferences. Pairs will only be housed with other pairs by request.</td>\n";
	echo "               </tr>\n";
	for($i=1; $i<4; $i++) {
		eval("\$matepref = \$camper->matepref$i;");
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'>Roommate Preference #$i: </td>\n";
		echo "                  <td colspan='3'><input type='text' name='campers-matepref$i-$camper->camperid' size='50' maxlength='50' value='$matepref' /></td>\n";
		echo "               </tr>\n";
	}
}
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_handicap-$camper->camperid' value='1'$camper->is_handicap/></td>\n";
echo "                  <td colspan='3'>Check this box if you require lodging in a handicap-accessible room.</td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'>\n";
echo "                  <input type='checkbox' name='campers-is_ymca-$camper->camperid' value='1'$camper->is_ymca/></td>\n";
echo "                  <td colspan='3'>Check this box if any members of your family are applying for YMCA scholarships.</td>\n";
echo "               </tr>\n";
echo "               <tr>\n";
echo "                  <td colspan='2' align='right'>Food Option: </td>\n";
echo "                  <td colspan='3'><select name='campers-foodoptionid-$camper->camperid'>\n";
foreach ($this->foodoptions as $foodoption) {
	if($foodoption->foodoptionid == $camper->foodoptionid) {
		$selected = " selected";
	} else {
		$selected = "";
	}
	echo "                  <option value='$foodoption->foodoptionid'$selected>$foodoption->name</option>\n";
}
echo "                  </select></td>\n";
echo "               </tr>\n";
if(!$camper->birthdate || $camper->hohid == "0") {
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>If you are under 18 and your parent or legal guardian is not attending MUUSA, please enter your sponsor here:</td>\n";
	echo "                  <td colspan='3'><input type='text' value='$camper->sponsor' name='campers-sponsor-$camper->camperid' size='40' maxlength='50' />\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Church Affiliation: </td>\n";
	echo "                  <td colspan='3'><select name='campers-churchid-$camper->camperid'>\n";
	echo "                     <option value='0'>No Affiliation</option>\n;";
	foreach ($this->churches as $church) {
		if($church->churchid == $camper->churchid) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                  <option value='$church->churchid'$selected>$church->statecd - $church->city: $church->name</option>\n";
	}
	echo "                  </select></td>\n";
	echo "               </tr>\n";
} else {
	echo "               <input type='hidden' name='campers-churchid-$camper->camperid' value='$camper->churchid' />\n";
}
echo "            </tbody>\n";
for($i=1; $i<7 && (!$camper->birthdate || $camper->hohid == "0") && ($camper->room == null || $i<=count($this->children)); $i++) {
	if($this->children[($i-1)]) {
		$child = $this->children[($i-1)];
		$childid = $child->camperid;
	} else {
		$child = new stdClass;
		$child->phonenbrs = array();
		for($j=0;$j<3;$j++) {
			$emptyNbr = new stdClass;
			$emptyNbr->phonenbrid = $i*10+$j;
			$emptyNbr->phonetypeid = 0;
			$emptyNbr->phonenbr = "";
			array_push($child->phonenbrs, $emptyNbr);
		}
		$childid = $i;
	}
	echo "            <tbody class='camper'>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='5'><hr /></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='5'><b>Spouse / Dependent #$i</b>\n";
	echo "                  </td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	if($child->sexcd == "M") {
		$sexcdm = " selected";
		$sexcdf = "";
	} elseif($child->sexcd == "F") {
		$sexcdm = "";
		$sexcdf = " selected";
	} else {
		$sexcdm = "";
		$sexcdf = "";
	}
	echo "                  <td><select name='campers-sexcd-$childid'$dis>\n";
	echo "                     <option value='0'>Gender</option>\n";
	echo "                     <option value='M' $sexcdm>Male</option>\n";
	echo "                     <option value='F' $sexcdf>Female</option>\n";
	echo "                  </select></td>\n";
	echo "                  <td align='right'>First Name: </td>\n";
	echo "                  <td><input type='text' name='campers-firstname-$childid' value='$child->firstname' size='25' maxlength='20'$dis /></td>\n";
	echo "                  <td align='right'>Last Name: </td>\n";
	echo "                  <td><input type='text' name='campers-lastname-$childid' value='$child->lastname' size='25' maxlength='30'$dis />\n";
	echo "                     <input type='hidden' name='campers-hohid-$childid' value='$camper->camperid' /></td>\n";
	echo "               </tr>\n";
	if($childid >= 1000) {
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'>\n";
		echo "                     <input type='checkbox' value='1' name='campers-notattending-$childid'$child->not_attending/></td>\n";
		echo "                     <td colspan='3'>Check this box if this person will not be attending this year.</td>\n";
		echo "               </tr>\n";
	}
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Email Address: (MUUSA will not contact this address)</td>\n";
	echo "                  <td colspan='3'><input type='text' value='$child->email' name='campers-email-$childid' size='50' maxlength='30' />\n";
	echo "               </tr>\n";
	foreach($child->phonenbrs as $phonenumber) {
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'>Phone Number: </td>\n";
		echo "                  <td colspan='3'><select name='phonenumbers-phonetypeid-$phonenumber->phonenbrid'>\n";
		foreach($this->phonetypes as $phonetype) {
			if($phonenumber->phonetypeid == $phonetype->phonetypeid) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			echo "                     <option value='$phonetype->phonetypeid'$selected>$phonetype->name</option>\n";
		}
		echo "                     </select>\n";
		echo "                     <input type='hidden' name='phonenumbers-camperid-$phonenumber->phonenbrid' value='$childid' />\n";
		echo "                     <input type='text' name='phonenumbers-phonenbr-$phonenumber->phonenbrid' value='$phonenumber->phonenbr' size='20' />\n";
		if($phonenumber->phonenbrid >= 1000) {
			echo "                     <input type='checkbox' name='phonenumbers-delete-$phonenumber->phonenbrid' value='delete' /> Delete this phone number\n";
		}
		echo "                  </td>\n";
		echo "               </tr>\n";
	}
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Birthdate (MM/DD/YYYY): </td>\n";
	echo "                  <td><input type='text' name='campers-birthdate-$childid' value='$child->birthdate' size='20' maxlength='10'$dis /></td>\n";
	$grade = "";
	if($child->birthdate) {
		$grade = $child->age + $child->gradeoffset;
	}
	echo "                  <td colspan='2'>Grade Entering in Fall 2012: \n";
	echo "                     <select name='campers-grade-$childid'$dis>\n";
	echo "                        <option value='0'>Not Applicable</option>\n";
	echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
	for($j=1; $j<13; $j++) {
		if($grade == $j) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                        <option value='$j'$selected>$j</option>\n";
	}
	echo "                     </select>\n";
	echo "                  </td>";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Volunteer Interest:\n";
	echo "                  <td colspan='3'><select name='volunteers-positionid-$childid" . "[]' size='5' multiple />\n";
	foreach($this->positions as $position) {
		$selected = "";
		if(count($this->volunteers) > 0) {
			foreach($this->volunteers as $volunteer) {
				if($volunteer->camperid == $childid && $volunteer->positionid == $position->positionid) {
					$selected = " selected";
				}
			}
		}
		echo "                        <option value='$position->positionid'$selected>$position->name</option>\n";
	}
	echo "                  </select></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_handicap-$childid' value='1'$child->is_handicap/></td>\n";
	echo "                  <td colspan='3'>Check this box if this member of your family requires access to a handicap-accessible room.</td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Food Option: </td>\n";
	echo "                  <td colspan='3'><select name='campers-foodoptionid-$childid'>\n";
	foreach ($this->foodoptions as $foodoption) {
		if($foodoption->foodoptionid == $child->foodoptionid) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                  <option value='$foodoption->foodoptionid'$selected>$foodoption->name</option>\n";
	}
	echo "                  </select></td>\n";
	echo "               </tr>\n";
	echo "            </tbody>\n";
}
echo "            </table>\n";
echo "            </div>\n";
echo "            <span class='article_separator'>&nbsp;</span>\n";
echo "            </div>\n";
if($camper->hohid == 0 || $camper->not_attending) {
	echo "            <p align='center'>Click \"Continue\" to proceed to workshop selection.<br />\n";
	echo "            <input type='button' value='Continue' onclick='checkForm();' /></form></p>\n";
}
echo "            <span class='article_separator'>&nbsp;</span>\n";
echo "         </td>\n";
echo "      </tr>\n";
?>
</table>

</div>
