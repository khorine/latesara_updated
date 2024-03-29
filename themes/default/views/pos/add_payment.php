<style>
.btn-prni {
    border: 1px solid #eee;
    cursor: pointer;
    height: 115px;
    margin: 0 0 3px 2px;
    padding: 2px;
    width: 10.5%;
    min-width: 100px;
    overflow: hidden;
    display: inline-block;
    font-size: 13px;
}

</style>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_payment'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("pos/add_payment/" . $inv->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <?php if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y H:i')), 'class="form-control " id="date"  readonly'); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("reference_no", "reference_no"); ?>
                        <?= form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $payment_ref), 'class="form-control tip" id="reference_no" readonly required="required"'); ?>
                    </div>
                </div>

                <input type="hidden" value="<?php echo $inv->id; ?>" name="sale_id"/>
                <input type="hidden" value="<?php echo $inv->chef_id; ?>" name="chef_id"/>
                <input type="hidden" value="<?php echo $inv->chef; ?>" name="chef"/>
                <input type="hidden" value="<?php echo $inv->cashier_id; ?>" name="cashier_id"/>
                <input type="hidden" value="<?php echo $inv->cashier; ?>" name="cashier"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">

                <div class="well well-sm well_1">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= "<b>Amount Due*</b>"//lang("amount", "amount_1"); ?>
                                        <input name="whole-amount-paid" type="text" id="amount_1"
                                               value="<?= $inv->grand_total - $inv->paid ?>"
                                               class="pa form-control kb-pad amount" required="required" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= lang("paying_by", "paid_by_1"); ?>
                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by"
                                            required="required">
                                        <option value="cash"><?= lang("cash"); ?></option>
                                        <option value="CC"><?= lang("CC"); ?></option>
                                        <option value="mpesa"><?= "Mobile Money"; ?></option>
                                        <option value="costcenter">Company/Cost Center</option>
										<option value="rooms">Room</option>
										<!--<option value="compli">Complimentary</option>-->
										<option value="reception">Reception</option>
										<option value="nonchrg">N/C</option>
										<option value="gift_card"><?= lang("gift_card"); ?></option>
                                        <option value="Cheque"><?= lang("cheque"); ?></option>
										<option value="staff"><?= lang("Staff"); ?></option>
                                        <?= $pos_settings->paypal_pro ? '<option value="ppp">' . lang("paypal_pro") . '</option>' : ''; ?>
                                        <?= $pos_settings->stripe ? '<option value="stripe">' . lang("stripe") . '</option>' : ''; ?>
                                        <option value="other"><?= lang("other"); ?></option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        
                        <div class="row">
                            
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= "<b>Amount Paid*</b>";//lang("amount", "amount_1"); ?>
                                        <input name="amount-paid" type="text" id="whole_amount_1"
                                               value="<?= $inv->grand_total - $inv->paid ?>"
                                               class="pa form-control kb-pad amount" required="required"/>
                                    </div>
                                </div>
                            </div> 
                             <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= "<b>Change</b>";//lang("amount", "amount_1"); ?>
                                        <b id="change" class="pa form-control kb-pad amount"> </b>
                                    </div>
                                </div>
                            </div> 
                            
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group gc" style="display: none;">
                            <?= lang("gift_card_no", "gift_card_no"); ?>
                            <input name="gift_card_no" type="text" id="gift_card_no" class="pa form-control kb-pad"/>

                            <div id="gc_details"></div>
                        </div>
                        <div class="pcc_1" style="display:none;">
                            <div class="form-group">
                                <input type="text" id="swipe_1" class="form-control swipe"
                                       placeholder="<?= lang('swipe') ?>"/>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="pcc_no" type="text" id="pcc_no_1" class="form-control"
                                               placeholder="<?= lang('cc_no') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <input name="pcc_holder" type="text" id="pcc_holder_1" class="form-control"
                                               placeholder="<?= lang('cc_holder') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="pcc_type" id="pcc_type_1" class="form-control pcc_type"
                                                placeholder="<?= lang('card_type') ?>">
                                            <option value="Visa"><?= lang("Visa"); ?></option>
                                            <option value="MasterCard"><?= lang("MasterCard"); ?></option>
                                            <option value="Amex"><?= lang("Amex"); ?></option>
                                            <option value="Discover"><?= lang("Discover"); ?></option>
                                        </select>
                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input name="pcc_month" type="text" id="pcc_month_1" class="form-control"
                                               placeholder="<?= lang('month') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <input name="pcc_year" type="text" id="pcc_year_1" class="form-control"
                                               placeholder="<?= lang('year') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3" id='ppp-stripe'>
                                    <div class="form-group">
                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1" class="form-control"
                                               placeholder="<?= lang('cvv2') ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="_1" style="display:none;">
                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                <input name="cheque_no" type="text" id="cheque_no_1" class="form-control cheque_no"/>
                            </div>
                        </div>
                        
                        
                        <div class="pmpesa_1" style="display:none;">
                            <div class="form-group"><?= 'Mobile Money Transaction Number' ; ?>
                                <input name="mpesa_txn_no" type="text" id="mpesa_txn_no_1" class="form-control  mpesa_txn_no"/>
                            </div>
                        </div>
						<div class="pcompliment_1" style="display:none;">
                            <div class="form-group"><?= ' Reception Remarks' ; ?>
                                <input name="complimentary_rmk" type="text" id="complimentary_rmk" class="form-control  complimentary_rmk"/>
                            </div>
                        </div>
						<div class="pnonchrg_1" style="display:none;">
                            <div class="form-group"><?= 'N/C Remarks' ; ?>
                                <input name="nonchrg_rmk" type="text" id="nonchrg_rmk" class="form-control  nonchrg_rmk"/>
                            </div>
                        </div>
                        
                          <div class="pcostcenter_1" style="display:none;">
                                <div class="form-group">Cost Center
                                    <select name="cost_center_no" id="cost_center_no_1" class="form-control cost_center_no">
                                    <option value="Walkin" selected>--Walkin--</option>
    
                                    <?php 
                                    foreach ($costcenter as $center) { ?>
                                        <option value="<?=$center->name?>"><?=$center->name?></option>
    
                                    <?php }?>
                                                        
                                    </select>
                                                    
                                </div>
                         </div>
						 
                          <div class="pstaff_1" style="display:none;">
                            <div class="form-group">Staff
                                <select name="staff_no_1" id="staff_no_1" class="form-control ">
                                                        
                                <?php 
                                foreach ($servicestaff as $staff) { ?>
                                <option value="<?=$staff->id?>"><?=$staff->first_name?></option>
    
                                <?php }?>
                                                        
                                </select>
                                                    
                            </div>
                         </div>
						 <div class="proom_1" style="display:none;">
                            <div class="form-group">Room
                                <input name="room_no" type="text" id="room_no" class="form-control " readonly/>
								<input type="hidden" name="room_cust" id="room_cust"/>
                                                    
                            </div>
                         </div>
						 <div class="modal fade" id="roomsdiv"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ROOMS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <?php //print_r($rooms); 
		 foreach ($rooms as $room) { 
		 if ($room->status =='occupied'){?>
           <button class="btn-prni btn-primary roomsbtn12"   value="<?php  echo $room->id;?>"> <?php  echo $room->name;?></button>
		   <input type="hidden" id="roomcustid<?php echo $room->id;?>" value="<?php echo $room->cust_id; ?>"> </input>
         <?php }
		 else {
			 ?>
		 <button class="btn-prni btn-danger " disabled > <?php  echo $room->name;?></button>	 
		 <?php
		 }}?>
         
    
      </div>
     <div class="row"> </div>
    </div>
	<div class="modal-footer"> </div>
  </div>
