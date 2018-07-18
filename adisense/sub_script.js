function create_highchart(chart_id, ref_f, chart_pga, chart_pgv, chart_title)
{
	var custom_chart = Highcharts.chart(chart_id, {

		chart: {
			type: 'gauge',
			plotBackgroundColor: null,
			plotBackgroundImage: null,
			plotBorderWidth: 0,
			plotShadow: false,
			spacingLeft: 5,
			spacingTop: 5,
			spacingBottom: 5,
			spacingRight: 5,
			backgroundColor: 'rgba(255,255,255,0)'
		},
		
		credits: {
			enabled: false
		},
		
		title: {
			text: chart_title,
			style: {
				color: '#E0E0E3',
				textTransform: 'uppercase',
				fontSize: '20px'
			}
		},

		pane: {
			startAngle: -150,
			endAngle: 150,
			background: [{
				backgroundColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
					stops: [
						[0, '#FFF'],
						[1, '#333']
					]
				},
				borderWidth: 0,
				outerRadius: '109%'
			}, {
				backgroundColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
					stops: [
						[0, '#333'],
						[1, '#FFF']
					]
				},
				borderWidth: 1,
				outerRadius: '107%'
			}, {
				// default background
			}, {
				backgroundColor: '#DDD',
				borderWidth: 0,
				outerRadius: '105%',
				innerRadius: '103%'
			}]
		},

		// the value axis
		yAxis: {
			min: 1,
			max: 10,

			minorTickInterval: 'auto',
			minorTickWidth: 1,
			minorTickLength: 10,
			minorTickPosition: 'inside',
			minorTickColor: '#666',

			tickPixelInterval: 30,
			tickWidth: 2,
			tickPosition: 'inside',
			tickLength: 10,
			tickColor: '#666',
			labels: {
				step: 0.1,
				rotation: 'auto'
			},
			title: {
				text: 'MMI'
			},
			plotBands: [{
				from: 0,
				to: 3,
				color: '#D7E0DF' // gray
			}, {
				from: 3,
				to: 4,
				color: '#42F4EB' // light blue
			}, {
				from: 4,
				to: 5,
				color: '#29D13D' // green
			}, {
				from: 5,
				to: 7,
				color: '#FFFB32' // yellow
			}, {
				from: 7,
				to: 8,
				color: '#FFB014' // orange
			}, {
				from: 8,
				to: 10,
				color: '#DF5353' // red
			}]
		},

		series: [{
			name: 'Intensity',
			data: (function() {
				var data_point = [1];
				var url_string = "getData.php?f=" + ref_f + "&lt=0";
				$.ajax({
					async: false,
					type: "GET",
					url: url_string,
					success: function(data) {
						if (data.numsamples > 0)
						{
							var date_string = (new Date(data.xdata)).getTime();
							data_point = [1];
							pga_string = "Magnitude Acceleration (PGA: 0 %g)";
							pgv_string = "Magnitude Velocity (PGV: 0 cm/s)";
							document.getElementById(chart_pga).innerHTML = pga_string;
							document.getElementById(chart_pgv).innerHTML = pgv_string;
						}
					},
					error: function(xhr) {
					}
				});
				return data_point;
			}()),
			tooltip: { 
				valueSuffix: ''
			}
		}]

	},
	function (chart) {
		if (!chart.renderer.forExport) {
			setInterval(function () {
				var point = chart.series[0].points[0];
				var url_string = "getData.php?f=" + ref_f + "&lt=0";
				$.ajax({
					async: true,
					type: "GET",
					url: url_string,
					success: function(data) {
						if (data.numsamples > 0)
						{
							point.update(data.intensity);
							document.getElementById(chart_pga).innerHTML = "Magnitude Acceleration (PGA: " + data.pga + " %g)";
							document.getElementById(chart_pgv).innerHTML = "Magnitude Velocity (PGV: " + data.pgv + " cm/s)";
						}
						else
						{
							point.update([1]);
							document.getElementById(chart_pga).innerHTML = "Magnitude Acceleration (PGA: 0 %g)";
							document.getElementById(chart_pgv).innerHTML = "Magnitude Velocity (PGV: 0 cm/s)";
						}
					},
					error: function(xhr) {
					}
				});			
			}, 3000);
		}
	});
	return custom_chart;

}
