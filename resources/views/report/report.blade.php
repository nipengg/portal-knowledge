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
            <option value="2018"  {{$filter === '2018' ? 'selected' : ''}} >2018</option>
        </select>
    </div>
    <br>

    <ul class="nav nav-tabs col-md-10 col-md-offset-1" id="myTab" role="tablist">
        <li class="nav-item active">
          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all"
            aria-selected="true">All</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#q1" role="tab" aria-controls="q1"
            aria-selected="false">Q1</a>
        </li>
    </ul>
      
<div class="tab-content" id="myTabContent">
<div class="tab-pane fade" id="all" aria-labelledby="all-tab" role="tabpanel">
    <div class="row">
     <div class="col-md-10 col-md-offset-1">
         <div class="panel panel-default">
             <div class="panel-heading">
                <b>Project Chart</b>
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
    $(document).ready(function(){
      $.get(url, function(response){

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
</div>

<div id="q1" class="tab-pane fade" role="tabpanel" aria-labelledby="q1-tab">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                   <b>Project Chart</b>
               </div>
                <div class="panel-body">
                    <canvas id="canvas3" height="280" width="600"></canvas>
                </div>
                <div class="panel-body">
                   <canvas id="canvas4" height="280" width="600"></canvas>
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
          const labels = response.map(data => data.status);
          const total = response.map(data => data.total);
          const status = response.map(data => data.status);
  
            const ctx = document.getElementById("canvas3").getContext('2d');
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
      $(document).ready(function(){
        $.get(url, function(response){
  
          const url = "{{url('/chart')}}";  
          const labels = response.map(data => data.status);
          const total = response.map(data => data.total);
          const status = response.map(data => data.status);
  
          const ctx = document.getElementById("canvas4").getContext('2d');
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
</div>

</div>
<hr/>
  </body>
  
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@stop
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js" charset="utf-8"></script>