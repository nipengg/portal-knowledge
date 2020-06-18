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
    <h2>Chart</h2>
    <hr/>
    <br>
    <div class="row">
     <div class="col-md-10 col-md-offset-1">
         <div class="panel panel-default">
             <div class="panel-heading">
            <b>Project Chart</b>
            {{-- <button class="btn btn-sm" onclick="location.href='/report/chart/1'">Q1</button>
            <button class="btn btn-sm" onclick="location.href='/report/chart/2'">Q2</button>
            <button class="btn btn-sm" onclick="location.href='/report/chart/3'">Q3</button>
            <button class="btn btn-sm" onclick="location.href='/report/chart/4'">Q4</button>
            <button class="btn btn-sm" onclick="location.href='/report/chart'" disabled>All</button> --}}
            </div>
             <div class="panel-body">
                 <canvas id="canvas" height="280" width="600"></canvas>
             </div>
             <div class="panel-body">
                <canvas id="canvas2" height="280" width="600"></canvas>
            </div>
         </div>
     </div>
   </div>
      <script>
      var url = "{{url('/chart')}}";
      var name = new Array();
      var proces = new Array();

      $(document).ready(function(){
        $.get(url, function(response){
          response.forEach(function(data){
            proces.push(data.total);
          });
          var ctx = document.getElementById("canvas").getContext('2d');
              var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Total project', 'Done On Time', 'Done Over Time', 'Progress', 'Cancel'],
                    datasets: [{
                        label: 'All',
                        data: proces,
                        borderWidth: 1,
                        backgroundColor: [
                        'rgba(54, 162, 235)',
                        'rgba(50,205,50)',
                        'rgba(255,0,0)',
                        'rgba(255,140,0)',
                        'rgba(148,0,211)',
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
      });
      </script>

<script>
    var url = "{{url('/chart')}}";
    var name = new Array();
    var total = new Array();

    $(document).ready(function(){
      $.get(url, function(response){
        response.forEach(function(data){
          total.push(data.total);
        });
        var ctx = document.getElementById("canvas2").getContext('2d');
            var myChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: ['Total project', 'Done On Time', 'Done Over Time', 'Progress', 'Cancel'],
                  datasets: [{
                      label: 'All',
                      data: total,
                      borderWidth: 1,
                      backgroundColor: [
                      'rgba(54, 162, 235)',
                      'rgba(50,205,50)',
                      'rgba(255,0,0)',
                      'rgba(255,140,0)',
                      'rgba(148,0,211)',
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
    });
    </script>
<hr/>
  </body>
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@stop
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js" charset="utf-8"></script>