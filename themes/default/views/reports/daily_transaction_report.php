<link href="https://fonts.googleapis.com/css?family=Moul|Battambang" rel="stylesheet"> 
<style type="text/css">
	.a_four_page,.header_report{
		width:21cm;
		//min-height:29.7cm;
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
		width:80%;
	}
	.title{
		text-align: center;
		font-size:16px;
		font-family: 'Moul', cursive;
		width:60%;
		float:left;
		margin-top:5px;
	}
	
	table tr td{
		border: 1px dashed black;
		padding:5px;
	}
	table tr{
		text-align:center;
		font-family:'Moul';
	}
	.border-none{
		margin-bottom:10px;
	}
	.tr-color{
		background:#DAEEF3
	}
	.sub_title{
		text-align:left;
	}
	.table > thead:first-child > tr:first-child > th, .table > thead:first-child > tr:first-child > td, .table-striped thead tr.primary:nth-child(2n+1) th 
	{
		background-color: #8DB4E2;
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
			<div class="header_report">
				<div style="float:left;">
					<?php if ($Settings->logo2) {
						echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px; width:150px;" />';
					} ?>
					<br/>
					<span style="font-family:'Battambang';">ស្នាក់ការកណ្តាល</span>
				</div>
				<div class="title">
					អង្គការ លើកស្ទួយសមត្ថភាពគ្រួសារ<br/>
					<span style="font-size:14px;">ថ្ងៃទី ១៦ ខែ​ កក្កដា ឆ្នាំ២០១៦</span>
				</div>
			</div>
			<table style="width:90%; margin:0 auto;">
			  <tr>
				<td style="border:none;"></td>
				<td style="border:none;"></td>
				<td style="border:none;"></td>
				<td style="border:none;"></td>
				<td style="border:none;"></td>
				<td style="border:none;"></td>
				<td colspan="2" style="border:none; text-align:right;"><em>ប្រាក់រៀល KHR</em></td>
			  </tr>
			  <tr>
				<td colspan="2" style="border:none;">ប្រតិបត្តិការចំណូល</td>
				<td style="border:none;"></td>
				<td style="width:30%; border:none;"></td>
				<td colspan="3" style="border:none;">ប្រតិបត្តិការចំណូល</td>
				<td style="border:none;"></td>
			  </tr>
			</table>
			<div class="col-lg-12">
				<div class="col-md-6">
					<div class="bs-example">
						<table class="table table-bordered">
							<thead>
								<td>ល.រ</td>
								<td>លេខវិក្កយបត្រ</td>
								<td>ប្រភេទ</td>
								<td>ចំនួនទឹកប្រាក់</td>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Balance in Bank</td>
									<td>BIB</td>
									<td>00</td>
								</tr>
								<tr>
									<td>2</td>
									<td>CO Rpt</td>
									<td>LNR</td>
									<td>00</td>
								</tr>
								<tr>
									<td>3</td>
									<td>CO Rpt</td>
									<td>PNT</td>
									<td>00</td>
								</tr>
								<tr>
									<td>4</td>
									<td>CO Rpt</td>
									<td>LNF</td>
									<td>00</td>
								</tr>
								<tr>
									<td>5</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>6</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>7</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-6">
					<div class="bs-example">
						<table class="table table-bordered">
							<thead>
								<td>ល.រ</td>
								<td>លេខវិក្កយបត្រ</td>
								<td>ប្រភេទ</td>
								<td>ចំនួនទឹកប្រាក់</td>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>FCB000306</td>
									<td>LND</td>
									<td>00</td>
								</tr>
								<tr>
									<td>2</td>
									<td>FCB000516</td>
									<td>LND</td>
									<td>00</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<table style="width:90%; margin:0 auto;">
			  <tr>
				<td style="border:none;"></td>
			  </tr>
			  <tr>
				<td style="border:none;" colspan="2">អ្នកអនុម័ត</td>
				<td style="border:none;"></td>
				<td style="border:none;" colspan="2">អ្នកត្រួតពិនិត្យ</td>
				<td style="border:none;"></td>
				<td style="border:none;" colspan="2">អ្នកត្រួតពិនិត្យ</td>
			  </tr>
			  <tr style="height:150px;">
				<td style="border:none;" colspan="2">អ៊ឹង ស៊ីណាន</td>
				<td style="border:none;"></td>
				<td style="border:none;" colspan="2">គ្មាន</td>
				<td style="border:none;"></td>
				<td style="border:none;" colspan="2">ស៊ីណាន</td>
			  </tr>
			</table>
			<br/><br/>
		</div>
	</div>
</div>