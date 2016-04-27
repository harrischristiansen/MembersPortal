/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-27
	For: Purdue Hackers - Membership Portal
*/


$('.validate').bValidator();

$(".datepicker").datepicker();

var selectedMember = -1;

$(".membersautocomplete").autocomplete({
	source: "/members-autocomplete",
	minLength: 2,
	autoFocus: true,
	select: function( event, ui ) {
		if(ui.item) {
			selectedMember = ui.item.id;
			$("#selectedMember").val(ui.item.value);
			$("#selectedEmail").val(ui.item.email);
			$("#selectedNumber").val("-");
		}
	}
});

function checkinMember() {
	if(selectedMember > 0) {
		$.get(
			"/checkin-member/"+eventID+"/"+selectedMember,
			function(data) {
			   console.log('Checked In: ' + data);
			}
		);
	}
}