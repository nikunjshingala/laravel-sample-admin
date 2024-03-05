<?php
  header('Content-type: application/javascript');
/* no store cache in browser */
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
	/* global moment:false, Chart:false, Sparkline:false */

	$(function () {
		'use strict'
		// Make the dashboard widgets sortable Using jquery UI
		$('.connectedSortable .card-header').css('cursor', 'move')

		var barChartCanvas = $('#barChart').get(0).getContext('2d')
		var barChartCanvas2 = $('#donutChart').get(0).getContext('2d')
		var barChartData = $.extend(true, {}, barChartOption)
		var temp0 = barChartOption.datasets[0]
		var temp1 = barChartOption.datasets[1]
		var temp2 = barChartOption.datasets[2]
		barChartData.datasets[0] = temp0
		barChartData.datasets[1] = temp1
		barChartData.datasets[2] = temp2

		var barChartOptions = {
			responsive: true,
			maintainAspectRatio: false,
			datasetFill: false,
			title: {
				display: true,
				text: "<?php echo trans('message.author_data_with_author_type_and_birth_date'); ?>"
			},
			scales: {
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'Count'
					}
				}],
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'Month'
					}
				}]
			}
		}

		new Chart(barChartCanvas, {
			type: 'bar',
			data: barChartData,
			options: barChartOptions,
		})
		var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
		
		var donutOptions = {
			maintainAspectRatio: false,
			responsive: true,
			title: {
				display: true,
				text: "<?php echo trans('message.author_data_with_author_country'); ?>"
			},
		}
		//Create pie or douhnut chart
		// You can switch between pie and douhnut using the method below.
		new Chart(donutChartCanvas, {
			type: 'doughnut',
			data: donutData,
			options: donutOptions,
		})
	});