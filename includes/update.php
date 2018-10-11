<?php

include_once( 'includes/status_messages.php' );

function DisplayUpdate(){
  $status = new StatusMessages();
  
  if ( isset($_POST['UpdateWebUI']) && CSRFValidate() ) {
      exec( '(cd /var/www/html/ && sudo git pull)' );
  }
  
  
  ?>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-lock fa-fw"></i><?php echo _("Configure Auth"); ?></div>
        <div class="panel-body">
          <p><?php $status->showMessages(); ?></p>
          <form role="form" action="?page=update" method="POST">
            <?php CSRFToken() ?>
            <input type="submit" class="btn btn-outline btn-primary" name="UpdateWebUI" value="<?php echo _("Update Web UI"); ?>" />
          </form>
        </div><!-- /.panel-body -->
      </div><!-- /.panel-default -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
<?php 
}

