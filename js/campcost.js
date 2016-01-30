jQuery(document).ready(function($) {
	$(".spinner").spinner({
		min: 0,
		stop : function() {
			muusaCalc($);
		}
	});
});

function muusaCalc($) {
	var duereg = 0.0;
	var duearr = 0.0;
	var adults_num = $("#muusa_adults_num").spinner("value");
	var ya_num = $("#muusa_ya_num").spinner("value");
	var burt_num = $("#muusa_burt_num").spinner("value");
	var children_num = $("#muusa_child_num").spinner("value");
	var infants_num = $("#muusa_infant_num").spinner("value");
	duereg += adults_num * 150;
	switch (parseInt($("#muusa_adults_hou").val(), 10)) {
	case 0:
		$("#muusa_adults_fee").html("$" + (adults_num * 150).toFixed(2));
		$("#muusa_child_fee").html("$" + (adults_num * 80).toFixed(2));
		break;
	case 1:
		var rate = adults_num * 550.0;
		switch (parseInt(adults_num, 10)) {
		case 1:
			rate = adults_num * 1200.0;
			break;
		case 2:
			rate = adults_num * 600.0;
			break;
		}
		if (children_num > 0 || infants_num > 0) {
			rate = adults_num * 550.0;
		}
		duearr += rate + (children_num * 264);
		$("#muusa_adults_fee").html("$" + (adults_num * 150 + rate).toFixed(2));
		$("#muusa_child_fee").html("$" + (children_num * 344).toFixed(2));
		break;
	case 3:
		duearr += adults_num * 395 + children_num * 264;
		$("#muusa_adults_fee").html("$" + (adults_num * 545).toFixed(2));
		$("#muusa_child_fee").html("$" + (children_num * 344).toFixed(2));
		break;
	case 4:
		duearr += adults_num * 288 + children_num * 162;
		$("#muusa_adults_fee").html("$" + (adults_num * 438).toFixed(2));
		$("#muusa_child_fee").html("$" + (children_num * 242).toFixed(2));
		break;
	}
	duereg += ya_num * 120;
	switch (parseInt($("#muusa_ya_hou").val())) {
	case 0:
		$("#muusa_ya_fee").html("$" + (ya_num * 120).toFixed(2));
		break;
	case 1:
		duearr += ya_num * 370;
		$("#muusa_ya_fee").html("$" + (ya_num * 490).toFixed(2));
		break;
	case 2:
		duearr += ya_num * 288;
		$("#muusa_ya_fee").html("$" + (ya_num * 408).toFixed(2));
		break;
	}
	duereg += burt_num * 110;
	duearr += burt_num * 380;
	$("#muusa_burt_fee").html("$" + (burt_num * 490).toFixed(2));
	duereg += children_num * 80;
	duereg += infants_num * 80;
	$("#muusa_infant_fee").html("$" + (infants_num * 80).toFixed(2));
	$("#muusa_duereg").html("$" + duereg.toFixed(2));
	$("#muusa_duearr").html("$" + duearr.toFixed(2));
	$("#muusa_total").html("$" + (duereg + duearr).toFixed(2));
}