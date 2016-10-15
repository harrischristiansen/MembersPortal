/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-27
	Project: Members Tracking Portal
*/


$('.validate').bValidator();

$(".datepicker").datepicker({
	dateFormat: 'yy-mm-dd',
});

$(document).ready(function() { 
	$(".sortableTable").tablesorter(); 
});

var selectedID = -1;

$(".membersautocomplete").autocomplete({
	source: "/members-autocomplete/"+eventID,
	minLength: 3,
	autoFocus: true,
	select: function(event, ui) {
		if(ui.item) {
			event.preventDefault();
			selectedID = ui.item.id;
			$("#memberName").val(ui.item.name);
			$("#memberEmail").val(ui.item.email);
			$("#memberPhone").val(ui.item.phone);
			$("#memberAttended").val(ui.item.attended);
			$("#graduationYear").val(ui.item.graduation_year);
			console.log(ui.item);
			if (ui.item.registered == 0) {
				$("#hasRegistered").html('<button class="btn btn-danger" type="button">Not Registered</button>');
			} else if (ui.item.registered > 0) {
				$("#hasRegistered").html('<button class="btn btn-success" type="button">Registered</button>');
			}
		}
	},
	focus: function(event, ui) {
		event.preventDefault();
	}
});

$("#memberName").change(function() {
	selectedID = -1;
	$("#memberAttended").val('N/A');
	$("#hasRegistered").html('');
	$("#graduationYear").val('Unknown');
});

$("#memberEmail").change(function() {
	selectedID = -1;
	$("#membersAttended").val('N/A');
	$("#hasRegistered").html('');
	$("#graduationYear").val('Unknown');
});

$("#memberPhone").change(function() {
	selectedID = -1;
	$("#memberAttended").val('N/A');
	$("#hasRegistered").html('');
	$("#graduationYear").val('Unknown');
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
	if(!$('#checkinForm').data('bValidator').validate()) {
		return;
	}
	
	selectedName = $("#memberName").val();
	$.ajax({
		url: '/checkin-member',
		type: 'post',
		data: {
			eventID:eventID,
			memberID:selectedID,
			memberName:$("#memberName").val(),
			memberEmail:$("#memberEmail").val(),
			memberPhone:$("#memberPhone").val(),
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
		} else if(data=="phone") {
			alertMsg = '<div class="alert alert-warning" role="alert">Checked In: '+selectedName+', but an invalid phone # was provided.</div>';
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
	$("#memberPhone").val("");
	$("#memberAttended").val("");
}

////////////////////////////////// Chart Options //////////////////////////////////

var dateChartProperties = {
    "type": "serial",
    "dataDateFormat": "YYYY-MM-DD",
    "legend": {
        "useGraphSettings": true
    },
    "valueAxes": [{
        "id":"v1",
        "axisColor": "#FF6600",
        "position": "left"
    }],
    "graphs": [{
        "valueAxis": "v1",
        "lineColor": "#FF6600",
        "bullet": "round",
        "hideBulletsCount": 30,
        "title": "# Members",
        "valueField": 'count',
        "type": "smoothedLine",
    }],
    "chartScrollbar": {
		"scrollbarHeight": 15
	},
    "chartCursor": {
        "cursorPosition": "mouse"
    },
    "categoryField": 'date',
    "categoryAxis": {
        "parseDates": true,
        "axisColor": "#DADADA",
        "minorGridEnabled": true
	}
};

var intChartProperties = {
    "type": "serial",
    "legend": {
        "useGraphSettings": true
    },
    "valueAxes": [{
        "id":"v1",
        "axisColor": "#FF6600",
        "position": "left"
    }],
    "graphs": [{
        "valueAxis": "v1",
        "lineColor": "#FF6600",
        "bullet": "round",
        "hideBulletsCount": 30,
        "title": "# Members",
        "valueField": 'count',
        "type": "smoothedLine",
    }],
    "chartScrollbar": {
		"scrollbarHeight": 15
	},
    "chartCursor": {
        "cursorPosition": "mouse"
    },
    "categoryField": 'key',
    "categoryAxis": {
        "axisColor": "#DADADA",
        "minorGridEnabled": true
	}
};

var textChartProperties = {
    "type": "serial",
    "legend": {
        "useGraphSettings": true
    },
    "valueAxes": [{
        "id":"v1",
        "axisColor": "#FF6600",
        "position": "left"
    }],
    "graphs": [{
        "valueAxis": "v1",
        "lineColor": "#FF6600",
        "bullet": "round",
        "hideBulletsCount": 30,
        "title": "# Members",
        "valueField": 'count',
        "type": "smoothedLine",
    }],
    "chartScrollbar": {
		"scrollbarHeight": 15
	},
    "chartCursor": {
        "cursorPosition": "mouse"
    },
    "categoryField": 'key',
    "categoryAxis": {
        "axisColor": "#DADADA",
        "minorGridEnabled": true,
	}
};