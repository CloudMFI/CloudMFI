<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <title><?php echo $this->lang->line("invoice") . " " . $inv->reference_no; ?></title>
    <style type="text/css">
         body {
		
		/*	height: 842px;*/
        width: 595px;
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
            background: #FFF;
			font-family: "Times New Roman", Times, serif;
			font-size:9pt;
        }	
	
		table{
			border-collapse: collapse;
		}
		td{
			text-align:center;	
			border-bottom:1px dotted #000000;
			line-height: 20px; 
			border-right:solid 1px black;
			border-left:solid 1px black;
			height:28px;
		}	
	
		table {
			font-family: 'Khmer OS'; 
			color: #000000;
			font-size: 12px;
		}
		.logo img{
			width:250px;
		}
		
				@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
    </style>
</head>

<body>
	<div class="container">
			<div class="no-print" style="width:100%"><div style="float:right;">
			<a href="<?php echo base_url().'sales/export_certify_latter/0/1/1'; ?>" style="text-decoration:none;border:1px solid #3498db;background-color:#3498db;color:white;padding:5px;border-radius:5px;">Export Excel<a/>
			</div><br/></div>
			<center>				
				<div class="logo"><img src="<?php echo base_url().'assets/uploads/other/logo-hired-purchase301.png';?>"/></div>
				<p style="font-size:19px;">លិខិតបញ្ជាក់​ភតិសន្យា​ហិរញ្ញវត្ថុ​​ទោចក្រយានយន្ត	</p>
			</center>
			<p style="font-size:13px;">សូម​បញ្ជាក់​ថា  ទោចក្រយានយន្ត  ដែលមាន​​​ព័ត៌មាន​លម្អិត​ដូច​ខាង​ក្រោម ត្រូវ​បាន​ជួល​ពី ជីអិល ហ្វាយនែន ភីអិលស៊ី 										
			(<b>GL FINANCE PLC.,</b>)   ដែល​ជា​ក្រុមហ៊ុន​មហាជនទទួល​ខុស​ត្រូវ​មាន​កម្រិត បង្កើត​ឡើង  និង  ស្ថិត​នៅ​ក្រោម​ច្បាប់​នៃ​										
			ព្រះរាជាណាចក្រ​កម្ពុជា  មាន​លេខ​ចុះ​បញ្ជី​លេខ  <br/> <b>Co. ០៦៩៧ E / ២០១២​</b>  ចុះ​ថ្ងៃទី​​​ <b> ១៤​​  </b>ខែ <b> មិនា</b>  ឆ្នាំ <b> ២០១២ </b>​មាន										
			​ការិយាល័យ​ស្ថិត​នៅអគារលេខ  ២៧០-២៧៤  មហា​វិថីកម្ពុជា​ក្រោម  សង្កាត់​មិត្តភាព  ខណ្ឌ ​៧ មករា  រាជធានី​ ភ្នំពេញ 										
			ព្រះរាជាណាចក្រ​កម្ពុជា      ស្រប​តាមកិច្ចព្រមព្រៀង​ភតិសន្យា ​     ហិរញ្ញវត្ថុលេខ  <?=$report->reference_no?>										
			ចុះ​ថ្ងៃទី    05/08/2016  ។  កិច្ចព្រមព្រៀង​ភតិសន្យាហិរញ្ញវត្ថុ​​នេះ​មាន​រយៈ​ពេល   <?=number_format($report->term)?>   ខែ  										
			គិត​ចាប់​ពី    <?= $this->erp->hrsd($report->due_date)?>   ដល់    
			<?php 
			$term = number_format($report->term);
			echo date('d-m-Y', strtotime("+".$term." months", strtotime($report->due_date)));
			?>   ។			</p>
			<br/>
			<div style="border:1px solid black;">
			<p  style="line-height:190%;font-size:13px;">ឈ្មោះអ្នកជួល​ ៖​​ <?=$report->customer_name_other ?><br/>
			លេខអត្តសញ្ញាប័ណ្ណ/លិខិតឆ្លងដែន ៖ <?=$report->gov_id ?> ចុះថ្ងៃទី <?= $this->erp->hrsd($report->date) ?>	<br/>
			លេខទូរស័ព្ទ ៖​ <?=$report->phone1 ?></p>
			</div>
			<br/>
			<div style="border:1px solid black;">
			<p  style="line-height:190%;font-size:13px;">លេខអត្តសញ្ញាណអ្នកជួល ៖<?=$report->reference_no?>
			<span style="padding-left:100px;">លេខគណនី​ ៖  <?=$report->account_number?></span></p>
			</div>
			<br/>
			<div style="border:1px solid black;">
			<p  style="line-height:190%;font-size:13px;">លេខម៉ាស៊ីន ៖​ <?=$report->engine ?>​ លេខតួ ៖ <?=(($report->frame)!='' ? $report->frame : 'N/A') ?><br/>
				ស្លាកលេខ ៖ 	</p>
			</div>
			<br/>
			<div style="border:1px solid black;">
			<center><b style="font-size:16px;">ព័ត៌មានអំពីទោចក្រយានយន្ត</b></center>
			<p  style="line-height:190%;font-size:13px;">		
				<?= $report->product_name?>, ម៉ូដែល ៖ <?=$report->type_name?>  ប្រភេទ ៖ <?=$report->subcategory_name?> CC ៖ <?= (($report->power )!='' ? $report->power  : 'N/A')?> ពណ៌ ៖ <?php if($report->name_other!=""){echo $report->name_other;}else{echo $report->name;}?>	<br/>									
				ឆ្នាំផលិត ៖ <?=(($report->product_year )!='' ? $report->product_year  : 'N/A')?> ចំនួន ៖​ <?= number_format($report->quantity);?> គ្រឿង		
			</p>
			</div>
			<br/>
		<center>
		<p style="font-size:9px;">
			ជីអិល ហ្វាយនែន ភីអិលស៊ី អាគារលេខ ២៧០-២៧៤ មហាវិថីកម្ពុជាក្រោម សង្កាត់មិត្តភាព ខណ្ឌ៧មករា រាជធានីភ្នំពេញ ព្រះរាជាណាចក្រកម្ពុជា <br/>										
