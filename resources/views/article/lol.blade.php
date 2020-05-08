{{-- <html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>jQuery .prop() method example</title>
    <style>
    	select, p, span {
        	font: 17px Calibri;
        }
    </style>
</head>
<body>
	<p>
    	Select a value from dropdown list!
    </p>

    <!--The SELECT dropdown list element-->
    <select id="sel">
        <option selected value="">-- select a number --</option>
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="15">15</option>
    </select>
    
    <input type="text" id="x">

    <p id="cont"> </p>

    <select name="column_select" id="column_select">
      <option value="col1">1 column</option>
      <option value="col2">2 column</option>
      <option value="col3">3 column</option>
  </select>
  
  <select name="layout_select" id="layout_select">
      <!--Below shows when '1 column' is selected is hidden otherwise-->
      <option value="col1">none</option>
  
      <!--Below shows when '2 column' is selected is hidden otherwise-->
      <option value="col2_ms">layout 1</option> 
      <option value="col2_sm">layout 2</option>
  
      <!--Below shows when '3 column' is selected is hidden otherwise-->
      <option value="col3_mss">layout 3</option>
      <option value="col3_ssm">layout 4</option>
      <option value="col3_sms">layout 5</option>
  </select>


</body>

<script>
    $(document).ready(function () {
        $('#sel').change(function () {
            
            $('#x').prop('disabled', true);
        });

        $("#layout_select").children('option:gt(0)').hide();
        $("#column_select").change(function() {
        $("#layout_select").children('option').hide();
        $("#layout_select").children("option[value^=" + $(this).val() + "]").show()
    })
    });
</script>
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery Show Hide Elements Using Select Box</title>
<style>
    .box{
        color: #fff;
        padding: 20px;
        display: none;
        margin-top: 20px;
    }
    .red{ background: #ff0000; }
    .green{ background: #228B22; }
    .blue{ background: #0000ff; }
</style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
$(document).ready(function(){
    $("select").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".box").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".box").hide();
            }
        });
    }).change();
});
</script>
</head>
<body>
    <div>
        <select>
            <option>Choose Color</option>
            <option value="red">Red</option>
            <option value="green">Green</option>
            <option value="blue">Blue</option>
        </select>
    </div>
    <input type="text" id="x">
    <div class="red box">You have selected <strong>red option</strong> so i am here</div>
    <div class="green box">You have selected <strong>green option</strong> so i am here</div>
    <div class="blue box">You have selected <strong>blue option</strong> so i am here</div>
</body>
</html>                            