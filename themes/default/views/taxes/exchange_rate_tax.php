
<div>
<table id="example" class="table table-bordered table-hover table-striped dataTable" cellspacing="0" width="100%">
  
 <thead>
            <tr>
                <th class="sorting"><?php echo lang("exchange_rate_code");  ?></th>
                <th class="sorting"><?php echo lang("exchange_rate_name");  ?></th>
                <th class="sorting"><?php echo lang("exchange_rate");  ?></th>
                <th class="sorting"><?php echo lang("action");  ?></th>
      
      
            </tr>
        </thead>
       
        <tbody>
		<?php
		foreach($condition_tax as $ct){
			echo '<tr>
			<td>'.$ct->code.'</td>
			<td>'.$ct->name.'</td>
			<td>'.intval($ct->rate).'</td>
			<td>
			<center>
			<a data-target="#myModal2" data-toggle="modal" title="" class="tip" href="'.base_url().'taxes/edit_condition_tax/'.$ct->id.'" data-original-title="Edit Exchange Rate"><i class="fa fa-edit"></i></a> 
		
			</td>
			</tr>';	
		}
		
		?>
		</tbody>    
    </table>
