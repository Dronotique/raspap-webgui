<?php

include_once( 'includes/status_messages.php' );

function DisplayModemConf(){
  $status = new StatusMessages();
  
  $modemConfFilePath = "/etc/sakis3g.conf";
  
  if( isset($_POST['UpdateAPN']) && CSRFValidate()){
      $jsonConf = json_decode($_POST['jsonConf'], true);
      var_dump($jsonConf);
      $newConf = '/var/www/uploads/sakis3g.conf';
      $newFileConf = fopen($newConf, "w+");
      
      foreach ($jsonConf as $key => $value){
          fwrite($newFileConf, $key . "=\"" . $value . "\"\n");
      }
      fclose($newFileConf);

  }
  
  
  $confTab = array();
  //Get Modem Configuration from file
  if (file_exists($modemConfFilePath)) {
      $fileContent = file($modemConfFilePath);
      
      $strJsonConf="{";
      foreach( $fileContent as $line ) {
          
          $stposEq = strpos($line, "=");
          if($stposEq ){
              if($strJsonConf != "{"){
                  $strJsonConf .= ",";
              }
              $conVal = substr($line, $stposEq + 2);
              $conVal = substr($conVal, 0, strlen($conVal)-2);
              
              $confTab[substr($line, 0, $stposEq)] = $conVal;
              $strJsonConf .= "'";
              $strJsonConf .= substr($line, 0, $stposEq);
              $strJsonConf .= "' : '";
              $strJsonConf .= $conVal;
              $strJsonConf .= "'";
          }
      }
      $strJsonConf .= "}";
      echo("<script>jsonConf=" . $strJsonConf . ";</script>");
  }
  
  ?>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-upload fa-fw"></i><?php echo _("3/4G Modem Configuration"); ?></div>
        <div class="panel-body">
          <p><?php $status->showMessages(); ?></p>
          <form role="form" action="?page=modem_conf" method="POST" enctype="multipart/form-data" id="formModem">
          	<input type="hidden" name="jsonConf" id="jsonConf"/>
            <?php CSRFToken() ?>
            <div class="row">
                <div class="form-group col-md-4">
                  	<label for="modemSelect"><?php echo _("USB Device selection"); ?></label>
                  	<select name="modemSelect" id="modemSelect">
<?php 
                        
                        exec( '(lsusb)', $return );
                        foreach( $return as $line ) {
                            $idPos = strpos($line, "ID");
                            $idModem = substr($line, $idPos+3, 9);
                            $libModem = substr($line, $idPos+13);
                            echo ("<option value='" . $idModem . "' " . ($confTab["USBMODEM"]==$idModem?'selected':'') . ">" . $libModem . "</option>");
                        }
                         
?>
                  	</select>
            	</div>	
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                  	<label for="apnSelect"><?php echo _("APN selection"); ?></label>
                  	<select name="apnSelect" id="apnSelect">
                  	</select>
            	</div>	
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                  	<label for="apnNetwork"><?php echo _("APN Network"); ?></label>
                  	<input type="text" name="apnNetwork" id="apnNetwork" value="<?php echo($confTab['##NETWORK']);?>">
            	</div>	
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                  	<label for="apnName"><?php echo _("APN Name"); ?></label>
                  	<input type="text" name="apnName" id="apnName"  value="<?php echo($confTab['CUSTOM_APN']);?>">
            	</div>	
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                  	<label for="apnUser"><?php echo _("User"); ?></label>
                  	<input type="text" name="apnUser" id="apnUser"  value="<?php echo($confTab['APN_USER']);?>">
            	</div>	
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                  	<label for="apnPwd"><?php echo _("Pawword"); ?></label>
                  	<input type="text"  name="apnPwd" id="apnPwd" value="<?php echo($confTab['APN_PASS']);?>">
            	</div>	
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                  	<label for="updatefirmwarefile"><?php echo _("Dial Number"); ?></label>
                  	<input type="text" name="apnDial" value="<?php echo($confTab['DIAL']);?>">
            	</div>	
            </div>
            <div class="row">
                  <div class="form-group col-md-4">
            		<input type="submit" class="btn btn-outline btn-primary" name="UpdateAPN" value="<?php echo _("Update APN"); ?>" />
            	</div>	
            </div>
          </form>
        </div><!-- /.panel-body -->
      </div><!-- /.panel-default -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
    <script>
    	var apnList = {};
    	$( document ).ready(function() {		
    	$.getJSON( "/config/apn.json", {},function() {
    	  console.log( "success" );
    		})
    	  .done(function(data) {
    		  apnList = data;  
    	    console.log( "second success" );
    	    
    	    for (var i = 0; i < data.length; i++) {
        	    apnLabel = data[i].country + " - " + data[i].network;
				apnSelected = false;
				if(apnLabel == '<?php echo($confTab['##NETWORK'])?>'){
					apnSelected = true;
				}
    	    	var option = new Option(apnLabel, i, apnSelected, apnSelected);
    	    	$("#apnSelect").append(option); 
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
    
        	$("#apnSelect").on("change", function(event) { 
        		$("#apnNetwork").val(apnList[$("#apnSelect").val()].country + " - " + apnList[$("#apnSelect").val()].network);
        		$("#apnName").val(apnList[$("#apnSelect").val()].apn);
        		$("#apnUser").val(apnList[$("#apnSelect").val()].user);
        		$("#apnPwd").val(apnList[$("#apnSelect").val()].password);
        		$("#apnDial").val(apnList[$("#apnSelect").val()].dial);
        	});


        	$("#formModem").on("submit", function(event) {
        		$("#jsonConf").val( JSON.stringify(jsonConf));
        	});
       	
    	});
    </script>
<?php 
}
?>