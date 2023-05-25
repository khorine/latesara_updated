
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Stock Report'); ?></h4>
        </div>
        <?php //$attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        //echo form_open_multipart("reports/stockSheetmngnt", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

                       <div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group ">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
				  
                  <input type="text" class="form-control pull-right datetime" name="sdate" id="sdate" value="">
					<input type="hidden" name="user" id="user" value="<?= $this->session->userdata('user_id'); ?>">
				</div>
                </div>
				 </div>
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">To</label>
                  <div class="input-group ">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
				  
                  <input type="text" class="form-control pull-right datetime" name="edate" id="edate" value="">
					
				</div>
                </div>
				 </div>
				</div>
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
				   <?php
                                        $wh[''] = '';
                                        foreach ($warehouses as $warehouse) {
                                            $wh[$warehouse->id] = $warehouse->name;
                                        }
                                        echo form_dropdown('stockwarehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="stockwarehouse" class="form-control pos-input-tip" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" style="width:100%;" ');
                                        ?>
				
					</div>
				 </div>
				</div>


        </div>
        <div class="modal-footer">
            <input type="button" id="get_stockmm" value="GENERATE REPORT" class="input btn btn-primary">

        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('#add-customer-form').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }, excluded: [':disabled']
        });
        $('select.select').select2({minimumResultsForSearch: 6});
        fields = $('.modal-content').find('.form-control');
        $.each(fields, function () {
            var id = $(this).attr('id');
            var iname = $(this).attr('name');
            var iid = '#' + id;
            if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
                $("label[for='" + id + "']").append(' *');
                $(document).on('change', iid, function () {
                    $('form[data-toggle="validator"]').bootstrapValidator('revalidateField', iname);
                });
            }
        });
		
					$( "#get_stockmm" ).click(function() {
    $sdate = $( "#sdate" ).val();
		$edate = $( "#edate" ).val();
		$user = $( "#user" ).val();
		$stockwarehouse = $( "#stockwarehouse" ).val(); 
	
        window.open("../../latesara/reports/stockSheetmngnt/?sdate="+$sdate+"&edate="+$edate+"&user="+$user+"&stockwarehouse="+$stockwarehouse);

    });
    });
</script>
