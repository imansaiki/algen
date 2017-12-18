<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>
<body>
  <div id="page-wrapper">
    <div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">TSP</h1>
				</div>
			</div>
      <div class="row">
        <div class="col-md-12">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
            
          </ul>
        </div>
      </div>

      <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
          <div class="row">
            <h2></h2>
            <div class="col-md-2">
            <div class="panel panel-info" >
    				 <div class="panel-heading ">Parameter</div>
    				    <div class="panel-body">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label" for="nis">Panjang Populasi</label>
                      <input type="number" class="form-control" id="populasi_maksimal" placeholder="Populasi"/>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="nis">Probabilita Mutasi</label>
                      <div class="input-group">
                        <input type="number" class="form-control" id="prob_mutasi" placeholder="probabilita"/>
                        <span class="input-group-addon" id="basic-addon1">%</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="nis">Max Iterasi</label>
                      <input type="number" class="form-control" id="banyak_iterasi" placeholder="iterasi"/>
                    </div>
                  </div>
                  <button type="button" class="form-control btn-primary" id="mulai_button">Mulai</button>
                </div>
            </div>
          </div>

        <div class="col-md-8">
        <div class="panel panel-info" >
         <div class="panel-heading ">Output</div>
            <div class="panel-body">
              <div class="form-group">
                <label class="col-md-2 control-label" for="nis">Iterasi Ke-</label>
                <div class="col-md-3">
                  <select class="form-control" style="display: none;"  id="selector_iterasi">

                  </select>
                </div>
              </div>
              <div id="output"></div>
            </div>
        </div>
      </div>


          </div>
        </div>
        <div id="menu1" class="tab-pane fade">
          <h2></h2>
            <div class="row">
              <div id="daftar_jarak"></div>
            </div>

        </div>
        <div id="menu2" class="tab-pane fade">

        </div>
      </div>

      </div>
    </div>
  </div>
</body>
<script>
$( document ).ready(function() {
  $( "#mulai_button" ).click(function() {
    var iterasi = $("#banyak_iterasi").val();
    var pop = $("#populasi_maksimal").val();
    var prob = $("#prob_mutasi").val();
    if (iterasi && pop && prob){
      var opt;
      for(var i=1; i<=iterasi;i++){
          opt+= "<option value='hsl"+i+"'>"+i+"</option>";

      };
      $.ajax({
            url: "<?php echo base_url('algenfunc/algenStart/')?>",
            method: "POST",
            data: { max_generasi : iterasi, max_populasi: pop, prob_mutasi: prob },
            success: function( result ) {
              $('#output').html(result);

            }
          });
          $("#selector_iterasi").html(opt);
          $("#selector_iterasi").val("hsl1");
          $("#selector_iterasi").show();
    }else{
      alert("isi form dengan lengkap");
    }

  });

  $(document).on("change", "#selector_iterasi", function(){
    $(".hasil").hide();
    $(".hasil."+$(this).val()).show();

  });
  $.get( "<?php echo base_url('algenfunc/tabelJarak/')?>", function( data ) {
    $( "#daftar_jarak" ).html( data );

  });


});

</script>
