var devBool=false;
function gb_fn_linkCacheHandlerJS(pCachedURLStr, pRealURLStr) {
	if(devBool){
		window.location=pRealURLStr;
	} else {
		window.location=pCachedURLStr;
	}
}

function gb_fn_linkHandlerJS(pCachedURLStr, pRealURLStr, seoBool=true) {
	if(seoBool==true || seoBool=="true" || seoBool=="1" || seoBool==1){
		return pCachedURLStr;
	} else {
		return pRealURLStr;
	}
}

function activateUserAccount(id){
	$.ajax({
		url: 'enableaccount.htm',
		type: 'POST',
		data: {"id" : id },
		dataType: 'json',
		cache: false,
		success: function(response){
			$(".alert").remove();
			if(response.success){
				var succ='<div class="alert alert-success "> <i class=" glyphicon glyphicon-ok-circle Added to your Cart"></i> '+response.success+'</div>';
				$(".displayMessageClass").after(succ);
			}	
			if(response.error){
				var err='<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="glyphicon glyphicon-exclamation-sign"></i> '+response.error+'</div>';
				$(".displayMessageClass").after(err);
			}
		}
	});
}

// jquery extend function
$.extend(
{
    redirectPost: function(location, args)
    {
        var form = '';
        $.each( args, function( key, value ) {
            form += '<input type="hidden" name="'+key+'" value="'+value+'">';
        });
        $('<form action="'+location+'" method="POST">'+form+'</form>').appendTo('body').submit();
    }
});
function enquire_about(para,valStr){
	var available_options='';
	$('.available_options').each(function(){
		if($(this).is(':checked')){
			if(available_options!=""){
				available_options+= ", "+$(this).val();
			}else{
				available_options+= $(this).val();
			}
		}
	});
	if(para=="code"){
		$.redirectPost('contact.htm', {'ava_options': available_options, 'code': valStr});
	}else{
		$.redirectPost('contact.htm', {'ava_options': available_options, 'uuid': valStr});
	}
}

function fetchUserPreferences(actionStr){
	$.ajax({
		url: 'fetchUserPreferences.htm',
		type: 'POST',
		data: {"action" : actionStr },
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.success){
				if(actionStr=="wishlist"){
					$("#wishlistMenu").show();
				}else if(actionStr=="cart"){
					$("#productPageCartID").attr("class", "col-sm-5 pading-lft");
					$(".displayCartsClass").show();
					$(".cartItemsClass").html(response.success);
				}
			}else if(response.error){
				if(actionStr=="wishlist"){
					$("#wishlistMenu").hide();
				}
				else if(actionStr=="cart"){
					$("#productPageCartID").attr("class", "col-sm-9 pading-lft");
					$(".displayCartsClass").hide();
					$(".cartItemsClass").html(0);
				}
			}				
		}
	});
}

function randomnum(){
	var number1 = 5;
	var number2 = 20;
	var randomnum = (parseInt(number2) - parseInt(number1)) + 1;
	var rand1 = Math.floor(Math.random()*randomnum)+parseInt(number1);
	var rand2 = Math.floor(Math.random()*randomnum)+parseInt(number1);
	$(".rand1").html(rand1);
	$(".rand2").html(rand2);
}

function generate_session(){
	$.ajax({
		url: 'addUserPreferences.htm',
		type: 'POST',
		data: {"uuid" : "", "action" : "cart" },
		dataType: 'json',
		cache: false,
		success: function(response){
						
		}
	});
}

function productMenuReset(){
	if($( window ).width()>=980){
		$("#ProductMainMenu").attr("onClick", "gb_fn_linkCacheHandlerJS('products.htm','products.htm')");
	}else{
		$("#ProductMainMenu").removeAttr("onClick");
	}
}
$( window ).resize(function() {
	//productMenuReset();
});

$(function(){
	if($.cookie('DreamFurnishingVisitor')){
		//console.log($.cookie('REAL_USER'));
	}else{
		generate_session();
	}
	
	//productMenuReset();
	fetchUserPreferences('wishlist');
	fetchUserPreferences('cart');
	
	$('#mainSearch').submit(function(){
		var key_srch=$('#search-terms').val().trim();
		if(key_srch==''){
			$('#search-terms').focus();
			return false;
		}
	});
});

  	(function(window){

	// get vars
	var searchEl = document.querySelector("#input");
	var labelEl = document.querySelector("#label");

	// register clicks and toggle classes
	labelEl.addEventListener("click",function(){
		if (classie.has(searchEl,"focus")) {
			classie.remove(searchEl,"focus");
			classie.remove(labelEl,"active");
		} else {
			classie.add(searchEl,"focus");
			classie.add(labelEl,"active");
		}
	});

	// register clicks outisde search box, and toggle correct classes
	document.addEventListener("click",function(e){
		var clickedID = e.target.id;
		if (clickedID != "search-terms" && clickedID != "search-label") {
			if (classie.has(searchEl,"focus")) {
				classie.remove(searchEl,"focus");
				classie.remove(labelEl,"active");
			}
		}
	});
}(window));
