@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Members Graphs
		<button type="button" class="btn btn-primary btn-sm pull-right">{{ count($members) }} members</button>
	</h3>
	
	<div class="panel panel-default">
		<div id="joinDates" class="graph"></div>
		<div id="memberYears" class="graph"></div>
		<div id="majorsGraph" class="graph"></div>
	</div>
	
</div></div>

@stop

@section("customJS")
<!-- Graphs (AMCharts) -->
<script type="text/javascript" src="//www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="//www.amcharts.com/lib/3/serial.js"></script>
<script type="text/javascript" src="//www.amcharts.com/lib/3/themes/light.js"></script>

<script type="text/javascript">
	var joinDatesData = JSON.parse('{!! json_encode($joinDates); !!}');
	var joinDates = AmCharts.makeChart("joinDates", $.extend( {
			"dataProvider": joinDatesData,
			"titles": [{
				"text": "Join Date",
				"size": 11
			}],
		}, dateChartProperties));
	
	var memberYearsData = JSON.parse('{!! json_encode($memberYears); !!}');
	var memberYears = AmCharts.makeChart("memberYears", $.extend( {
			"dataProvider": memberYearsData,
			"titles": [{
				"text": "Graduation Year",
				"size": 11
			}],
		}, intChartProperties));
	
	var majorsGraphData = JSON.parse('{!! json_encode($majorsData); !!}');
	var majorsGraph = AmCharts.makeChart("majorsGraph", $.extend( {
			"dataProvider": majorsGraphData,
			"titles": [{
				"text": "Major",
				"size": 11
			}],
		}, textChartProperties));
</script>

@stop