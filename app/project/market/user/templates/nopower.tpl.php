<?php include framework::temp('market/header'); ?>
  
<script language="javascript">
window.onload=function(){
var table1=document.getElementById("table1");
for(var i=0;i<table1.rows.length;i++){
if (i%2==0) // 偶数行时
table1.rows[i].className="twice";
}
}
</script>

<script type="text/javascript" >

function submitEditForm(){

	 $('#applyForm').ajaxSubmit({
	        dataType:'json',
	        async: false,
	        success:function(json) {
	            var html = '';
	            if(typeof(json.error) != 'undefined'){
	                $.each(json.error,function(key,item){
	                    html += item+"\n";
	                })
	               alert(html);
	            } else {
	                alert(json.message);
	                $('#applyForm').resetForm();
	            }
	            $('.submit').attr('disabled',false);
	            location.href = "#";
	        }
	    });
}


</script>
 
<style type="text/css">
<!--
body {
	background:url(images/con-background01.png) repeat-x #fff;
	background-attachment:fixed;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>

<body>
  <div class="main_r">
    <div class="l_title">

    </div>
    
      <div class="cboth"></div>
 
  </div>
  <div class="main_r1">
 
    您没有对应权限， <input type='button' value='点此返回' onclick="javascript:window.history.go(-1)"> 
	<input type='button' value='关闭窗口' onclick="javascript:window.close()">

  </div>
<?php include framework::temp('market/footer'); ?>