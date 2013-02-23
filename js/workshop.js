$(window).load(function() {
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
	$("#submitWorkshops").button().click(function() {
		submit();
		return false;
	});
});

function submit() {
	var attendeeCount = 0;
	$("#muusaApp span.camper").each(
			function() {
				var fid = $(this).attr("id");
				$("div.desired", $(this)).each(
						function() {
							var prefs = $(".workshop-yes li", $(this));
							var tid = $("h6", $(this)).attr("class");
							if (prefs.size() > 0) {
								prefs.each(function(index, val) {
									addHidden("attendees-eventid-"
											+ attendeeCount, $(this).val());
									addHidden("attendees-fiscalyearid-"
											+ attendeeCount, fid);
									addHidden("attendees-timeid-"
											+ attendeeCount, tid);
									addHidden("attendees-choicenbr-"
											+ attendeeCount++, index + 1);
								});

							}
						});
				var prefs = $(".volunteers li", $(this));
				if (prefs.size() > 0) {
					prefs.each(function() {
						addHidden("volunteers-positionid-" + attendeeCount, $(
								this).val());
						addHidden("volunteers-fiscalyearid-" + attendeeCount++,
								fid);
					});
				}
			});
	$("#muusaApp").closest("form").submit();
}

function addHidden(fieldname, fieldvalue) {
	$("<input>").attr({
		type : "hidden",
		name : fieldname,
		value : fieldvalue
	}).appendTo("#muusaApp");
}