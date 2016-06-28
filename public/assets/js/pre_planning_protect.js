   function videoPlay(videoNum)
    {
     document.getElementById('light').style.display='block';
    document.getElementById('fade').style.display='block';
document.getElementById("video-box").setAttribute("src",videoNum);
document.getElementById("video-box").load();
document.getElementById("video-box").play();
    }
    
        function videoclose(xyz)
    {   
document.getElementById('light').style.display='none';
document.getElementById('fade').style.display='none';
    var vid=document.getElementById("video-box");
         vid.pause(); 
    }
    
        
function getState(val) {
    var menu= document.getElementById('ajax_menu').value; 
    var sub_menu=$('ul#ajax_sub_menu').find('li.active').data('interest'); 
    //alert(menu);
    $.ajax({
    type: "POST",
    url: "<?php echo $this->url('state', array('action' => 'index')); ?>",
    data:{country_id:val,srch_menu:menu,srch_sub_menu:sub_menu},
        //data:'country_id='+val+'menu_serch'+menu,
    success: function(data){
var ss=$('#state-list').html(jQuery(data).find('#result').html()); 
var ss1=$('#output-checklist').html(jQuery(data).find('#checklist').html()); 
var ss2=$('#output-forms').html(jQuery(data).find('#forms').html()); 
var ss3=$('#output-resources').html(jQuery(data).find('#resources').html()); 
var ss4=$('#output-articals').html(jQuery(data).find('#articals').html()); 
var ss5=$('#output-tools').html(jQuery(data).find('#tools').html());
var ss6=$('#view_ads').html(jQuery(data).find('#ads').html());

    }
    });
}
function getCity(val) {
    var count_id=document.getElementById('country-list').value;
    var menu= document.getElementById('ajax_menu').value; 
    var sub_menu=$('ul#ajax_sub_menu').find('li.active').data('interest'); 
    
    $.ajax({
    type: "POST",
    url: "<?php echo $this->url('state', array('action' => 'index')); ?>",
    data:{state_id:val,country_id:count_id,srch_menu:menu,srch_sub_menu:sub_menu},
    //data:'state_id='+val,
    success: function(data){
    var ss=$('#city-list').html(jQuery(data).find('#cities').html()); 
var ss1=$('#output-checklist').html(jQuery(data).find('#checklist').html()); 
var ss2=$('#output-forms').html(jQuery(data).find('#forms').html()); 
var ss3=$('#output-resources').html(jQuery(data).find('#resources').html()); 
var ss4=$('#output-articals').html(jQuery(data).find('#articals').html()); 
var ss5=$('#output-tools').html(jQuery(data).find('#tools').html());
var ss6=$('#view_ads').html(jQuery(data).find('#ads').html());
    }
    });
}
    
    function SubmitCity(val) {
        var state=document.getElementById('state-list').value;
    var count_id =document.getElementById('country-list').value;
    var menu= document.getElementById('ajax_menu').value; 
    var sub_menu=$('ul#ajax_sub_menu').find('li.active').data('interest'); 
        
    $.ajax({
    type: "POST",
    url: "<?php echo $this->url('state', array('action' => 'index')); ?>",
    data:{city_id:val,state_id:state,country_id:count_id,srch_menu:menu,srch_sub_menu:sub_menu},
        //data:'city_id='+val,
    success: function(data){
    //var ss=$('#state-list').html(jQuery(data).find('#result').html()); 
var ss1=$('#output-checklist').html(jQuery(data).find('#checklist').html()); 
var ss2=$('#output-forms').html(jQuery(data).find('#forms').html()); 
var ss3=$('#output-resources').html(jQuery(data).find('#resources').html()); 
var ss4=$('#output-articals').html(jQuery(data).find('#articals').html()); 
var ss5=$('#output-tools').html(jQuery(data).find('#tools').html());
var ss6=$('#view_ads').html(jQuery(data).find('#ads').html());
    }
    });
}

    
    $(document).ready(function () {   
$("#forms").click(function(){
$( "#preplanning-forms" ).dialog({
   width: 900,
   modal:true
});
});
});
    
    $(document).ready(function () {   
$("#checklists").click(function(){
$( "#preplanning-checklists" ).dialog({
   width: 900,
   modal:true
});
});
}); 
    
        $(document).ready(function () {   
$("#lockbox").click(function(){
$( "#lockbox-view" ).dialog({
   width: 900,
   modal:true
});
});
}); 
    
        $(document).ready(function () {   
$("#tab1primarypop1").click(function(){
$( "#tab1primary" ).dialog({
   width: 900,
   modal:true
});
});
});
    
            $(document).ready(function () {   
$("#tab2primarypop2").click(function(){
$( "#tab2primary" ).dialog({
   width: 900,
   modal:true
});
});
});
    
            $(document).ready(function () {   
$("#tab3primarypop3").click(function(){
$( "#tab3primary" ).dialog({
   width: 900,
   modal:true
});
});
});
    

