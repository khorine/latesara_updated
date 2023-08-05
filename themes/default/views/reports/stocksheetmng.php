<?php
function product_name($name)
{
    return character_limiter($name, (isset($pos_settings->char_per_line) ? ($pos_settings->char_per_line-8) : 35));
}
//print_r($inv);
//die('WOO');
?>
    <html>
    <head>
        <meta charset="utf-8">
        <title><?= $page_title . " " . lang("no") . " " . $inv->id; ?></title>
        <base href="<?= base_url() ?>"/>
        <meta http-equiv="cache-control" content="max-age=0"/>
        <meta http-equiv="cache-control" content="no-cache"/>
        <meta http-equiv="expires" content="0"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
        <link rel="stylesheet" href="<?= $assets ?>styles/theme.css" type="text/css"/>
        <style type="text/css" media="all">
            body {
                color: #000;
                font-size:14px;
            }

            #wrapper {
                max-width: 480px;
                margin: 0 auto;
                padding-top: 20px;
            }

            .btn {
                border-radius: 0;
                margin-bottom: 5px;
            }

            h3 {
                margin: 5px 0;
            }
			.table_morecondensed>thead>tr>th, 
.table_morecondensed>tbody>tr>th, 
.table_morecondensed>tfoot>tr>th, 
.table_morecondensed>thead>tr>td, 
.table_morecondensed>tbody>tr>td, 
.table_morecondensed>tfoot>tr>td{ padding: 2px; 
 border-top: 1px solid #000;}

            @media print {
                .no-print {
                    display: none;
                }

                #wrapper {
                    max-width: 480px;
                    width: 100%;
                    min-width: 250px;
                    margin: 0 auto;
                }
				
                .page-break  {page-break-after: always; }
/*                footer {page-break-after: always;}*/
            }
            
          
        </style>
	<script type="text/javascript">
	 $(document).ready(function () {
	//var table = $('#stckdata').DataTable
	$('#stckdata').DataTable();
	
	});
	</script>
    </head>

    <body>

