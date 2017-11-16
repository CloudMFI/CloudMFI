<link href="https://fonts.googleapis.com/css?family=Moul|Battambang" rel="stylesheet"> 
<style type="text/css">
	.a_four_page,.header_report{
		width:29.7cm;
		//min-height:10cm;
		height:auto;
		margin:0 auto;
		font-size:13px;
		font-family:'Time New Roman';
	}
	@media print{
			 #wrapper{
				 margin:0px auto;
			 }
			#bg{
				 background-color:#a5e2ef !important;
			 }
		}
	.header_report{
		height:100px;
		//border:1px solid red;
	}
	.title{
		text-align: center;
		font-size:14px;
		font-family: 'Moul', Time New Roman;
		width:60%;
		float:left;
		margin-top:5px;
	}
	.tr-color{
		background:#DAEEF3
	}
	.bs-example{
    	//margin: 10px;
    }
	.table > thead:first-child > tr:first-child > th, .table > thead:first-child > tr:first-child > td, .table-striped thead tr.primary:nth-child(2n+1) th 
	{
		background-color: #9e9e9e;
		border-color: #9e9e9e;
		border-top: 1px solid #9e9e9e;
		color: black;
		text-align: center;
		font-family:'Moul';
		font-weight:Normal;
	}
	.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td 
	{
		border: 1px dashed #000;
		font-family: 'Battambang', Time New Roman;
		font-size:12px;
	}
</style>

