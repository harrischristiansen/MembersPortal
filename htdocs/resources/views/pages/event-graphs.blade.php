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
	var firstChartData = [{"date":"2016-09-20","count":1},{"date":"2016-09-21","count":6},{"date":"2016-09-22","count":4}];
	var firstGraph = AmCharts.makeChart("firstGraph", $.extend( {
			"dataProvider": firstChartData,
			"titles": [{
				"text": "First Graph",
				"size": 11
			}],
		}, dateChartProperties));
</script>

@stop