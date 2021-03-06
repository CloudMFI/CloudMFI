<div class="clearfix"></div>
<?= '</div></div></div></div></div>'; ?>
<div class="clearfix"></div>
<footer>
<a href="#" id="toTop" class="blue" style="position: fixed; bottom: 30px; right: 30px; font-size: 30px; display: none;">
    <i class="fa fa-chevron-circle-up"></i>
</a>

    <p style="text-align:center;">&copy; <?= date('Y') . " " . $Settings->site_name; ?> (v<?= $Settings->version; ?>
        ) <?php if ($_SERVER["REMOTE_ADDR"] == '127.0.0.1') {
            echo ' - Page rendered in <strong>{elapsed_time}</strong> seconds';
        } ?></p>
</footer>
<?= '</div>'; ?>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>
<div id="modal-loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
	<?php foreach($send_dates as $send_date) { ?>
	<input type="hidden" name="send_date[]" class="send_date" value="<?= $send_date->send_date; ?>" id="<?= $send_date->id; ?>" />
	<?php } ?>
</div>
<div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>
<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code); ?>
<script type="text/javascript">
var dt_lang = <?=$dt_lang?>, dp_lang = <?=$dp_lang?>, site = <?=json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats))?>;
var lang = {paid: '<?=lang('paid');?>', pending: '<?=lang('pending');?>', pending: '<?=lang('pending');?>', completed: '<?=lang('completed');?>',  processing: '<?=lang('processing');?>', ordered: '<?=lang('ordered');?>', received: '<?=lang('received');?>', partial: '<?=lang('partial');?>', sent: '<?=lang('sent');?>', r_u_sure: '<?=lang('r_u_sure');?>', due: '<?=lang('due');?>', returned: '<?=lang('returned');?>', transferring: '<?=lang('transferring');?>', active: '<?=lang('active');?>', inactive: '<?=lang('inactive');?>', free: '<?=lang('free');?>', book: '<?=lang('book');?>', busy: '<?=lang('busy');?>', unexpected_value: '<?=lang('unexpected_value');?>', select_above: '<?=lang('select_above');?>', confirmed: '<?=lang('confirmed');?>', not_yet: '<?=lang('not_yet');?>', '1': '<?= lang('no')?>', '0': '<?=lang('yes')?>', 'applicant': '<?=lang('applicant')?>', 'approved': '<?=lang('approved')?>', 'activated': '<?=lang('activated')?>','rejected': '<?=lang('rejected')?>','approved_condition': '<?=lang('approved_condition')?>','pending_po': '<?=lang('pending_po')?>', 'registered': '<?= lang('registered')?>', 'draft': '<?= lang('draft')?>', 'disburse': '<?= lang('disburse')?>' };
</script>
<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/core.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/perfect-scrollbar.min.js"></script>
<?= ($m == 'purchases' && ($v == 'add' || $v == 'edit' || $v == 'purchase_by_csv')) ? '<script type="text/javascript" src="' . $assets . 'js/purchases.js"></script>' : ''; ?>
<?= ($m == 'purchases' && ($v == 'add_purchase_return')) ? '<script type="text/javascript" src="' . $assets . 'js/purchases_return.js"></script>' : ''; ?>
<?= ($m == 'transfers' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/transfers.js"></script>' : ''; ?>
<?= ($m == 'sales' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/sales.js"></script>' : ''; ?>
<?= ($m == 'sales' && ($v == 'add_return' )) ? '<script type="text/javascript" src="' . $assets . 'js/return_sales.js"></script>' : ''; ?>
<?= ($m == 'quotes' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/quotes.js"></script>' : ''; ?>

<script type="text/javascript" charset="UTF-8">var r_u_sure = "<?=lang('r_u_sure')?>";
    <?=$s2_file_date?>
    $.extend(true, $.fn.dataTable.defaults, {"oLanguage":<?=$dt_lang?>});
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
    $(window).load(function () {
        $('.mm_<?=$m?>').addClass('active');
        $('.mm_<?=$m?>').find("ul").first().slideToggle();
        $('#<?=$m?>_<?=$v?>').addClass('active');
        $('.mm_<?=$m?> a .chevron').removeClass("closed").addClass("opened");
    });
</script>

<script>
/*
    function removeCommas(str) {
        while (str.search(",") >= 0) {
            str = (str + "").replace(',', '');
        }
        return Number(str);
    };
	*/

	$(document).ready(function () {
		/*
            $('input.number-sp').keyup(function(event) {
              // skip for arrow keys
              if(event.which >= 37 && event.which <= 40) return;

              // format number
              $(this).val(function(index, value) {
                return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                ;
              });
            }).bind('change', function(){
                
            });
			*/
                    $('.email').click(function () {
						return false;
                        var email = prompt("<?= lang("email_address"); ?>", "<?= isset($customer->email)?$customer->email:'' ?>");
                        if (email != null) {
                            $.ajax({
                                type: "post",
                                url: "<?= site_url('reports/email_receipt') ?>",
                                data: {<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email},
                                dataType: "json",
                                success: function (data) {
                                    alert(data.msg);
                                },
                                error: function () {
                                    alert('<?= lang('ajax_request_failed'); ?>');
                                    return false;
                                }
                            });
                        }
                        return false;
                    });
                }); 
	<?php if (!isset($pos_settings->java_applet)) { ?>
        /* $(window).load(function () {
            window.print();
        }); */
    <?php } ?>
	
	setInterval(function(){
		var current_datetime = moment().format('DD/MM/YYYY h:m');
		var send_date = '';
		var ids = '';
		var help = false;
			var i = 0;
		$('.send_date').each(function() {
			send_date = moment($(this).val()).format('DD/MM/YYYY h:m');
			if(current_datetime == send_date) {
				if(i == 0) {
					ids = $(this).attr('id');
				}else {
					ids += '___'+ $(this).attr('id');
				}
				help = true;
				i += 1;
			}
		});
		//alert(ids); return false;
		if(help) {
			help = false;
			$.ajax({
					type: "get",
					async: true,
					url: "<?= site_url('system_settings/send_sms') ?>/"+ ids
				});
		}
	}, 61000);
	
</script>

</body>
</html>