</div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="form-group" style="display: none;">
                <?= lang("attachment", "attachment") ?>
                <input id="attachment" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

          <div class="form-group" style="display: none;">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_payment','Complete Payment', 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<script type="text/javascript" src="<?= $assets ?>pos/js/parse-track-data.js"></script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $(document).on('change', '#gift_card_no', function () {
            var cn = $(this).val() ? $(this).val() : '';
            if (cn != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_gift_card/" + cn,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('incorrect_gift_card')?>');
                        } else if (data.customer_id !== null && data.customer_id != <?=$inv->customer_id?>) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('gift_card_not_for_customer')?>');

                        } else {
                            var due = <?=$inv->grand_total-$inv->paid?>;
                            if (due > data.balance) {
                                $('#amount_1').val(formatDecimal(data.balance));
								$('#whole_amount_1').val(formatDecimal(data.balance));
                            }
                            $('#gc_details').html('<small>Card No: <span style="max-width:60%;float:right;">' + data.card_no + '</span><br>Value: <span style="max-width:60%;float:right;">' + currencyFormat(data.value) + '</span><br>Balance: <span style="max-width:60%;float:right;">' + currencyFormat(data.balance) + '</span></small>');
                            $('#gift_card_no').parent('.form-group').removeClass('has-error');
                        }
                    }
                });
            }
        });
        $( "#whole_amount_1" ).mouseout(function() {
       // alert($(this).val()+"_"+$('#amount_1').val());
        $('#change').empty();
         $('#change').append($(this).val()- $('#amount_1').val());
         
        
        });
        $(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            
            localStorage.setItem('paid_by', p_val);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                $('#amount_1').focus();
                 $('.pmpesa_1').hide();
                $('.pcostcenter_1').hide();
				$('.pstaff_1').hide();
				$('.proom_1' ).hide();
				$('#roomsdiv').hide();
				$('.pcompliment_1' ).hide();
				$('.pnonchrg_1' ).hide();
            } else if (p_val == 'CC' || p_val == 'stripe' || p_val == 'ppp') {
                if (p_val == 'CC') {
                    $('#ppp-stripe').hide();
                } else {
                    $('#ppp-stripe').show();
                }
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                    $('.pmpesa_1').hide();
                $('.pcostcenter_1').hide();
				$('.pstaff_1').hide();
                $('.pcc_1').show();
                $('#swipe_1').focus();
				$('.proom_1' ).hide();
				$('#roomsdiv').hide();
				$('.pcompliment_1' ).hide();
				$('.pnonchrg_1' ).hide();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                 $('.pmpesa_1').hide();
                $('.pcostcenter_1').hide();
				$('.pstaff_1').hide();
                $('#cheque_no_1').focus();
				$('.proom_1' ).hide();
				$('.pcompliment_1' ).hide();
				$('.pnonchrg_1' ).hide();
				$('#roomsdiv').hide();
            } else if (p_val == 'mpesa') {
            
               //alert(p_val);
                $('.pcc_1' ).hide();
                $('.pcash_1' ).hide();
                $('.pcheque_1' ).hide();
                $('.pcostcenter_1' ).hide();
				$('.pstaff_1').hide();
                $('.pmpesa_1' ).show();
				$('.pcompliment_1' ).hide();
				$('.pnonchrg_1' ).hide();
                //$('.pcc_' + pa_no).hide();
                //$('.pcash_' + pa_no).show();
               // $('#payment_note_').show();
                $('#mpesa_txn_no_' ).focus();
				$('.proom_1' ).hide();
				$('#roomsdiv').hide();				
            } else if (p_val == 'reception') {
            
               //alert(p_val);
                $('.pcc_1' ).hide();
                $('.pcash_1' ).hide();
                $('.pcheque_1' ).hide();
                $('.pcostcenter_1' ).hide();
				$('.pstaff_1').hide();
                $('.pmpesa_1' ).hide();
				$('.pcompliment_1' ).show();
				$('.pnonchrg_1' ).hide();
                //$('.pcc_' + pa_no).hide();
                //$('.pcash_' + pa_no).show();
               // $('#payment_note_').show();
                $('#complimentary_rmk' ).focus();
				$('.proom_1' ).hide();
				$('#roomsdiv').hide();				
            } 
			else if (p_val == 'nonchrg') {
            
               //alert(p_val);
                $('.pcc_1' ).hide();
                $('.pcash_1' ).hide();
                $('.pcheque_1' ).hide();
                $('.pcostcenter_1' ).hide();
				$('.pstaff_1').hide();
                $('.pmpesa_1' ).hide();
				$('.pcompliment_1' ).hide();
				$('.pnonchrg_1' ).show();
                //$('.pcc_' + pa_no).hide();
                //$('.pcash_' + pa_no).show();
               // $('#payment_note_').show();
                $('#mpesa_txn_no_' ).focus();
				$('.proom_1' ).hide();
				$('#roomsdiv').hide();				
            }
			else if (p_val == 'costcenter') {
                //alert(p_val);
                $('.pcc_1' ).hide();
                $('.pcash_1' ).hide();
                $('.pcheque_1' ).hide();
                 $('.pcostcenter_1').show();
				 $('.pstaff_1').hide();
				 $('.pcompliment_1' ).hide();
                $('.pmpesa_1' ).hide();
                $('.proom_1' ).hide();
                $('#room_no' ).focus();
				$('#roomsdiv').hide();
				$('.pnonchrg_1' ).hide();
            } 
			else if (p_val == 'staff') {
                //alert(p_val);
                $('.pcc_1' ).hide();
                $('.pcash_1' ).hide();
                $('.pcheque_1' ).hide();
                 $('.pcostcenter_1').hide();
				  $('.pstaff_1').show();
				 $('.pcompliment_1' ).hide();
                $('.pmpesa_1' ).hide();
                $('.proom_1' ).hide();
                $('#staff_no_1' ).focus();
				$('#roomsdiv').hide();
				$('.pnonchrg_1' ).hide();
            }
			else if (p_val == 'rooms') {
                //alert(p_val);
                $('.pcc_1' ).hide();
                $('.pcash_1' ).hide();
                $('.pcheque_1' ).hide();
                 $('.pcostcenter_1').hide();
				 $('.pstaff_1').hide();
				 $('#roomsdiv').modal('show');
                $('.pmpesa_1' ).hide();
				$('.proom_1' ).show();
				$('.pcompliment_1' ).hide();
				$('.pnonchrg_1' ).hide();
               // $('#payment_note_1' ).show();
                $('#cost_center_no_1' ).focus();
            }
			else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                    $('.pmpesa_1').hide();
                $('.pcostcenter_1').hide();
				$('.pstaff_1').hide();
				$('#proom_1' ).hide();
				$('.pcompliment_1' ).hide();
				$('.pnonchrg_1' ).hide();
            }
            if (p_val == 'gift_card') {
                    //$('.pmpesa_1').hide();
                //$('.pcostcenter_1').hide();
                $('.gc').show();
                $('#gift_card_no').focus();
				$('#proom_1' ).hide();
            } else {
              $('.gc').hide();
                 //   $('.pmpesa_1').hide();
                //$('.pcostcenter_1').hide();
            }
        });
        $('#pcc_no_1').change(function (e) {
            var pcc_no = $(this).val();
            localStorage.setItem('pcc_no_1', pcc_no);
            var CardType = null;
            var ccn1 = pcc_no.charAt(0);
            if (ccn1 == 4)
                CardType = 'Visa';
            else if (ccn1 == 5)
                CardType = 'MasterCard';
            else if (ccn1 == 3)
                CardType = 'Amex';
            else if (ccn1 == 6)
                CardType = 'Discover';
            else
                CardType = 'Visa';

            $('#pcc_type_1').select2("val", CardType);
        });
		
		  $('.roomsbtn12').click(function () {
			 
			  var $table = $(this).val();
                     var $test = $('#amount_1').val();
					 var $custid = $('#roomcustid'+$table).val();
           $("#whole_amount_1").val($test);  
		    $("#room_no").val($table);
		 $("#room_cust").val($custid);
$('#roomsdiv').hide();		   
    });
		
        $('.swipe').keypress(function (e) {

            //var payid = $(this).attr('id'),
            var id = 1; //payid.substr(payid.length - 1);
            var TrackData = $(this).val();
            if (e.keyCode == 13) {
                e.preventDefault();

                var p = new SwipeParserObj(TrackData);

                if (p.hasTrack1) {
                    // Populate form fields using track 1 data
                    var CardType = null;
                    var ccn1 = p.account.charAt(0);
                    if (ccn1 == 4)
                        CardType = 'Visa';
                    else if (ccn1 == 5)
                        CardType = 'MasterCard';
                    else if (ccn1 == 3)
                        CardType = 'Amex';
                    else if (ccn1 == 6)
                        CardType = 'Discover';
                    else
                        CardType = 'Visa';

                    $('#pcc_no_' + id).val(p.account);
                    $('#pcc_holder_' + id).val(p.account_name);
                    $('#pcc_month_' + id).val(p.exp_month);
                    $('#pcc_year_' + id).val(p.exp_year);
                    $('#pcc_cvv2_' + id).val('');
                    $('#pcc_type_' + id).val(CardType);

                }
                else {
                    $('#pcc_no_' + id).val('');
                    $('#pcc_holder_' + id).val('');
                    $('#pcc_month_' + id).val('');
                    $('#pcc_year_' + id).val('');
                    $('#pcc_cvv2_' + id).val('');
                    $('#pcc_type_' + id).val('');
                }

                $('#pcc_cvv2_' + id).focus();
            }

        }).blur(function (e) {
            $(this).val('');
        }).focus(function (e) {
            $(this).val('');
        });
       
    });
</script>
