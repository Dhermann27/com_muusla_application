jQuery(document)
		.ready(
				function($) {
					$("#muusaApp").tabs({
						active : 0,
						beforeActivate : function(event, ui) {
							trap($, event, ui);
							recalc($, event, ui);
							return true;
						},
						activate : function() {
							$("html, body").animate({
								scrollTop : 0
							}, "slow");
						}
					}).tooltip({
						position : {
							at : "right center"
						}
					});
					$(".paymentYears").accordion({
						heightStyle : "content",
						header : "h4"
					});
					$(".link").button({
						icons : {
							primary : "ui-icon-image"
						},
						text : false
					}).click(function() {
						openLink($(this));
						return false;
					});
					$("#paypalRedirect").dialog({
						modal : true,
						minWidth : 800,
						buttons : [ {
							text : "Proceed to PayPal.com",
							click : function() {
								jQuery("#paypalForm").submit();
								return false;
							}
						} ]
					});
					$(".radios").buttonset();
					$(".roomtypes").accordion({
						collapsible : true,
						heightStyle : "content",
						header : "h4",
						active : false
					});
					// $(".roomtypeSave").button().click(function() {
					// $(this).closest("div.roomtypes").accordion({
					// active : false
					// });
					// return false;
					// });
					// $(".roomtype-yes, .roomtype-no").sortable({
					// placeholder : "ui-state-highlight",
					// connectWith : ".connectedRoomtype"
					// }).disableSelection();
					$("#nextCamper").button().click(function() {
						$("#muusaApp").tabs({
							active : 1
						});
						return false;
					});
					$("#addCamper").button().click(function() {
						addRow($("tbody.camperBody :first"), "tbody", false);
						return false;
					});
					$("#removeCamper").button().click(function() {
						var tbody = $("tbody.camperBody");
						if (tbody.length > 1 && !tbody.last().attr("id"))
							tbody.last().remove();
						return false;
					});
					$("#nextPayment").button().click(function() {
						$("#muusaApp").tabs({
							active : 2
						});
						return false;
					});
					$(".recalc").blur(function() {
						donationCalc($);
					});
					$("#nextWorkshop")
							.button()
							.click(
									function() {
										window.location.href = "http://muusa.org/registration/workshops";
										return false;
									});
					$("#backDetails")
							.button({
								icons : {
									primary : "ui-icon-triangle-1-w"
								}
							})
							.click(
									function() {
										window.location.href = "http://muusa.org/index.php/admin/database/campers";
										return false;
									});
					$("#forwardWorkshop")
							.button({
								icons : {
									primary : "ui-icon-triangle-1-e"
								}
							})
							.click(
									function() {
										window.location.href = "http://muusa.org/registration/workshops";
										return false;
									});
					$("#finishPaypal").button().click(function() {
						submit($);
						return false;
					});
					$(".finishWorkshop").button().click(function() {
						$("#paypalAmt").val("0");
						submit($);
						return false;
					});
				});

function switchNextRow(obj) {
	obj.parents("tr").next().toggle();
}

function openLink(obj) {
	eval("jQuery(\"#room-" + obj.parents("li").val() + "\").dialog(\"open\");");
}

function trap($, event, ui) {
	var panel = $("#" + ui.oldPanel.attr("id"));
	$(".notempty:visible", panel).each(
			function() {
				if (!errorCheck(event, $(this), $(this).val() == "",
						"Field cannot be empty")) {
					return false;
				}
			});
	$(".notzero:visible", panel).each(
			function() {
				if (!errorCheck(event, $(this), $(this).val() == 0,
						"Please select a value")) {
					return false;
				}
			});
	var seen = {};
	$(".email:visible").each(
			function() {
				var email = $(this).val();
				if (!errorCheck(event, $(this), email != "" && seen[email],
						"Duplicate email address")) {
					return false;
				} else {
					seen[email] = true;
				}
			});
	return true;
}

function errorCheck(event, obj, check, msg) {
	if (check) {
		obj.addClass("ui-state-error").attr("title", msg).focus().scrollTo();
		event.preventDefault();
		event.stopPropagation();
		return false;
	} else {
		obj.removeClass("ui-state-error").attr("title", "");
		return true;
	}
}

