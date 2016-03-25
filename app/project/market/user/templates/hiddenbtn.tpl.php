
<link href="<?php echo HIGHPHP_WWW_HOST; ?>css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript">

function hiddenleft(){ 
    //parent.document.all("main").cols="0,25,*";
	window.parent.frames['main'].cols="0,9,*";
//    $(".navPoint").parents().find("#main").attr('cols', '0,25,*');
    $("#show").css("display","");
    $("#hid").css("display","none"); 
}

function showleft(){ 
  //  parent.document.all("main").cols="163,25,*";
	window.parent.frames['main'].cols="133,9,*";
    $("#hid").css("display","");
    $("#show").css("display","none"); 
}

</script>

<script type="text/javascript" src="<?php echo HIGHPHP_WWW_HOST; ?>js/jquery/jquery-1.7.2.js"></script>  
<div style="background:url(../images/con_backound02.png) repeat-y #283842;height:100%; overflow:hidden;">	
		<span id="hid" class="navPoint" style="margin-top:200px;float:left;">
		<a href="javascript:void(0)" onclick="hiddenleft()"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/o_l.png" alt="隐藏左侧导航"></a></span>
		
		<span id="show" style="display:none;margin-left:0px;margin-top:200px;float:left;" class=navPoint>
		<a href="javascript:void(0)" onclick="showleft()"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/o_r.png" alt="显示导航"></a></span>
		
</div>		
