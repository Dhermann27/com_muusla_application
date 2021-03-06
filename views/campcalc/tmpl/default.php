<?php defined('_JEXEC') or die('Restricted access'); ?>
<link type="text/css"
	href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
	rel="stylesheet" />
<script type="text/javascript"
	src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/campcost.js"></script>
<script>var thisyear = 0;</script>
<style>
input.spinner {
	width: 2em;
	text-align: center;
}
</style>
<div id="ja-content">
	<div class="componentheading">MUUSA Camp Cost Calculator</div>
	<table id="muusaApp">
		<tr>
			<td valign="top">
				<div>
					<h3>
						These numbers are approximations and can change based on several
						other factors, such as additional excursion costs and volunteer
						credits. You should <i>not</i> use this page to precisely
						determine your camp costs.
					</h3>
					<table>
						<tr align="center">
							<td>&nbsp;</td>
							<td>Number<br /> Attending
							</td>
							<td>Housing Arrangements</td>
							<td>MUUSA Fees</td>
						</tr>
						<tr>
							<td><b>Adults</b></td>
							<td align="center"><input id="muusa_adults_num" class="spinner" /></td>
							<td><select id="muusa_adults_hou" class="ui-corner-all"
								onchange="muusaCalc(jQuery);">
									<option value="0" selected>Select one</option>
									<option value="1">Guestroom, Cabin, or Loft (+$550 or more)</option>
									<option value="3">Camp Lakewood (dorm style, +$395)</option>
									<option value="4">Tent Camping (+$288)</option>
							</select></td>
							<td align="right" id="muusa_adults_fee">$0.00</td>
						</tr>
						<tr>
							<td><b>Young Adults</b> (18-20 years old)</td>
							<td align="center"><input id="muusa_ya_num" class="spinner" /></td>
							<td><select id="muusa_ya_hou" class="ui-corner-all"
								onchange="muusaCalc(jQuery);">
									<option value="0" selected>Select one</option>
									<option value="1">YA Cabin (+$380)</option>
									<option value="2">Tent Camping (+$288)</option>
							</select></td>
							<td align="right" id="muusa_ya_fee">$0.00</td>
						</tr>
						<tr>
							<td><b>Jr./Sr. High School</b></td>
							<td align="center"><input id="muusa_burt_num" class="spinner" /></td>
							<td>Burt/Meyer Cabins (+$380)</td>
							<td align="right" id="muusa_burt_fee">$0.00</td>
						</tr>
						<tr>
							<td><b>Children</b> (6 years old or older)</td>
							<td align="center"><input id="muusa_child_num" class="spinner" /></td>
							<td>Must room with parents (+$264/$162)</td>
							<td align="right" id="muusa_child_fee">$0.00</td>
						</tr>
						<tr>
							<td><b>Children</b> (Up to 5 years old)</td>
							<td align="center"><input id="muusa_infant_num" class="spinner" /></td>
							<td>Must room with parents ($80)</td>
							<td align="right" id="muusa_infant_fee">$0.00</td>
						</tr>
						<tr>
							<td colspan="3" align="right">Amount Due Upon Registration: <span
								id="muusa_duereg">$0.00</span>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" align="right">Amount Due Upon Arrival: <span
								id="muusa_duearr">$0.00</span>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" align="right"><b>Total Camp Cost</b>: <span
								id="muusa_total">$0.00</span></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</div>
