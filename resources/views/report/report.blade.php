@extends('layouts.masterr')

@section('title')
    Chart
@stop

@section('content')
<head>
    <style>

    </style>
</head>
<body>
    <script>
        $(document).ready(function () {
            $("#filter-picker").on('change', function(){
                window.location.href = "{{url('/report/chart/?year=')}}" + $("#filter-picker").val();
            });
        });
    </script>
    <h2>Chart</h2>
    <hr/>
    <div>
        <select class="selectpicker" id="filter-picker" style="background: white !important;">
            <option value="2020" {{$year === '2020' ? 'selected' : ''}} >2020</option>
            <option value="2019"  {{$year === '2019' ? 'selected' : ''}} >2019</option>
            <option value="2018"  {{$year === '2018' ? 'selected' : ''}} >2018</option>
        </select>
    </div>
    <br>
    
    <ul class="nav nav-tabs col-md-10 col-md-offset-1" id="myTab" role="tablist">
        <li class="nav-item active">
          <a class="nav-link chart-link active" id="home-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all"
            aria-selected="true" data-quarter="0">All</a>
        </li>
        @for ($i = 1; $i <= 4; $i++)
        <li class="nav-item">
            <a class="nav-link chart-link" id="profile-tab" data-toggle="tab" href="#q{{$i}}" role="tab" aria-controls="q1"
              aria-selected="false" data-quarter={{$i}} >Q{{$i}}</a>
        </li>
        @endfor
        
    </ul>
      
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade active in" id="all" aria-labelledby="all-tab" role="tabpanel">
        <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>Project Chart</b>
                </div>
                <div class="panel-body">
                    <canvas id="canvas-bar-0" height="280" width="600"></canvas>
                </div>
            
                <div class="panel-body">
                    <canvas id="canvas-pie-0" height="280" width="600"></canvas>
                </div>
        
            </div>
        </div>
    </div>
    </div>
    @for ($i = 0; $i <= 4; $i++)
        <div id="q{{$i}}" class="tab-pane fade" role="tabpanel" aria-labelledby="q{{$i}}-tab">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <b>Project Chart</b>
                    </div>
                    <div class="panel-body">
                        <canvas id="canvas-bar-{{$i}}" height="280" width="600"></canvas>
                    </div>
                
                    <div class="panel-body">
                        <canvas id="canvas-pie-{{$i}}" height="280" width="600"></canvas>
                    </div>
                    </div>
                </div>
            </div>
        </div> 
    @endfor
</div>
<hr/>
<script>
    $(document).ready(function(){
        $('.chart-link').each(function(){
            const quarter = $(this).data('quarter');
            renderChart('bar', quarter);
            renderChart('pie', quarter);
        });
    });
    const renderChart = (chartType, quarter) => {
      const year = {!! json_encode($year) !!};
      $.get("{{url('/chart')}}", {year, quarter, chartType}, function(response){
      const labels = response.map(data => data.status);
      const total = response.map(data => data.total);
      const status = response.map(data => data.status);

      var sum = total.reduce(function(a, b){
        return a + b;
      }, );
      
        const ctx = document.getElementById(`canvas-${chartType}-${quarter}`).getContext('2d');
            const myChart = new Chart(ctx, {
              type: chartType,
              data: {
                  labels: status,
                  datasets: [{
                      label: `All Status : ${sum/2}`,
                      data: total,
                      borderWidth: 1,
                      backgroundColor: [
                      'rgba(54, 162, 235)',
                      'rgba(50,205,50)',
                      'rgba(255,0,0)',
                      'rgba(255,140,0)',
                      'rgba(148,0,211)',
                      'rgba(255,192,203)',
                      ],
                  }]
              },
              options: {
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero:true
                          }
                      }]
                  }
              }
          });
        });
    }
    </script>
  </body>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@stop
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js" charset="utf-8"></script>