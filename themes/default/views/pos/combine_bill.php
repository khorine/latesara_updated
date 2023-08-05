<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Combine_Bill'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("pos/combine_bill/" . $inv->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <?php if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("reference_no", "reference_no"); ?>
                        <?= form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $inv->reference_no), 'class="form-control tip" id="reference_no" required="required" readonly="true"'); ?>
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
                            <div class="col-sm-6" style="display: none">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= "<b>Amount Due*</b>"//lang("amount", "amount_1"); ?>
                                        <input name="amount-paid" type="text" id="amount_1"
                                               value="<?= $inv->grand_total - $inv->paid ?>"
                                               class="pa form-control kb-pad amount" required="required"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= lang("Combine to Bill No:", "bill_no"); ?>
                                    
											<?php
                        $cat[''] = "";
                        foreach ($bills as $category) {
                            $cat[$category->id] = $category->id;
                        }
                        echo form_dropdown('bill_no', $cat, (isset($_POST['bill_no']) ? $_POST['bill_no'] : ''), 'class="form-control select" id="bill_no" placeholder="' . lang("select") . " " . lang("Bill") . '" required="required" style="width:100%"')
                        ?>
                                    
                                </div>
                            </div>

                        </div>
                        
                       
                        <div class="clearfix"></div>              
                        
               
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_payment7','Combine Bill', 'class="btn btn-primary"'); ?>

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
                $('.pcc_1').show();
                $('#swipe_1').focus();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                 $('.pmpesa_1').hide();
                $('.pcostcenter_1').hide();
                $('#cheque_no_1').focus();
            } else if (p_val == 'mpesa') {
            
               //alert(p_val);
                $('.pcc_1' ).hide();
                $('.pcash_1' ).hide();
                $('.pcheque_1' ).hide();
                $('.pcostcenter_1' ).hide();
                $('.pmpesa_1' ).show();
                //$('.pcc_' + pa_no).hide();
                //$('.pcash_' + pa_no).show();
               // $('#payment_note_').show();
                $('#mpesa_txn_no_' ).focus();           
            } else if (p_val == 'costcenter') {
                //alert(p_val);
                $('.pcc_1' ).hide();
                $('.pcash_1' ).hide();
                $('.pcheque_1' ).hide();
                 $('.pcostcenter_1').show();
                $('.pmpesa_1' ).hide();
               // $('#payment_note_1' ).show();
                $('#cost_center_no_1' ).focus();
            }else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                    $('.pmpesa_1').hide();
                $('.pcostcenter_1').hide();
            }
            if (p_val == 'gift_card') {
                    //$('.pmpesa_1').hide();
                //$('.pcostcenter_1').hide();
                $('.gc').show();
                $('#gift_card_no').focus();
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
        $("#date").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
            language: 'sma',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        }).datetimepicker('update', new Date());
    });
</script>
