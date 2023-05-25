<script type="text/javascript">
$(document).ready(function () {
$(".amountg").on("change", function (e) {

          var total = 0;
        $('.amountg').each( function(){
           total += parseFloat($(this).val());
        });
        $('#totalg_amnt').val(total);
//alert(total);
        });
//$('#checkth').on('change', function() {
 //    var checked = this.checked
 //  $('span').html(checked.toString())
//});
		
    });
</script>
 <style type="text/css" media="all">
            @media print {
                .no-print {
                    display: none;
                }
				html, body {
        height: auto;
        font-size: 10px; /* changing to 10pt has no impact */
    }
	.fsize{
	font-size: 8px;	
	}
            }
        </style>
<div class="modal-dialog modal-lg fsize" style="font-size: 8px">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php 
			 foreach ($customer as $cstmer) {
				 $cname =$cstmer->name;
				 $custid = $cstmer->id;
			 }
			echo lang('Client Bills') . " ( " . $cname. "  )";; ?></h4>
        </div>
      <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'bill-room-form');
        echo form_open_multipart("sales/allbills/" . $custid, $attrib); ?>
        <div class="modal-body">
            <div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th ><?= $this->lang->line("#"); ?></th>
                        <th ><?= $this->lang->line("date"); ?></th>
                        <th ><?= $this->lang->line("Ref"); ?></th>
                        <th style="width:30%"><?= $this->lang->line("Item"); ?></th>
                        <th ><?= $this->lang->line("amount"); ?></th>
						 <th width="150px"><?= $this->lang->line("Pay Amount"); ?></th>
                      

                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($sale_items)) {
                        $total=0;
                        foreach ($sale_items as $sale_item) { ?>
                            <tr >
                                <input type="text" style="display: none" name="warehouse" value="1">
                                <td>
                                    <input type="checkbox" name="checkbox" >
                                </td>
                                <td><?= $this->sma->hrld($sale_item->date); ?></td>
                                <td><?= $sale_item->reference_no ?></td>
                                <td><?= $sale_item->product_name ?></td>

                                <td><?= $this->sma->formatMoney($sale_item->subtotal); ?></td>
								
                               <td ><input style="width:150px" class="form-control amountg" type="text" name="amountg[<?= $sale_item->id; ?>]" value="0"/></td>

                            </tr>

                        <?php $total+=$sale_item->subtotal;$vat = ($total *18)/100;
                            $tourism_levy = ($total*2)/100;
                            $service_charge = ($total*2)/100; }
                    } else {
                        echo "<tr><td colspan='8'>" . lang('no_data_available') . "</td></tr>";
                    } ?>
                    </tbody>
                    <tfoot>
                        <td></td>
                        <td></td>
                        <td><b>Total Due</b></td>
                       
                        <td><b><?= $this->sma->formatMoney($total); ?></b></td>
                   
                    </tfoot>
                </table>
            </div>
           <div class="row no-print">
                    <div class="col-lg-12">
					<div class="col-md-3">
                                <div class="form-group">
                                <label>Date</label>
                                <input name="billg_date" class="form-control date" type="text" id="billg_date" value="<?php echo date('d/m/Y'); ?>" readonly>
                            </div>
                            </div>
					<div class="col-md-3">
                                <div class="form-group">
                                <label>Total Amount</label>
                                <input name="totalg_amnt" class="form-control" type="text" id="totalg_amnt" value="0" readonly>
                            </div>
                            </div>
							<div class="col-md-3">
                                <div class="form-group">
                                <label>Paid By</label>
                               <select name="paidg_by" id="paidg_by_1" class="form-control paid_by"
                                            required="required">
                                        <option value="cash"><?= lang("cash"); ?></option>
                                        <option value="cheque"><?= lang("cheque"); ?></option>
                                        <option value="bank">Bank</option>
                                        <option value="mpesa"><?= lang("Mobile Money"); ?></option>
                                        <option value="cc">Credit Card</option>

                                    </select>
                            </div>
                            </div>
							
            </div>
			</div>
           
          
        </div>
		
        <div class="modal-footer no-print">
		<span class="pull-left col-xs-2">
        <a href="javascript:window.print()" id="web_print" class="btn btn-block btn-success"
           onClick="window.print();return false;"><?= lang("Print"); ?></a>
    </span>
            <?php echo form_submit('billg_room', lang('Pay_Bill'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

