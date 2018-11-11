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
                   			<input type="button" name="addCamera" onclick="addCamera();" value="+" class="btn btn-info"/> 
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
                    			<select name="type" id="typeNewCnx" class="form-control" onchange="toogleFormConnexion();">
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
                    			<input type="button" name="addCnx" onclick="addConnexion()" value="+" class="col-md-6 btn btn-info"/>
                    		</td>
                    	</form>
                  	</tr>
        	</table>
        </div>
      </div>
    </div>
</div>
<script>

    function addCamera(){
    	var cameraType = document.getElementById("newCamera").value;
    	var jsonCam = "";
    	if(cameraType == "USB" || cameraType == "WEBCAM" ){
    		var index = prompt("Please enter the index of the camera you want to connect", "0");
    		if(index == null || index == ''){
        		index = 0;
    		}
    		jsonCam = {"type":cameraType,"attributes":{"class":"CameraConfAttrIndex","index":index},"liveviews":[]};
    	}else if(cameraType == "MJPEG" || cameraType == "RTSP" ){
    		var url = prompt("Please enter the url of the camera you want to connect", "");
    		if(url == null || url == ''){
        		return;
    		}
    		jsonCam = {"type":cameraType,"attributes":{"class":"CameraConfAttrWeb","url":url},"liveviews":[]};
    	}else {
    		jsonCam = {"type":cameraType,"attributes":{},"liveviews":[]};
    	}

    	confJson.cameras.push(jsonCam);

    	refreshTableCamera();
    }

	function addCameraToHtml(index, jsonCamera){
		var markup = '<tr><td>'
			+ jsonCamera.type;

		markup += printAttributes(jsonCamera.attributes);

		markup += '</td><td><ul>'
		for(var i=0; i < jsonCamera.liveviews.length; i++){
			markup += addLiveviewToHtml(index, i, jsonCamera.liveviews[i]);
		}
		markup += '</ul></td>'
				+ '<td><input type="button" name="addLiveview' + index + '" value="Add Liveview" class="btn btn-info" onclick="currentCameraSelected=' + index + ';$(\'#newLiveviewPanel\').dialog(\'open\');"><br>'
				+ '<input type="button" name="delCamera' + index + '" onclick="delCamera(' + index + ')" value="Delete camera" class="btn btn-warning"></td></tr>';

		$("#table_camera").append(markup);
	}

	function delCamera(camera){
		if(confJson.cameras.length > camera){
			confJson.cameras.splice(camera, 1);

			refreshTableCamera();
		}
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
		markup += '<input type="button" onclick="delLiveview(' + indexCamera + ', ' + indexLiveview +');" value="-" class="btn btn-warning"></li>';

		return markup;
	}

	function delLiveview(camera, liveview){
		if(confJson.cameras.length > camera){
			if(confJson.cameras[camera].liveviews.length > liveview){
				confJson.cameras[camera].liveviews.splice(liveview, 1);

				refreshTableCamera();
			}
		}
	}
	
	function addLiveview(){

		var liveviewType = $("#newLiveView").val();
		var liveview = "";
		
		if(liveviewType == "HTTP_MJPEG"){ 
			var httpPort = $("#newLiveviewHttpPort").val();
			liveview = {"type":liveviewType,"attributes":{"class":"LiveviewConfAttrHttp","port":httpPort},"filters":[]};
		}else{
			liveview = {"type":liveviewType,"attributes":{"class":"LiveviewConfAttr"},"filters":[]};
		}

		var filters = [];
		$("#sortableSelectedFilters").children("li").each(function( index ) {
			filters.push({"type" : $( this ).text(), "parameters" : null});
			//console.log({"type" : $( this ).text(), "parameters" : null});
		});

		
		confJson.cameras[currentCameraSelected].liveviews.push(filters);

		$("#newLiveviewPanel").dialog("close");

		console.log($.param(confJson));
		
		refreshTableCamera();
	}

	function refreshTableCamera(){
		$("#table_camera").empty();
		 $("#table_camera").html(originalCameraTab);

		 for (var i = 0; i < confJson.cameras.length; i++) {
			addCameraToHtml(i, confJson.cameras[i]);
		}
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
			$("#tableconnexion").empty();
			 $("#tableconnexion").html(originalConnexionTab);

			 for (var i = 0; i < confJson.connexions.length; i++) {
				addConnexionToHtml(i, confJson.connexions[i]);
			}
		}
	}

	function addConnexion(){
		if($("#typeNewCnx").find(':selected').val() == "SERIAL"){
			var serialPort = document.getElementById("serialPortAdd").value;
			var serialSpeed = document.getElementById("serialSpeedAdd").value;
			var newSerialCnx = {"type":"MAVLINK_SERIAL","attributes":{"class":"ConnexionConfAttrSerial","speed":serialSpeed,"port":serialPort}};
			confJson.connexions.push(newSerialCnx);
		}else{
			var host = document.getElementById("hostAdd").value;
			var hostPort = document.getElementById("hostPortAdd").value;
			var newWebCnx = {"type":"MAVLINK_UDP","attributes":{"class":"ConnexionConfAttrWeb","host":host,"port":hostPort}};
			confJson.connexions.push(newWebCnx);
		}
		$("#tableconnexion").empty();
		 $("#tableconnexion").html(originalConnexionTab);

		 for (var i = 0; i < confJson.connexions.length; i++) {
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


	var confJson ={};
	$( document ).ready(function() {
		var confAPI = "/proxy.php";
		var confJsonPath = "http://localhost:8079/";
    	$.getJSON( confAPI, {csurl: confJsonPath},function() {
    	  console.log( "success" );
    		})
    	  .done(function(data) {
    		confJson = data;  
    	    console.log( "second success" );
    	    for (var i = 0; i < confJson.cameras.length; i++) {
    			addCameraToHtml(i, confJson.cameras[i]);
    		}
    
    
    		for (var i = 0; i < confJson.connexions.length; i++) {
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

    		for (var i = 0; i < confJson.availableFilter.length; i++) {
    			var markup = '<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' + confJson.availableFilter[i] + '</li>';
    			$("#sortableAvailableFilters").append(markup); 
    		}


    		$( function() {
    			$( "ul.droptrue" ).sortable({
    		      connectWith: "ul"
    		    });
    		 
    		    $( "#sortableAvailableFilters, #sortableSelectedFilters"  ).disableSelection();
    		} );
    		
    	  })
    	  .fail(function(jqXHR, textStatus, errorThrown) {
    	        console.log("error " + textStatus);
    	        console.log("error thrown" + errorThrown);
    	        console.log("incoming Text " + jqXHR.responseText);
    	  })
    	  .always(function() {
    	    console.log( "complete" );
    	  });
    
    	toogleFormConnexion();

    	$("#newLiveviewPanel").dialog({
    		autoOpen: false,
    	    minWidth: 600,
    	    minHeight: 400,
    	    show: {
    	        effect: "clip",
    	        duration: 200
    	    },
    	    hide: {
    	        effect: "clip",
    	        duration: 200
    	    }
    	});
    	
	});

	var originalCameraTab = $("#table_camera").html();
	var originalConnexionTab = $("#tableconnexion").html();
	var currentCameraSelected = 0;
	
	function toogleFormConnexion(){
		
		if($("#typeNewCnx").find(':selected').val() == "SERIAL"){
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
	
</script>
<div class="modal" id="newLiveviewPanel" title="Add new liveview">
	<select name="newLiveView" id="newLiveView" class="form-control" onchange="if(this.options[this.selectedIndex].value == 'VIDEO_OUT'){$('#newLiveviewPort').hide()}else{$('#newLiveviewPort').show()}">
    	<option value="VIDEO_OUT">VIDEO_OUT</option>
    	<option value="HTTP_MJPEG">HTTP_MJPEG</option>
    </select><br/>
    <div id="newLiveviewPort" style="display:none">
    	HTTP Port : <input type="text" name="port" id="newLiveviewHttpPort"/><br/>
    </div>
   <table>
   	<tr>
   		<th>
   			Available Filters
   		</th>
   		<th>
   			Selected Filters
   		</th>
   	</tr>
   	<tr>
   		<style>
   		   #sortableAvailableFilters, #sortableSelectedFilters { list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 200px"}
           #sortableAvailableFilters li, #sortableSelectedFilters li { margin: 5px; padding: 5px; font-size: 1.2em; }
        </style>
   		<td>
   			<ul id="sortableAvailableFilters"  class="droptrue">
    		</ul>
   		</td>
   		<td>
   			<ul id="sortableSelectedFilters"  class="droptrue">
   				<!-- li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Push here the filters you selected</li-->
    		</ul>
   		</td>
   	</tr>
   </table>
    <input type="button" value="Add" onclick="addLiveview();"/>
</div>

<?php 
}
?>