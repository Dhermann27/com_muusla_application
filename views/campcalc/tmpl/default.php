<?php defined('_JEXEC') or die('Restricted access'); ?>
<script type='text/javascript' language='Javascript'
	src='components/com_muusla_application/js/campcost.js'></script>
<div id="ja-content">
<div class="componentheading">MUUSA Camp Cost Calculator</div>
<table class="blog" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
		<div>
		<h3>These numbers are approximations and can change based on several
		other factors, such as additional excursion costs and volunteer credits.
		You should <i>not</i> use this page to precisely determine your camp costs.</h3>
		<table width="100%">
			<tr align="center">
				<td>&nbsp;</td>
				<td>Number<br />
				Attending</td>
				<td>Housing Arrangements</td>
				<td>Registration</td>
				<td>Housing</td>
			</tr>
			<tr>
				<td><b>Adults</b> (21 years old and up)</td>
				<td align="center"><select id="muusa_adults_num" onchange="muusaCalc();">
					<option value="0" selected>0</option>
					<?php for($i=1; $i<10; $i++) {
						echo "				<option value='$i'>$i</option>\n";
					}?>
				</select></td>
				<td><select id="muusa_adults_hou" onchange="muusaCalc();">
					<option value="0" selected>Select one</option>
					<option value="1">Guestroom, Cabin, or Loft ($520+)</option>
					<option value="3">Camp Lakewood (dorm style, $385)</option>
					<option value="4">Tent Camping ($282)</option>
				</select></td>
				<td align="right" id="muusa_adults_feereg">$0.00</td>
				<td align="right" id="muusa_adults_feehouse">$0.00</td>
			</tr>
			<tr>
				<td><b>Young Adults</b> (18 through 20 years old)</td>
				<td align="center"><select id="muusa_ya_num" onchange="muusaCalc();">
					<option value="0" selected>0</option>
					<?php for($i=1; $i<10; $i++) {
						echo "				<option value='$i'>$i</option>\n";
					}?>
				</select></td>
				<td><select id="muusa_ya_hou" onchange="muusaCalc();">
					<option value="0" selected>Select one</option>
					<option value="1">YA Cabin ($355)</option>
					<option value="2">Tent Camping ($282)</option>
				</select></td>
				<td align="right" id="muusa_ya_feereg">$0.00</td>
				<td align="right" id="muusa_ya_feehouse">$0.00</td>
			</tr>
			<tr>
				<td><b>Jr./Sr. High School</b> (Burt/Meyer Programs)</td>
				<td align="center"><select id="muusa_burt_num" onchange="muusaCalc();">
					<option value="0" selected>0</option>
					<?php for($i=1; $i<10; $i++) {
						echo "				<option value='$i'>$i</option>\n";
					}?>
				</select></td>
				<td>Burt/Meyer Cabins ($330)</td>
				<td align="right" id="muusa_burt_feereg">$0.00</td>
				<td align="right" id="muusa_burt_feehouse">$0.00</td>
			</tr>
			<tr>
				<td><b>Children</b> (5 years old to entering 6th grade)</td>
				<td align="center"><select id="muusa_child_num" onchange="muusaCalc();">
					<option value="0" selected>0</option>
					<?php for($i=1; $i<10; $i++) {
						echo "				<option value='$i'>$i</option>\n";
					}?>
				</select></td>
				<td>Must room with parents ($252/$156)</td>
				<td align="right" id="muusa_child_feereg">$0.00</td>
				<td align="right" id="muusa_child_feehouse">$0.00</td>
			</tr>
			<tr>
				<td><b>Infants/Toddlers</b> (4 years old and younger)</td>
				<td align="center"><select id="muusa_infant_num" onchange="muusaCalc();">
					<option value="0" selected>0</option>
					<?php for($i=1; $i<10; $i++) {
						echo "				<option value='$i'>$i</option>\n";
					}?>
				</select></td>
				<td>Must room with parents (FREE)</td>
				<td align="right" id="muusa_infant_feereg">$0.00</td>
				<td align="right" id="muusa_infant_feehouse">$0.00</td>
			</tr>
			<tr>
				<td colspan="3" align="right">Amount Due Upon Registration: <span id="muusa_duereg">$0.00</span></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="right">Amount Due Upon Arrival: <span id="muusa_duearr">$0.00</span></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="right"><b>Total Camp Cost</b>: <span id="muusa_total">$0.00</span></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		</div>
		</td>
	</tr>
</table>
</div>
