<?php 

function DisplayConnexionConfigJson() {
?>

<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-lock fa-fw"></i><?php echo _("Configure Cameras and Connexions"); ?></div>
        <div class="panel-body" id="xonomy_editor">
        		<table class="table table-responsive table-striped" id="table_camera">
        			   <tr>
                        <th colspan="5"><?php echo _("Cameras"); ?></th>
                      </tr>
                      <tr>
                        <th><?php echo _("Type"); ?></th>
                        <th><?php echo _("Attributes"); ?></th>
                        <th><?php echo _("Action"); ?></th>
                        <th><?php echo _(""); ?></th>
                        <th></th>
                      </tr>
                </table>
                <table>
                      <tr>
                          <td>
        	                  Add new camera :
                          </td>
                          <td>
            	              <select name="newCamera" id="newCamera"  class="form-control">
                        	</select>
                    	</td>
						<td>
                   			<input type="submit" name="addCamera" value="+" class="btn btn-info"/> 
                   		</td>
                    		
        			</tr>
                </table>
        
      			<table class="table table-responsive table-striped" id="tableconnexion">
      				<tr>
                        <th colspan="5"><?php echo _("Output Connections"); ?></th>
                      </tr>
                      <tr>
                        <th><?php echo _("Type"); ?></th>
                        <th><?php echo _("Host/Serial Port"); ?></th>
                        <th><?php echo _("Host Port/Serial Speed"); ?></th>
                        <th><?php echo _(""); ?></th>
                        <th></th>
                      </tr>
                </table>
				<table>
	               	<tr>
                		<form method="POST" action="?page=configurator_modif" name="conf_form_add">
                    		<td>
                    			<select name="type"  class="form-control" onchange="toogleForm();">
                  					<option id="serialOpt" value="SERIAL" on>Serial Port</option>
                  					<option id="webOpt" value="WEB">Web</option>
                  				</select>
                    		</td>
                    		<td>
                    			<select name="serialPort" id="serialPortAdd"  class="form-control">
                    			</select>
                  				<input type="text" name="host" id="hostAdd"  class="form-control" />
                    		</td>
                    		<td>
                    			<select name="serialSpeed" id="serialSpeedAdd"  class="form-control">
                  					<option value="9600">9600</option>
                  					<option value="19200">19200</option>
                  					<option value="38400">38400</option>
                  					<option value="57600">57600</option>
                  					<option value="115200">115200</option>
                  				</select>
                  				<input type="text" name="hostPort" id="hostPortAdd"  class="form-control"/>
                    		</td>
                    		<td>
                    			<input type="submit" name="addCnx" value="Add" class="col-md-6 btn btn-info"/>
                    		</td>
                    		<script>
                        		
                    		</script> 
                    	</form>
                  	</tr>
        	</table>
        </div>
      </div>
    </div>
</div>
<script src="../vendor/jquery/jquery.min.js"></script>
<script>


    function addCamera(cameraType, attributeType, attributeVal){
    
    }

	function addCameraToHtml(jsonCamera){

	}

	function delCamera(camera){

	}


	
	function addLiveviewToHtml(jSonLiveview){
		
	}

	function delLiveview(camera, liveview){

	}
	
	function addFilterToHtml(jSonFilter){

	}

	function addConnexionToHtml(index, jSonConnexion){
		var markup = "<tr><td>"
			+ jSonConnexion.type 
			+ '</td><td>';
		if(jSonConnexion.type == "MAVINK_SERIAL"){
			markup += jSonConnexion.port
			+ '</td><td>'
			+ jSonConnexion.speed;
		}else{
			markup += jSonConnexion.host
			+ '</td><td>'
			+ jSonConnexion.port;
		}
		markup += '</td><td><form method="POST" action="?page=configurator_modif" name="conf_form' + index 
			+ '"><input type="hidden" value="' + index
			+ '" name="idCnx"><input type="submit" name="deleteCnx" value="Delete" class="col-md-6 btn btn-warning"></form></td></tr>';
		
		document.getElementById("tableconnexion").append(markup);
	}

	function delConnexion(connexion){

	}

	var confAPI = "/proxy.php?callback=?";
	var confJsonPath = "http://localhost:8079/";
	var confJson ="";
	$.getJSON( confAPI, {csurl: confJsonPath},function() {
	  console.log( "success" );
		})
	  .done(function(data) {
		confJson = data;  
	    console.log( "second success" );
	  })
	  .fail(function(jqXHR, textStatus, errorThrown) {
	        console.log("error " + textStatus);
	        console.log("error thrown" + errorThrown);
	        console.log("incoming Text " + jqXHR.responseText);
	  })
	  .always(function() {
	    console.log( "complete" );
	  });
	  
	
	for (var i = 0; i < confJson.cameras.length; i++) {
		addCameraToHtml(confJson.cameras[i]);
	}


	for (var i = 0; i < confJson.cameras.length; i++) {
		addConnexionToHtml(i, confJson.connexions[i]);
	}

	for (var i = 0; i < confJson.availableCameras.length; i++) {
		var option = new Option(confJson.availableCameras[i], confJson.availableCameras[i]);
		document.getElementById("newCamera").append($(option)); 
	}

	for (var i = 0; i < confJson.availableSerial.length; i++) {
		var option = new Option(confJson.availableSerial[i], confJson.availableSerial[i]);
		document.getElementById("serialPortAdd").append($(option)); 
	}


	function toogleFormConnexion(){
		if(document.getElementById('serialOpt').selected == 'selected'){
			document.getElementById("hostAdd").style.display='none';
			document.getElementById("hostPortAdd").style.display='none';
			document.getElementById("serialPortAdd").style.display='block';
			document.getElementById("serialSpeedAdd").style.display='block';
		}else{
			document.getElementById("hostAdd").style.display='block';
			document.getElementById("hostPortAdd").style.display='block';
			document.getElementById("serialPortAdd").style.display='none';
			document.getElementById("serialSpeedAdd").style.display='none';
		}
	}
	toogleForm();
	
</script>
<?php 
}
?>