<div id="wrapper" class="a_four_page">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
			<div class="header_report" style="width:80%">
				<div style="float:left;">
					<?php if ($Settings->logo2) {
						echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px; width:150px;" />';
					} ?>
					<br/>
					<span style="font-family:'Battambang';">ស្នាក់ការកណ្តាល</span>
				</div>
				<div class="title">
					អង្គការ លើកស្ទួយសមត្ថភាពគ្រួសារ<br/>
					<span>ថ្ងៃទី ១៦ ខែ​ កក្កដា ឆ្នាំ២០១៦</span><br/>
					<span>របាយការណ៍ប្រតិបត្តិការសាច់ប្រាក់ប្រចាំថ្ងៃ</span><br/><br/>
					<span style="color:red; padding-left:120px;">KHR : 00</span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<span style="color:red;">របាយការណ៍សាច់ប្រាក់ត្រឹមត្រូវ</span>
					
				</div>	
			</div>
				<span style="font-family:'Moul'; margin:0 0 0 160px"><em>ប្រាក់រៀល KHR</em></span>
			<div class="col-lg-12">
				<div class="col-md-6">
					<div class="bs-example">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>កូដ</th>
									<th colspan="2">ប្រភេទប្រតិបត្តិការ</th>
									<th>ចំនួន</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>COH</td>
									<td>ទឹកប្រាក់ក្នុងដៃ</td>
									<td>Cash on Hand</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>BIB</td>
									<td>ទឹកប្រាក់នៅក្នុងធនាគារ</td>
									<td>Balance in Bank</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>WFB</td>
									<td>ទឹកប្រាក់ដកពីធនាគារ</td>
									<td>Withdrawal from Bank</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>LNR</td>
									<td>ទទួលប្រាក់កម្ចី</td>
									<td>Loan Repay</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>LNF</td>
									<td>កម្រៃសេវាឥណទាន</td>
									<td>Loan Fee</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>PNT</td>
									<td>ប្រាក់ពិន័យ</td>
									<td>Penalty</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>CFI</td>
									<td>ប្រាក់សេវាមោឃភាព (ក្នុងប្រព័ន្ធ)</td>
									<td>Cancellation Fee​ in System</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>EXC</td>
									<td>ប្តូរប្រាក់</td>
									<td>Exchange</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>UIC</td>
									<td>ចំណូលពីការបង់ថ្លៃទឹក ភ្លើង</td>
									<td>Utilities Income</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>ADI</td>
									<td>ចំណូលរដ្ឋបាល</td>
									<td>Admin Income</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>FTI</td>
									<td>សាច់ប្រាក់ផ្ទេរចូល</td>
									<td>Fund transfer In</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>SRA</td>
									<td>ចំណូលពីការលក់សម្ភារៈ</td>
									<td>Sale Return and Allowance</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>CFO</td>
									<td>ប្រាក់សេវាមោឃភាព (ក្រៅប្រព័ន្ធ)</td>
									<td>Cancellation Fee​ out System</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>KHR : </td>
								</tr>
								<tr style="background-color:#C4D79B; color:red; text-align:center;">
									<td style="border:none;"></td>
									<td style="border:none;font-family:'Moul';">សរុប</td>
									<td style="border:none;"></td>
									<td style="border:none;">KHR : 1,600,000.00</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-6">
					<div class="bs-example">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>កូដ</th>
									<th colspan="2">ប្រភេទប្រតិបត្តិការ</th>
									<th>ចំនួន</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>EBL</td>
									<td>ចំនួនទឹកប្រាក់ចុងគ្រា</td>
									<td>Ending Balance</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>SLR</td>
									<td>ចំណាយលើប្រាក់បៀវត្សរ</td>
									<td>Salary</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>DIB</td>
									<td>ដាក់ប្រាក់ចូលធនាគារ</td>
									<td>Deposit in Bank</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>LND</td>
									<td>បើកប្រាក់កម្ចី</td>
									<td>Loan Disbusre</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>TME</td>
									<td>ប្រាក់ឧបត្ថម្ភបេសកកម្មធ្វើដំណើរ</td>
									<td>Travel Mission Expense</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>FME</td>
									<td>ប្រាក់ឧបត្ថម្ភបេសកកម្ម អាហារ</td>
									<td>Food Mission Expense</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>HIS</td>
									<td>ចំណាយលើធានារ៉ាប់រងបុគ្គលិក</td>
									<td>Health Staff Insurance</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>EXC</td>
									<td>ប្តូរប្រាក់</td>
									<td>Exchange</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>UTE</td>
									<td>ចំណាយថ្លៃទឹក ភ្លើង</td>
									<td>Utilities Expense</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>ADE</td>
									<td>ចំណាយរដ្ឋបាល</td>
									<td>Admin Expense</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>FTO</td>
									<td>សាច់ប្រាក់ផ្ទេរចេញ</td>
									<td>Fund transfer Out</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>MTE</td>
									<td>ចំណាយលើកិច្ចប្រជុំទូទៅ</td>
									<td>Meeting Expense</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>MPE</td>
									<td>ចំណាយលើការផ្សព្វផ្សាយ</td>
									<td>Marketing / Promotion</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>OEM</td>
									<td>ចំណាយទិញសម្ភារការិយាល័យ</td>
									<td>Office Equipment</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>ORT</td>
									<td>ចំណាយលើការជួលការិយាល័យ</td>
									<td>Office Rental</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>SPL</td>
									<td>ចំណាយលើថ្លៃសំាងម៉ូតូបុគ្គលិក</td>
									<td>Staff Petroleum </td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>SMR</td>
									<td>ចំណាយលើថ្លៃជួលម៉ូតូ</td>
									<td>Staff Motor Rental</td>
									<td>KHR : </td>
								</tr>
								<tr>
									<td>PHC</td>
									<td>ចំណាយថ្លកាតទូរស័ព្ទបុគ្គលិក</td>
									<td>Phone Card</td>
									<td>KHR : </td>
								</tr>
								<tr style="background-color:#C4D79B; color:red; text-align:center;">
									<td style="border:none;"></td>
									<td style="border:none;font-family:'Moul';">សរុប</td>
									<td style="border:none;"></td>
									<td style="border:none;">KHR : 1,600,000.00</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<table style="width:80%; margin:0 auto; border:none;font-family:'Moul';text-align:center;">
					<tr>
						<td>អ្នកអនុម័ត</td>
						<td>អ្នកត្រួតពិនិត្យ</td>
						<td>អ្នករៀបចំ</td>
					</tr>
					<tr style="height:150px;">
						<td>អ៊ឹង ស៊ីណាន</td>
						<td>គ្មាន</td>
						<td>អ៊ុង សុគន្ធា</td>
					</tr>
				</table>
			</div>
			
			<br/><br/>
		</div>
	</div>
</div>