$(window).load(function() {
	$(".spinner").spinner({
		stop : function() {
			muusaCalc();
		}
	});
});

function muusaCalc() {
	var duereg = 0.0;
	var duearr = 0.0;
	var adults_num = $("#muusa_adults_num").spinner("value");
	var ya_num = $("#muusa_ya_num").spinner("value");
	var burt_num = $("#muusa_burt_num").spinner("value");
	var children_num = $("#muusa_child_num").spinner("value");
	var infants_num = $("#muusa_infant_num").spinner("value");
	duereg += adults_num * 140;
	$("#muusa_adults_feereg").html("$" + (adults_num * 140).toFixed(2));
	switch (parseInt($("#muusa_adults_hou").val(), 10)) {
	case 0:
		break;
	case 1:
		var rate = adults_num * 540.0;
		if (children_num > 0 || infants_num) {
			rate = adults_num * 520.0;
		} else if (adults_num == 3) {
			rate = adults_num * 530.0;
		} else if (adults_num > 3) {
			rate = adults_num * 525.0;
		}
		duereg += adults_num * 50;
		duearr += rate - (adults_num * 50) + (children_num * 252);
		$("#muusa_adults_feehouse").html("$" + rate.toFixed(2));
		$("#muusa_child_feehouse").html("$" + (children_num * 252).toFixed(2));
		break;
	case 2:
		duereg += adults_num * 50;
		duearr += adults_num * 335 + children_num * 156;
		$("#muusa_adults_feehouse").html("$" + (adults_num * 385).toFixed(2));
		$("#muusa_child_feehouse").html("$" + (children_num * 156).toFixed(2));
		break;
	case 3:
		duereg += adults_num * 50;
		duearr += adults_num * 335 + children_num * 156;
		$("#muusa_adults_feehouse").html("$" + (adults_num * 385).toFixed(2));
		$("#muusa_child_feehouse").html("$" + (children_num * 156).toFixed(2));
		break;
	case 4:
		duereg += adults_num * 50;
		duearr += adults_num * 232 + children_num * 156;
		$("#muusa_adults_feehouse").html("$" + (adults_num * 282).toFixed(2));
		$("#muusa_child_feehouse").html("$" + (children_num * 156).toFixed(2));
		break;
	default:
		break;
	}
	duereg += ya_num * 120;
	$("#muusa_ya_feereg").html("$" + (ya_num * 120).toFixed(2));
	switch (parseInt($("#muusa_ya_hou").val())) {
	case 0:
		break;
	case 1:
		duereg += ya_num * 50;
		duearr += ya_num * 295;
		$("#muusa_ya_feehouse").html("$" + (ya_num * 325).toFixed(2));
		break;
	case 2:
		duereg += ya_num * 50;
		duearr += ya_num * 232;
		$("#muusa_ya_feehouse").html("$" + (ya_num * 282).toFixed(2));
		break;
	default:
		break;
	}
	duereg += burt_num * 160;
	duearr += burt_num * 280;
	$("#muusa_burt_feereg").html("$" + (burt_num * 110).toFixed(2));
	$("#muusa_burt_feehouse").html("$" + (burt_num * 330).toFixed(2));
	duereg += children_num * 80;
	$("#muusa_child_feereg").html("$" + (children_num * 80).toFixed(2));
	duereg += infants_num * 60;
	$("#muusa_infant_feereg").html("$" + (infants_num * 60).toFixed(2));
	$("#muusa_duereg").html("$" + duereg.toFixed(2));
	$("#muusa_duearr").html("$" + duearr.toFixed(2));
	$("#muusa_total").html("$" + (duereg + duearr).toFixed(2));
}