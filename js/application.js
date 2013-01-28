$(window).load(function() {
   $("#muusaApp").tabs();
   $(".info").button( { icons: { primary: "ui-icon-info"}, text: false } ).click(function() { switchNextRow($(this)); return false; } );
   $(".link").button( { icons: { primary: "ui-icon-link"}, text: false } ).click(function() { openLink($(this)); return false; } );
   $(".radios").buttonset();
   $(".add").button( { icons: { primary: "ui-icon-plus"}, text: false } ).click(function() {  addRow($(this)); return false; } );
   $(".birthday").datepicker({ yearRange: "c-100:c+0", changeMonth: true, changeYear: true });
   $(".roomtypes").accordion( { collapsible: true, heightStyle: "content", header: "h4", active: false } );
   $(".roomtypeSave").button().click(function() { $(this).closest("div.roomtypes").accordion({active: false});  return false;} );
   $(".roomtype-yes, .roomtype-no" ).sortable({ placeholder: "ui-state-highlight", connectWith: ".connectedRoomtype"}).disableSelection();
   $(".dialog-message").dialog( { modal: true, autoOpen: false, minWidth: 800, buttons: { Ok: function() { $( this ).dialog( "close" ); } } });
   $(".delete").button( { icons: { primary: "ui-icon-minus"}, text: false } ).click(function() {  hideThisRow($(this)); return false; } );
   $("#nextCamper").button().click(function() { $("#muusaApp").tabs({active: 1}); return false; });
   $("#addCamper").button().click(function() { addCamper(); return false; });
   $("#removeCamper").button().click(function() { removeCamper($(this)); return false; });
   $("#nextWorkshop").button().click(function() { $("#muusaApp").tabs({active: 2}); return false; });
   $(".workshopSelection").accordion( { heightStyle: "content", header: "h4" } );
   $(".workshopTimes").accordion( { heightStyle: "content", header: "h5" } );
   $(".workshop-yes, .workshop-no" ).sortable({ placeholder: "ui-state-highlight", connectWith: ".connectedWorkshop"}).disableSelection();
   $("#nextPayment").button().click(function() { $("#muusaApp").tabs({active: 3}); return false; });
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
	$("#camperBody").clone(true).removeAttr("id").insertBefore("#lastrow");
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

function listbox_up(id) {
	listbox_move("selected-" + id + "[]", "up");
}

function listbox_down(id) {
	listbox_move("selected-" + id + "[]", "down");
}

function listbox_move(listID, direction) {
	var listbox = document.getElementsByName(listID)[0];
	var selIndex = listbox.selectedIndex;
	var increment = direction == 'up' ? -1 : 1;
	if (-1 == selIndex
			|| (selIndex + increment) < 0
			|| (selIndex + increment) > (listbox.options.length - 1)
			|| listbox.options[selIndex].text.indexOf("(Leader)") != -1
			|| listbox.options[selIndex + increment].text.indexOf("(Leader)") != -1) {
		return;
	}

	var selValue = listbox.options[selIndex].value;
	var selText = listbox.options[selIndex].text;
	listbox.options[selIndex].value = listbox.options[selIndex + increment].value
	listbox.options[selIndex].text = listbox.options[selIndex + increment].text
	listbox.options[selIndex + increment].value = selValue;
	listbox.options[selIndex + increment].text = selText;
	listbox.selectedIndex = selIndex + increment;
}

function listbox_movefrom(id) {
	listbox_moveacross("selected-" + id + "[]", "available-" + id + "[]");
}

function listbox_moveto(id) {
	listbox_moveacross("available-" + id + "[]", "selected-" + id + "[]");
}

function listbox_moveacross(sourceID, destID) {
	var src = document.getElementsByName(sourceID)[0];
	var dest = document.getElementsByName(destID)[0];
	for ( var count = 0; count < src.options.length; count++) {
		if (src.options[count].selected == true
				&& src.options[count].text.indexOf("(Leader)") == -1) {
			var option = src.options[count];
			var newOption = document.createElement("option");
			newOption.value = option.value;
			newOption.text = option.text;
			newOption.selected = true;
			try {
				dest.add(newOption, null); // Standard
				src.remove(count, null);
			} catch (error) {
				dest.add(newOption); // IE only
				src.remove(count);
			}
			count--;
		}
	}
}

function selectSubmit() {
	var selects = document.application.getElementsByTagName("select");
	for ( var i = 0; i < selects.length; i++) {
		if (selects[i].name.match(/^available-/)) {
			for ( var j = 0; j < selects[i].options.length; j++) {
				selects[i].options[j].selected = false;
			}
		}
		if (selects[i].name.match(/^selected-/)) {
			for ( var j = 0; j < selects[i].options.length; j++) {
				selects[i].options[j].selected = true;
			}
		}
	}
	document.getElementsByName('application')[0].submit();
}