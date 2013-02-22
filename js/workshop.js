$(window).load(
		function() {
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

function addRow(obj, type) {
	hiddenRow = obj.parents(type).nextAll(type + ".hidden").first();
	hiddenRow.clone(true).removeClass("hidden").insertBefore(hiddenRow).show();
}

function trap(event, ui) {
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
				if (!errorCheck(event, $(this), !/^\d*$/.test($(this).val()),
						"Only numbers are allowed")) {
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

function recalc(event, ui) {
	if (ui.newPanel.attr("id") == "appWorkshop") {
		/*$("#appWorkshop h4").addClass("deleteMe");
		$("#appCamper tbody.camperBody:not(.hidden)")
				.each(
						function() {
							var campername = $(".firstname", $(this)).val()
									+ " " + $(".lastname", $(this)).val();
							if ($(".attending", $(this)).val() != 0) {
								var camper = $("#appWorkshop h4:contains('"
										+ campername + "')");
								if (camper.size() >= 1) {
									camper.first().show();
									camper.removeClass("deleteMe");
								} else {
									var grade = pInt($(".grade", $(this)).val());
									if (grade < 13) {
										var dummy = $("#childDummy");
										var newrow = dummy.clone(true)
												.removeAttr("id").insertBefore(
														dummy);
										$("h4", newrow).text(campername)
												.removeClass("deleteMe");
										$("p", newrow)
												.html(
														"<strong>Automatically enrolled in program-specific activities.</strong>");
										newrow.show();
									} else {
										var dummy = $("#workshopDummy");
										var newrow = dummy.clone(true)
												.removeAttr("id").insertBefore(
														dummy);
										$("h4", newrow).text(campername)
												.removeClass("deleteMe");
										newrow.show();
									}
								}
							} else {
								$(
										"#appWorkshop h4:contains('"
												+ campername + "')")
										.removeClass("deleteMe").hide();
							}

						});*/
	} else if (ui.newPanel.attr("id") == "appPayment") {
		$("#appPayment tr.dummy").remove();
		$("#noattending").hide();
		var dummy = $("#paymentDummy");
		var now = $.datepicker.formatDate('m/dd/yy', new Date());
		var deposit = 0.0;
		var total = 0.0;
		var registered = new Array();
		$("#appPayment tr").filter(
				function() {
					return $("td.chargetype:contains('Registration Fee')",
							$(this)).size() > 0
				}).each(function() {
			registered.push($("td.memo", $(this)).text());
		});
		$("#appCamper tbody.camperBody:not(.hidden)")
				.filter(function() {
					return $(".attending[value!='0']", $(this)).size() > 0
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
							if (grade > 5) {
								deposit += 50.0;
							}
						});
		if (deposit == 0.0) {
			$("#noattending").show();
		} else if ($("#appPayment td.chargetype:contains('Housing Fee')")
				.size() == 0) {
			var housingdepo = $("#appPayment td.chargetype:contains('Housing Deposit')");
			if (housingdepo)
				var newrow = dummy.clone(true).removeAttr("id").addClass(
						"dummy").insertBefore(dummy);
			$(".chargetype", newrow).text("Housing Deposit");
			$(".amount", newrow).text("$" + deposit.toFixed(2));
			$(".date", newrow).text(now);
			newrow.show();
		}
		$("#appPayment td.amount").each(function() {
			total += pFloat($(this).text());
		})
		total += Math.abs(pFloat($("#donation").val()));
		$("#amountNow").text("$" + total.toFixed(2));
	}
}

function donationCalc() {
	var total = 0.0;
	$("#appPayment td.amount:visible").each(function() {
		total += pFloat($(this).text());
	});
	var donation = Math.abs(pFloat($("#donation").val()));
	if (isNaN(donation)) {
		donation = 0.0;
	}
	total += donation;
	$("#donation").val("$" + donation.toFixed(2));
	$("#amountNow").text("$" + total.toFixed(2));
}

function submit() {
	var camperCount = 100;
	$("#appCamper tbody.camperBody:not(.hidden)")
			.each(
					function() {
						var phoneCount = 200;
						$(".phonenbrs:not(.hidden)", $(this))
								.each(
										function() {
											if ($("input[type=text]", $(this))
													.val() != "") {
												$("select,input", $(this))
														.each(
																function() {
																	incName(
																			$(this),
																			phoneCount);
																});
												$(
														"input[name*='phonenumbers-phonenbrid']",
														$(this))
														.val(phoneCount);
												phoneCount++;
											} else {
												$(this).remove();
											}
										});
						if ($(".attending", $(this)).val() > 0) {
							var camperid = $(this).attr("id") != undefined ? $(
									this).attr("id") : camperCount++;
							$("input:not(.hidden),select:not(.hidden)", $(this))
									.each(function() {
										incName($(this), camperid);
									});
							$("input[name*='campers-camperid']", $(this)).val(
									camperid);
							$("input[name*='phonenumbers-camperid']", $(this))
									.val(camperid);
							addHidden("roomtype_preferences-buildingids-"
									+ camperid, $(".roomtype-yes li", $(this)));
							addHidden("roommate_preferences-names-" + camperid,
									$("input.roommates", $(this)));
						}
					});
	$("#appWorkshop div.desired").each(
			function() {
				addHidden("attendees-" + $("h6", $(this)).attr("class"), $(
						".workshop-yes li", $(this)));
			});
	addHidden("volunteers" + $("h6", $(this)).attr("class"),
			$("#appWorkshop .volunteers li"));
	$("#muusaApp").closest("form").submit();
}

function incName(obj, count) {
	if (obj.attr("name") != undefined && !obj.attr("name").match(/-\d+$/)) {
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

function hideThis(obj) {
	obj.remove();
}

function pInt(val) {
	return val != "" ? parseInt(val.replace(/[^0-9\.-]+/g, ""), 10) : 0;
}

function pFloat(val) {
	return val != "" ? parseFloat(val.replace(/[^0-9\.-]+/g, "")) : 0.0;
}
