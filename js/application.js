$(window).load(function() {
	$("#muusaApp").tabs({
		active : 0,
		beforeActivate : function(event, ui) {
			recalc(event, ui);
			return true;
		}
	});
	$(".info").button({
		icons : {
			primary : "ui-icon-info"
		},
		text : false
	}).click(function() {
		switchNextRow($(this));
		return false;
	});
	$(".link").button({
		icons : {
			primary : "ui-icon-link"
		},
		text : false
	}).click(function() {
		openLink($(this));
		return false;
	});
	$(".radios").buttonset();
	$(".add").button({
		icons : {
			primary : "ui-icon-plus"
		},
		text : false
	}).click(function() {
		addRow($(this));
		return false;
	});
	$(".birthday").datepicker({
		yearRange : (thisyear - 100) + ":" + thisyear,
		changeMonth : true,
		changeYear : true
	});
	$(".roomtypes").accordion({
		collapsible : true,
		heightStyle : "content",
		header : "h4",
		active : false
	});
	$(".roomtypeSave").button().click(function() {
		$(this).closest("div.roomtypes").accordion({
			active : false
		});
		return false;
	});
	$(".roomtype-yes, .roomtype-no").sortable({
		placeholder : "ui-state-highlight",
		connectWith : ".connectedRoomtype"
	}).disableSelection();
	$(".dialog-message").dialog({
		modal : true,
		autoOpen : false,
		minWidth : 800,
		buttons : {
			Ok : function() {
				$(this).dialog("close");
			}
		}
	});
	$(".delete").button({
		icons : {
			primary : "ui-icon-minus"
		},
		text : false
	}).click(function() {
		hideThisRow($(this));
		return false;
	});
	$("#nextCamper").button().click(function() {
		$("#muusaApp").tabs({
			active : 1
		});
		return false;
	});
	$("#addCamper").button().click(function() {
		addCamper();
		return false;
	});
	$("#removeCamper").button().click(function() {
		removeCamper($(this));
		return false;
	});
	$("#nextWorkshop").button().click(function() {
		$("#muusaApp").tabs({
			active : 2
		});
		return false;
	});
	$(".workshopSelection").accordion({
		heightStyle : "content",
		header : "h4"
	});
	$(".workshopTimes").accordion({
		heightStyle : "content",
		header : "h5"
	});
	$(".workshop-yes, .workshop-no").sortable({
		placeholder : "ui-state-highlight",
		connectWith : ".connectedWorkshop"
	}).disableSelection();
	$("#nextPayment").button().click(function() {
		$("#muusaApp").tabs({
			active : 3
		});
		return false;
	});
	$("#donation").blur(function() {
		donationCalc();
	});
	$("#nextFinish").button().click(function() {
		submit();
		return false;
	});
});

function switchNextRow(obj) {
	obj.parents("tr").next().is(":visible") ? obj.parents("tr").next().hide()
			: obj.parents("tr").next().show();
}

function openLink(obj) {
	eval("$(\"#room-" + obj.parents("li").attr("id") + "\").dialog(\"open\");");
}

function addRow(obj) {
	hiddenRow = obj.parents("tr").nextAll("tr.hidden").first();
	hiddenRow.clone(true).removeClass("hidden").insertBefore(hiddenRow).show();
}

function addCamper() {
	// $(".camperBody").clone(true).removeAttr("id").insertBefore("#lastrow");
	// WRONG
}

