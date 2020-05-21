<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style type="text/css">
		    table {
    border-spacing: 0;
    width: 100%;
    }
    th {
    background: #404853;
    background: linear-gradient(#687587, #404853);
    border-left: 1px solid rgba(0, 0, 0, 0.2);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    padding: 8px;
    text-align: left;
    text-transform: uppercase;
    }
    th:first-child {
    border-top-left-radius: 4px;
    border-left: 0;
    }
    th:last-child {
    border-top-right-radius: 4px;
    border-right: 0;
    }
    td {
    border-right: 1px solid #c6c9cc;
    border-bottom: 1px solid #c6c9cc;
    padding: 8px;
    }
    td:first-child {
    border-left: 1px solid #c6c9cc;
    }
    tr:first-child td {
    border-top: 0;
    }
    tr:nth-child(even) td {
    background: #e8eae9;
    }
    tr:last-child td:first-child {
    border-bottom-left-radius: 4px;
    }
    tr:last-child td:last-child {
    border-bottom-right-radius: 4px;
    }
    img {
    	width: 40px;
    	height: 40px;
    	border-radius: 100%;
    }
    .center {
    	text-align: center;
    }
	</style>
  <link rel="stylesheet" href="">
	<title>Laporan Data Request</title>
</head>
<body>
<h1 class="center">LAPORAN DATA REQUEST</h1>
<div style="font-size: 8pt">
  Done : {{$done->where('accepted_answer_id', 1)->count()}} &nbsp; &nbsp;
  Open : {{$done->where('accepted_answer_id', 0)->count()}} &nbsp; &nbsp;
  Open pending : {{$done->where('accepted_answer_id', 2)->count()}} &nbsp; &nbsp;
  Stop : {{$done->where('accepted_answer_id', 3)->count()}} &nbsp; &nbsp;
  Stop pending : {{$done->where('accepted_answer_id', 4)->count()}} &nbsp; &nbsp;
  | &nbsp; &nbsp;
  LEFO : {{$done->where('category_name', 'LEFO')->count()}} &nbsp; &nbsp;
  Review : {{$done->where('category_name', 'Review')->count()}} &nbsp; &nbsp;
</div>
<hr/>
 <table id="pseudo-demo" style="font-size: 5pt">
                      <thead>
                        <tr>
                          <th>
                            Request Type
                          </th>
                          <th>
                            Title
                          </th>
                          <th>
                            Topic
                          </th>
                          <th>
                            Requestor Date
                          </th>
                          <th>
                            Requestor Name
                          </th>
                          <th>
                            Department
                          </th>
                          <th>
                            Closing Date
                          </th>
                          <th>
                            Total Workouts Days
                          </th>
                          <th>
                            Status
                          </th>
                          <th>
                            Rating
                          </th>
                          <th>
                            Refrence Data
                          </th>
                          <th>
                            Additional Information User
                          </th>
                          <th>
                            Additional Information Admin
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($questions as $data)
                        <tr>
                          <td class="py-1">
                            {{$data->category_name}}
                          </td>
                          <td>
                            {{$data->question_title}}
                          </td>
                          <td>
                            -
                          </td>
                          <td>
                            {{$data->created_at}}
                          </td>
                          <td>
                            {{$data->users}}
                          </td>
                          <td>
                            {{$data->department}}
                          </td>
                          <td>
                            {{$data->closed_at}}
                          </td>
                          <td>
                            @if($data->closed_at === null)
                            Not Finished Yet
                            @else
                            {{$data->days}} Day</td>
                            @endif
                          </td>
                          <td>
                            @if($data->accepted_answer_id === 0)
                                Open
                            @elseif($data->accepted_answer_id === 1)
                                Done
                            @elseif($data->accepted_answer_id === 2)
                                Open pending
                            @elseif($data->accepted_answer_id === 3)
                                Stop
                            @elseif($data->accepted_answer_id === 4)
                                Stop pending
                            @endif
                          </td>
                          <td>
                            {{$data->post_rating}}
                          </td>
                          <td>
                            @foreach($data->refrences as $refrence)
                            @if($refrence === null)
                            -
                            @else
                            {{$refrence}}
                            @endif
                            @endforeach
                          </td>
                          <td>
                            @if($data->additional_information === null)
                              -
                            @else
                              {{$data->additional_information}}
                            @endif
                          </td>
                          <td>
                            @if($data->additional_information_admin === null)
                              -
                            @else
                              {{$data->additional_information_admin}}
                            @endif
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
<hr/>
<div style="font-size: 8pt">
  Done : {{$done->where('accepted_answer_id', 1)->count()}} &nbsp; &nbsp;
  Open : {{$done->where('accepted_answer_id', 0)->count()}} &nbsp; &nbsp;
  Open pending : {{$done->where('accepted_answer_id', 2)->count()}} &nbsp; &nbsp;
  Stop : {{$done->where('accepted_answer_id', 3)->count()}} &nbsp; &nbsp;
  Stop pending : {{$done->where('accepted_answer_id', 4)->count()}} &nbsp; &nbsp;
  | &nbsp; &nbsp;
  LEFO : {{$done->where('category_name', 'LEFO')->count()}} &nbsp; &nbsp;
  Review : {{$done->where('category_name', 'Review')->count()}} &nbsp; &nbsp;
</div>
</body>
</html>