function recalc($, event, ui) {
	if (ui.newPanel.attr("id") == "appPayment") {
		$("#payments" + thisyear + " tr.pending").remove();
		$("#noattending").hide();
		var total = totalCharges($);
		var registered = new Array();
		$("#payments" + thisyear + " tr").filter(
				function() {
					return $("td.chargetype:contains('Registration Fee')",
							$(this)).size() > 0;
				}).each(function() {
			registered.push($("td.memo", $(this)).text());
		});
		$("#appCamper tbody.camperBody")
				.filter(
						function() {
							return $(".attending", $(this)).val() != 0
									&& $(".firstname", $(this)).val() != "";
						})
				.each(
						function() {
							var campername = $(".firstname", $(this)).val()
									+ " " + $(".lastname", $(this)).val();
							var grade = pInt($(".grade", $(this)).val());
							if ($.inArray(campername, registered) == -1) {
								var newrow = $("<tr class='pending'><td class='chargetype'></td><td class='amount' align='right'></td><td class='date' align='center'></td><td class='memo'></td></tr>");
								$(".chargetype", newrow).text(
										"Registration Fee");
								var fee = findFee(
										$(".birthday", $(this)).val(), grade);
								total += fee;
								$(".amount", newrow).text("$" + fee.toFixed(2));
								$(".date", newrow).html("<i>Pending</i>");
								$(".memo", newrow).text(campername);
								$("#payments" + thisyear + " tr").first()
										.after(newrow);
							}
							registered = jQuery.grep(registered,
									function(value) {
										return value != campername;
									});
						});

		$("#payments" + thisyear + " tr").filter(
				function() {
					return $("td.chargetype:contains('Registration Fee')",
							$(this)).size() > 0
							&& $.inArray($("td.memo", $(this)).text(),
									registered) != -1;
				}).remove();
		total += Math.abs(pFloat($("#donation").val()));
		$("#amountNow").text("$" + total.toFixed(2));
		$("#paypalAmt").val(total.toFixed(2));
	}
}

function donationCalc($) {
	var total = totalCharges($);
	var donation = Math.abs(pFloat($("#donation").val()));
	if (isNaN(donation)) {
		donation = 0.0;
	}
	total += donation;
	$("#donation").val(donation.toFixed(2));
	$("#amountNow").text("$" + total.toFixed(2));
	$("#paypalAmt").val(Math.max(total, 0).toFixed(2));
}

function totalCharges($) {
	var total = 0.0;
	$("#payments" + thisyear + " td.amount").each(function() {
		total += pFloat($(this).text());
	});
	$("#payments" + thisyear + " input[name*='charges-amount']").each(
			function() {
				total += pFloat($(this).val());
			});
	return total;
}

function submit($) {
	var camperCount = 100;
	var phoneCount = 200;
	$("#appCamper tbody.camperBody").filter(
			function() {
				return $(".attending", $(this)).val() != 0
						&& $(".firstname", $(this)).val() != "";
			})
			.each(
					function() {
						$(".phonenbrs", $(this)).each(function() {
							$("select,input", $(this)).each(function() {
								incName($(this), phoneCount);
							});
							phoneCount++;
						});
						var camperid = $("input[name*='camper-id']", $(this))
								.val();
						if (camperid == undefined) {
							camperid = camperCount++;
						}
						$("select[name*='camper'],input[name*='camper']",
								$(this)).each(function() {
							incName($(this), camperid);
						});
						$("input[name*='phonenumber-camperid']", $(this)).val(
								camperid);
						addHidden($, "roommatepreference-names-" + camperid, $(
								"input.roommates", $(this)));
					});
	$("#muusaApp").closest("form").submit();
}

function addHidden($, fieldname, selector) {
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
		if (age <= feeTable.fees[i].agemax && age >= feeTable.fees[i].agemin
				&& grade <= feeTable.fees[i].grademax
				&& grade >= feeTable.fees[i].grademin) {
			return feeTable.fees[i].fee;
		}
	}
	return 0.0;
}
