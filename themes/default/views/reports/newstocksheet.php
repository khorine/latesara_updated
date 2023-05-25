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
                font-size:12px;
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

    </head>

    <body>

<div class="container">


        <div class="text-center">

 <h4 style="text-transform:uppercase;"><b><?= lang('Stock_Sheet_Report'); ?> </b></h4>
 <h4 ><b>Tessara Bistro</b></h4>
  <h4 ><b>Location: Bar</b></h4>
 <div class="text-left"> <h5><b>Date:   <?php echo '  '.date('d-m-Y',strtotime("yesterday"));?></b></h5></div>

            <div style="clear:both;"></div>
			
            <table class="table table-sm table-striped table_morecondensed" border="1">
			<thead class="thead-dark">
                        <tr class="active">
							<th><?= lang("#"); ?></th>
                            <th><?= lang("product_name"); ?></th>
                            <th><?= lang("OP_Stock"); ?></th>
							<th><?= lang("UOM"); ?></th>
							<th><?= lang("Issues"); ?></th>
							<th><?= lang("Total"); ?></th>
                            <th><?= lang("Sales"); ?></th>
							<th><?= "Rate" ?></th>
                            <th><?= lang("Amount"); ?></th>
                            <th><?= lang("Adjst"); ?></th>
							<th><?= lang("CL/Stock"); ?></th>
                        </tr>
                        </thead>
                <tbody>
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
						<tr style="font-size:11px"><td><?= $r; ?></td><td><?= $records->name; ?></td><td><?= $openingstck; ?></td><td><?= $records->unit; ?></td><td><?= round($records->purchaseqty,0); ?></td><td style="text-align: center"><?= $totals; ?></td><td style="text-align: center"><?= round($records->saleqty,0); ?></td><td></td><td></td><td style="text-align: center"><?= round($records->adjstqty,0); ?></td><td style="text-align: center"><?= $closingstck; ?></td></tr>
							<?php
						}
						
						?>
            
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
</body>
</html>