ទូរស័ព្ទលេខ ០២៣ ៩៩០ ៣៣០ ទូរសារលេខ ០២៣ ៩៩០ ៣២៧										<br/>
អ្នកជួលត្រូវរក្សាទុកលិខិតបញ្ជាក់នេះនៅជាប់នឹងទោចក្រយានយន្តដែលបានជួល និង បិទស្លាក “បានជួលពី ជីអិល ហ្វាយនែន ភីអិលស៊ី” នៅលើ			<br/>							
ទោចក្រយានយន្តដែលបានជួលនៅគ្រប់ពេល។ ស្លាកសម្គាល់នេះ មិនត្រូវដកចេញ ឬធ្វើឱ្យមើលមិនស្គាល់ ដោយជនណាម្នាក់ក្រៅពី ជីអិល ហ្វាយនែន		<br/>								
ភីអិលស៊ី ឬអ្នកតំណាងរបស់ ជីអិល ហ្វាយនែន ភីអិលស៊ី ឡើយ។		<br/>
		</p>
		</center><br/>
		<div style="page-break-after: always;"></div>
		<div style="border:1px solid black;margin-top:10px;">
			<p  style="line-height:190%;font-size:12px;">		
លិខិតបញ្ជាក់នេះមានប្រសិទ្ធភាព ចាប់ពីកាលបរិច្ឆេទប្រគល់ទោចក្រយានយន្តដែលបានជួល ៖ <?=$report->approved_date ?>	
			</p>
		<br/>

			<p style="padding-left:80px;font-size:14px;"><b>អ្នកជួល<span style="padding-left:80px;">ក្រុមហ៊ុនភតិសន្យាហិរញ្ញវត្ថុ ជី អិល ហ្វាយនែន ភីអិលស៊ី</span></b></p>
			<p style="padding-left: 73px; font-size: 14px; margin-bottom: 0px;">ស្នាមមេដៃ<span style="padding-left:220px;">តាមរយៈតំណាងស្របច្បាប់</span></p>
			<img style="padding-left:300px;" src="<?php echo base_url().'assets/uploads/other/gl_tra.png';?>"/>
			<p style="font-size:14px;margin-bottom:0;margin-top:0;">ឈ្មោះ ៖ <?=$report->customer_name_other ?><span style="padding-left:180px;"><b>ឈ្មោះ ៖</b></span><span><b style="padding-left:20px;"><?=$report->creater_name?></b></span></p>
			<p style="font-size:14px;padding-left:270px;margin-top:0;"><b>មុខតំណែង ៖ ប្រធាននាយកប្រតិបត្តិ ប្រតិបត្តិការ</b></p>
			</div>
			
			<center>
			<p style="font-size:9px;line-height:190%;">
			ជីអិល ហ្វាយនែន ភីអិលស៊ី អាគារលេខ ២៧០-២៧៤ មហាវិថីកម្ពុជាក្រោម សង្កាត់មិត្តភាព ខណ្ឌ៧មករា រាជធានីភ្នំពេញ ព្រះរាជាណាចក្រកម្ពុជា 	<br/>								
ទូរស័ព្ទលេខ ០២៣ ៩៩០ ៣៣០ ទូរសារលេខ ០២៣ ៩៩០ ៣២៧								<br/>	
អ្នកជួលត្រូវរក្សាទុកលិខិតបញ្ជាក់នេះនៅជាប់នឹងទោចក្រយានយន្តដែលបានជួល និង បិទស្លាក “បានជួលពី ជីអិល ហ្វាយនែន ភីអិលស៊ី” នៅលើ		<br/>							
ទោចក្រយានយន្តដែលបានជួលនៅគ្រប់ពេល។ ស្លាកសម្គាល់នេះ មិនត្រូវដកចេញ ឬធ្វើឱ្យមើលមិនស្គាល់ ដោយជនណាម្នាក់ក្រៅពី ជីអិល ហ្វាយនែន		<br/>							
ភីអិលស៊ី ឬអ្នកតំណាងរបស់ ជីអិល ហ្វាយនែន ភីអិលស៊ី ឡើយ។
			</p>
			</center>
</body>

</html>









