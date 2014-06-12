jQuery(document)
		.ready(
				function($) {
					$(".workshopSelection").accordion({
						heightStyle : "content",
						header : "h4"
					});
					$(".workshopTimes").accordion({
						heightStyle : "content",
						header : "h5"
					});
					$(".link").button({
						icons : {
							primary : "ui-icon-image"
						},
						text : false
					}).click(function() {
						openLink($, $(this));
						return false;
					});
					$(".workshop-yes, .workshop-no").sortable({
						placeholder : "ui-state-highlight",
						connectWith : ".connectedWorkshop"
					}).disableSelection();
					$("#submitWorkshops").button().click(function() {
						submit($);
						return false;
					});
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
				});

function openLink($, obj) {
	eval("$(\"#room-" + obj.parents("li").val() + "\").dialog(\"open\");");
}

function submit($) {
	var attendeeCount = 0;
	$("#muusaApp span.camper").each(
			function() {
				var fid = $(this).attr("id");
				$("div.desired", $(this)).each(
						function() {
							var tid = $("h6", $(this)).attr("class");
							$(".workshop-yes li", $(this)).each(
									function(index, val) {
										addHidden($,
												"yearattending__workshop-workshopid-"
														+ attendeeCount,
												$(this).val());
										addHidden($,
												"yearattending__workshop-yearattendingid-"
														+ attendeeCount, fid);
										addHidden($,
												"yearattending__workshop-timeid-"
														+ attendeeCount, tid);
										addHidden($,
												"yearattending__workshop-choicenbr-"
														+ attendeeCount++,
												index + 1);
									});
						});
				$(".volunteers li", $(this)).each(
						function() {
							addHidden($,
									"yearattending__volunteer-volunteerpositionid-"
											+ attendeeCount, $(this).val());
							addHidden($,
									"yearattending__volunteer-yearattendingid-"
											+ attendeeCount++, fid);
						});
			});
	$("#muusaApp").closest("form").submit();
}

function addHidden($, fieldname, fieldvalue) {
	$("<input>").attr({
		type : "hidden",
		name : fieldname,
		value : fieldvalue
	}).appendTo("#muusaApp");
}