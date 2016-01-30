<?php
defined('_JEXEC') or die('Restricted access');
/**
 * camper.php
 * XHTML Block containing the camper information block
 *
 * @param object $camper
 *            The database camper object
 *            
 */
$camperid = $camper->id >= 1000 ? $camper->id : "";
$user = JFactory::getUser();
?>
<tbody id="<?php echo $camperid;?>" class="camperBody">
	<tr valign="bottom">
		<td width="25%">&nbsp;</td>
	</tr>
	<tr>
		<td>Pronoun Preference</td>
		<td>
			<button class="btn fa fa-info info right" title="Show Pronoun Help"></button>
			<select name="camper-sexcd-<?php echo $camperid;?>"
			class="notzero ui-corner-all">
				<option value="0">Choose a pronoun</option>
				<option value="M"
					<?php echo $camper->sexcd == "M" ? " selected" : "";?>>He/him</option>
				<option value="F"
					<?php echo $camper->sexcd == "F" ? " selected" : "";?>>She/her</option>
				<option value="F"
					<?php echo $camper->sexcd == "T" ? " selected" : "";?>>They/them</option>
				<option value="F"
					<?php echo $camper->sexcd == "A" ? " selected" : "";?>>All/any</option>
				<option value="F"
					<?php echo $camper->sexcd == "Y" ? " selected" : "";?>>Xe/Xir</option>
				<option value="F"
					<?php echo $camper->sexcd == "Z" ? " selected" : "";?>>Ze/Zir</option>
				<option value="X"
					<?php echo $camper->sexcd == "X" ? " selected" : "";?>>Other</option>
		</select> 
      <?php
    if ($camper->id >= 1000) {
        echo "<input type='hidden' name='camper-id-$camperid' value='$camperid' />";
    }
    ?>
      </td>
		<td align="right"><select
			name="camper-attending-<?php echo $camperid;?>"
			class="attending ui-corner-all">
            <?php if($this->editcamper) {?>
            <option value="-1"
					<?php echo $camper == null || $camper->days == -1 ? " selected" : "";?>>Not
					Attending</option>
            <?php
                
                for ($i = 6; $i >= 0; $i --) {
                    $selected = $camper->days == $i ? " selected" : "";
                    echo "                        <option value='$i'$selected>$i nights</option>\n";
                }
            } else {
                ?>
            <option value="6"
					<?php echo $camper->days > -1 || $this->sumdays == 0 ? " selected" : "";?>>Attending</option>
				<option value="-1"
					<?php echo $camper->days == -1 && $this->sumdays > 0 ? " selected" : "";?>>Not
					Attending</option>
            <?php }?>
      </select></td>
	</tr>
	<tr class="myhidden" valign="top">
		<td><h4>Why do we ask?</h4></td>
		<td colspan="3">
			<p>MUUSA is an intentionally inclusive community that welcomes
				everyone regardless of their biological sex or gender
				identification. We ask that you include your preferred pronoun for
				two reasons:</p>
			<p>For lodging purposes, we segregate our Junior High campers into
				one of two cabins in accordance with Missouri state law. Please
				contact us to make special arrangements for any camper who would
				prefer alternatives.</p>
			<p>If you are a single camper and have not found a roommate, our
				Registrar attempts to match you with someone of the same pronoun
				preference and age range. We strongly suggest that, if this applies
				to you, that you seek out your own roommate using social media or
				your church community.</p>
		</td>
	</tr>
	<tr>
		<td>First Name</td>
		<td colspan="2"><input type="text"
			name="camper-firstname-<?php echo $camperid;?>" maxlength="30"
			class="inputtext firstname notempty ui-corner-all"
			value="<?php echo $camper->firstname;?>" /></td>
	</tr>
	<tr>
		<td>Last Name</td>
		<td colspan="2"><input type="text"
			name="camper-lastname-<?php echo $camperid;?>" maxlength="30"
			class="inputtext lastname notempty ui-corner-all"
			value="<?php echo $camper->lastname;?>" /></td>
	</tr>
   <?php
