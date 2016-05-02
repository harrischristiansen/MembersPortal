/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-27
	Project: Members Tracking Portal
*/


$('.validate').bValidator();

$(".datepicker").datepicker({
	dateFormat: 'yy-mm-dd',
});

var selectedName = "";
var selectedMember = -1;

$(".membersautocomplete").autocomplete({
	source: "/members-autocomplete",
	minLength: 2,
	autoFocus: true,
	select: function( event, ui ) {
		if(ui.item) {
			selectedName = ui.item.value;
			selectedMember = ui.item.id;
			$("#selectedMember").val(ui.item.value);
			$("#selectedEmail").val(ui.item.email);
			$("#selectedNumber").val(ui.item.attended);
		}
	},
	close:  function( event, ui ) {
		$(".membersautocomplete").val("");
	}
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
	if(selectedMember > 0) {
		$.get(
			"/checkin-member/"+eventID+"/"+selectedMember,
			function(data) {
				if(data=="true") {
					alertMsg = '<div class="alert alert-success" role="alert">Checked In: '+selectedName+'</div>';
				} else if(data=="repeat") {
					alertMsg = '<div class="alert alert-warning" role="alert">Already Checked In: '+selectedName+'</div>';
				} else {
					alertMsg = '<div class="alert alert-danger" role="alert">Failed to check in '+selectedName+'.</div>';
				}
				$(alertMsg).hide().appendTo("#checkinAlerts").slideDown(600).delay(5000).slideUp(600);
			}
		);
	}
}