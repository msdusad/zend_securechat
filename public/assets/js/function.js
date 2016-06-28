//slider///
$(window).load(function(){
      $('.slider')._TMS({
              show:0,
              pauseOnHover:false,
              prevBu:'.prev',
              nextBu:'.next',
              playBu:false,
              duration:1000,
              preset:'fade', 
              pagination:true,//'.pagination',true,'<ul></ul>'
              pagNums:false,
              slideshow:10000,
              numStatus:false,
              banners:false,
          waitBannerAnimation:false,
        progressBar:false
      })  
      });    
     $(window).load (
function(){$('.carousel1').carouFredSel({auto: false,prev: '.prev',next: '.next', width: 1229, items: {
      visible : {min: 1,
       max: 4
},
height: 'auto',
width: 400,
    }, responsive: false, 
    scroll: 4, 
    mousewheel: false,    
    swipe: {onMouse: false, onTouch: false}});
   });

//filter sub category////
function showData(cat)
{
  

document.getElementById(cat).style.color="red";


     $.ajax({
     
          type: "POST",
          data: { cat:cat},
          url: "getdata",
          
          success: function(data) 
          {             
              var ss=$('#hide').html(jQuery(data).find('#hide').html()); 
              //$("#hide").html(data);  
          }
        });
    //var fullid="hide-"+show;
    //alert(show);
    //document.getElementById(fullid).style.display="block";
    //document.getElementById(hide).style.display="none";    
}

//filter country state city in select box//
$(document).ready(function()
{
 $("#country").change(function()
 {
  var id=$(this).val();
  var dataString = 'stateid='+ id;
  $.ajax
  ({
   type: "POST",
   url: "getdata",
   data: dataString,
   cache: false,
   success: function(data)
   {
     // $("#state").html(html);
       var ss=$('#state').html(jQuery(data).find('#state_list').html()); 
       
   } 
   });
  });
 $("#state").change(function()
 {
  var id=$(this).val();  
  var dataString='cityid='+ id;
 $.ajax
  ({
   type: "POST",
   url: "getdata",
   data: dataString,
   cache: false,
   success: function(data)
   { 
    //$("#city").html(html);
    var ss=$('#city').html(jQuery(data).find('#city_list').html()); 
    
   } 
   });
  }); 
});

//filter product select subcat value///
function subcat(catval,subcat){
        $.ajax
        ({
        type: "POST",
        url: "getdata",
        data: {cat_value:catval,sub_cat:subcat},
        success: function(data)
        {   
            
             var ss=$('#pro_items').html(jQuery(data).find('#data1').html()); 
           // $("#pro_items").html(html);
          
        } 
        });
    }


// gorccrry copied

  function searchproduct(searchthis)
    {

  $.ajax
  ({
   type: "POST",
   url: "getdata",
   data: {searchpro:searchthis},
   cache: false,
   success: function(data)
   {
     // $("#state").html(html);
       var ss=$('#pro_items').html(jQuery(data).find('#data1').html()); 
       
   } 
   });

    }


    function addtocart(pid)
    {
        document.form1.productid.value=pid;
        document.form1.command.value='add';
        document.form1.submit();
        //window.location=document.getElementById('redirecthere').href; 
    }
    
///filter product according country state city ////////
   $('.act a').click(function(e) {
    e.preventDefault(); 
    $('.act a').removeClass('selected');
    var show=$(this).addClass('selected');
       //alert(show);
});
function country_data(country){
    cat_val=$('ul#ajax_cat_val').find('li.active').data('interest');
    subcat_val=$('ul#ajax_subcat_val').find('li.active').data('interest');
    //alert(cat_val);
    //alert(subcat_val);
    //alert(country);
        $.ajax({
          type: "POST",
          data: { cat_val:cat_val,subcat_val:subcat_val,country:country},
          url: "getdata",
          success: function(data) 
          {  
              //$("#pro_items").html(data);
               var ss=$('#pro_items').html(jQuery(data).find('#data1').html()); 
          }
        });
      }

      function brandfilter(brandfiltervalue){

  $.ajax({
          type: "POST",
          data: { searchbrand:brandfiltervalue},
          url: "getdata",
          success: function(data) 
          {  
             // $("#pro_items").html(data);
              var ss=$('#pro_items').html(jQuery(data).find('#data1').html()); 
          }
        });

      }



function state_data(country,state){
    cat_val=$('ul#ajax_cat_val').find('li.active').data('interest');
    subcat_val=$('ul#ajax_subcat_val').find('li.active').data('interest');
        $.ajax({
          type: "POST",
          data: { cat_val:cat_val,subcat_val:subcat_val,country_id:country,state_id:state},
          url: "getdata",
          success: function(data) 
          { 
            //$("#pro_items").html(data);
             var ss=$('#pro_items').html(jQuery(data).find('#data1').html()); 
          }
        });
      }
function city_data(country,state,city){
    cat_val=$('ul#ajax_cat_val').find('li.active').data('interest');
    subcat_val=$('ul#ajax_subcat_val').find('li.active').data('interest');
        $.ajax({
          type: "POST",
          data: { cat_val:cat_val,subcat_val:subcat_val,country_ids:country,state_ids:state,city_ids:city},
          url: "getdata",
          success: function(data) 
          { 
           // $("#pro_items").html(data);
             var ss=$('#pro_items').html(jQuery(data).find('#data1').html()); 
          }
        });
      }    