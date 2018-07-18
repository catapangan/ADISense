function create_highstock(chart_id, ref_q, ref_f)
{
	var custom_chart = Highcharts.stockChart(chart_id, {
		chart: {
			events: {
				load: function () {
					alert('Nikko');
					var series = this.series[0];
					setInterval(function () {
						var url_string = "getSamples.php?q=" + ref_q + "&f=" + ref_f + "&lt=" + series.points[series.data.length-1].x;
						$.ajax({
							async: true,
							type: "GET",
							url: url_string,
							success: function(data) {
								var i = 0, date_tmp;
								for (i = 0; i < data.numsamples; i++)
								{
									date_tmp = (new Date(data.xdata[i])).getTime();
									series.addPoint([date_tmp, data.ydata[i]], true, true);
								}
								
							},
							error: function(xhr) {
							}
						});
					}, 10000);	
				}
			},
			spacingLeft: 5,
			spacingTop: 5,
			spacingBottom: 5,
			spacingRight: 5,
			marginTop: 0,
			backgroundColor: {
			linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
			stops: [
				[0, '#9a9a9b'],
				[1, '#CeCeC0']
			]
		  },
		  style: {
			 fontFamily: '\'Unica One\', sans-serif'
		  },
		  plotBorderColor: '#606063'
		},

		rangeSelector: {
			buttons: [{
				count: 1,
				type: 'minute',
				text: '1M'
			}, {
				count: 5,
				type: 'minute',
				text: '5M'
			}, {
				type: 'all',
				text: 'All'
			}],
			inputEnabled: false,
			selected: 0,
			buttonTheme: {
				fill: '#505053',
				stroke: '#000000',
				style: {
					color: '#CCC'
				},
				states: {
					hover: {
						fill: '#707073',
						stroke: '#000000',
						style: {
							color: 'white'
						}
					},
					select: {
						fill: '#000003',
						stroke: '#000000',
						style: {
						color: 'white'
						}
					}
				}
			},
			inputBoxBorderColor: '#505053',
			inputStyle: {
				backgroundColor: '#333',
				color: 'silver'
			},
			labelStyle: {
				color: 'silver'
			}
		},
		
		navigation: {
			buttonOptions: {
				symbolStroke: '#DDDDDD',
				theme: {
					fill: '#505053'
				}
			}
		},

		title: {
			text: '',
			style: {
				color: '#E0E0E3',
				textTransform: 'uppercase',
				fontSize: '20px'
			}
		},
		
		subtitle: {
			style: {
				color: '#E0E0E3',
				textTransform: 'uppercase'
			}
		},
		
		xAxis: {
			gridLineColor: '#707073',
			labels: {
				style: {
					color: '#000000'
				}
			},
			lineColor: '#000000',
			minorGridLineColor: '#505053',
			tickColor: '#707073',
			title: {
				style: {
					color: '#A0A0A3'
				}
			}
		},
		yAxis: {
			gridLineColor: '#707073',
			labels: {
				style: {
					color: '#000000'
				}
			},
			lineColor: '#000000',
			minorGridLineColor: '#505053',
			tickColor: '#707073',
			tickWidth: 1,
			title: {
				style: {
					color: '#A0A0A3'
				}
			}
		},
		tooltip: {
			backgroundColor: 'rgba(0, 0, 0, 0.85)',
			style: {
				color: '#F0F0F0'
			}
		},
		plotOptions: {
			series: {
				color: 'rgba(0,0,0,1)',
				dataLabels: {
					color: '#B0B0B3'
				},
				marker: {
					lineColor: '#000000'
				}
			},
			boxplot: {
				fillColor: '#000000'
			},
			candlestick: {
				lineColor: 'white'
			},
			errorbar: {
				color: 'white'
			}
		},
		legend: {
			itemStyle: {
				color: '#E0E0E3'
			},
			itemHoverStyle: {
				color: '#FFF'
			},
			itemHiddenStyle: {
				color: '#606063'
			}
		},
		credits: {
			enabled: false,
			style: {
				color: '#666'
			}
		},
		labels: {
			style: {
				color: '#707073'
			}
		},

		drilldown: {
			activeAxisLabelStyle: {
				color: '#F0F0F3'
			},
			activeDataLabelStyle: {
				color: '#F0F0F3'
			}
		},
		
		navigator: {
			enabled: false,
			handles: {
				backgroundColor: '#666',
				borderColor: '#AAA'
			},
			outlineColor: '#CCC',
			maskFill: 'rgba(255,255,255,0.1)',
			series: {
				color: '#7798BF',
				lineColor: '#A6C7ED'
			},
			xAxis: {
				gridLineColor: '#505053'
			}
		},

		scrollbar: {
			barBackgroundColor: '#808083',
			barBorderColor: '#808083',
			buttonArrowColor: '#CCC',
			buttonBackgroundColor: '#606063',
			buttonBorderColor: '#606063',
			rifleColor: '#FFF',
			trackBackgroundColor: '#404043',
			trackBorderColor: '#404043'
		},

		legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
		background2: '#505053',
		dataLabelsColor: '#B0B0B3',
		textColor: '#C0C0C0',
		contrastTextColor: '#F0F0F3',
		maskColor: 'rgba(255,255,255,0.3)',

		exporting: {
			enabled: false
		},

		series: [{
			name: 'Magnitude',
			data: (function () {
				var data_point = [];
				
				$.ajax({
					async: false,
					type: "GET",
					url: "getNow.php",
					success: function(data) {
						var date_str = (new Date(data.now)).getTime(), i = 0;
						for(i = -999; i < 0; i++)
						{
							data_point.push([date_str + (i * 1000), 0]);
						}
					},
					error: function(xhr) {
					}
				});
				
				return data_point;					
			}())
		}]
	});
	
	return custom_chart;
}
