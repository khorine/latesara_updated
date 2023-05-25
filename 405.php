<!DOCTYPE html>
<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 include '../../modules/functions.php'; 
@include '../../includes/database.php';
if(!(isset($_SESSION['puser_id']))) {
  
header("location:../../index.php"); 
    
}else{
	$db=new MySQLDatabase();
$con = $db->open_connection();
$accessgroup = $_SESSION['pusergroup'];
$qr7="SELECT reports FROM user_group_priviledge WHERE usergroup_id = '$accessgroup' LIMIT 1";
$qry7=mysqli_query($con,$qr7)or die(mysqli_error($con));
while ($row7= mysqli_fetch_array($qry7)) { $reports=$row7['reports'];   }
	//$success=loadgrppermission();
	//die($success["dashpriviledge"]);
	if($reports!=1){
		header("location:../../pages/tables/accounts.php");
	}
	
?>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Property Manager | Reports</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
 <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
	
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

<script src="../../js/jquery.msgBox.js" type="text/javascript"></script>
<script type='text/javascript' src='../../js/jquery-1.9.1.js'></script>
<script type='text/javascript' src='../../js/jquery.js'></script>
<script type='text/javascript' src='../../js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="./css/jquery.autocomplete.css" />
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

 <?php include '../../modules/header.php' ?>
  <!-- Left side column. contains the logo and sidebar -->
 <?php include_once "../../aside.php" ?>  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reports
        <small>advanced tables</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Data tables</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">PROPERTY REPORTS</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
		<script type="text/javascript">
	
	

$(document).ready(function() {
	$("#sorttenants").hide();
	$("#sortproperty").hide();//hide field for soting property
    $("#rentalrprt").hide();//hide field for soting property
	$("#vacancydet_rprt").hide();
	 $("#expected_rprt").hide();
	  $("#popup-box-1").hide();
	   $("#prop_rprt").hide();
	    $("#comprehensive_rprt").hide();
	  
	  
	  
	     $('#properties').change(function() { 
       propid=$("#properties").val();
     $.get("../../modules/loadcheckbox.php?propid="+$("#properties").val(), function(data){ 
         if($('#docsavailable').empty()){
        $('#docsavailable').append(data); 
    }
    else{
        $('#docsavailable').replaceWith(data); 
        
    }
    });
 
       $( "#load" ).load( "../../modules/searchbynamecall.php?propid="+$("#properties").val(), function() {
  //alert( "Load was performed." );
});  
    })

	$("#load").click (function() {
		//alert('Test');
   //event.preventDefault();
alert("photo "+$("#ejamaatlink").attr("class")+" will be used in reports"); 
   $('#searchfield').val($("#ejamaatlink").attr("title"));
    });
 
	  
 $("#tenanttarget").click(function() {
	 
	
       $("#sorttenants").show();
	   //$("#sorttenants").hide();
	$("#sortproperty").hide();//hide field for soting property
    $("#rentalrprt").hide();//hide field for soting property
	$("#vacancydet_rprt").hide();
	 $("#expected_rprt").hide();
	  $("#popup-box-1").hide();

    });
	 $( "#closetenant" ).click(function() {
  
        $("#sorttenants").hide();});//close window on X

 $( "#propertytarget" ).click(function() {
		
        $("#sortproperty").show(); 
		$("#sorttenants").hide();
    $("#rentalrprt").hide();//hide field for soting property
	$("#vacancydet_rprt").hide();
	 $("#expected_rprt").hide();
	  $("#popup-box-1").hide();
		});
    
        $( "#monthlyrenttrgt" ).click(function() {
  
        $("#rentalrprt").show(); });
		
		
           $( "#vacancy_details" ).click(function() {
  
        $("#vacancydet_rprt").show(); });
		
		 $( "#comprehensive" ).click(function() {
  
        $("#comprehensive_rprt").show(); });
		 $( "#soldrpt" ).click(function() {
  
        $("#sold_rprt").show(); });
		$( "#incmexpFsta" ).click(function() {
  
        $("#incmex_rprt").show(); });
		
		$( "#report2" ).click(function() {
  
        $("#incmex_rprt2").show(); });
		
		$( "#reportb" ).click(function() {
  
        $("#bnking_rprt").show(); });
		
		$( "#closev_soldrprt" ).click(function() {
  
        $("#sold_rprt").hide(); });
		$( "#closev_ierprt" ).click(function() {
  
        $("#incmex_rprt").hide(); });
		$( "#closev_ierprt" ).click(function() {
  
        $("#incmex_rprt").hide(); });
		$( "#closev_ierprt2" ).click(function() {
  
        $("#incmex_rprt2").hide(); });
		 $( "#closev_comprprt" ).click(function() {
  
        $("#comprehensive_rprt").hide(); });
		
           
		              $( "#expected_details" ).click(function() {
  
        $("#expected_rprt").show(); });
				              $( "#actualrnt" ).click(function() {
  
        $("#actual_rprt").show(); });
		$( "#prop_report" ).click(function() {
  
        $("#prop_rprt").show(); });
				$( "#closeprop_rpt" ).click(function() {
  
        $("#prop_rprt").hide(); });
		   
		   
                   $( "#closerentalrprt" ).click(function() {
  
        $("#rentalrprt").hide(); });
		
    $( "#closev_rprt" ).click(function() {
  
        $("#vacancydet_rprt").hide(); });
	    $( "#closev_expctdrprt" ).click(function() {
  
        $("#expected_rprt").hide(); });	
		
		$( "#closev_actualrprt" ).click(function() {
  
        $("#actual_rprt").hide(); });	
		
		$( "#closev_bnkprt" ).click(function() {
  
        $("#bnking_rprt").hide(); });	
		
		
       	   $( "#closeproperty" ).click(function() {
  
        $("#sortproperty").hide(); }); //close window on X
	
   
    
  $( "#propertylist" ).click(function() {
    
       window.open("../../modules/defaultreports.php?report=propertylist&id="+$("#propertysort").val()+"&sort="+$("#ascdescproperty").val()+"&property_units="+$("#property_units").val()+"&property_type="+$("#property_type").val())
		
    });
      $( "#mnthlyrentrpt" ).click(function() {
    
       window.open("../../modules/rentbal.php?propid="+$("#propertynme").val());
		
	
    });
	      $( "#vacancyrpt" ).click(function() {
    
       window.open("../../modules/vacancydet.php?unit_id="+$("#v_propertynme").val()+"&vfrom_date="+$("#vfrom_date").val()+"&vto_date="+$("#vto_date").val());
		
	
    });
		      $( "#comprerpt" ).click(function() {
			if($("#comp_rptype").val()=='detailed'){
       window.open("../../modules/comprehensive.php?unit_id="+$("#comp_propertynme").val()+"&from_comp_date="+$("#from_comp_date").val()+"&to_comp_date="+$("#to_comp_date").val());
			}else{
				
		 window.open("../../modules/comprehensivesumm.php?unit_id="+$("#comp_propertynme").val()+"&from_comp_date="+$("#from_comp_date").val()+"&to_comp_date="+$("#to_comp_date").val());
		
			}
	
    });
	      $( "#soldrerpt" ).click(function() {
			if($("#sold_rptype").val()=='summary'){
       window.open("../../modules/soldrpt.php?unit_id="+$("#sold_propertynme").val()+"&from_comp_date="+$("#from_sold_date").val()+"&to_comp_date="+$("#to_sold_date").val());
			}else{
				
		 window.open("../../modules/soldsumm.php?unit_id="+$("#sold_propertynme").val()+"&from_comp_date="+$("#from_sold_date").val()+"&to_comp_date="+$("#to_sold_date").val());
		
			}
	
    });
		      $( "#iererpt" ).click(function() {
			if($("#ie_rptype").val()=='summary'){
			window.open("../../modules/inexprpt.php?unit_id="+$("#ie_propertynme").val()+"&from_comp_date="+$("#from_ie_date").val()+"&to_comp_date="+$("#to_ie_date").val());
			}else{
				
		 window.open("../../modules/iexpdet.php?unit_id="+$("#ie_propertynme").val()+"&from_comp_date="+$("#from_ie_date").val()+"&to_comp_date="+$("#to_ie_date").val());
		
			}
	
    });
		      $( "#iererpt2" ).click(function() {
			if($("#ie_rptype2").val()=='summary'){
			window.open("../../modules/inexprpt2.php?prop_id="+$("#ie2_propertynme").val()+"&from_comp_date="+$("#from_ie_date2").val()+"&to_comp_date="+$("#to_ie_date2").val());
			}else{
				
		 window.open("../../modules/iexpdet2.php?prop_id="+$("#ie_propertynme").val()+"&from_comp_date="+$("#from_ie_date2").val()+"&to_comp_date="+$("#to_ie_date2").val());
		
			}
	
    });
	
	      $( "#bnkrpt" ).click(function() {
			window.open("../../modules/bnkingrpt.php?from_date="+$("#from_bnk_date").val()+"&to_date="+$("#to_bnk_date").val());
				
    });
	
	
	 $( "#expected_rentalIcme" ).click(function() {
    
       window.open("../../modules/expectedincme.php?unit_id="+$("#expctd_propertynme").val()+"&usd="+$("#usd").val()+"&inr="+$("#inr").val()+"&from="+$("#expcfrom_date").val()+"&to="+$("#expcto_date").val());
		
	
    });
	$( "#actual_rentalIcme" ).click(function() {
    
       window.open("../../modules/summrent.php?unit_id="+$("#actual_propertynme").val()+"&usd="+$("#actual_usd").val()+"&inr="+$("#actual_inr").val()+"&from_date="+$("#actual_from_date").val()+"&to_date="+$("#actual_to_date").val());
		
	
    });
    
     $( "#tenantlist" ).click(function() {
    
       window.open("../../modules/defaultreports.php?report=tenantlist&propertyid="+$("#propertyname2").val()+"&id="+$("#tenantsort").val()+"&sort="+$("#ascdesctenant").val()+"&date_from="+$("#date_from").val()+"&date_to="+$("#date_to").val())
		
	
    });

	
	$("#groupchecked").click(function(){
		
  var names="";
  
  $('input:checked').each(function() { names+=($(this).attr("value")+'$');});
  if (names===""){
	  
   alert("Select property or documents for reporting!"); 
  }else{
    if($('#footer').val()==""){alert("Please insert footer");  }else{
        alert(names);
        var path=$('#searchfield').val();
        if (path==""){path=0;}
		//alert(path);
       window.open("../../modules/prepare_report.php?propid="+propid+"&footer="+$('#footer').val()+"&docstring="+names+"&path="+path);
       
    }
  }
});

});

    


</script>
<style>
.ui-menu { width: 150px;
}
</style>

<div class="col-12">

<div class="panel panel-default">
<div class="panel-heading">Summary Reports</div>
  <div class="panel-body">
  <div class="margin">
	
   
<div class="btn-group">
   <button type="button" class="btn btn-danger" id="expected_details">Expected Rental Income</button>
   </div>
   <div class="btn-group">
       <button type="button" class="btn btn-info" id="actualrnt">Actual Rent Income</button>
	   </div>
	   <div class="btn-group">
	<button type="button" class="btn btn-warning" id="vacancy_details">Vacancy Details</button>
	</div>
	<div class="btn-group">
	<button type="button" class="btn btn-primary" id="comprehensive">Comprehensive Report</button>
  </div>
  <div class="btn-group">
	<button type="button" class="btn btn-primary" id="soldrpt">Sold Report</button>
  </div>
  <div class="btn-group">
	<button type="button" class="btn btn-info" id="incmexpFsta">Income and Expenditure</button>
  </div>
  <div class="btn-group">
	<button type="button" class="btn btn-primary" id="report2">Report2</button>
  </div>
  <div class="btn-group">
	<button type="button" class="btn btn-primary" id="reportb">Banking</button>
  </div>
  </div>
  
</div>
</div>
<div class="panel panel-default">
<div class="panel-heading">Detailed Reports</div>
  <div class="panel-body"> 
  <div class="margin">
  <div class="btn-group">
    <button type="button" class="btn btn-info" id="monthlyrenttrgt">Monthly Rental</button>
  </div>
  <div class="btn-group">
   <button type="button" class="btn btn-success popup-link-1" id="prop_report">Property Report</button>
   </div>
   <div class="btn-group">
    <button type="button" class="btn btn-warning" id="tenanttarget">Tenants list</button>
	</div>
	<div class="btn-group">
    <button type="button" class="btn btn-primary" id="propertytarget">Property list</button>
</div>
      <!--sort propertyreport -->
<div class="modal fade in sm" id="sortproperty">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h5><u>PROPERTY LIST</u>
 <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeproperty">
                  <span aria-hidden="true">&times;</span></button>

</div>
 <div class="modal-body">
 <form role="form">
 <div class="box-body">
  <div class="row">
 <div class="col-lg-6">
 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT UNITS</label>
				  <select id="property_units" name="property_units" class="form-control input" multiple="multiple" >
<option selected="selected" value="all">All Properties</option>   
<?php populatepropunits(); ?>
    </select>
                  
                </div>
			</div>
			 <div class="col-lg-6">
 <div class="form-group">
 
                  <label for="exampleInputEmail1">Property Type</label>
				  <select id="property_type" name="property_type" class="form-control input">
<option selected="selected" value="all">---</option>   
<option  value="2">Commercial</option> 
<option value="2">Non-Commercial</option> 
    </select>
                  
                </div>
			</div>
			</div>
 <div class="row">
 <div class="col-lg-6"> 
 <div class="form-group">
 
                  <label for="exampleInputEmail1">Sort By</label>
                  <select id="propertysort" name="propertysort"  class="input form-control">			 
				  <?php searchparametersproperty(); ?>
				  </select>
                </div></div>
				<div class="col-lg-6"> 
				<div class="form-group">
                  <label for="exampleInputFile">Asc/Desc</label>
                  <select id="ascdescproperty" name="ascdesproperty"  class="input form-control">
				  <option selected="selected" value="ASC">Ascending</option>
				<option selected="selected" value="DESC">Descending</option>
					</select>
                </div></div>
				</div>
</div>
<div class="box-footer">
               
				<input type="button" id="propertylist" value="GENERATE REPORT" class="input btn btn-primary">
              </div></form>
</div>
</div>
</div>
</div>
</div>
  
  </div>
</div>
</div>


<!--sort sorttenants -->
<div class="modal fade in sm" id="sorttenants">
<div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><center><u>Sort Tenant Report</u>
 <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closetenant">
                  <span aria-hidden="true">&times;</span></button>
</center>
</div>
 <div class="modal-body">
             <form role="form">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Select  Property</label>
                  <select id="propertyname2" name="propertyname2"  style="width:305px;" class="input form-control">
				  <option selected="selected" value="all">---</option> 
				  <?php echo   populateproperties(); ?>
				  </select>
                </div>
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="date_from" id="date_from" value="<?php echo date('Y-m-d')?>">
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
                  <input type="date" class="form-control pull-right" name="date_to" id="date_to" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div>
				<div class="row">
				<div class="col-lg-6"> 
                <div class="form-group">
                  <label for="exampleInputPassword1">Sort By</label>
                  <select id="tenantsort" name="tenantsort"   class="input form-control">
				  <option selected="selected" value="">---</option>
				  <?php echo searchparameterstenant(); ?>
				  </select>
                </div></div>
				<div class="col-lg-6"> 
                <div class="form-group">
                  <label for="exampleInputFile">Asc/Desc</label>
                  <select id="ascdesctenant" name="ascdesctenant"  class="input form-control">
				  <option selected="selected" value="ASC">Ascending</option>
				<option selected="selected" value="DESC">Descending</option>
					</select>
                </div>
                </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
               
				<input type="button" id="tenantlist" value="GENERATE REPORT" class="input btn btn-primary">
              </div>
            </form>
   
</div></div></div></div>


<!--Expected Rental Income -->
<div class="modal fade in" id="expected_rprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h4><u>Expected Rental Income</u><a href="#" id="closev_expctdrprt" style="float:right;font-size:24px;"><img src="../../images/lightbox-btn-close.gif"></a></h4></center><br/>
</div>
 <div class="modal-body">
 <form role="form">
 <div class="box-body">
 <div class="row">
				<div class="col-lg-12"> 
  <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT UNITS</label>
				  <select id="expctd_propertynme" name="expctd_propertynme" class="form-control input" multiple="multiple" >
<option selected="selected" value="all">---All---</option>   
<?php populatepropunits(); ?>
    </select>
                  
                </div>
				</div>
				</div>
				<div class="row" style="display:none">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" name="expcfrom_date" id="expcfrom_date" value="<?php echo date('Y-m-d')?>">
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
                  <input type="text" class="form-control pull-right" name="expcto_date" id="expcto_date" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div> 
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">USD</label>
       
                  <input type="text" class="form-control pull-right" name="usd" id="usd" >
            
                </div>
				 </div>
				 <div class="col-lg-6"> 
			<div class="form-group">
                  <label for="exampleInputEmail1">INR</label>
       
                  <input type="text" class="form-control pull-right" name="inr" id="inr" >
            
                </div>
				 </div>
				</div>

</div>
<div class="box-footer">
               
				<input type="button" id="expected_rentalIcme" value="GENERATE REPORT" class="input btn btn-primary">
              </div>
</form></div>

</div>
</div>
</div>

<!--Expected Rental Income -->
<div class="modal fade in" id="actual_rprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h4><u>Actual Rental Income</u><a href="#" id="closev_actualrprt" style="float:right;font-size:24px;"><img src="../../images/lightbox-btn-close.gif"></a></h4></center><br/>
</div>
 <div class="modal-body">
 <form role="form">
 <div class="box-body">
  <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT UNITS</label>
				  <select id="actual_propertynme" name="actual_propertynme"  class="input form-control" multiple="multiple">
<option selected="selected" value="all">--All Units--</option>   
<?php populatepropunits(); ?>
    </select>
                  
                </div>
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
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">USD</label>
       
                  <input type="text" class="form-control pull-right" name="actual_usd" id="actual_usd" >
            
                </div>
				 </div>
				 <div class="col-lg-6"> 
			<div class="form-group">
                  <label for="exampleInputEmail1">INR</label>
       
                  <input type="text" class="form-control pull-right" name="actual_inr" id="actual_inr" >
            
                </div>
				 </div>
				</div>

</div>
<div class="box-footer">
               
				<input type="button" id="actual_rentalIcme" value="GENERATE REPORT" class="input btn btn-primary">
              </div>
</form></div>

</div>
</div>
</div>


<!--//Comprehensive Report -->
<div class="modal fade in" id="comprehensive_rprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h5><u>COMPREHENSIVE REPORT</u><a href="#" id="closev_comprprt" style="float:right;font-size:24px;"><img src="../../images/lightbox-btn-close.gif"></a></h5></center><br/>
</div>
<div class="modal-body">
 <form role="form">
 <div class="box-body">

 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT UNITS</label>
				  <select id="comp_propertynme" name="comp_propertynme"  class="input form-control" multiple="multiple">
<option selected="selected" value="all">---</option>   
<?php populatepropunits(); ?>
    </select>
                </div>
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="from_comp_date" id="from_comp_date" value="<?php echo date('Y-m-d')?>">
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
                  <input type="date" class="form-control pull-right" name="to_comp_date" id="to_comp_date" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div>
<div class="form-group">
 
                  <label for="exampleInputEmail1">Report Type</label>
				  <select id="comp_rptype" name="comp_rptype"  class="input form-control">
<option  value="detailed">Detailed</option>   
<option selected="selected" value="summary">Summary</option>
    </select>
                  
                </div>

</div>
<div class="box-footer">
               
				<input type="button" id="comprerpt" value="GENERATE REPORT" class="input btn btn-primary">
              </div>

</form></div>

</div>
</div>
</div>

<!--//Sold Report -->
<div class="modal fade in" id="sold_rprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h5><u>SOLD REPORT</u><a href="#" id="closev_soldrprt" style="float:right;font-size:24px;"><img src="../../images/lightbox-btn-close.gif"></a></h5></center><br/>
</div>
<div class="modal-body">
 <form role="form">
 <div class="box-body">

 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT UNITS</label>
				  <select id="sold_propertynme" name="sold_propertynme"  class="input form-control" multiple="multiple">
<option selected="selected" value="all">--All Units-</option>   
<?php populatepropunits(); ?>
    </select>
                </div>
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="from_sold_date" id="from_sold_date" value="<?php echo date('Y-m-d')?>">
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
                  <input type="date" class="form-control pull-right" name="to_sold_date" id="to_sold_date" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div>
<div class="form-group">
 
                  <label for="exampleInputEmail1">Report Type</label>
				  <select id="sold_rptype" name="sold_rptype"  class="input form-control">
<option selected="selected" value="summary">Summary</option>
<option  value="detailed">Detailed</option>   
    </select>
                  
                </div>

</div>
<div class="box-footer">
               
				<input type="button" id="soldrerpt" value="GENERATE REPORT" class="input btn btn-primary">
              </div>

</form></div>

</div>
</div>
</div>

<div class="modal fade in" id="incmex_rprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h5><u>INCOME AND EXPENDITURE REPORT</u><a href="#" id="closev_ierprt" style="float:right;font-size:24px;"><img src="../../images/lightbox-btn-close.gif"></a></h5></center><br/>
</div>
<div class="modal-body">
 <form role="form">
 <div class="box-body">

 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT UNITS</label>
				  <select id="ie_propertynme" name="ie_propertynme"  class="input form-control" multiple="multiple">
<option selected="selected" value="all">--All Units-</option>   
<?php populatepropunits(); ?>
    </select>
                </div>
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="from_ie_date" id="from_ie_date" value="<?php echo date('Y-m-d')?>">
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
                  <input type="date" class="form-control pull-right" name="to_ie_date" id="to_ie_date" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div>
<div class="form-group">
 
                  <label for="exampleInputEmail1">Report Type</label>
				  <select id="ie_rptype" name="ie_rptype"  class="input form-control">
<option selected="selected" value="summary">Summary</option>
<option  value="detailed">Detailed</option>   
    </select>
                  
                </div>

</div>
<div class="box-footer">
               
				<input type="button" id="iererpt" value="GENERATE REPORT" class="input btn btn-primary">
              </div>

</form></div>

</div>
</div>
</div>

<div class="modal fade in" id="incmex_rprt2">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h5><u>INCOME AND EXPENDITURE REPORT2</u><a href="#" id="closev_ierprt2" style="float:right;font-size:24px;"><img src="../../images/lightbox-btn-close.gif"></a></h5></center><br/>
</div>
<div class="modal-body">
 <form role="form">
 <div class="box-body">
<div class="row">
<!--<div class="col-lg-6"> 
 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT UNITS</label>
				  <select id="ie2_unitnme" name="ie2_unitnme"  class="input form-control" multiple="multiple">
<option selected="selected" value="all">--All Units-</option>   
<?php populatepropunits(); ?>
    </select>
                </div></div> -->
				<div class="col-lg-6"> 
 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT Property</label>
				  <select id="ie2_propertynme" name="ie2_propertynme"  class="input form-control" multiple="multiple">
<option selected="selected" value="all">--All Units-</option>   
<?php populateproperties(); ?>
    </select>
                </div></div>
				</div>
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="from_ie_date2" id="from_ie_date2" value="<?php echo date('Y-m-d')?>">
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
                  <input type="date" class="form-control pull-right" name="to_ie_date2" id="to_ie_date2" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div>
<div class="form-group">
 
                  <label for="exampleInputEmail1">Report Type</label>
				  <select id="ie_rptype2" name="ie_rptype"  class="input form-control">
<option selected="selected" value="summary">Summary</option>
<option  value="detailed">Detailed</option>   
    </select>
                  
                </div>

</div>
<div class="box-footer">
               
				<input type="button" id="iererpt2" value="GENERATE REPORT" class="input btn btn-primary">
              </div>

</form></div>

</div>
</div>
</div>

<div class="modal fade in" id="bnking_rprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h5><u>Banking Report</u><a href="#" id="closev_bnkprt" style="float:right;font-size:24px;"><img src="../../images/lightbox-btn-close.gif"></a></h5></center><br/>
</div>
<div class="modal-body">
 <form role="form">
 <div class="box-body">

				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="from_bnk_date" id="from_bnk_date" value="<?php echo date('Y-m-d')?>">
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
                  <input type="date" class="form-control pull-right" name="to_bnk_date" id="to_bnk_date" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div>


</div>
<div class="box-footer">
               
				<input type="button" id="bnkrpt" value="GENERATE REPORT" class="input btn btn-primary">
              </div>

</form></div>

</div>
</div>
</div>



<!--//Vacancy Details Report -->
<div class="modal fade in" id="vacancydet_rprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h4><u>Vacancy Details Report</u><a href="#" id="closev_rprt" style="float:right;font-size:24px;"><img src="../../images/lightbox-btn-close.gif"></a></h4></center><br/>
</div>
<div class="modal-body">
 <form role="form">
 <div class="box-body">

 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT UNITS</label>
				  <select id="v_propertynme" name="v_propertynme"  class="input form-control" multiple>
<option selected="selected" value="all">---</option>   
<?php populatepropunits(); ?>
    </select>
                  
                </div>
				<div class="row">
				<div class="col-lg-6"> 
				 <div class="form-group">
                  <label for="exampleInputEmail1">From</label>
                  <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="vfrom_date" id="vfrom_date" value="<?php echo date('Y-m-d')?>">
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
                  <input type="date" class="form-control pull-right" name="vto_date" id="vto_date" value="<?php echo date('Y-m-d')?>">
                </div>
                </div>
				 </div>
				</div>


</div>
<div class="box-footer">
               
				<input type="button" id="vacancyrpt" value="GENERATE REPORT" class="input btn btn-primary">
              </div>

</form></div>

</div>
</div>
</div>

<!--sorting property report-->
<div class="modal fade in" id="prop_rprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
  <center><div class="top"><h5>Documents On Property Report</h5></div></center><div class="close" id="closeprop_rpt">X</div>
  </div>
  <div class="modal-body">
 <form role="form">
  <div class="box-body">
  
 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT PROPERTY FOR REPORT :</label>
				  <select id="properties" name="propertyname"  class="input form-control">
<option selected="selected" value="all">---</option>   
<?= populateproperties(); ?>
    </select>
                  
                </div>
<hr/>

 <div class="form-group">
 
                  <label for="exampleInputEmail1"> DOCUMENTS TO INCLUDE: | FOOTER:</label>
				  <input id="footer" name="footer"  class="input form-control">
<div id="docsavailable">
</div>

                  
                </div>

<B><font color=\'orange\'>SELECT PHOTO TO APPEAR ON REPORT</font></B>
<div id="load"></div>
<center><input id="searchfield" type="hidden" /></center>
<br/>
</div>
<div class="box-footer">
               
				<input type="button" id="groupchecked" value="GENERATE REPORT" class="input btn btn-primary">
              </div>
</form></div>

</div>
</div>
</div>


<!--Monthly Report -->
<div class="modal fade in" id="rentalrprt">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><center><h5><u>MONTHLY RENTAL REPORT</u>
 <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closerentalrprt">
                  <span aria-hidden="true">&times;</span></button>

</div>
 <div class="modal-body">
 <form role="form">
 <div class="box-body">
 <div class="row">
 <div class="col-lg-6"> 
 <div class="form-group">
 
                  <label for="exampleInputEmail1">SELECT PROPERTY</label>
                  <select id="propertynme" name="propertynme"  class="input form-control">
				<option selected="selected" value="all">---</option>				  
				  <?php populateproperties(); ?>
				  </select>
                </div></div>
				
				</div>
</div>
<div class="box-footer">
               
				<input type="button" id="mnthlyrentrpt" value="GENERATE REPORT" class="input btn btn-primary">
              </div></form>
</div>
</div>
</div>
</div>


          </div>
          <!-- /.box -->
		  <?php
@$db->close_connection();
?>
     
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 <?php include '../../footer.php' ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- Select2 -->
<script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- page script -->
<script>
    $('#expctd_propertynme').select2({
       // dropdownParent: $('#expected_rprt')
    });
	$('#sold_propertynme').select2({
       // dropdownParent: $('#expected_rprt')
    });
	$('#ie_propertynme').select2({
       // dropdownParent: $('#expected_rprt')
    });
	
	$('#actual_propertynme').select2({
       // dropdownParent: $('#expected_rprt')
    });
	
  $(function () {
	      //Initialize Select2 Elements
 
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
	$('#date_from').datepicker({
      autoclose: true
    })
  })
</script>
</body>
</html>
<?php 
}
?>
