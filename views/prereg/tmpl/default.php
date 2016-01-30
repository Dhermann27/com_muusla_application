<?php
defined('_JEXEC') or die('Restricted access');
?>
<div id="ja-content">
	<link type="text/css"
		href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
		rel="stylesheet" />
	<strong>Pregistration guarantees you:</strong>
	<ul>
		<li>Access to the Priority Registration Period:
			<ul>
				<li>Opens on <?php echo $this->year[0];?></li>
				<li>If you attended, the ability to choose your room from <?php echo $this->year[1]-1;?></li>
				<li>If you did not attend or want to change your room, you have the
					ability to choose from rooms not locked by other preregistered
					campers, before regular room selection begins</li>
				<li>Once a new room is chosen, your old room becomes available to
					other preregistered campers</li>
			</ul>
		</li>
		<li><i>If you do not register, pay your family's deposit,
				and choose your room within 30 days, you forfeit this lock and your
				<?php echo $this->year[1]-1;?> room becomes available to other campers</i></li>
	</ul>
	<form id="muusaApp" name="_xclick"
		action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<table>
			<thead>
				<tr>
					<th>Camper Name</th>
					<th>Age during MUUSA <?php echo $this->year[1];?> </th>
					<th>Preregister?</th>
				</tr>
			</thead>
	<?php foreach ($this->campers as $camper) {	?>
	<tr align="center">
				<td align="left"><?php echo $camper->firstname . " " . $camper->lastname;?></td>
				<td><?php echo $camper->age;?></td>
				<td><?php if($camper->is_prereg>0) { echo "Preregistered"; } else {?>
				<select id="<?php echo $camper->id;?>" class="prereg ui-corner-all">
						<option value="0">Do not preregister</option>
						<option value="<?php echo number_format($camper->fee, 2);?>">Preregister</option>
				</select><?php }?></td>
			</tr>
	<?php }?>
	<tfoot>
				<tr align="center">
					<td><strong>Total Preregistration Cost: </strong></td>
					<td align="left" id="total">$0.00</td>
					<td><input type="image"
						src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif"
						style="margin-right: 7px; background-color: rgba(255, 255, 255, 0.01); border: 0px;"
						alt="Submit payment via PayPal" /></td>
				</tr>
			</tfoot>
		</table>
		<input type="hidden" id="payPalAmt" name="amount" /> <input
			type="hidden" id="camperIds" name="custom" /> <input type="hidden"
			name="cmd" value="_xclick"> <input type="hidden" name="business"
			value="muusaregistar@gmail.com"> <input type="hidden"
			name="currency_code" value="USD"> <input type="hidden"
			name="item_name" value="MUUSA Preregistration"> <img alt=""
			border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"
			width="1" height="1" />
	</form>
	<strong>There is no advantage to preregistering campers younger than 18
		(in the Children's, Meyer, or Burt programs).</strong>
	<script>
	jQuery(document).ready(function($) {
	    $(".prereg").change(function() {
	        var total = 0.0;
	        var campers = new Array();
	        $(".prereg").each(function() {
		        var amt = parseFloat($(this).val());
		        if(amt > 0.0) {
		           total += parseFloat(amt);
		           campers.push($(this).attr("id") + "-" + parseFloat(amt));
		        }
	        });
	        $("#total").text("$" + total.toFixed(2));
	        $("#payPalAmt").val(total.toFixed(2));
	        $("#camperIds").val(campers.join("|"));
	    });
	});
	</script>
</div>
