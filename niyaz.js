   $(function(){
  
  var niyazArray=new Array();
  
  $("#login-div").slideDown(300);
  
  $("#binfo").live('click',function(){
      
  $("#login-message").removeClass("login-error").removeClass("login-progressing").removeClass("login-successful").addClass("login-info").html("<img align='left' height='40px' width='40px' src='../assets/images/info.png'/>&nbsp;Contact anjuman burhani for assistance");          
      
      
  });
  
  $("#niyazback").live('click',function(){
      // window.location="../niyazconfirmation/logout.php";
window.close();

  });
  
  $("#adminlogin").live('click',function(){
     
      admin_Login();
  });
  
  $("#niyazproceed").live('click',function(){
    
    
    if( $("input:checkbox:checked").length===0){
     
          alert("You must at least select one member")  ;
    }
else{
    for( var $i=0;$i<=$(".niyazejno").length;$i++){
		
          if($("#nyz"+$i).is(':checked')) {
           
             var $nyznmbr=document.getElementById("nyj"+$i).innerHTML;
             
                  niyazArray.push($nyznmbr);
				  
    }
else{
    
   }
          
            
    }
	var $visitormale=0; var $visitorfemale=0;
      var $sabilno=$("#hiddensabilno").val();
	  $visitormale=$("#malevis").val();
	  $visitorfemale=$("#femalevis").val();
      saveniyaz(niyazArray,$sabilno,$visitormale,$visitorfemale);
         
       niyazArray=[];
        
         }
  });
  
    $(".cancelreg").live('click',function(){
			var $id=$(this).attr("id");
           
           var $cell="cgl"+$id.substring(9);
		  // alert ($cell);
          //if($("#nyz"+$i).is(':checked')) {
           
             var $itsnumber= $cell.val();
             
                 
      cancelniyaz($itsnumber);
      
  });
  
 
  
   $("#usname").keypress(function(e){   
     
       if(e.keyCode===13){
            login();
       }
       
   });
   
   
    $("#pswd").keypress(function(e){   
       
 
       if(e.keyCode===13){
            login();
       }
   });
   
   
  
    $("#niyazlogin").live('click',function(){
       
      login();
    
    
 });
 
 function admin_Login(){
     
   var $usname=$.trim($("#adminusname").val());
    
    var $pwd=$.trim($("#adminpswd").val());
    
    
    if($usname==="" || $pwd===""){
        
       $("#login-message").removeClass("login-successful").removeClass("login-info").removeClass("login-progressing").addClass("login-error").html("<img align='left' height='40px' width='40px' src='../assets/images/cross.png'/>Username/password cannot be empty") ;            
    }
    
    else{
        
    
    var $dataString={usname:$usname,pwd:$pwd};
    
     
    var $urlString="../admin/login_data.php?action=login"; 

               $.ajax({                                                                        
                
                url: $urlString,
                
                type: "GET",
                
                dataType:"json",
                
                contentType: "application/json",
               
                cache: false,
                
                data:$dataString,
                
                success: function(response) {
                 
                  
                 
              if(response.id===1){
                   
                $("#login-message").css("display","none") ;  
                
                 window.location="../admin/?pageId=2"; 
                    
                 }  
             
             else{
                 $("#login-message").removeClass("login-successful").removeClass("login-info").removeClass("login-progressing").addClass("login-error").html("<img align='left' height='40px' width='40px' src='../../assets/images/cross.png'/>"+response.msg);          
             }
                },
              error:function (xhr, ajaxOptions, thrownError){
                    
              $("#login-message").removeClass("login-successful").removeClass("login-info").removeClass("login-progressing").addClass("login-error").html("<img align='left' height='40px' width='40px' src='../../assets/images/cross.png'/>"+thrownError);          
                   
                },
               beforeSend:function(){                       
                
             $("#login-message").removeClass("login-error").removeClass("login-info").removeClass("login-successful").addClass("login-progressing").html("<img align='left' height='40px' width='40px' src='../../assets/images/green_rot.gif'/>Requesting...");
                
                }
                 
  
              });      
        
    }
  
   
     
 }
 
 function login(){
      
     var $usname=$.trim($("#usname").val());
    
     var $pwd=$.trim($("#pswd").val());
    
    
    if($usname==="" || $pwd===""){
        
       $("#login-message").removeClass("login-successful").removeClass("login-info").removeClass("login-progressing").addClass("login-error").html("<img align='left' height='40px' width='40px' src='../assets/images/cross.png'/>Username/password cannot be empty") ;            
    }
    
    else{
        
    
    var $dataString={sabilno:$usname,ejamaat:$pwd};
     
    var $urlString="../niyazconfirmation/json_redirect.php?action=niyazlogin"; 

               $.ajax({                                                                        
                
                url: $urlString,
                
                type: "GET",
                
                dataType:"json",
                
                contentType: "application/json",
               
                cache: false,
                
                data:$dataString,
                
                success: function(response) {
                 
                  
                 
              if(response.id===1){
                   
                $("#login-message").css("display","none") ;  
                  
                  window.location="../niyazconfirmation/?pageId=1&file="+$usname;
                 }  
             
               else{
                 $("#login-message").removeClass("login-successful").removeClass("login-info").removeClass("login-progressing").addClass("login-error").html("<img align='left' height='40px' width='40px' src='../assets/images/cross.png'/>Wrong sabil file no /Ejamaat");          
               }
                },
              error:function (xhr, ajaxOptions, thrownError){
                    
              $("#login-message").removeClass("login-successful").removeClass("login-info").removeClass("login-progressing").addClass("login-error").html("<img align='left' height='40px' width='40px' src='../assets/images/cross.png'/>"+thrownError);          
                   
                },
               beforeSend:function(){                       
                
             $("#login-message").removeClass("login-error").removeClass("login-info").removeClass("login-successful").addClass("login-progressing").html("<img align='left' height='40px' width='40px' src='../assets/images/green_rot.gif'/>Requesting...");
                
                }
                 
  
              });      
        
    }
  
 }
 
 function cancelniyaz($itsnumber){  
   
     var $dataString={ejno:$itsnumber}; 
     
    var $urlString="../niyazconfirmation/json_redirect.php?action=niyazcancel"; 

                $.ajax({                                                                        
                
                url: $urlString,
                
                type: "GET",
                
                dataType:"json",
                
                contentType: "application/json",
               
                cache: false,
                
                data:$dataString,
                
                success: function(response) {
                 
                  
                 
              if(response.id===1){
                  
                 $("#d_progress").dialog("destroy"); 
                  
              
                  alert("Attendance Canceled");
				  basicLargeDialog("#d_progress",50,150);
                  location.reload();
                  $(".niyazejno").removeAttr("checked");
                  
                  //window.location.href="../mumin.php";
				  window.close();
                 }  
             
             else{
                alert("whoops! An error occured");           
             }
                },
              error:function (xhr, ajaxOptions, thrownError){
                    
                   
                   
                },
               beforeSend:function(){                       
                basicLargeDialog("#d_progress",50,150);
               
               $(".ui-dialog-titlebar").hide();
          
                
                }
                 
  
              });      
          
 }
 function saveniyaz(array,$sabilno,$visitormale,$visitorfemale){  
   
     var $dataString={ejno:array,sabilno:$sabilno}; 
     
    var $urlString="../niyazconfirmation/json_redirect.php?action=niyazsave&male="+$visitormale+"&female="+$visitorfemale; 

                $.ajax({                                                                        
                
                url: $urlString,
                
                type: "GET",
                
                dataType:"json",
                
                contentType: "application/json",
               
                cache: false,
                
                data:$dataString,
                
                success: function(response) {
                 
                  
                 
              if(response.id===1){
                  
                 $("#d_progress").dialog("destroy"); 
                  
              
                  alert("Thank you for confirming attendance");
				  basicLargeDialog("#d_progress",50,150);
                  location.reload();
                  $(".niyazejno").removeAttr("checked");
                  
                  //window.location.href="../mumin.php";
				  window.close();
                 }  
             
             else{
                alert("whoops! An error occured");           
             }
                },
              error:function (xhr, ajaxOptions, thrownError){
                    
                   
                   
                },
               beforeSend:function(){                       
                basicLargeDialog("#d_progress",50,150);
               
               $(".ui-dialog-titlebar").hide();
          
                
                }
                 
  
              });      
          
 }
 function basicLargeDialog(id,$width,$height){
        $(id).dialog({
            height: $height,
            width:$width,
            modal: true
        });
  }
 });
 