function recalc(event, ui) {
	if (ui.newPanel.attr("id") == "appPayment") {
		$("#appPayment tr.dummy").remove();
		$("#noattending").hide();
		if ($("#appPayment td.amount").size() == 1) {
			var dummy = $("#paymentDummy");
			var now = $.datepicker.formatDate('m/dd/yy', new Date());
			var deposit = 0.0;
			var total = 0.0;
			$("#appCamper tbody.camperBody").each(
					function() {
						if ($(".attending", $(this)).val() > 0) {
							var newrow = dummy.clone(true).removeAttr("id")
									.addClass("dummy").insertBefore(dummy);
							$(".chargetype", newrow).text("Registration Fee");
							var grade = pInt($(".grade", $(this)).val());
							var fee = findFee($(".birthday", $(this)).val(),
									grade);
							total += fee;
							$(".amount", newrow).text("$" + fee.toFixed(2));
							if (grade > 5) {
								deposit += 50.0;
							}
							$(".date", newrow).text(now);
							$(".memo", newrow).text(
									$(".firstname", $(this)).val() + " "
											+ $(".lastname", $(this)).val());
							newrow.show();
						}
					});
			if (deposit == 0.0) {
				$("#noattending").show();
			} else {
				total += deposit;
				total += Math.abs(pFloat($("#donation").val()));
				$("#amountNow").text("$" + total.toFixed(2));
				var newrow = dummy.clone(true).removeAttr("id").addClass(
						"dummy").insertBefore(dummy);
				$(".chargetype", newrow).text("Housing Deposit");
				$(".amount", newrow).text("$" + deposit.toFixed(2));
				$(".date", newrow).text(now);
				$(".memo", newrow).text("HOUSING DEPOSIT MSG");
				newrow.show();
			}
		}
	}
}

function donationCalc() {
	var total = 0.0;
	$("#appPayment td.amount:visible").each(function() {
		total += pFloat($(this).text());
	});
	var donation = Math.abs(pFloat($("#donation").val()));
	total += donation;
	$("#donation").val("$" + donation.toFixed(2));
	$("#amountNow").text("$" + total.toFixed(2));
}

function submit() {
	$("#appCamper tbody.camperBody").each(
			function() {
				if ($(".attending", $(this)).val() > 0) {
					var camperid = $(this).attr("id");
					addHidden("roomtype_preferences-buildingids-" + camperid,
							$(".roomtype-yes li", $(this)));
					addHidden("roommate_preferences-names-" + camperid, $(
							"input.roommates", $(this)));
					var phonecount = 1;
					$(".phonenbrs:not(.hidden)", $(this)).each(function() {
						if ($("input[type=text]", $(this)).val() != "") {
							incName($("select,input", $(this)), phonecount++);
						}
					});
				}
			});
	$("#appWorkshop div.desired").each(
			function() {
				addHidden("attendees-eventids-"
						+ $("h6", $(this)).attr("class"), $(".workshop-yes li",
						$(this)));
			});
	addHidden("volunteers-positionids-0", $("#appWorkshop .volunteers li",
			$(this)));
	$("#muusaApp").closest("form").submit();
}

function incName(obj, count) {
	if (!obj.attr("name").match(/-\d+$/)) {
		obj.attr("name", obj.attr("name") + "-" + count);
	}
}

function addHidden(fieldname, selector) {
	var arr = new Array();
	selector.each(function() {
		var str = $(this).val();
		if (str != "" && str != 0) {
			arr.push(escape(str));
		}
	});
	if (arr.length > 0) {
		$("<input>").attr({
			type : "hidden",
			name : fieldname,
			value : arr.join(",")
		}).appendTo("#muusaApp");
	}
}

function findFee(birthday, grade) {
	var age = getAge(birthday);
	for ( var i = 0; i < feeTable.fees.length; i++) {
		if (age < feeTable.fees[i].agemax && age > feeTable.fees[i].agemin
				&& grade < feeTable.fees[i].grademax
				&& grade > feeTable.fees[i].grademin) {
			return feeTable.fees[i].fee;
		}
	}
	return 0.0;
}

function getAge(dateString) {
	var birthDate = new Date(dateString);
	var age = campDate.getFullYear() - birthDate.getFullYear();
	var m = campDate.getMonth() - birthDate.getMonth();
	if (m < 0 || (m === 0 && campDate.getDate() < birthDate.getDate())) {
		age--;
	}
	return age;
}

