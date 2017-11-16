
    <style type="text/css">

	
		.table table{
			border-collapse: collapse;
		}
	
		.table table {
			font-family: 'Khmer OS'; 
			color: #000000;
			font-size: 12px;
		}
		
		.put border td{border:1px solid black;}
		.text-info{
			margin-left:35px;
		}
		.t_c{text-align:center;}
		.t_r{text-align:right;}
		.td_dashed{border:1px dashed black;}
		.color_green{color:#009900;}
		.color_blue{color:#3366CC;}
		.row-title td{color:white;background-color:#009900;padding-top:5px;padding-bottom:5px;text-align:center;}
		.row-data td{text-align:center;}
		.padding_l_r_5{padding-left:5px;padding-right:5px;}
    </style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('payment_schedule'); ?></h4>
		</div>
        <div class="modal-body">
			<div class="row">
			

	<div class="container">
		<center><p style="font-weight:bold;">កាលវិភាគបង់ប្រាក់ប្រចាំខែ Monthly Payment Schedule</p></center>
			<table width="100%" style="font-size:11px;">
				<tr>
					<td width="50%">ឈ្មោះអតិថិជន : <span class="color_blue" id="name111"></span></td>
					<td width="50%"></td>
				</tr>
				<tr>
					<td>អាសយដ្ឋាន :  <span class="color_blue">N/A</span></td>
					<td>Dealer Number :  <span class="color_blue">N/A</span></td>
				</tr>
				<tr>
					<td>លេខទួរស័ព្ទ​ : <span class="color_blue" id="phone"></span></td>
					<td>LID Number : <span class="color_blue"> N/A</span></td>
				</tr>
				<tr>
					<td>លេខតូដក្រុមហ៊ុន :  <span class="color_blue">N/A</span></td>
					<td>លេខអតិថិជន :  <span class="color_blue">N/A</span></td>
				</tr>
			</table>
			
			<table  style="margin-top:15px;font-size:11px;border:2px solid black;width:100%;">
				<tr><td height="10px" style="width: 250px;"></td><td class=" padding_l_r_5 "></td><td style=""></td><td class=" padding_l_r_5"></td><td style="width:20px;"></td></tr>
				<tr>
					<td class="t_r">ប្រភេទម៉ូតូ (Motorcycle model)</td>
					<td  class="t_c td_dashed color_blue" id="product"></td>
					<td  class="t_r">ទឹកប្រាក់ភតិសន្យា (lease amount)</td>
					<td  class="t_c td_dashed color_green">N/A</td><td></td>
				</tr>
				
				<tr>
					<td  class="t_r">ឆ្នាំផលិត​ (Year)</td>
					<td  class="t_c td_dashed color_blue" id="year111"></td>
					<td  class="t_r">អត្រាការប្រាក់​ (Interest rate)</td>
					<td  class="t_c td_dashed color_blue" id="interest_rate111"></td><td></td>
				</tr>
				
				<tr>
					<td  class="t_r">កំលាំងម៉ាស៊ីន(CC)</td>
					<td  class="t_c td_dashed color_blue" id="power111"></td>
					<td  class="t_r">ចំនួនវិភាគសង(Number of payments)</td>
					<td  class="t_c td_dashed color_blue" id="term_in_month111"> </td><td></td>
				</tr>
				
				<tr>
					<td  class="t_r">តម្លៃម៉ូតូ(Price)</td>
					<td  class="t_c td_dashed color_green" id="price"></td>
					<td  class="t_r">ប្រាក់ត្រូវបង់ប្រចាំខែ(Monthly instalment amount)</td>
					<td  class="t_c td_dashed color_green">N/A</td><td></td>
				</tr>
				
				<tr>
					<td  class="t_r">អត្រាប្រាក់បង់មុន(Advance payment rate)</td>
					<td  class="t_c td_dashed color_blue" id="advance_payment_rate111"></td>
					<td  class="t_r">ការិយបរិច្ខេទកិច្ចសន្យា(Contract date)</td>
					<td  class="t_c td_dashed color_blue">N/A</td><td></td>
				</tr>
				
				<tr>
					<td  class="t_r">អត្រាប្រាក់បង់មុន(Advance payment)</td>
					<td  class="t_c td_dashed color_green">N/A</td>
					<td  class="t_r">ការិយបរិច្ខេទបង់ប្រាក់ដំបូង(First due date)</td>
					<td  class="t_c td_dashed color_blue">N/A</td><td></td>
				</tr>
				<tr><td height="10px;"></td><td></td><td></td></tr>
			</table>
		
		
		
			<table style="margin-top:15px;font-size:11px;border-collapse:collapse;width:100%;">
			
				
				<?php 
				$k=1;
				
				foreach ($pts as $data) {
					if($k==1){
						echo 	'<tr class="row-title">
					<td>​លរ</td>
					<td>កាលបរិច្ឆេទ</td>
					<td>សរុបប្រាក់ត្រូវបង់</td>
					<td>ប្រាក់ផ្ទេកម្មសិទ្ធិ</td>
					<td>ប្រាក់សេវាកម្ម</td>
					<td>ប្រាក់ធានារ៉ាប់រង</td>
					<td>ប្រាក់ដើមត្រូវបង់</td>
					<td>ការប្រាក់ត្រូវបង់</td>
					<td>សមតុល្យប្រាក់ដើមនៅសល់</td>
				</tr>';	
					}
					/*
					if(date('m', strtotime($data["dateline"]))==01){
						echo 	'<tr class="row-title">
					<td>​លរ</td>
					<td>កាលបរិច្ឆេទ</td>
					<td>សរុបប្រាក់ត្រូវបង់</td>
					<td>ប្រាក់ផ្ទេកម្មសិទ្ធិ</td>
					<td>ប្រាក់សេវាកម្ម</td>
					<td>ប្រាក់ធានារ៉ាប់រង</td>
					<td>ប្រាក់ដើមត្រូវបង់</td>
					<td>ការប្រាក់ត្រូវបង់</td>
					<td>សមតុល្យប្រាក់ដើមនៅសល់</td>
				</tr>';	
						
					}
					*/
				$princ=$this->erp->formatMoney($data["principle"]);
				$interest=$this->erp->formatMoney($data["interest"]);
				
				$total=$princ+$interest;
				
				echo '<tr class="row-data">
					<td class="t_c">'.$k.'</td>
					<td class="t_c">'. $this->erp->hrsd($data["dateline"]).'</td>
					<td class="t_r">'.$total.'</td>
					<td class="t_r">N/A</td>
					<td class="t_r">N/A</td>
					<td class="t_r">N/A</td>
					<td class="t_r">'.$this->erp->formatMoney($data["principle"]).'</td>
					<td class="t_r">'.$this->erp->formatMoney($data["interest"]).'</td>
					<td class="t_r">'.$this->erp->formatMoney($data["balance"]).'</td>
				</tr>';
				
				$k++;
					
					} ?>
			</table>

			<table width="100%" style="font-size:13px;margin-top:30px;">
				<tr>
					<td class="t_c" style="width:50%;">Canadia Bank PLC</td>
					<td class="t_c"  style="width:50%;">ស្នាមមេដៃរបស់អតិថិជន/Lessee</td>
				</tr>
				
				<tr><td height="130px;"></td><td></td></tr>
		
				<tr style="margin-top:150px;">
					<td class="t_c"  style="width:50%;">ឈ្មោះ/Name: ...........................</td>
					<td class="t_c"  style="width:50%;">ឈ្មោះ/Name: ...........................</td>
				</tr>
				<tr>
					<td  class="t_c" >ថ្ងៃខែឆ្នាំ/<?=date('Y-m-d'); ?></td>
					<td  class="t_c" >ថ្ងៃខែឆ្នាំ/<?=date('Y-m-d'); ?></td>
				</tr>
			</table>
			<p style="font-size:9px;margin-top:10px;">សម្គាល់ :ថ្លៃសេវាបន្ថែម 0.55 ដុល្លារ នឹងបូកបញ្ចូលរាល់ការបង់ប្រាក់ប្រចាំខែនៅតាមបញ្ជីរដែលជាដៃគូសហការទទួល ប្រាក់របស់ Canadia Bank PLC ។</p>
	</div>
</div>			
	
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
           </button>
		   <a href="<?=base_url().'Installment_Payment/export_payment_schedule_to_excel/0/1/'.$sale_id;?>">
				<div class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" >
					<i class="fa fa-file-excel-o "></i> <?= lang('Export Excel'); ?>
				</div>
		   </a>
        </div>
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
<script>

$(document).ready(function() {

var product= localStorage.getItem('product');
var dealer= localStorage.getItem('dealer');
var year= localStorage.getItem('year');
var power= localStorage.getItem('power');
var Advance_payment_rate= localStorage.getItem('Advance_payment_rate');
var phone= localStorage.getItem('phone');
var price= localStorage.getItem('price');
var interest_rate= localStorage.getItem('interest_rate');
var term_in_month= localStorage.getItem('term_in_month');
var name= localStorage.getItem('name');
var p_ir=(interest_rate*100)+'%';
$( "#phone" ).text( phone );
$( "#product" ).text( product );
$( "#dealer" ).text( dealer.replace("_", " "));
$( "#year111" ).text( year );
$( "#power111" ).text( power );
$( "#price" ).text( price );
$( "#interest_rate111" ).text( p_ir );
$( "#advance_payment_rate111" ).text( (Advance_payment_rate*100)+'%' );
$( "#term_in_month111" ).text( term_in_month );
$( "#name111" ).text( name );


});



</script>