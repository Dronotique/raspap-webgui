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
                        <th><?php echo _("Liveviews"); ?></th>
                        <th><?php echo _("Actions"); ?></th>
                        <th><?php echo _(""); ?></th>
                        <th></th>
                      </tr>
                </table>
                <table  class="table table-responsive table-striped" >
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
				<table  class="table table-responsive table-striped" >
	               	<tr>
                		<form method="POST" action="?page=configurator_modif" name="conf_form_add">
                    		<td>
                    			<select name="type"  class="form-control" onchange="toogleFormConnexion();">
                  					<option id="serialOpt" value="SERIAL">Serial Port</option>
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

	function addCameraToHtml(index, jsonCamera){
		var markup = '<tr><td>'
			+ jsonCamera.type;

		markup += printAttributes(jsonCamera.attributes);

		markup += '</td><td><ul>'
		for(var i=0; i < jsonCamera.liveviews.length; i++){
			markup += addLiveviewToHtml(index, i, jsonCamera.liveviews[i]);
		}
		markup += '</ul><div class="modal" id="newLiveviewPanel' + index + '">'
    			+ '<select name="newLiveView' + index + '" id="newLiveView' + index + '" class="form-control">'
    			+ '<option value="VIDEO_OUT">VIDEO_OUT</option><option value="HTTP_MJPEG">HTTP_MJPEG</option></select></div></td>'
				+ '<td><input type="button" name="addLiveview' + index + '" value="Add Liveview" class="btn btn-info" onclick="document.getElementById(\'newLiveviewPanel' + index + '\').style.display=\'block\';"><br>'
				+ '<input type="submit" name="delCamera' + index + '" value="Delete camera" class="btn btn-warning"></td></tr>';

		$("#table_camera").append(markup);
	}

	function delCamera(camera){

	}


	
	function addLiveviewToHtml(indexCamera, indexLiveview, jSonLiveview){
		var markup = '<li>' + jSonLiveview.type;
		markup += printAttributes(jSonLiveview.attributes);

		if(jSonLiveview.filters.length > 0){
			markup += " Filters : ";
			for(var j=0; j < jSonLiveview.filters.length; j++){
				if(j > 0){
					markup += ", ";
				}
				markup += jSonLiveview.filters[j].type;
			}
		}
		markup += '<input type="button" onclick="delLiveview(' + indexCamera + ', ' + indexLiveview +');" value="-" class="btn btn-info"></li>';

		return markup;
	}

	function delLiveview(camera, liveview){
		if(confJson.cameras.length > camera){
			confJson.connexions.splice(index, 1);
		}

		 $("#cnxRow" + index).remove();
	}
	
	function addFilterToHtml(jSonFilter){

	}

	function addConnexionToHtml(index, jSonConnexion){
		var markup = "<tr id='cnxRow" + index + "'><td>"
			+ jSonConnexion.type 
			+ '</td><td>';
		if(jSonConnexion.type == "MAVLINK_SERIAL"){
			markup += jSonConnexion.attributes.port
			+ '</td><td>'
			+ jSonConnexion.attributes.speed;
		}else{
			markup += jSonConnexion.attributes.host
			+ '</td><td>'
			+ jSonConnexion.attributes.port;
		}
		markup += '</td><td><input type="button" name="deleteCnx" value="Delete" class="col-md-6 btn btn-warning" onclick="delConnexion(' + index + ')"></td></tr>';
		
		$("#tableconnexion").append(markup);
	}

	function delConnexion(index){
		if(confJson.connexions.length > index){
			confJson.connexions.splice(index, 1);
		}

		 $("#tableconnexion").empty();
		 $("#tableconnexion").html(originalConnexionTab);

		 for (var i = 0; i < confJson.cameras.length; i++) {
			addConnexionToHtml(i, confJson.connexions[i]);
		}
	}


	function printAttributes(jsonAttr) {
		var retAttributes = "";

		var i =0;
		Object.keys(jsonAttr).forEach(function(key) {
			if(key != "class"){
				if(i > 0){
					retAttributes+= ', ';
				}
				retAttributes+=key + ':' + jsonAttr[key];
				i++;
			}
		})

		if (i > 0){
			retAttributes = '(' + retAttributes + ')';
		}

		return retAttributes;
		
	}
	
	var confAPI = "/proxy.php";
	var confJsonPath = "http://localhost:8079/";
	var confJson ={};
	$.getJSON( confAPI, {csurl: confJsonPath},function() {
	  console.log( "success" );
		})
	  .done(function(data) {
		confJson = data;  
	    console.log( "second success" );
	    for (var i = 0; i < confJson.cameras.length; i++) {
			addCameraToHtml(i, confJson.cameras[i]);
		}


		for (var i = 0; i < confJson.cameras.length; i++) {
			addConnexionToHtml(i, confJson.connexions[i]);
		}

		for (var i = 0; i < confJson.availableCameras.length; i++) {
			var option = new Option(confJson.availableCameras[i], confJson.availableCameras[i]);
			$("#newCamera").append(option); 
		}

		for (var i = 0; i < confJson.availableSerial.length; i++) {
			var option = new Option(confJson.availableSerial[i], confJson.availableSerial[i]);
			$("#serialPortAdd").append(option); 
		}
	  })
	  .fail(function(jqXHR, textStatus, errorThrown) {
	        console.log("error " + textStatus);
	        console.log("error thrown" + errorThrown);
	        console.log("incoming Text " + jqXHR.responseText);
	  })
	  .always(function() {
	    console.log( "complete" );
	  });


	var originalCameraTab = $("#table_camera").html();
	var originalConnexionTab = $("#tableconnexion").html();

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
	toogleFormConnexion();
	
</script>
<?php 
}
?>