function muusaCalc() {
	var duereg = 0.0;
	var duearr = 0.0;
	var adults_num = parseInt(muusaValue(document
			.getElementById("muusa_adults_num")));
	var ya_num = parseInt(muusaValue(document.getElementById("muusa_ya_num")));
	var burt_num = parseInt(muusaValue(document
			.getElementById("muusa_burt_num")));
	var children_num = parseInt(muusaValue(document
			.getElementById("muusa_child_num")));
	var infants_num = parseInt(muusaValue(document
			.getElementById("muusa_infant_num")));
	duereg += adults_num * 140;
	document.getElementById("muusa_adults_feereg").innerHTML = "$" + (adults_num * 140).toFixed(2);
	switch (document.getElementById("muusa_adults_hou").selectedIndex) {
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
		document.getElementById("muusa_adults_feehouse").innerHTML = "$" + rate.toFixed(2);
		document.getElementById("muusa_child_feehouse").innerHTML = "$"
				+ (children_num * 252).toFixed(2);
		break;
	case 2:
		duereg += adults_num * 50;
		duearr += adults_num * 335 + children_num * 156;
		document.getElementById("muusa_adults_feehouse").innerHTML = "$"
				+ (adults_num * 385).toFixed(2);
		document.getElementById("muusa_child_feehouse").innerHTML = "$"
				+ (children_num * 156).toFixed(2);
		break;
	case 3:
		duereg += adults_num * 50;
		duearr += adults_num * 335 + children_num * 156;
		document.getElementById("muusa_adults_feehouse").innerHTML = "$"
				+ (adults_num * 385).toFixed(2);
		document.getElementById("muusa_child_feehouse").innerHTML = "$"
				+ (children_num * 156).toFixed(2);
		break;
	case 4:
		duereg += adults_num * 50;
		duearr += adults_num * 232 + children_num * 156;
		document.getElementById("muusa_adults_feehouse").innerHTML = "$"
				+ (adults_num * 282).toFixed(2);
		document.getElementById("muusa_child_feehouse").innerHTML = "$"
				+ (children_num * 156).toFixed(2);
		break;
	default:
		break;
	}
	duereg += ya_num * 120;
	document.getElementById("muusa_ya_feereg").innerHTML = "$"
			+ (ya_num * 120).toFixed(2);
	switch (document.getElementById("muusa_ya_hou").selectedIndex) {
	case 0:
		break;
	case 1:
		duereg += ya_num * 50;
		duearr += ya_num * 295;
		document.getElementById("muusa_ya_feehouse").innerHTML = "$"
				+ (ya_num * 325).toFixed(2);
		break;
	case 2:
		duereg += ya_num * 50;
		duearr += ya_num * 232;
		document.getElementById("muusa_ya_feehouse").innerHTML = "$"
				+ (ya_num * 282).toFixed(2);
		break;
	default:
		break;
	}
	duereg += burt_num * 160;
	duearr += burt_num * 280;
	document.getElementById("muusa_burt_feereg").innerHTML = "$"
			+ (burt_num * 110).toFixed(2);
	document.getElementById("muusa_burt_feehouse").innerHTML = "$"
			+ (burt_num * 330).toFixed(2);
	duereg += children_num * 80;
	document.getElementById("muusa_child_feereg").innerHTML = "$"
			+ (children_num * 80).toFixed(2);
	duereg += infants_num * 60;
	document.getElementById("muusa_infant_feereg").innerHTML = "$"
			+ (infants_num * 60).toFixed(2);
	document.getElementById("muusa_duereg").innerHTML = "$" + duereg.toFixed(2);
	document.getElementById("muusa_duearr").innerHTML = "$" + duearr.toFixed(2);
	document.getElementById("muusa_total").innerHTML = "$" + (duereg+duearr).toFixed(2);
}

function muusaValue(obj) {
	return obj.options[obj.selectedIndex].value;
}
