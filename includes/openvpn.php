<?php 
function DisplayOpenVPNConfig() {

    
    if(CSRFValidate()){
        if ( isset($_POST['StartOpenVPN'])  ) {
            //start OpenVpn
            exec("sudo openvpn --config " . RASPI_OPENVPN_CLIENT_CONFIG);
        }else if( isset($_POST['StopOpenVPN']) ) {
            
        }
        
        if ( isset($_POST['SaveOpenVPNSettings']) ) {
            if ( isset($_POST['openvpn-config'])) {
                if($_FILES['openvpn-config']['error'] != ""){
                    $status->addMessage($_FILES['openvpn-config']['error'] , 'danger');
                }else {
                    //error_reporting(-1);
                    $uploadfile = $uploaddir . $RASPI_OPENVPN_CLIENT_CONFIG;
                    if (move_uploaded_file($_FILES['openvpn-config']['tmp_name'], $uploadfile)) {
                        $status->addMessage('Configuration File uploaded', 'info');
                    } else {
                        $status->addMessage('Configuration File error', 'danger');
                    };
                }
            }
            
            $fileAuth = fopen($RASPI_OPENVPN_AUTH_CONFIG, 'w');
            fwrite($fileAuth, $_POST["openvpn_login"] . "\n");
            fwrite($fileAuth, $_POST["openvpn_pwd"] . "\n");
            fclose($fileAuth);
            
            if($_POST["openvpn_autostart"] == 'true'){
                $fileAuth = fopen($UPLOAD_DIR . "openvpn.autostart", 'w');
                fclose($fileAuth);
            }else{
                unlink($UPLOAD_DIR . "openvpn.autostart");
            }
        
        }
    }
        
	exec( 'cat '. RASPI_OPENVPN_CLIENT_CONFIG, $returnClient );
	exec( 'cat '. RASPI_OPENVPN_AUTH_CONFIG, $returnAuth );
	exec( 'cat '. RASPI_OPENVPN_SERVER_CONFIG, $returnServer );
	exec( 'pidof openvpn | wc -l', $openvpnstatus);

	foreach( $returnClient as $a ) {
	    echo($a . "\n");
	}
	foreach( $returnAuth as $a ) {
	    echo($a . "\n");
	}
	foreach( $returnServer as $a ) {
	    echo($a . "\n");
	}
	
	if( $openvpnstatus[0] == 0 ) {
		$status = '<div class="alert alert-warning alert-dismissable">OpenVPN is not running
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
	} else {
		$status = '<div class="alert alert-success alert-dismissable">OpenVPN is running
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
	}

	// parse client settings
	foreach( $returnClient as $a ) {
		if( $a[0] != "#" ) {
			$arrLine = explode( " ",$a) ;
			$arrClientConfig[$arrLine[0]]=$arrLine[1];
		}
	}

	// parse server settings
	foreach( $returnServer as $a ) {
		if( $a[0] != "#" ) {
			$arrLine = explode( " ",$a) ;
			$arrServerConfig[$arrLine[0]]=$arrLine[1];
		}
	}
	?>
	<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-lock fa-fw"></i> Configure OpenVPN </div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<!-- Nav tabs -->
			<!-- ul class="nav nav-tabs">
				<li class="active"><a href="#openvpnclient" data-toggle="tab">Client settings</a></li>
				<li><a href="#openvpnserver" data-toggle="tab">Server settings</a></li>
			</ul-->
			<!-- Tab panes -->
			<div class="tab-content">
				<form role="form" action="?page=openvpn_conf" method="POST">
				<?php CSRFToken() ?>
				<p><?php echo $status; ?></p>
				<div class="tab-pane fade in active" id="openvpnclient">

					<h4>Client settings</h4>

					<div class="row">
						<div class="form-group col-md-4">
							<label>Select OpenVPN configuration file (.ovpn)</label>
							<input type="file" name="openvpn-config">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Login</label> 
						<input type="text" class="form-control" name="openvpn_login" value="<?php echo htmlspecialchars($returnAuth[0], ENT_QUOTES); ?>" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Password</label> 
						<input type="text" class="form-control" name="openvpn_pwd" value="<?php echo htmlspecialchars($returnAuth[1], ENT_QUOTES); ?>" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Auto start ?</label> 
						<input type="checkbox" class="form-control" name="openvpn_autostart" value="true" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
							<label for="code">Client Log</label>
							<input type="text" class="form-control" id="disabledInput" name="log-append" type="text" placeholder="<?php echo htmlspecialchars($arrClientConfig['log-append'], ENT_QUOTES); ?>" disabled="disabled" />
						</div>
					</div>
				</div>
				<!-- div class="tab-pane fade" id="openvpnserver">
					<h4>Server settings</h4>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Port</label> 
						<input type="text" class="form-control" name="openvpn_port" value="<?php echo htmlspecialchars($arrServerConfig['port'], ENT_QUOTES); ?>" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Protocol</label>
						<input type="text" class="form-control" name="openvpn_proto" value="<?php echo htmlspecialchars($arrServerConfig['proto'], ENT_QUOTES); ?>" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Root CA certificate</label>
						<input type="text" class="form-control" name="openvpn_rootca" placeholder="<?php echo htmlspecialchars($arrServerConfig['ca'], ENT_QUOTES); ?>" disabled="disabled" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Server certificate</label>
						<input type="text" class="form-control" name="openvpn_cert" placeholder="<?php echo htmlspecialchars($arrServerConfig['cert'], ENT_QUOTES); ?>" disabled="disabled" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Diffie Hellman parameters</label>
						<input type="text" class="form-control" name="openvpn_dh" placeholder="<?php echo htmlspecialchars($arrServerConfig['dh'], ENT_QUOTES); ?>" disabled="disabled" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">KeepAlive</label>
						<input type="text" class="form-control" name="openvpn_keepalive" value="<?php echo htmlspecialchars($arrServerConfig['keepalive'], ENT_QUOTES); ?>" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-4">
						<label for="code">Server log</label>
						<input type="text" class="form-control" name="openvpn_status" placeholder="<?php echo htmlspecialchars($arrServerConfig['status'], ENT_QUOTES); ?>" disabled="disabled" />
						</div>
					</div>
				</div-->
				<input type="submit" class="btn btn-outline btn-primary" name="SaveOpenVPNSettings" value="Save settings" />
				<?php
				/*
				if($hostapdstatus[0] == 0) {
					echo '<input type="submit" class="btn btn-success" name="StartOpenVPN" value="Start OpenVPN" />' , PHP_EOL;
				} else {
					echo '<input type="submit" class="btn btn-warning" name="StopOpenVPN" value="Stop OpenVPN" />' , PHP_EOL;
				}
				*/
?>
				</form>
		</div><!-- /.panel-body -->
	</div><!-- /.panel-primary -->
	<!-- div class="panel-footer"> Information provided by openvpn</div-->
</div><!-- /.col-lg-12 -->
</div><!-- /.row -->
<?php
}
?>