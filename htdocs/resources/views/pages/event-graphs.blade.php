@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>{{ $event->name }} Graphs
		<a href="/event/{{ $event->id }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Event</button></a>
	</h3>
	
	<div class="panel panel-default">
		<div id="firstGraph" class="graph"></div>
	</div>
	
</div></div>

@stop

@section("customJS")
<!-- Graphs (AMCharts) -->
<script type="text/javascript" src="//www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="//www.amcharts.com/lib/3/serial.js"></script>
<script type="text/javascript" src="//www.amcharts.com/lib/3/themes/light.js"></script>

<script type="text/javascript">
	var firstChartData = [{"date":"2016-09-20","items":1},{"date":"2016-09-21","items":6},{"date":"2016-09-22","items":4}];
	var firstChart = AmCharts.makeChart("firstGraph", {
	    "type": "serial",
	    "dataDateFormat": "YYYY-MM-DD",
	    "legend": {
	        "useGraphSettings": true
	    },
	    "dataProvider": firstChartData,
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
	        "valueField": "items",
	        "type": "smoothedLine",
	    }],
	    "chartScrollbar": {
			"scrollbarHeight": 15
		},
	    "chartCursor": {
	        "cursorPosition": "mouse"
	    },
	    "categoryField": "date",
	    "categoryAxis": {
	        "parseDates": true,
	        "axisColor": "#DADADA",
	        "minorGridEnabled": true
	    }
	});
</script>

@stop