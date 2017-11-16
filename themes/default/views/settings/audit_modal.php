<style>
	hr{
	border-color:#333;
	
	}
	@media print {
		.no-print {
			display:none !important;
		}
	}
</style>
<div class="modal-dialog modal-lg no-modal-header" style="width:100%;">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			
			
			
			
			<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<?php
	$address = '';
	$address.=$biller->address;
	$address.=($biller->city != '')? ', '.$biller->city : '';
	$address.=($biller->postal_code != '')? ', '.$biller->postal_code : '';
	$address.=($biller->state != '')? ', '.$biller->state : '';
	$address.=($biller->country != '')? ', '.$biller->country : '';
?>
<br class="no-print">
<br class="no-print">
<br class="no-print">
<br class="no-print">
<center>
	<div class="row" style="padding:5px; padding-left:10px; padding-right:10px;">
		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-left"><?=lang('Current')?></span>
				</div>
				<div class="panel-body">
					
				</div>
			</div>
		</div>
	</div>
</center>
			
			
			
			
		</div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
    });
</script>
