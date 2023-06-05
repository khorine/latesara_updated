<?php
function product_name($name)
{
    return character_limiter($name, (isset($pos_settings->char_per_line) ? ($pos_settings->char_per_line-8) : 35));
}
//print_r($inv);
//die('WOO');
if ($modal) {
    echo '<div class="modal-dialog no-modal-header"><div class="modal-content"><div class="modal-body"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>';
} else { ?>
    <!doctype html>
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
                font-size:13px;
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

<?php } ?>
<div id="wrapper">
    <div id="receiptData">
    <div class="no-print">
        <?php if ($message) { ?>
            <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <?= is_array($message) ? print_r($message, true) : $message; ?>
            </div>
        <?php } ?>
    </div>
   	    <div id="receipt-data" <?php echo $display;?>>
        <div class="text-center">
          <!--    <img src="<?//= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?//= $biller->company; ?>">
<!--            <h3 style="text-transform:uppercase;"><b>$inv->id$biller->company != '-' ? $biller->company : $biller->name; ?></b></h3>-->
            <h5 style="text-transform:uppercase;font-size:18px"><b><?= "TESSARA BISTRO "?></b></h5>
                <h6 >MIREMA DRIVE<h6 >  
                <h6 >NAIROBI</h6>
				
				

 <h6 style="text-transform:uppercase;font-size:16px"><b>ORDER NO: </b> <?php
 $t=1;
 foreach($billnos as $row1){
	 $t++;
	 if($t % 5 == 0)
    {
        echo "<br>";
    }
	 echo '<b>'.$row1->id.' ,</b>';
 }
 ?></h6>
            <?php
           //echo "<br>" . lang("tel") . ": " . $biller->phone . "<br>";
            ?>
            <?php
            if ($pos_settings->cf_title1 != "" && $pos_settings->cf_value1 != "") {
              //  echo $pos_settings->cf_title1 . ": " . $pos_settings->cf_value1 . "<br>";
            }
            if ($pos_settings->cf_title2 != "" && $pos_settings->cf_value2 != "") {
               // echo $pos_settings->cf_title2 . ": " . $pos_settings->cf_value2 . "<br>";
            }
            echo '</p></div>';
			 echo '--------------------------------------------------------------------------------------------------------------------------<br>';
				
            if ($Settings->invoice_view == 1) { ?>
                <div class="col-sm-12 text-center">
                    <h4 style="font-weight:bold;"><?= lang('tax_invoice'); ?></h4>
                </div>
            <?php }
			//echo  $warehousename->name . " <br>";
            //echo "<p>" . lang("reference_no") . ": " . $inv->reference_no . "<br>";
           // echo lang("customer") . ": " . $inv->customer . "   -    "."No of Customer(s) : ". $inv->count_cust.  "<br>";
           // echo "Cashier" . ": " . $inv->cashier . "</br>";
           // echo "Chef" . ": " . $inv->chef . "</br>";
            echo "<span style='font-size:16px'>".lang("date") . ": " . $this->sma->hrld(date('Y-m-d H:i:s')) . "</p></span>";
           
            ?>
            <div style="clear:both;"></div>
            <table class="table table-striped table-condensed">
                <tbody>
				<tr><td style="text-align:center" colspan="2">Item</td><td align="centre">Qty</td><td align="centre"></>Price</td><td  style="text-align:center"></>Total</td></tr>
                <?php
                $r = 1;
				$totalamnt =0;
                $tax_summary = array();
                foreach ($bill as $row) {
                    if (isset($tax_summary[$row->tax_code])) {
                        $tax_summary[$row->tax_code]['items'] += $row->quantity;
                        $tax_summary[$row->tax_code]['tax'] += $row->item_tax;
                        $tax_summary[$row->tax_code]['amt'] += ($row->quantity * $row->net_unit_price) - $row->item_discount;
                    } else {
                        $tax_summary[$row->tax_code]['items'] = $row->quantity;
                        $tax_summary[$row->tax_code]['tax'] = $row->item_tax;
                        $tax_summary[$row->tax_code]['amt'] = ($row->quantity * $row->net_unit_price) - $row->item_discount;
                        $tax_summary[$row->tax_code]['name'] = $row->tax_name;
                        $tax_summary[$row->tax_code]['code'] = $row->tax_code;
                        $tax_summary[$row->tax_code]['rate'] = $row->tax_rate;
                    }
					
                    echo '<tr style="font-size:15px"><td colspan="2">#:&nbsp;&nbsp;' . product_name($row->product_name)  ;
                    $data7 = '#' . $r . ':  ' . product_name($row->product_name) . '*' . $row->tax_code . '' . PHP_EOL;
					echo '<td>&nbsp' . number_format($row->quantity) . '</td>';
					$data8 = '' . $this->sma->formatNumber($row->quantity) . ' x ';

                    if ($row->item_discount != 0) {
                        echo '<td><del>' . $this->sma->formatMoney($row->net_unit_price + ($row->item_discount / $row->quantity) + ($row->item_tax / $row->quantity)) . '</del></td>';
                    $data9 = '  ' . $this->sma->formatMoney($row->net_unit_price + ($row->item_discount / $row->quantity) + ($row->item_tax / $row->quantity)) . '  ';
					}

                    echo '<td>'.$this->sma->formatMoney($row->net_unit_price + ($row->item_tax / $row->quantity)) .'</td><td><span class="pull-right">*' .$this->sma->formatMoney($row->subtotal) . '</span></td></tr>';
                    $data10 = $this->sma->formatMoney($row->net_unit_price + ($row->item_tax / $row->quantity)) . '  ' . $this->sma->formatMoney($row->subtotal) . PHP_EOL;
					$totalamnt = $totalamnt +  $row->subtotal;
					$r++;
                }
                ?>
                </tbody>
                <tfoot>
                
                  
                <?php
                //if ($inv->product_tax != 0) {
	
                  echo '<tr><th colspan="4" style="text-align:right;font-size:11px">' . lang("Sub Total") . '</th><th class="text-right" style="font-size:11px">' . $this->sma->formatMoney($totalamnt- $inv->product_tax - ($totalamnt*0.16) ) . '</th></tr>';
                    echo '<tr ><td colspan="4" style="text-align:right; font-size:11px" >' . lang("VAT 16%") . '</td><td class="text-right" style="font-size:11px">' . $this->sma->formatMoney($totalamnt*0.16) . '</td></tr>';
					// echo '<tr ><td colspan="4"><i>All prices are VAT inclusive</i></td></tr>';
                    //echo '<tr><td colspan="4" style="text-align:right;font-size:11px">' . lang("Catering Levy 2%") . '</td><td class="text-right" style="font-size:11px">' . $this->sma->formatMoney(0.02 * $totalamnt) . '</td></tr>';
               

				//}
                if ($inv->order_discount != 0) {
                    echo '<tr><th  colspan="4">' . lang("order_discount") . '</th><th class="text-right">'.$this->sma->formatMoney($inv->order_discount) . '</th></tr>';
                }
                
                if ($pos_settings->rounding) { 
                    $round_total = $this->sma->roundNumber($inv->grand_total, $pos_settings->rounding);
                    $rounding = $this->sma->formatMoney($round_total - $inv->grand_total);
                ?>
                    <tr>
                        <th><?= lang("rounding"); ?></th>
                        <th class="text-right"><?= $rounding; ?></th>
                    </tr>
                   
                <?php } else { ?>
                    
                <?php }
                //if ($inv->paid < $inv->grand_total) { ?>
                
                    <tr style="font-size:15px">
                        <th colspan="4"><?= lang("due_amount"); ?></th>
                        <th class="text-right"><?= $this->sma->formatMoney($totalamnt); ?></th>
                    </tr>

                <?php //}
  echo '<p style="font-size:14px">You were served by : <b>'.$soldby->first_name.'</b>&nbsp;</p>';
   echo 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br>';
			
                 ?>
               <!--   <tr>
                        <th><?= "<b>Total Paid</b>"; ?></th>
                        <th class="text-right"><?= $this->sma->formatMoney($inv->bill_change+$inv->paid); ?></th>
                  </tr>-->
                 <!-- <tr style="font-size:15px"><td colspan="2"><br>Send Money: 0717 616 500</td><td align="center" colspan="2"><br>-- Adetha Muchai</td><td></td></tr>-->
				 <tr style="font-size:20px"><td colspan="2"><br><b>Mpesa Till No:  8257060</b></td><td align="center" colspan="2"><br></td><td></td></tr>
                </tfoot>
            </table>

            
			
                <?php
        
                $themes = array(
                    $pos_settings->custom_theme1,
                    $pos_settings->custom_theme2,
                    $pos_settings->custom_theme3,
                    $pos_settings->custom_theme4,
                    $pos_settings->custom_theme5,
                    $pos_settings->custom_theme6,
                    $pos_settings->custom_theme7,
                    $pos_settings->custom_theme8,
                    $pos_settings->custom_theme9,
                );

                if($themes[0] !=""){ ?>
                    <div class="well well-sm" style="text-align: left"> 
                    <?php
                    echo "<b style='font-size:18px'>TESSARA BISTRO NIGHT THEMES </b><br>";
                }
                foreach ($themes as $theme){
                    if ($theme != ""){
                        echo "<b style='font-size:15px'>". $theme . "</b><br>";
                    }
                }
                ?>
                </div>
                <div class="well well-sm" style="text-align: center">
                
                <?php echo "<< THANK YOU >>";
