<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_payment'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("sales/bulk_payment/", $attrib); ?>
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
                        <?= form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $payment_ref), 'class="form-control tip" id="reference_no" required="required"'); ?>
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
                                        <input name="amount-paid" type="text" id="amount_1" value="<?= $inv->grand_total - $inv->paid ?>" class="pa form-control kb-pad amount" required="required"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= lang("paying_by", "paid_by_1"); ?>
                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by" required="required">
                                        <option value="cash"><?= lang("cash"); ?></option>
                                        <option value="CC"><?= lang("CC"); ?></option>
                                        <option value="mpesa"><?= "M-Pesa"; ?></option>
                                        <option value="costcenter">Cost Center</option>
                                        <option value="gift_card"><?= lang("gift_card"); ?></option>
                                        <option value="Cheque"><?= lang("cheque"); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= lang("note", "note_1"); ?>
                                        <?= form_textarea('note', '', 'class="form-control" id="note_1"'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= lang("attachment", "attachment_1"); ?>
                                        <?= form_upload('attachment', '', 'id="attachment_1" class="form-control"'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pcash_1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("amount", "cash_1"); ?>
                                            <input name="amount[]" type="text" id="cash_1" class="form-control kb-pad amount" required="required"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("date", "date_1"); ?>
                                            <?= form_input('date[]', '', 'class="form-control datetime" id="date_1" required="required"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pcc_1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("amount", "cc_1"); ?>
                                            <input name="amount[]" type="text" id="cc_1" class="form-control kb-pad amount" required="required"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("card_holder", "card_holder_1"); ?>
                                            <?= form_input('card_holder[]', '', 'class="form-control" id="card_holder_1"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pmpesa_1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("amount", "mpesa_1"); ?>
                                            <input name="amount[]" type="text" id="mpesa_1" class="form-control kb-pad amount" required="required"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("transaction_id", "transaction_id_1"); ?>
                                            <?= form_input('transaction_id[]', '', 'class="form-control" id="transaction_id_1"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pcostcenter_1" style="display:none;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            Cost Center Number
                                            <select name="cost_center_no" id="cost_center_no_1" class="form-control cost_center_no">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("amount", "costcenter_1"); ?>
                                            <input name="amount[]" type="text" id="costcenter_1" class="form-control kb-pad amount" required="required"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pgift_card_1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("amount", "gift_card_1"); ?>
                                            <input name="amount[]" type="text" id="gift_card_1" class="form-control kb-pad amount" required="required"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("gift_card_no", "gift_card_no_1"); ?>
                                            <?= form_input('gift_card_no[]', '', 'class="form-control" id="gift_card_no_1"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pcheque_1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("amount", "cheque_1"); ?>
                                            <input name="amount[]" type="text" id="cheque_1" class="form-control kb-pad amount" required="required"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("bank", "bank_1"); ?>
                                            <?= form_input('bank[]', '', 'class="form-control" id="bank_1"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr/>
                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="button" id="add_payment_row"><i class="fa fa-plus"></i> <?= lang('add_payment_row'); ?></button>
                            <button class="btn btn-primary" type="button" id="delete_payment_row"><i class="fa fa-trash-o"></i> <?= lang('delete_payment_row'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?= lang('submit'); ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close'); ?></button>
        </div>
        <?= form_close(); ?>
    </div>
</div>