if (! $this->editcamper &&
         ($camperid == "" ||
         ($camperid >= 1000 && strcasecmp($camper->email, $user->email) == 0))) {
    ?>
   <tr>
		<td>
			<button class="btn fa fa-info info right"
				title="Show E-mail
            Address Help"></button> Email Address
		</td>
      <?php
    echo "								<td colspan='3'>$user->email\n";
    echo "                                 <input type='hidden' name='camper-email-$camperid' value='$user->email' class='email' />\n";
    echo "                              </td>\n";
    ?>
   </tr>
	<tr class="myhidden" valign="top">
		<td><h4>Why can't I change my email address?</h4></td>
		<td colspan="3">
			<p>We need the same email as your http://muusa.org account. This lets
				us connect our camper database to the website user database. Use the
				Contact Us link above, if you would like your preferred email
				changed.</p>
		</td>
	</tr>
   <?php
} else {
    ?>
   <tr>
		<td>Email Address</td>
		<td colspan="3"><input type="text"
			name="camper-email-<?php echo $camperid;?>"
			value="<?php echo $camper->email;?>"
			class="inputtextshort email ui-corner-all" /></td>
	</tr>
   <?php
}
if (count($camper->phonenbrs) == 0) {
    $nbrindex = 0;
    $phonenumber = new stdClass();
    include 'phonenumber.php';
} else {
    foreach ($camper->phonenbrs as $nbrindex => $phonenumber) {
        include 'phonenumber.php';
    }
}
?>
   <tr>
		<td>Birthday</td>
		<td><input type="text" maxlength="10"
			name="camper-birthdate-<?php echo $camperid;?>"
			class="birthday validday notempty ui-corner-all"
			value="<?php echo $camper->birthday;?>" /></td>
		<td align="right">Grade Entering in Fall <?php echo $this->year["year"]?>
      </td>
		<td><select name="camper-grade-<?php echo $camperid;?>"
			class="grade ui-corner-all">
            <?php
            echo "                        <option value='13'>Not Applicable</option>\n";
            $selected = isset($camper->grade) && $camper->grade <= 0 ? " selected" : "";
            echo "                        <option value='0'$selected>Kindergarten or Earlier</option>\n";
            for ($j = 1; $j < 13; $j ++) {
                $selected = min($camper->grade, 13) == $j ? " selected" : "";
                echo "                        <option value='$j'$selected>$j</option>\n";
            }
            ?>
      </select></td>
	</tr>
   <?php
if (count($camper->roommates) == 0) {
    $mateindex = 0;
    $name = "";
    include 'roommate.php';
} else {
    foreach ($camper->roommates as $mateindex => $name) {
        include 'roommate.php';
    }
}
?>
   <tr>
		<td>Accessibility</td>
		<td colspan="2" valign="middle">Do you require a room accessible by
			the disabled?</td>
		<td><select name="camper-is_handicap-<?php echo $camperid;?>"
			class="ui-corner-all">
				<option value="0"
					<?php echo $camper->is_handicap == "1" ? "" : " selected";?>>No</option>
				<option value="1"
					<?php echo $camper->is_handicap == "1" ? " selected" : "";?>>Yes</option>
		</select></td>
	</tr>
	<tr>
		<td>Food Options</td>
		<td colspan="2" valign="middle">Which option best describes your
			eating restrictions?</td>
		<td><select name="camper-foodoptionid-<?php echo $camperid;?>"
			class="ui-corner-all">
            <?php
            foreach ($this->foodoptions as $foodoption) {
                $selected = $camper->foodoptionid == $foodoption->id ? " selected" : "";
                echo "                  <option value='$foodoption->id'$selected>$foodoption->name</option>\n";
            }
            ?>
      </select></td>
	</tr>
	<tr>
		<td>
			<button class="btn fa fa-info info right"
				title="Show Sponsor
            Help"></button> Sponsor (if applicable)
		</td>
		<td colspan="3"><input type="text" maxlength="30"
			name="camper-sponsor-<?php echo $camperid;?>"
			class="inputtext ui-corner-all"
			value="<?php echo $camper->sponsor;?>" /></td>
	</tr>
	<tr class="myhidden" valign="top">
		<td><h4>When is a sponsor required?</h4></td>
		<td colspan="3">
			<p>A sponsor is required if the camper will be under the age of 18 on
				the first day of camp and a parent or legal guardian is not
				attending for the entire length of time that the camper will be on
				YMCA property. A sponsor is asked to attend the informational
				meetings in the parents' stead, and if the camper is asked to leave
				for any reason, the sponsor will be required to assist the camper
				home.</p>
			<p>If you are having difficulty finding a sponsor, please let us know
				using the Contact Us form above. Oftentimes, we have adults in your
				area who are willing to volunteer, and may also be willing to offer
				transportation.</p>
		</td>
	</tr>
	<tr>
		<td>Church Affiliation</td>
		<td colspan="3"><?php
echo "                     <select name='camper-churchid-$camperid' class='ui-corner-all'>\n";
echo "                     <option value='0'>No Affiliation</option>\n";
foreach ($this->churches as $church) {
    $selected = $camper->churchid == $church->id ? " selected" : "";
    echo "                  <option value='$church->id'$selected>$church->statecd - $church->city: $church->name</option>\n";
}
echo "                  </select></td>\n";
?>
      </td>
	</tr>
	<tr>
		<td colspan="4"><hr /></td>
	</tr>
</tbody>