echo '<p><br>Developed by Techsavanna Ltd :<br>info@techsavanna.technology  </p>';				?>
				
            </div>
            <?php
            if ($payments) {
                echo '<table class="table table-striped table-condensed"><tbody>';
                foreach ($payments as $payment) {
                    echo '<tr>';
                    if ($payment->paid_by == 'cash' && $payment->pos_paid) {
                        echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                        echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                        echo '<td>' . lang("change") . ': ' . ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0) . '</td>';
                    }
                    if (($payment->paid_by == 'CC' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe') && $payment->cc_no) {
                        echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                        echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                        echo '<td>' . lang("no") . ': ' . 'xxxx xxxx xxxx ' . substr($payment->cc_no, -4) . '</td>';
                        echo '<td>' . lang("name") . ': ' . $payment->cc_holder . '</td>';
                    }
                    if ($payment->paid_by == 'Cheque' && $payment->cheque_no) {
                        echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                        echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                        echo '<td>' . lang("cheque_no") . ': ' . $payment->cheque_no . '</td>';
                    }
                    if ($payment->paid_by == 'gift_card' && $payment->pos_paid) {
                        echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                        echo '<td>' . lang("no") . ': ' . $payment->cc_no . '</td>';
                        echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                        echo '<td>' . lang("balance") . ': ' . ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0) . '</td>';
                    }
                    if ($payment->paid_by == 'other' && $payment->amount) {
                        echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                        echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                        echo $payment->note ? '</tr><td colspan="2">' . lang("payment_note") . ': ' . $payment->note . '</td>' : '';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table>';
            }

            if ($Settings->invoice_view == 1) {
                if (!empty($tax_summary)) {
                    echo '<h4 style="font-weight:bold;">' . lang('tax_summary') . '</h4>';
                    echo '<table class="table table-condensed"><thead><tr><th>' . lang('name') . '</th><th>' . lang('code') . '</th><th>' . lang('qty') . '</th><th>' . lang('tax_excl') . '</th><th>' . lang('tax_amt') . '</th></tr></td><tbody>';
                    foreach ($tax_summary as $summary) {
                        echo '<tr><td>' . $summary['name'] . '</td><td class="text-center">' . $summary['code'] . '</td><td class="text-center">' . $this->sma->formatNumber($summary['items']) . '</td><td class="text-right">' . $this->sma->formatMoney($summary['amt']) . '</td><td class="text-right">' . $this->sma->formatMoney($summary['tax']) . '</td></tr>';
                    }
                    echo '</tbody></tfoot>';
                    echo '<tr><th colspan="4" class="text-right">' . lang('total_tax_amount') . '</th><th class="text-right">' . $this->sma->formatMoney($inv->product_tax) . '</th></tr>';
                    echo '</tfoot></table>';

                }
            }
            ?>

            <?= $inv->note ? '<p class="text-center">' . $this->sma->decode_html($inv->note) . '</p>' : ''; ?>
            <?= $inv->staff_note ? '<p class="no-print"><strong>' . lang('staff_note') . ':</strong> ' . $this->sma->decode_html($inv->staff_note) . '</p>' : ''; ?>
            
        </div> 
        <div style="clear:both;"></div>
    </div>    
<?php if ($modal) {
    echo '</div></div></div></div>';
} else { ?>
<div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
    <hr>
    <?php if ($message) { ?>
    <div class="alert alert-success">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <?= is_array($message) ? print_r($message, true) : $message; ?>
    </div>
<?php } ?>

    <?php if ($pos_settings->java_applet) { ?>
        <span class="col-xs-12"><a class="btn btn-block btn-primary" onClick="printReceipt()"><?= lang("print"); ?></a></span>
        <span class="col-xs-12"><a class="btn btn-block btn-info" type="button" onClick="openCashDrawer()">Open Cash
                Drawer</a></span>
        <div style="clear:both;"></div>
    <?php } else { ?>
        <span class="pull-right col-xs-12">
        <a href="javascript:window.print()" id="web_print" class="btn btn-block btn-primary"
           onClick="window.print();return false;"><?= lang("web_print"); ?></a>
    </span>
    <?php } ?>
    <span class="pull-left col-xs-12"><a class="btn btn-block btn-success" href="#" id="email"><?= lang("email"); ?></a></span>

    <span class="col-xs-12">
        <a class="btn btn-block btn-warning" onclick="window.close()"><?= lang("back_to_pos"); ?></a>
    </span>
    <?php if (!$pos_settings->java_applet) { ?>
        <div style="clear:both;"></div>
        <div class="col-xs-12" style="background:#F5F5F5; padding:10px;">
            <p style="font-weight:bold;">Please don't forget to disable the header and footer in browser print
                settings.</p>

            <p style="text-transform: capitalize;"><strong>FF:</strong> File &gt; Print Setup &gt; Margin &amp;
                Header/Footer Make all --blank--</p>

            <p style="text-transform: capitalize;"><strong>chrome:</strong> Menu &gt; Print &gt; Disable Header/Footer
                in Option &amp; Set Margins to None</p></div>
    <?php } ?>
    <div style="clear:both;"></div>

</div>

</div>
<canvas id="hidden_screenshot" style="display:none;">

</canvas>
<div class="canvas_con" style="display:none;"></div>
<script type="text/javascript" src="<?= $assets ?>pos/js/jquery-1.7.2.min.js"></script>
<?php if ($pos_settings->java_applet) {
        function drawLine()
        {
            $size = $pos_settings->char_per_line;
            $new = '';
            for ($i = 1; $i < $size; $i++) {
                $new .= '-';
            }
            $new .= ' ';
            return $new;
        }

        function printLine($str, $sep = ":", $space = NULL)
        {
            $size = $space ? $space : $pos_settings->char_per_line;
            $lenght = strlen($str);
            list($first, $second) = explode(":", $str, 2);
            $new = $first . ($sep == ":" ? $sep : '');
            for ($i = 1; $i < ($size - $lenght); $i++) {
                $new .= ' ';
            }
            $new .= ($sep != ":" ? $sep : '') . $second;
            return $new;
        }

        function printText($text)
        {
            $size = $pos_settings->char_per_line;
            $new = wordwrap($text, $size, "\\n");
            return $new;
        }

        function taxLine($name, $code, $qty, $amt, $tax)
        {
            return printLine(printLine(printLine(printLine($name . ':' . $code, '', 18) . ':' . $qty, '', 25) . ':' . $amt, '', 35) . ':' . $tax, ' ');
        }

        ?>

        <script type="text/javascript" src="<?= $assets ?>pos/qz/js/deployJava.js"></script>
        <script type="text/javascript" src="<?= $assets ?>pos/qz/qz-functions.js"></script>
        <script type="text/javascript">
            deployQZ('themes/<?=$Settings->theme?>/assets/pos/qz/qz-print.jar', '<?= $assets ?>pos/qz/qz-print_jnlp.jnlp');
            usePrinter("<?= $pos_settings->receipt_printer; ?>");
            <?php /*$image = $this->sma->save_barcode($inv->reference_no);*/ ?>
            function printReceipt() {
                //var barcode = 'data:image/png;base64,<?php /*echo $image;*/ ?>';
                receipt = "";
                receipt += chr(27) + chr(69) + "\r" + chr(27) + "\x61" + "\x31\r";
                receipt += "<?= $biller->company; ?>" + "\n";
                receipt += " \x1B\x45\x0A\r ";
                receipt += "<?= $biller->address . " " . $biller->city . " " . $biller->country; ?>" + "\n";
                receipt += "<?= $biller->phone; ?>" + "\n";
                receipt += "<?php if ($pos_settings->cf_title1 != "" && $pos_settings->cf_value1 != "") { echo printLine($pos_settings->cf_title1 . ": " . $pos_settings->cf_value1); } ?>" + "\n";
                receipt += "<?php if ($pos_settings->cf_title2 != "" && $pos_settings->cf_value2 != "") { echo printLine($pos_settings->cf_title2 . ": " . $pos_settings->cf_value2); } ?>" + "\n";
                receipt += "<?=drawLine();?>\r\n";
                receipt += "<?php if($Settings->invoice_view == 1) { echo lang('tax_invoice'); } ?>\r\n";
                receipt += "<?php if($Settings->invoice_view == 1) { echo drawLine(); } ?>\r\n";
                receipt += "\x1B\x61\x30";
                receipt += "<?= printLine(lang("reference_no") . ": " . $inv->reference_no) ?>" + "\n";
                receipt += "<?= printLine(lang("sales_person") . ": " . $biller->name); ?>" + "\n";
                receipt += "<?= printLine("Chef" . ": " . $inv->chef); ?>" + "\n";
                receipt += "<?= printLine("Cashier". ": " . $inv->cashier); ?>" + "\n";
                receipt += "<?= printLine(lang("customer") . ": " . $inv->customer); ?>" + "\n";
                receipt += "<?= printLine(lang("date") . ": " . date($dateFormats['php_ldate'], strtotime($inv->date))) ?>" + "\n\n";
                receipt += "<?php $r = 1;
            foreach ($rows as $row): ?>";
                receipt += "<?= "#" . $r ." "; ?>";
                receipt += "<?= printLine(product_name(addslashes($row->product_name)).($row->variant ? ' ('.$row->variant.')' : '').":".$row->tax_code, '*'); ?>" + "\n";
                receipt += "<?= printLine($this->sma->formatNumber($row->quantity)."x".$this->sma->formatMoney($row->net_unit_price+($row->item_tax/$row->quantity)) . ":  ". $this->sma->formatMoney($row->subtotal), ' ') . ""; ?>" + "\n";
                receipt += "<?php $r++;
            endforeach; ?>";
                receipt += "\x1B\x61\x31";
                receipt += "<?=drawLine();?>\r\n";
                receipt += "\x1B\x61\x30";
                receipt += "<?= printLine(lang("total") . ": " . $this->sma->formatMoney($inv->total+$inv->product_tax)); ?>" + "\n";
                <?php if ($inv->order_tax != 0) { ?>
                receipt += "<?= printLine(lang("tax") . ": " . $this->sma->formatMoney($inv->order_tax)); ?>" + "\n";
                <?php } ?>
                <?php if ($inv->total_discount != 0) { ?>
                receipt += "<?= printLine(lang("discount") . ": (" . $this->sma->formatMoney($inv->product_discount).") ".$this->sma->formatMoney($inv->order_discount)); ?>" + "\n";
                <?php } ?>
                <?php if($pos_settings->rounding) { ?>
                receipt += "<?= printLine(lang("rounding") . ": " . $rounding); ?>" + "\n";
                receipt += "<?= printLine(lang("grand_total") . ": " . $this->sma->formatMoney($this->sma->roundMoney($inv->grand_total+$rounding))); ?>" + "\n";
                <?php } else { ?>
                receipt += "<?= printLine(lang("grand_total") . ": " . $this->sma->formatMoney($inv->grand_total)); ?>" + "\n";
                <?php } ?>
                <?php if($inv->paid < $inv->grand_total) { ?>
                receipt += "<?= printLine(lang("paid_amount") . ": " . $this->sma->formatMoney($inv->paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("due_amount") . ": " . $this->sma->formatMoney($inv->grand_total-$inv->paid)); ?>" + "\n\n";
                <?php 
  echo '<p>You were served by:'.$soldby->first_name.'&nbsp;'.$soldby->last_name.'</p>';
            } 

                ?>

                <?php
                if($payments) {
                    foreach($payments as $payment) {
                        if ($payment->paid_by == 'cash' && $payment->pos_paid) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->sma->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("change") . ": " . ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0)); ?>" + "\n";
                <?php  } if (($payment->paid_by == 'CC' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe') && $payment->cc_no) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->sma->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("card_no") . ": xxxx xxxx xxxx " . substr($payment->cc_no, -4)); ?>" + "\n";
                <?php } if ($payment->paid_by == 'Cheque' && $payment->cheque_no) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->sma->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("cheque_no") . ": " . $payment->cheque_no); ?>" + "\n";
                <?php if ($payment->paid_by == 'other' && $payment->amount) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->sma->formatMoney($payment->amount)); ?>" + "\n";
                receipt += "<?= printText(lang("payment_note") . ": " . $payment->note); ?>" + "\n";
                <?php }
            }

        }
    }

    if($Settings->invoice_view == 1) {
        if(!empty($tax_summary)) {
    ?>
                receipt += "\n" + "<?= lang('tax_summary'); ?>" + "\n";
                receipt += "<?= taxLine(lang('name'),lang('code'),lang('qty'),lang('tax_excl'),lang('tax_amt')); ?>" + "\n";
                receipt += "<?php foreach ($tax_summary as $summary): ?>";
                receipt += "<?= taxLine($summary['name'],$summary['code'],$this->sma->formatNumber($summary['items']),$this->sma->formatMoney($summary['amt']),$this->sma->formatMoney($summary['tax'])); ?>" + "\n";
                receipt += "<?php endforeach; ?>";
                receipt += "<?= printLine(lang("total_tax_amount") . ":" . $this->sma->formatMoney($inv->product_tax)); ?>" + "\n";
                <?php
                    }
                }
                ?>
                receipt += "\x1B\x61\x31";
                receipt += "\n" + "<?= $biller->invoice_footer ? printText(str_replace(array('\n', '\r'), ' ', $this->sma->decode_html($biller->invoice_footer))) : '' ?>" + "\n";
                receipt += "\x1B\x61\x30";
                <?php if(isset($pos_settings->cash_drawer_cose)) { ?>
                print(receipt+receipt, '', '<?=$pos_settings->cash_drawer_cose;?>');
            
                
                <?php } else { ?>
                print(receipt+receipt, '', '');
                
                
                <?php } ?>

            }

        </script>
    <?php } ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#email').click(function () {
                        var email = prompt("<?= lang("email_address"); ?>", "<?= $customer->email; ?>");
                        if (email != null) {
                            $.ajax({
                                type: "post",
                                url: "<?= site_url('pos/email_receipt') ?>",
                                data: {<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email, id: <?= $inv->id; ?>},
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
        <?php if (!$pos_settings->java_applet) { ?>
        $(window).load(function () {
            window.print();
        });
    <?php } ?>
            </script>
</body>
</html>
<?php } ?>