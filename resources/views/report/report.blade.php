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
                window.location.href = "{{url('/report/chart/?filter=')}}" + $("#filter-picker").val();
            });
        });
    </script>
    <h2>Chart</h2>
    <hr/>
    <div>
        <select class="selectpicker" id="filter-picker" style="background: white !important;">
            <option value="2020" {{$filter === '2020' ? 'selected' : ''}} >2020</option>
            <option value="2019"  {{$filter === '2019' ? 'selected' : ''}} >2019</option>
        </select>
    </div>
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
      var filter = {!! json_encode($filter) !!};    
      var url = "{{url('/chart/:filter')}}";
      url = url.replace(':filter', filter);

      $(document).ready(function(){
        $.get(url, function(response){
        //   response.forEach(function(data){
        //     proces.push(data.total);
        //   });
        const labels = response.map(data => data.status);
        const total = response.map(data => data.total);
        const status = response.map(data => data.status);

          const ctx = document.getElementById("canvas").getContext('2d');
              const myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: status,
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
      });
      </script>

<script>
    // var url = "{{url('/chart')}}";
    // var name = new Array();
    // var total = new Array();

    $(document).ready(function(){
      $.get(url, function(response){
        // response.forEach(function(data){
        //   total.push(data.total);
        // });

        const url = "{{url('/chart')}}";  
        const labels = response.map(data => data.status);
        const total = response.map(data => data.total);
        const status = response.map(data => data.status);

        const ctx = document.getElementById("canvas2").getContext('2d');
            const myChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: status,
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
    });
    </script>
<hr/>
  </body>
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@stop
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js" charset="utf-8"></script>