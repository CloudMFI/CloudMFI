<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= lang('view_kitchen') . " | " . $Settings->site_name; ?></title>
    <base href="<?= base_url() ?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
	<meta http-equiv="refresh" content="300">
    <meta http-equiv="pragma" content="no-cache"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= $assets ?>styles/theme.css" type="text/css"/>
    <style type="text/css">
		.list-products{
			background-color:#f39c12;
			padding-top:15px;
			
		}
		.list-products:hover{
			background:#EC971F;
			cursor:pointer;
		}
		.panel-body{
			border:1px solid #bdc3c7;
			background-color:#ecf0f1;	
		}
		.modal-dialog{
			width:50%;
		}
		.modal-content{
			border-radius:0 !important;
		}
		.btn{
			border-radius:0 !important;
		}
		.modal-footer{
			border:0;
			text-align:center;
		}
		.btn-default{
			opacity: 0;
		}
		.btn-primary{
			position:absolute;
			right:20px;
		}
    </style>
</head>
<body>
<div class="col-sm-12 content">
	<?php if ($page) { ?>
		<div class="modal-footer" style="padding:0;">
			<div class="page_con"><?= $page ?></div>
		</div>
	<?php } ?>
	<div class="panel panel-default no-radious">
		<div class="panel-body">
			<?php
				$i=1;
				foreach($data as $kit)
				{
			?>
				<div class="list-products col-md-2 col-xs-2 col-sm-2" style="padding:0;border:3px solid #bdc3c7;">
					<div style="padding:10px;">
						<i style="text-align:right;position:absolute; background-color:#2c3e50;color:#ecf0f1;min-width:30px;padding-right:8px;font-size:14pt;font-weight:bold;">
							<span id="<?= base_url()."pos/complete_kitchen/".$kit->idd?>" class="<?= $i?>" text="<?= $kit->image;?>" name="<?=$kit->table?>" qty="<?=number_format($kit->quantity);?>"><?= $i;?></span>
						</i>
						<!--
						<a class="btn-danger clear_suspend" style="position:absolute;top:100px;" hrefs="<?= base_url()."pos/complete_kitchen/".$kit->idd?>" id="clear_suspend">clear</a>
						-->
						<img src="<?= base_url().'assets/uploads/'.$kit->image;?>"  class="img-thumbnail" style=" width:100% !important;height:200px !important;" >
					</div>
					<div class="name" style="text-align:center;color:white;"><p><?= $kit->name?></p></div>
					<div class="tnq" >
						<div style="float:left;background-color:#ac2925;color:white;width:50%;padding:2px;border-right:1px solid #ecf0f1;font-size:9pt;"><span><?= lang('table'); ?>: </span><span style="font-size:18pt;font-weight:bold"><?= number_format($kit->table)?> </span></div>
						<div style="float:right;background-color:#2c3e50;color:white;width:50%;text-align:right;padding:2px;font-size:9pt;" ><span><?= lang('qty'); ?>: </span><span style="font-size:18pt;font-weight:bold"><?= number_format($kit->quantity)?> </span> </div>
					</div>
				</div>
			<?php
				$i++;
				}
			?>
		</div>
	</div>
	<input type="text" id="Onclick" style="opacity: 1;" />
  
</div>
<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script src="https://raw.githubusercontent.com/makeusabrew/bootbox/master/bootbox.js"></script>
<script>
	/*
	$('.clear_suspend').on('click', function (e) {
		var hrefs = $(this).attr("hrefs");        
		bootbox.confirm("<?= $this->lang->line('leave_alert') ?>", function (gotit) {
			if (gotit == true) {
				window.location.href = hrefs;
			} 
		});
		return false;
	});
	*/
	var site = "<?=base_url().'assets/uploads/'?>";
	document.onkeydown=function(evt){
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if(keyCode == 13)
        {
            var val = $('#Onclick').val();
			var id  = $('.'+val).attr('id');
			var img = $('.'+val).attr('text');
			var table  = $('.'+val).attr('name');
			var qty = $('.'+val).attr('qty');
			//$("#showmodal").trigger('click');
			//var image = '<img src="'+site + img+'" width="100%"/>';
			//$(image).appendTo('#text');
			var html = '<div class="list-products"><div style="padding:10px;"><i style="text-align:right;position:absolute; background-color:#2c3e50;color:#ecf0f1;min-width:30px;padding-right:8px;font-size:14pt;font-weight:bold;"><span>'+val+'</span></i><img src="'+site+img+'"  class="img-thumbnail" style=" width:100% !important;height:350px !important;" ></div><div class="tnq" ><div style="float:left;background-color:#ac2925;color:white;width:50%;padding:2px;border-right:1px solid #ecf0f1;font-size:9pt;"><span><?= lang('table'); ?>: </span><span style="font-size:18pt;font-weight:bold">'+table+'</span></div><div style="float:right;background-color:#2c3e50;color:white;width:50%;text-align:right;padding:2px;font-size:9pt;" ><span><?= lang('qty'); ?>: </span><span style="font-size:18pt;font-weight:bold">'+qty+'</span> </div></div></div><br/>';
			
			bootbox.confirm("<?= $this->lang->line('leave_alert') ?><br/><br/>"+html, function (gotit) {
				if (gotit == true) {
					window.location.href = id;
				}else{
					window.location.reload();
				}
			});
			$('#Onclick').val('');
        }
		if(keyCode == 27){
			$('#Onclick').focus();
		}
    }
	$(document).ready(function () {
		$('#Onclick').focus();
		$('body').click(function(){
			$('#Onclick').focus();
		});
		
		$("#Onclick").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A, Command+A
				(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
        window.setInterval(function () {
            window.location.reload();
        }, 300000);
    });
</script>
</body>
</html>