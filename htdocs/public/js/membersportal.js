/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-27
	Project: Members Tracking Portal
*/


$('.validate').bValidator();

$(".datepicker").datepicker({
	dateFormat: 'yy-mm-dd',
});

var selectedID = -1;

$(".membersautocomplete").autocomplete({
	source: "/members-autocomplete",
	minLength: 2,
	autoFocus: true,
	select: function( event, ui ) {
		if(ui.item) {
			selectedID = ui.item.id;
			$("#memberName").val(ui.item.value);
			$("#memberEmail").val(ui.item.email);
			$("#memberAttended").val(ui.item.attended);
		}
	}
});

$("#memberName").change(function() {
	selectedID = -1;
});

$("#memberEmail").change(function() {
	selectedID = -1;
});

$(".locationsautocomplete").autocomplete({
	source: "/locations-autocomplete",
	minLength: 2,
	select: function( event, ui ) {
		if(ui.item) {
			$("#city").val(ui.item.city);
		}
	}
});

$(".citiesautocomplete").autocomplete({
	source: "/cities-autocomplete",
	minLength: 2
});

function checkinMember() {
	selectedName = $("#memberName").val();
	$.ajax({
		url: '/checkin-member',
		type: 'post',
		data: {
			eventID:eventID,
			memberID:selectedID,
			memberName:$("#memberName").val(),
			memberEmail:$("#memberEmail").val()
		},
		headers: {
			"X-XSRF-TOKEN":$.cookie("XSRF-TOKEN")
		}
	}).done(function (data) {
		if(data=="true") {
			alertMsg = '<div class="alert alert-success" role="alert">Checked In: '+selectedName+'</div>';
			clearCheckinFields();
		} else if(data=="new") {
			alertMsg = '<div class="alert alert-success" role="alert">Checked In: '+selectedName+' and created their account!</div>';
			clearCheckinFields();
		} else if(data=="repeat") {
			alertMsg = '<div class="alert alert-warning" role="alert">Already Checked In: '+selectedName+'</div>';
			clearCheckinFields();
		} else if(data=="invalid") {
			alertMsg = '<div class="alert alert-danger" role="alert">Error: Please enter valid information for all fields!</div>';
		} else if(data=="exists") {
			alertMsg = '<div class="alert alert-danger" role="alert">Error: An account with that email address already exists, please select it from the dropdown!</div>';
		} else {
			alertMsg = '<div class="alert alert-danger" role="alert">Failed to check in '+selectedName+'.</div>';
		}
		$(alertMsg).hide().appendTo("#checkinAlerts").slideDown(600).delay(6000).slideUp(600);
	});
}

function clearCheckinFields() {
	$("#memberName").val("");
	$("#memberEmail").val("");
	$("#memberAttended").val("");
}