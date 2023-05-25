<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="box-body">
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="actual_from_date" id="actual_from_date" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				 <div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">To</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="actual_to_date" id="actual_to_date" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div>
				

</div>
<div class="box-footer">
               
				<input type="button" id="actual_rentalIcme" value="GENERATE REPORT" class="input btn btn-primary">
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
    });
</script>
