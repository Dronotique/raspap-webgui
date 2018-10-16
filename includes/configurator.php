<?php 

function modifyConnexionConfig() {
    //$xmlConfFileContent = readfile("configuration.xml");
    //$xmlConfFile = new SimpleXMLElement(utf8_encode($xmlConfFileContent));
    $xmlConfFile = simplexml_load_file("configuration.xml");
    $availableCnx = $xmlConfFile->children('connexions');
    
    $currentCameras = array ();
    $i=0;
    foreach ($xmlConfFile->cameras->cameras as $camera) {
        $currentCameras[$i] = $camera;
        $i++;
    }
    
    $currentSerial = array ();
    $i=0;
    foreach ($xmlConfFile->availableSerial->availableSerial as $serial) {
        $currentSerial[$i] = $serial;
        $i++;
    }
    
    $currentConnexions = array ();
    $i=0;
    foreach ($xmlConfFile->connexions->connexions as $cnx) {
        if($cnx->type == "MAVLINK_SERIAL"){
            $currentConnexions[$i] = array ("type" => $cnx->type, "port" => $cnx->attributes->serial_port_com, "speed" => $cnx->attributes->serial_speed_com);
        }else if($cnx->type == "MAVLINK_UDP"){
            $currentConnexions[$i] = array ("type" => $cnx->type, "host" => $cnx->attributes->host, "port" => $cnx->attributes->port);
        }else{
            $currentConnexions[$i] = $cnx->children();
        }
        $i++;
    }
    if(isset($_POST["deleteCnx"]) || isset($_POST["addCnx"])){
        
        if(! isset($_POST["deleteCnx"])){
            $_POST["idCnx"] = "-1";
        }
        
        $cameraList = $xmlConfFile->children('cameras');
        $serialList = $xmlConfFile->children('availableSerial');
        //On réécrit le fichier
        $newXmlConf = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?><configurator/>"); //xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
        //$root = $newXmlConf->children('/configurator');
        $xmlCameras = $newXmlConf->addChild("cameras");
        for ($i=0; $i < sizeof($currentCameras);$i++){
            $xmlCameras->addChild("cameras", $currentCameras[$i]);
        }
        
        $xmlSerials = $newXmlConf->addChild("availableSerial");
        for ($i=0; $i < sizeof($currentSerial);$i++){
            $xmlSerials->addChild("availableSerial", $currentSerial[$i]);
        }
        $connexions = $newXmlConf->addChild("connexions");
        for($i=0; $i < sizeof($currentConnexions);$i++){
            if($_POST["idCnx"]!=$i){
                $newCnx = $connexions->addChild("connexions");
                $newCnx->addChild("type", $currentConnexions[$i]["type"]);
                $attrs = $newCnx->addChild("attributes");
                $attrs-> addAttribute("xmlns:xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
                if($currentConnexions[$i]["type"] == "MAVLINK_SERIAL"){
                    $attrs->addAttribute("xsi:xsi:type", "connexionConfAttrSerial");
                    $attrs->addChild("serial_port_com",  $currentConnexions[$i]["port"]);
                    $attrs->addChild("serial_speed_com",  $currentConnexions[$i]["speed"]);
                }else if($currentConnexions[$i]["type"] == "MAVLINK_UDP"){
                    $attrs->addAttribute("xsi:xsi:type", "connexionConfAttrWeb");
                    
                    $attrs->addChild("host",  $currentConnexions[$i]["host"]);
                    $attrs->addChild("port",  $currentConnexions[$i]["port"]);
                }
            }
        }
        
        if(isset($_POST["addCnx"])){
            $newCnx = $connexions->addChild("connexions");
            
            if($_POST["type"] == "SERIAL"){
                $newCnx->addChild("type", "MAVLINK_SERIAL");
                $attrs = $newCnx->addChild("attributes");
                $attrs-> addAttribute("xmlns:xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
                $attrs->addAttribute("xsi:xsi:type", "connexionConfAttrSerial");
                $attrs->addChild("serial_port_com",  $currentSerial[$_POST["serialPort"]]);
                $attrs->addChild("serial_speed_com",  $_POST["serialSpeed"]);
            }else if($_POST["type"] == "WEB"){
                $newCnx->addChild("type", "MAVLINK_UDP");
                $attrs = $newCnx->addChild("attributes");
                $attrs-> addAttribute("xmlns:xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
                $attrs->addAttribute("xsi:xsi:type", "connexionConfAttrWeb");
                $attrs->addChild("host",  $_POST["host"]);
                $attrs->addChild("port",  $_POST["hostPort"]);
            }
        }
        
        $newXmlConf->asXml("configuration.xml");
    }
    DisplayConnexionConfig();
}

function DisplayConnexionConfig() {
    //$xmlConfFileContent = readfile("configuration.xml");
    //$xmlConfFile = new SimpleXMLElement(utf8_encode($xmlConfFileContent));
    $xmlConfFile = simplexml_load_file("configuration.xml");
    $availableCnx = $xmlConfFile->children('connexions');

    $currentCameras = array ();
    $i=0;
    foreach ($xmlConfFile->cameras->cameras as $camera) {
        $currentCameras[$i] = $camera;
        $i++;
    }
    
    $currentSerial = array ();
    $i=0;
    foreach ($xmlConfFile->availableSerial->availableSerial as $serial) {
        $currentSerial[$i] = $serial;
        $i++;
    }
    
    $currentConnexions = array ();
    $i=0;
    foreach ($xmlConfFile->connexions->connexions as $cnx) {
        if($cnx->type == "MAVLINK_SERIAL"){
            $currentConnexions[$i] = array ("type" => $cnx->type, "port" => $cnx->attributes->serial_port_com, "speed" => $cnx->attributes->serial_speed_com);
        }else if($cnx->type == "MAVLINK_UDP"){
            $currentConnexions[$i] = array ("type" => $cnx->type, "host" => $cnx->attributes->host, "port" => $cnx->attributes->port);
        }else{
            $currentConnexions[$i] = $cnx->children();
        }
        $i++;
    }
?>
<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-lock fa-fw"></i><?php echo _("Configure Connexions"); ?></div>
        <div class="panel-body" id="xonomy_editor">
      			<table class="table table-responsive table-striped">
                      <tr>
                        <th><?php echo _("Type"); ?></th>
                        <th><?php echo _("Value1"); ?></th>
                        <th><?php echo _("Value2"); ?></th>
                        <th><?php echo _(""); ?></th>
                        <th></th>
                      </tr>
<?php 
for($i=0; $i < sizeof($currentConnexions);$i++){
?>
          			<tr>            
<?php 
if($currentConnexions[$i]["type"] == "MAVLINK_SERIAL"){
    echo("<td>Serial Port</td><td>".$currentConnexions[$i]['port'] . "</td><td>" . $currentConnexions[$i]['speed'] . "</td>");
}else if($currentConnexions[$i]["type"] == "MAVLINK_UDP"){
    echo("<td>Web</td><td>".$currentConnexions[$i]['host'] . "</td><td>" . $currentConnexions[$i]['port'] . "</td>");
}
?>
                		<td>
                			<form method="POST" action="?page=configurator_modif" name="conf_form<?php echo($i);?>">
      							<input type="hidden" value="<?php echo($i);?>" name="idCnx"/>
                				<input type="submit" name="deleteCnx" value="Delete" class="col-md-6 btn btn-warning"/>
                			</form>
                		</td>
                	</tr>
<?php 
}
?>
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
<?php 
                            for($i=0; $i < sizeof($currentSerial);$i++){
                                echo("<option value=\"" . $i . "\">" . $currentSerial[$i] . "</option>");
                            }
?>
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
                        		function toogleForm(){
                        			if(document.getElementById('serialOpt').selected == 'selected'){
                        				document.getElementById("hostAdd").style.dysplay='none';
                            			document.getElementById("hostPortAdd").style.dysplay='none';
                            			document.getElementById("serialPortAdd").style.dysplay='block';
                            			document.getElementById("serialSpeedAdd").style.dysplay='block';
                        			}else{
                        				document.getElementById("hostAdd").style.dysplay='block';
                            			document.getElementById("hostPortAdd").style.dysplay='block';
                            			document.getElementById("serialPortAdd").style.dysplay='none';
                            			document.getElementById("serialSpeedAdd").style.dysplay='none';
                        			}
                    			}
                        		toogleForm();
                    		</script> 
                    	</form>
                  	</tr>
        	</table>
        </div>
      </div>
    </div>
</div>
<?php 
}
?>