function removeCamper(obj) {
	obj.parents("tbody").remove();
}

function hideThisRow(obj) {
	obj.parents("tr").remove();
}

function checkForm() {
	var tbodys = document.getElementsByTagName("tbody");
	for ( var i = 0; i < tbodys.length; i++) {
		var inputs = tbodys[i].getElementsByTagName("input");
		if (tbodys[i].className == "camper"
				&& inputs[0].name.match(/^campers-firstname/)
				&& inputs[0].value != "") {
			for ( var j = 0; j < inputs.length; j++) {
				if (inputs[j].name.match(/firstname/)
						&& checkNotEmpty(inputs[j], "first name"))
					return false;
				if (inputs[j].name.match(/lastname/)
						&& checkNotEmpty(inputs[j], "last name"))
					return false;
				if (inputs[j].name.match(/address1/)
						&& checkNotEmpty(inputs[j], "address"))
					return false;
				if (inputs[j].name.match(/city/)
						&& checkNotEmpty(inputs[j], "city"))
					return false;
				if (inputs[j].name.match(/zipcd/)
						&& (checkNotEmpty(inputs[j], "zip code") || checkZip(inputs[j])))
					return false;
				if (inputs[j].name.match(/birthdate/)
						&& (checkNotEmpty(inputs[j], "birth date") || checkDate(
								inputs[j], "birth date")))
					return false;
				if (inputs[j].name.match(/phonenbr-/) && inputs[j].value != ""
						&& checkPhone(inputs[j], "phone number"))
					return false;
			}
			var selects = tbodys[i].getElementsByTagName("select");
			for ( var j = 0; j < selects.length; j++) {
				if (selects[j].name.match(/sexcd/)
						&& checkNotZero(selects[j], "gender"))
					return false;
				if (selects[j].name.match(/statecd/)
						&& checkNotEmpty(selects[j], "state"))
					return false;
			}
		}
	}
	document.getElementsByName('application')[0].submit();
	return true;
}

function checkNotEmpty(obj, msg) {
	if (obj.value == "") {
		obj.focus();
		alert("Your " + msg + " cannot be empty.");
		return true;
	}
	return false;
}

function checkNotZero(obj, msg) {
	if (obj.options[obj.selectedIndex].value == "0") {
		obj.focus();
		alert("Please select " + msg + ".");
		return true;
	}
	return false;
}

function checkZip(obj) {
	str = obj.value.replace("-", "");
	if (!/^\d*$/.test(str)) {
		obj.focus();
		alert("Your zip code must be all digits.");
		return true;
	}
	if (str.length != 5 && str.length != 9) {
		obj.focus();
		alert("Your zip code must be 5 or 9 digits long.");
		return true;
	}
	return false;
}

function checkDate(obj, msg) {
	str = obj.value;
	if (!/^([1-9]|0[1-9]|1[012])[- \/.]([1-9]|0[1-9]|[12][0-9]|3[01])[- \/.][0-9]{4}$/
			.test(str)) {
		obj.focus();
		alert("Your " + msg + " must be in MM/DD/YYYY format.");
		return true;
	}
	return false;
}

function checkPhone(obj, msg) {
	str = obj.value.replace(/\(/g, "").replace(/\)/g, "").replace(/ /g, "")
			.replace(/\-/g, "");
	if (str == "") {
		return false;
	}
	if (!/^\d*$/.test(str)) {
		obj.focus();
		alert("Your " + msg + " must be all digits.");
		return true;
	}
	if (str.length != 10) {
		obj.focus();
		alert("Your " + msg + " must be 10 digits long.");
		return true;
	}
	return false;
}

function pInt(val) {
	return val != "" ? parseInt(val.replace(/[^0-9\.-]+/g, ""), 10) : 0;
}

function pFloat(val) {
	return val != "" ? parseFloat(val.replace(/[^0-9\.-]+/g, "")) : 0.0;
}