<?php
function isMobile() {
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
?>
<!-- get lat long  ---->
<script>
	function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: -34.397, lng: 150.644},
			zoom: 19
		});
		var infoWindow = new google.maps.InfoWindow({map: map});
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};
				document.getElementById("lat").innerHTML = pos['lat'];
				document.getElementById("long").innerHTML = pos['lng'];
				infoWindow.setPosition(pos);
				infoWindow.setContent('ទីនេះ');
				map.setCenter(pos);
			}, function() {
				handleLocationError(true, infoWindow, map.getCenter());
			});
		} else {
			handleLocationError(false, infoWindow, map.getCenter());
		}
	}

	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ?
			'Error: The location service failed.' :
			'Error: Your browser doesn\'t support location.');
	}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB5kKVl8qsGJYhGagFFEH1dE-LPSoD01bg&callback=initMap"></script>


<!-- end get lat long -->

<?php								
	echo form_open_multipart("quotes/insert_fields_check")
?>
<div class="box">
	<div class="box-header">
		<h2 class="blue"><i
			class="fa-fw fa fa-barcode"></i><?= lang('fields_check') ; ?>
		</h2>
	</div>
	<div class="box-content">
		<div class="row">
			<div class="col-lg-12">
				<div>
					<div class="modal-body">		           
						<!-- Fields Check -->
						<div class="col-md-6 col-sm-6">										
							<div class="form-group">
								<?= lang("applicant", "applicant"); ?>
								<?php
								$app[(isset($_POST['applicant']) ? $_POST['applicant'] : '')] = (isset($_POST['applicant']) ? $_POST['applicant'] : '');
								
								
								if(is_array($ref)):
								foreach($ref  as $ref_id): 
								$app[$ref_id->id] = $ref_id->reference_no;
								endforeach;
								endif; 
								
								
								echo form_dropdown('applicant', $app, (isset($_POST['applicant']) ? $_POST['applicant'] : ''), 'id="applicant" placeholder= ""  ' . $this->lang->line("select") . ' ' . $this->lang->line("applicant") . '" class="form-control input-tip select" style="width:100%;"');
								?>
							</div><br/>										
						</div>
						<div class="col-md-12 col-sm-12">											
							<p>ឯកសារយោងនៃអាសយដ្ឋានបច្ចុប្បន្ន</p>
						</div>			
						<div class="row">
						
							<div class="col-md-12 col-lg-12">
								<div class="col-md-3 col-sm-6">
									<input type="checkbox" name="fc_id_card" id="id_card">អត្តសញ្ញាណប័ណ្ណ
								</div>	
								<div class="col-md-4 col-sm-6">
									<input type="checkbox" name="fc_family_book" id="family_book">សៀវភៅគ្រួសារ 
								</div>
								<div class="col-md-3 col-sm-6">
									<input type="checkbox" name="fc_staying_book" id="staying_book">សៀវភៅស្នាក់នៅ
								</div>
								<div class="col-md-3 col-sm-6">
									<input type="checkbox" name="fc_water_invoice" id="water_invoice">វិក័យប័ត្រទឹក
								</div>
								<div class="col-md-4 col-sm-6">
								<input type="checkbox" name="fc_electricity_invoice" id="electricity_invoice">វិក័យប័ត្រប្រើប្រាស់អគ្គិសនី
								</div>
								<div class="col-md-4 col-sm-6">		
									<input type="checkbox" name="fc_check_property" id="check_property">លិខិតបញ្ជាក់អចលនទ្រព្យ
								</div>
								<div class="col-md-7 col-sm-6">		
									<input type="checkbox" name="fc_check_landlord" id="check_landlord">លិខិតបញ្ជាក់ពីអាជ្ញាធរ(មេភូមិ, ចៅសង្កាត់/មេឈំ)
								</div>
								<div class="col-md-5">	
									<div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">	
									<input type="checkbox" name="fc_other" id="other">ផ្សេងៗ
									</div>
									<div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
										<?= form_input('fc_other_textbox',(isset($_POST['fc_other_textbox']) ? $_POST['fc_other_textbox'] : ''), 'class="form-control tip" id="other_textbox"') ?>
									</div>
								</div>
								<div>
										<div class="col-md-4"><p>អាសយដ្ឋាន​បច្ចុប្បន្នអ្នកស្នើសុំ</p></div>
										<div class="col-md-8"><?= form_input('fc_current_address',(isset($_POST['fc_current_address']) ? $_POST['fc_current_address'] : ''), 'class="form-control tip" id="current_address"') ?></div>
								</div>
								<div>
										<div class="col-md-4"><p>លេខទូរសព្ទអ្នកស្នើសុំ</p></div>
										<div class="col-md-8"><?= form_input('fc_phone_number',(isset($_POST['fc_phone_number']) ? $_POST['fc_phone_number'] : ''), 'class="form-control tip" id="phone_number"') ?></div>
								</div>
							</div>
						</div>			
						<div class="row">
							<br/>
							<!--
							<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d690.9392807942934!2d104.91320950409512!3d11.584597304753792!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xed6f304ce6b0ced0!2sCloudNET+Cambodia!5e0!3m2!1sen!2skh!4v1471332406301" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
							-->
				
							Latitude  : <span id="lat"></span><br/><input type="hidden" name="latitude_" id="latitude_" value="" />
							Longitude: <span id="long"></span><input type="hidden" name="longtitute_" id="longtitute_" value="" />
							<div id="map" style="width:100%; height:300px;"></div>
							<br/>
						</div>
												
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<p>បរិយាយកាសការងារ</p>
								<div class="col-md-2">
									<input type="checkbox" name="fc_business1" id="business1">ជំនួញ
								</div>
								<div class="col-md-2">
									<input type="checkbox" name="fc_company1" id="company1">ក្រុមហ៊ុន
								</div>
								<div class="col-md-2">
									<input type="checkbox" name="fc_other1" id="other1">ផ្សេងៗ
								</div>
								<div>
									<div class="col-md-2"><p>ឈ្មោះ</p></div>
									<div class="col-md-3"><?= form_input('fc_name',"", 'class="form-control tip" id="name"') ?></div>
								</div>
							</div>	
							<div class="col-md-12 col-lg-12">
								<div class="col-md-2">
									<input type="checkbox" name="fc_business2" id="business2">ជំនួញ
								</div>
								<div class="col-md-2">
									<input type="checkbox" name="fc_company2" id="company2">ក្រុមហ៊ុន
								</div>
								<div class="col-md-2">
									<input type="checkbox" name="fc_other2" id="other2">ផ្សេងៗ
								</div>
								<div>
									<div class="col-md-2"><p>លេខទូរសព្ទ</p></div>
									<div class="col-md-3"><?= form_input('fc_phone',(isset($_POST['fc_phone']) ? $_POST['fc_phone'] : ''), 'class="form-control tip" id="name"') ?></div>
								</div>
							</div>	
							<div class="col-md-12 col-lg-12">
								<div class="col-md-2">
									<input type="checkbox" name="fc_business3" id="business3">ជំនួញ
								</div>
								<div class="col-md-2">
								<input type="checkbox" name="fc_company3" id="company3">ក្រុមហ៊ុន
								</div>
								<div class="col-md-2">
									<input type="checkbox" name="fc_other3" id="other3">ផ្សេងៗ
								</div>
								<div>
									<div class="col-md-2"><p>អាសយដ្ឋាន​បច្ចុប្បន្ន</p></div>
									<div class="col-md-3"><?= form_input('fc_address',(isset($_POST['fc_address']) ? $_POST['fc_address'] : ''), 'class="form-control tip" id="current_address"') ?></div>
								</div>
							</div>	
							<div class="col-md-12 col-lg-12">
								<div class="col-md-2">
									<input type="checkbox" name="fc_business4" id="business4">ជំនួញ
								</div>
								<div class="col-md-2">
									<input type="checkbox" name="fc_company4" id="company4">ក្រុមហ៊ុន
								</div>
								<div class="col-md-2">
									<input type="checkbox" name="fc_other4" id="other4">ផ្សេងៗ
								</div>
								<div class="col-md-12" style="padding-top:10px;">
									<div class="col-md-3"><p>ម៉ោងធ្វើកា​រពី</p></div>
									<div class="col-md-2"><?= form_input('fc_start_time',(isset($_POST['fc_start_time']) ? $_POST['fc_start_time'] : ''), 'class="form-control tip" id="start_time"') ?></div>
									<div class="col-md-1"><p>ដល់</p></div>
									<div class="col-md-2"><?= form_input('fc_end_time',(isset($_POST['fc_end_time']) ? $_POST['fc_end_time'] : ''), 'class="form-control tip" id="end_time"') ?></div>
								</div>
							</div>
								<div class="col-md-12 col-lg-12">
									<div class="col-md-12" style="padding-top:10px;">
										<div class="col-md-3"><p>ធ្វើការប៉ុន្មានថ្ងៃក្នុងមួយសប្តាហ៍?</p></div>
										<div class="col-md-9"><?= form_input('hours',(isset($_POST['hours']) ? $_POST['hours'] : ''), 'class="form-control tip" id="hours"') ?></div>
									</div>
								</div>
							</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<br/>
							<!--<div id="map-canvas" style="height:100%;width:100%"></div>-->
								<!--
								<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d690.9392807942934!2d104.91320950409512!3d11.584597304753792!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xed6f304ce6b0ced0!2sCloudNET+Cambodia!5e0!3m2!1sen!2skh!4v1471332406301" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
							-->
							<br/>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<p>សេចក្តីសំរេច:</p>
							<div class="col-md-3">
								<input type="checkbox" name="fc_evaluate" id="evaluate">ចុះវាយតំលៃ
							</div>
							<div class="col-md-3">
								<input type="checkbox" name="fc_none_evaluate" id="none_evaluate">មិនចុះវាយតំលៃ
							</div>
						</div>
					</div>
				</div><br/>
				<input type="submit" class="btn btn-primary" value="<?=lang('submit')?>" name="submitQoute" />
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>