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
							primary : "ui-icon-image"
						},
						text : false
					}).click(function() {
						openLink($(this));
						return false;
					});
					var pp = $("#paypalRedirect");
					if (pp != undefined) {
						pp.dialog();
						setTimeout("jQuery('#paypalForm').submit();", 1000);
					}
					$(".radios").buttonset();
					$(".add").button({
						icons : {
							primary : "ui-icon-plus"
						},
						text : false
					}).click(function() {
						addRow($(this), "tr");
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
						hideThis($(this).parents("tr"));
						return false;
					});
					$("#nextCamper").button().click(function() {
						$("#muusaApp").tabs({
							active : 1
						});
						return false;
					});
					$("#addCamper").button().click(
							function() {
								$("#appCamper tbody.camperBody:hidden :first")
										.show().find(
												".roomtype-yes, .roomtype-no")
										.sortable({
											placeholder : "ui-state-highlight",
											connectWith : ".connectedRoomtype"
										}).disableSelection();
								return false;
							});
					$(".removeCamper").button().click(
							function() {
								$(this).parents("tbody").hide().find(
										".firstname").val("");
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
										window.location.href = "http://muusa.org/index.php?Itemid=222";
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
										window.location.href = "http://muusa.org/index.php?Itemid=222";
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
	obj.parents("tr").next().is(":visible") ? obj.parents("tr").next().hide()
			: obj.parents("tr").next().show();
}

function openLink(obj) {
	eval("jQuery(\"#room-" + obj.parents("li").val() + "\").dialog(\"open\");");
}

function addRow(obj, type) {
	hiddenRow = obj.parents(type).nextAll(type + ".hidden").first();
	hiddenRow.clone(true).removeClass("hidden").insertBefore(hiddenRow).show();
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
	$(".onlydigits:visible", panel).each(
			function() {
				if (!errorCheck(event, $(this), !/^\d*$/.test($(this).val()
						.replace(/-/g, "")), "Only numbers are allowed")) {
					return false;
				}
			});
	$(".validday:visible", panel)
			.each(
					function() {
						if (!errorCheck(
								event,
								$(this),
								!/^([1-9]|0[1-9]|1[012])\/([1-9]|0[1-9]|[12][0-9]|3[01])\/[0-9]{4}$/
										.test($(this).val()),
								"Must be a valid date in MM/DD/YYYY format")) {
							return false;
						}
					});
	$(".validphone:visible", panel).each(
			function() {
				if (!errorCheck(event, $(this), !/^\d{0,10}$/.test($(this)
						.val().replace(/\D/g, "")),
						"Must be a valid 10-digit phone number")) {
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
		$("#appPayment tr.dummy").remove();
		$("#noattending").hide();
		var dummy = $("#paymentDummy");
		var now = $.datepicker.formatDate('m/dd/yy', new Date());
		var deposit = 0.0;
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
								var newrow = dummy.clone(true).removeAttr("id")
										.addClass("dummy").insertBefore(dummy);
								$(".chargetype", newrow).text(
										"Registration Fee");
								var fee = findFee(
										$(".birthday", $(this)).val(), grade);
								$(".amount", newrow).text("$" + fee.toFixed(2));
								$(".date", newrow).text(now);
								$(".memo", newrow).text(campername);
								newrow.show();
							}
							registered = jQuery.grep(registered,
									function(value) {
										return value != campername;
									});
							if (grade > 6) {
								deposit += 50.0;
							}
						});

		$("#payments" + thisyear + " tr").filter(
				function() {
					return $("td.chargetype:contains('Registration Fee')",
							$(this)).size() > 0
							&& $.inArray($("td.memo", $(this)).text(),
									registered) != -1;
				}).remove();
		if (deposit == 0.0) {
			$("#noattending").show();
		} else if ($(
				"#payments" + thisyear
						+ " td.chargetype:contains('Housing Fee')").size() == 0) {
			var housingdepo = $("#payments" + thisyear + " tr").filter(
					function() {
						return $("td.chargetype", $(this)).text().contains(
								"Housing Deposit");
					});
			if (housingdepo.size() == 0) {
				var housingdepo = dummy.clone(true).removeAttr("id").addClass(
						"dummy").insertBefore(dummy);
				$(".chargetype", housingdepo).text("Housing Deposit");
				$(".date", housingdepo).text(now);
				housingdepo.show();
			}
			$(".amount", housingdepo).text("$" + deposit.toFixed(2));
		}
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
	$("#paypalAmt").val(total.toFixed(2));
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
	$("#appCamper tbody.camperBody")
			.filter(
					function() {
						return $(".attending", $(this)).val() != 0
								&& $(".firstname", $(this)).val() != "";
					})
			.each(
					function() {
						$(".phonenbrs", $(this))
								.filter(
										function() {
											return $("input[type=text]",
													$(this)).val() != ""
													&& $(
															"input[name*='phonenumbers-phonenbrid']",
															$(this)).val() == "";
										})
								.each(
										function() {
											$("select,input", $(this)).each(
													function() {
														incName($(this),
																phoneCount);
													});
											$(
													"input[name*='phonenumbers-phonenbrid']",
													$(this)).val(phoneCount++);
										});
						var camperid = $("input[name*='campers-camperid']",
								$(this)).val();
						if (camperid == undefined) {
							camperid = camperCount++;
						}
						$("select[name*='campers'],input[name*='campers']",
								$(this)).each(function() {
							incName($(this), camperid);
						});
						$("input[name*='phonenumbers-camperid']", $(this)).val(
								camperid);
						addHidden($, "roomtype_preferences-buildingids-"
								+ camperid, $(".roomtype-yes li", $(this)));
						addHidden($, "roommate_preferences-names-" + camperid,
								$("input.roommates", $(this)));
					});
	$("#muusaApp").closest("form").submit();
}

function incName(obj, count) {
	if (obj.attr("name") != undefined && !obj.attr("name").match(/\d+$/)) {
		obj.attr("name", obj.attr("name") + count);
	}
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

function hideThis(obj) {
	obj.remove();
}

function pInt(val) {
	return val != undefined && val != "" ? parseInt(val.replace(/[^0-9\.-]+/g,
			""), 10) : 0;
}

function pFloat(val) {
	return val != undefined && val != "" ? parseFloat(val.replace(
			/[^0-9\.-]+/g, "")) : 0.0;
}