<div class="container">


        <div class="text-center">

 <h4 style="text-transform:uppercase;"><b><?= lang('Stock_Management'); ?> </b></h4>
 <h4 ><b>TESSARA BISTRO & GARDEN</b></h4>
  <h4 ><b>Location: Bar</b></h4>
 <div class="text-left"> <h5><b>Date:   <?php echo '  '.date('d-m-Y',strtotime("yesterday"));?></b></h5></div>

            <div style="clear:both;"></div>
			<?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/add_stockadjustment", $attrib)
                ?>
            <table class="table table-sm table-striped table_morecondensed" id="stckdata" border="1">
			<thead class="thead-dark">
                        <tr class="active">
							<th><?= lang("#"); ?></th>
                            <th><?= lang("product_name"); ?></th>
                            <th><?= lang("OP_Stock"); ?></th>
							<th><?= lang("UOM"); ?></th>
							<th><?= lang("Issues"); ?></th>
							<th><?= lang("Total"); ?></th>
                            <th><?= lang("Sales"); ?></th>
							
                            <th><?= lang("Price"); ?></th>
                            <th><?= lang("Adjst"); ?></th>
							<th><?= lang("CL/Stock"); ?></th>
							<th><?= lang("Actual"); ?></th>
							<th><?= lang("Diff"); ?></th>
							<th><?= lang("Diff_Price"); ?></th>
                        </tr>
                        </thead>
                <tbody>
				<input type="hidden" name="stdate" id="stdate" value="<?= $stdate; ?>">
				<?php
						//print_r($sheet);
						$r=0;
						foreach ($sheet as $records){
							
							
							
							
							if($records->unit =="cocktail" || $records->unit =="Cocktail" ){
								$closingstck = 0;
								$openingstck = 0 ;
							}else {
								$openingstck = $records->purchaseopqty - $records->saleopqty + $records->adjstopqty ;
								
								$totals = $openingstck+$records->purchaseqty;
								$closingstck = $totals - $records->saleqty+$records->adjstqty;
							}
							
							$r++;
							?>
						<tr style="font-size:14px"><td><?= $r; ?> 
                        <input type="text" hidden name="code<?= $r; ?>" size="4" value="<?= $records->code; ?>"></input></td>
                            <td><?= $records->name; ?></td>
                            <td><?= $openingstck; ?></td>
                            <td><?= $records->unit; ?></td>
                            <td><?= round($records->purchaseqty,0); ?></td>
                            <td style="text-align: center"><?= $totals; ?></td>
                            <td style="text-align: center"><?= round($records->saleqty,0); ?></td>
                            <td style="text-align: center" id="amnt<?= $r; ?>"><?= round($records->price,0); ?></td>
                            <td style="text-align: center"><?= round($records->adjstqty,0); ?>
                                <input type="text" hidden id="adjstdiff<?= $r; ?>" name="adjstdiff<?= $r; ?>" size="4" value="0"></input>
                            </td>
                            <td style="text-align: center" id="clt<?= $r; ?>"><?= $closingstck; ?></td>
						<td style="text-align: center"><input type="text" size="4" class="actual" id="<?= $r; ?>" name="actual<?= $r; ?>"></input></td>
                        <td style="text-align: center" id="diff<?= $r; ?>" ></td>
                        <td style="text-align: center" class="diffamt"  id="diffamt<?= $r; ?>" >0</td></tr>
							<?php
						}
						
						?>
             <tr class="active">
                            <th><?= lang("#"); ?> <input type="text" name="count" value="<?=$r; ?>" hidden></th>
                            <th><?= lang("product_name"); ?></th>
                            <th><?= lang("OP_Stock"); ?></th>
                            <th><?= lang("UOM"); ?></th>
                            <th><?= lang("Issues"); ?></th>
                            <th><?= lang("Total"); ?></th>
                            <th><?= lang("Sales"); ?></th>
                            
                            <th><?= lang("Price"); ?></th>
                            <th><?= lang("Adjst"); ?></th>
                            <th><?= lang("CL/Stock"); ?></th>
                            <th><?= lang("Actual"); ?></th>
                            <th><?= lang("Diff"); ?></th>
                            <th ><input type="text" size="6" id ="diffamtotal"> </input></th>
                        </tr>
                          <tr class="active">
                            <th>
                                <?php echo form_submit('update_stock', $this->lang->line("Update Stock"), 'class="btn btn-primary"'); ?>
                                
                            </th>
                       
                        </tr>
                 </tbody>
                </tfoot>
                
            </table>
	
			<div class="well well-sm" style="text-align: center">
               
                <br></br>
                <?php
				echo "<b>Printed on: ".date('d-m-Y H:i:s')." by  ". $users->first_name ;
				echo "</b><br><br><< THANK YOU >>";
		?>
				
            </div>
           
           
        <div style="clear:both;"></div>
    </div>    

<script type="text/javascript" src="<?= $assets ?>pos/js/jquery-1.7.2.min.js"></script>


        <script type="text/javascript" src="<?= $assets ?>pos/qz/js/deployJava.js"></script>
        <script type="text/javascript" src="<?= $assets ?>pos/qz/qz-functions.js"></script>
        <script type="text/javascript">
 </div>
 </div>
 <script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
 <script type="text/javascript">
    $(document).ready(function () {
        
        $('.actual').keyup(function () {
          var sum = 0;
       //alert($(this).prop('id'));
       $id = $(this).prop('id');
      $diff =  $("#clt"+$id).html() - $(this).val() ;
      $diffamnt = $diff * $("#amnt"+$id).html()
            $("#diff"+$id).html($diff);
             $("#diffamt"+$id).html($diffamnt);
             $("#adjstdiff"+$id).val($diff);
			 
$('.diffamt').each(function(){
				//alert($(this).html());
                    sum += parseFloat($(this).html());

             });
          
            $("#diffamtotal").val(sum);    
        });
        
         
       });
       
</script>
</body>
</html>
