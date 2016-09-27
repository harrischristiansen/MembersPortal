@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>{{ env('ORG_NAME') }} Members
	</h3>
	
	<div class="panel panel-default">
		<div id="joinDates" class="graph"></div>
	</div>
	
</div></div>

@stop

@section("customJS")
<!-- Graphs (AMCharts) -->
<script type="text/javascript" src="//www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="//www.amcharts.com/lib/3/serial.js"></script>
<script type="text/javascript" src="//www.amcharts.com/lib/3/themes/light.js"></script>

<script type="text/javascript">
	
	var commonChartProperties = {
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
	        "title": "# Items",
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
		
	var joinDatesData = JSON.parse('{!! json_encode($joinDates); !!}');
	var joinDates = AmCharts.makeChart("joinDates", $.extend( {
			"dataProvider": joinDatesData,
			"titles": [{
				"text": "Joined Dates",
				"size": 11
			}],
		}, commonChartProperties));
</script>

@stop