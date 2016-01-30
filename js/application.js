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
					$(".link").click(function() {
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
					$("#nextCamper").click(function() {
						$("#muusaApp").tabs({
							active : 1
						});
						return false;
					});
					$("#addCamper").click(function() {
						addRow($("tbody.camperBody :first"), "tbody", false);
						return false;
					});
					$("#removeCamper").click(function() {
						var tbody = $("tbody.camperBody");
						if (tbody.length > 1 && !tbody.last().attr("id"))
							tbody.last().remove();
						return false;
					});
					$("#nextPayment").click(function() {
						$("#muusaApp").tabs({
							active : 2
						});
						return false;
					});
					$(".recalc").blur(function() {
						donationCalc($);
					});
					$("#nextWorkshop")
							.click(
									function() {
										window.location.href = "http://muusa.org/registration/workshops";
										return false;
									});
					$("#forwardWorkshop")
							.click(
									function() {
										window.location.href = "http://muusa.org/registration/workshops";
										return false;
									});
					$("#paypalAmt").css("border-right", "0px").css(
							"margin-right", "0px").focus(
							function() {
								$(this).val("");
								var arr = [
										"$0.00 (Pay Another Amount)",
										"$"
												+ Math.max(
														pFloat($("#amountNow")
																.text()), 0)
														.toFixed(2)
												+ " (Amount Due Now)" ];
								var arrival = $("#amountArrival");
								if (arrival.length > 0) {
									arr.push("$"
											+ Math.max(pFloat(arrival.text()),
													0).toFixed(2)
											+ " (Total Amount Due)");
								}
								$(this).autocomplete({
									minLength : 0,
									source : arr

								}).autocomplete("search", "");
							});
					$("#finishPaypal").removeClass("ui-corner-all").addClass(
							"ui-corner-right").click(function() {
						submit($);
						return false;
					});
					$("#finishWorkshop").click(function() {
						$("#paypalAmt").val("0");
						submit($);
						return false;
					});
					$("#registerAll").click(function() {
						$("#paypalAmt").val("0");
						$("#appCamper tbody.camperBody .attending").val("6");
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
		var registered = new Array();
		$("#payments" + thisyear + " tr").filter(function() {
			return $("td.chargetype:contains('MUUSA')", $(this)).size() > 0;
		}).each(function() {
			registered.push($("td.memo", $(this)).text());
		});
		$("#appCamper tbody.camperBody")
				.filter(
						function() {
							return $(".attending", $(this)).val() != -1
									&& $(".firstname", $(this)).val() != "";
						})
				.each(
						function() {
							var days = pInt($(".attending", $(this)).val());
							var campername = $(".firstname", $(this)).val()
									+ " " + $(".lastname", $(this)).val();
							var grade = pInt($(".grade", $(this)).val());
							if ($.inArray(campername, registered) == -1) {
								var newrow = $("<tr class='pending'><td class='chargetype'></td><td class='amount' align='right'></td><td class='date' align='center'></td><td class='memo'></td></tr>");
								$(".chargetype", newrow).text("MUUSA Deposit");
								var fee = Math.min(findFee($(".birthday",
										$(this)).val(), grade), 30 * days);
								$(".amount", newrow).text("$" + fee.toFixed(2));
								$(".date", newrow).html("<i>Pending</i>");
								$(".memo", newrow).text(campername);
								$("#payments" + thisyear + " tr").first()
										.after(newrow);
							} else {
								var fee = Math.min(findFee($(".birthday",
										$(this)).val(), grade), 30 * days);
								$("td.memo:contains('" + campername + "')")
										.parent()
										.filter(
												"td.chargetype:contains('Fees')")
										.find(".amount").text(
												"$" + fee.toFixed(2));
							}
							registered = jQuery.grep(registered,
									function(value) {
										return value != campername;
									});
						});

		$("#payments" + thisyear + " tr")
				.filter(
						function() {
							return $("td.chargetype:contains('MUUSA Deposit')",
									$(this)).size() > 0
									&& $.inArray($("td.memo", $(this)).text(),
											registered) != -1;
						}).remove();
		donationCalc($);
	}
}

function donationCalc($) {
	var donation = Math.abs(pFloat($("#donation").val()));
	if (isNaN(donation)) {
		donation = 0.0;
	}
	$("#donation").val(donation.toFixed(2));

	var totalNow = totalCharges($, true);
	$("#amountNow").text("$" + totalNow.toFixed(2));
	$("#paypalAmt").val(Math.max(totalNow, 0).toFixed(2));
	var later = $("#amountArrival");
	if (later.length) {
		later.text("$" + totalCharges($, false).toFixed(2));
	}
}

function totalCharges($, isNow) {
	var total = 0.0;
	$("#payments" + thisyear + " td.amount").each(function() {
		if (!isNow || $(this).prev().text() != "Housing Fee") {
			total += pFloat($(this).text());
		}
	});
	$("#payments" + thisyear + " input[name*='charge-amount']").each(
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
				return $(".attending", $(this)).val() != -1
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
	for (var i = 0; i < feeTable.fees.length; i++) {
		if (age <= feeTable.fees[i].agemax && age >= feeTable.fees[i].agemin
				&& grade <= feeTable.fees[i].grademax
				&& grade >= feeTable.fees[i].grademin) {
			return feeTable.fees[i].fee;
		}
	}
	return 0.0;
}
