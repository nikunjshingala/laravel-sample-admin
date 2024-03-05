@extends('app')

@section('pageTitle', trans('message.dashboard'))
@section('contentTitle', trans('message.dashboard'))

@section('content')
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-info">
					<div class="inner">
						<h3>{{$data['authorCount'] ?? '0'}}</h3>
						<p>{{trans('message.authors')}}</p>
					</div>
					<div class="icon">
						<i class="nav-icon fas fa-at"></i>
					</div>
					<a href="{{route('authorIndex')}}" class="small-box-footer">{{trans('message.more_info')}} <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-success">
					<div class="inner">
						<h3>{{$data['userCount'] ?? '0'}}</h3>
						<p>{{trans('message.users')}}</p>
					</div>
					<div class="icon">
						<i class="nav-icon fas fa-user"></i>
					</div>
					<a href="{{route('userIndex')}}" class="small-box-footer">{{trans('message.more_info')}} <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
		</div>
		<div class="chart mt-10">
			<canvas id="barChart" style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
		</div>

		<div class="card-body">
			<canvas id="donutChart" style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
		</div>
		<!-- /.row -->
		
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@stop

@section('javascript')
<script src="{{asset('global_assets/plugins/chart.js/Chart.min.js')}}"></script>
<script>
	//Bar Chart data json

	var barChartOption = {
		labels: ["<?php echo $monthList; ?>"],
		datasets: [{
				label: "<?php echo $mainChartArray[0]['label']; ?>",
				backgroundColor: 'rgba(60,141,188,0.9)',
				borderColor: 'rgba(60,141,188,0.8)',
				pointRadius: false,
				pointColor: '#3b8bba',
				pointStrokeColor: 'rgba(60,141,188,1)',
				pointHighlightFill: '#fff',
				pointHighlightStroke: 'rgba(60,141,188,1)',
				data: [<?php echo $mainChartArray[0]['data']; ?>]
			},
			{
				label: "<?php echo $mainChartArray[1]['label']; ?>",
				backgroundColor: 'rgba(210, 214, 222, 1)',
				borderColor: 'rgba(210, 214, 222, 1)',
				pointRadius: false,
				pointColor: 'rgba(210, 214, 222, 1)',
				pointStrokeColor: '#c1c7d1',
				pointHighlightFill: '#fff',
				pointHighlightStroke: 'rgba(220,220,220,1)',
				data: [<?php echo $mainChartArray[1]['data']; ?>]
			},
			{
				label: "<?php echo $mainChartArray[2]['label']; ?>",
				backgroundColor: 'rgba(50, 200, 100, 1)',
				borderColor: 'rgba(50, 200, 100, 1)',
				pointRadius: false,
				pointColor: 'rgba(50, 200, 100, 1)',
				pointStrokeColor: '#000',
				pointHighlightFill: '#000',
				pointHighlightStroke: 'rgba(50, 200, 100,1)',
				data: [<?php echo $mainChartArray[2]['data']; ?>]
			},
		]
	}

	//Donut data json
	var donutData = {
		labels: [
			"<?php echo $donutChartData['country']; ?>"
		],
		datasets: [
			{
				data: [<?php echo $donutChartData['data']; ?>],
				backgroundColor: ["<?php echo $donutChartData['color']; ?>"],
			}
		]
	}
</script>
<script type="text/javascript" src="{!! url('resource', ['js','dashboard']); !!}"></script>
@endsection