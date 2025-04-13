// JavaScript Document
function to_content(){
    $.ajax({
        type: "GET",
        url: "index.php",
        success: function (data) {
            var s = data,
                $build = $(s);
            var content = $build[9];
                content = $(content).attr('content');
            $('meta[name="viewcontent"]').attr('content', content);
        }
    });
}
// register
function user_register(event) {
	event.preventDefault();
	$('#buttonreg').prop('disabled', true);
	var reg_nama = $('#reg_nama').val();
	var reg_mail = $('#reg_mail').val();
	var reg_lang = $('#reg_language').val();
	var reg_pass = $('#reg_pass').val();
	var reg_pass2 = $('#reg_pass2').val();
	var reg_setuju = $('#reg_setuju').val();
	var atpos = reg_mail.indexOf("@");
	var dotpos = reg_mail.lastIndexOf(".");
	if ( reg_nama == '' || reg_mail == '' || reg_pass == '' || reg_pass2 == '' ) {
		$('#mainregnotif').hide();
		$('#mainregnotif').html('<div class="notifno">'+lang.req_field+'</div>').slideDown(500).delay(3000).slideUp(500);
	} else if ( atpos<1 || dotpos<atpos+2 || dotpos+2>=reg_mail.length ) {
		$('#mainregnotif').hide();
		$('#mainregnotif').html('<div class="notifno">'+lang.req_email+'</div>').slideDown(200).delay(5000).slideUp(200);
	} else if ( reg_pass.length < '6' ||  reg_pass2.length < '6' ) {
		$('#mainregnotif').hide();
		$('#mainregnotif').html('<div class="notifno">'+lang.req_pass+'</div>').slideDown(200).delay(5000).slideUp(200);
	} else if ( reg_pass != reg_pass2 ) {
		$('#mainregnotif').hide();
		$('#mainregnotif').html('<div class="notifno">'+lang.req_samepass+'</div>').slideDown(500).delay(3000).slideUp(500);
	} else if ( $('#reg_setuju').prop('checked') == false ) {
		$('#mainregnotif').hide();
		$('#mainregnotif').html('<div class="notifno">'+lang.req_term+'</div>').slideDown(500).delay(3000).slideUp(500);
	} else {
		$('#regloader').fadeIn(300);
		
		$.ajaxSetup({
		    url : global_url+"/penumpang/kirim.php",
            headers : {
                'content': $('meta[name="viewcontent"]').attr('content')
            },
            global: false,
            type : 'POST'
        });
        
        $.ajax({
            data : {
                reg_nama: reg_nama,
    			reg_mail: reg_mail,
    			reg_lang: reg_lang,
    			reg_pass: reg_pass,
    			regcode: global_var
            },
            success : function(data,status){
                    to_content()
                	if ( status == 'success' && data.indexOf('berhasil')>= 0 ) {
        				$('#buttonreg').prop('disabled', false);
        				// window.parent.location.reload(true);
        				$('#regloader').fadeOut(300);
        				$('#registerbox').slideUp(1500);
        				$('#startnotif').slideUp(100);
        				// get userid
        				var id_user = data.split('|||');
                        var pisah = id_user[1];
        				var userid = pisah * 1;
        				//post to mail // please comment jika localhost
        				/*
        				$.post("//akun.us10.list-manage.com/subscribe/post?u=397e2516a23c291663a40cf2b&amp;id=a0b5ba8b27", {			
              				EMAIL: reg_mail,
        					FNAME: reg_nama,
        					USERID: userid,
        					BAHASA: reg_lang,
        					PREMIUM: '0',
        					b_397e2516a23c291663a40cf2b_a0b5ba8b27: '',
        					subscribe: 'Subscribe'
            			}, function(data,status){});
            			*/
        				// success
        				var selamat = '<h2>'+lang.welcome+'</h2><p><strong>'+lang.hello+', '+reg_nama+'!</strong><br />'+lang.th_reg+'</p><p>'+lang.th_next+'</p><p><input type="button" class="buttonreg" id="buttonreg" value="'+lang.th_continue+' &raquo;" onclick="window.location.href=\''+global_url+'/?cash=0\'"/></p>';
        				$('#selamatbox').html(selamat).delay(1600).slideDown(2000);
        			} else if ( status == 'success' && data.indexOf('emailwrong')>= 0)  {
        				$('#buttonreg').prop('disabled', false);
        				$('#regloader').fadeOut(300);
        				$('#reg_mail').val('');
        				$('#mainregnotif').hide();
        				$('#mainregnotif').html('<div class="notifno">'+lang.email_used+'</div>')
        					.fadeIn(500).delay(3000).fadeOut(500);
        			} else {
        				$('#buttonreg').prop('disabled', false);
        				$('#regloader').fadeOut(300);
        				$('#mainregnotif').hide();
        				$('#mainregnotif').html('<div class="notifno">'+lang.wrong+'</div>')
        					.fadeIn(500).delay(3000).fadeOut(500);
        			}
            }, error : function(){
                $('#regloader').fadeOut(300);
    			$('#mainlognotif').html('<div class="notifno">'+lang.wrong+'</div>')
    				.slideDown(500).delay(3000).slideUp(500)
            }
            
        })
		
// 	    $.post("penumpang/kirim.php", { 			
//       		reg_nama: reg_nama,
// 			reg_mail: reg_mail,
// 			reg_lang: reg_lang,
// 			reg_pass: reg_pass,
// 			regcode: global_var
//     	}, function(data,status){
// 			if ( status == 'success' && data.indexOf('berhasil')>= 0 ) {
// 				$('#buttonreg').prop('disabled', false);
// 				// window.parent.location.reload(true);
// 				$('#regloader').fadeOut(300);
// 				$('#registerbox').slideUp(1500);
// 				$('#startnotif').slideUp(100);
// 				// get userid
// 				var id_user = data.split('|||');
//                 var pisah = id_user[1];
// 				var userid = pisah * 1;
// 				//post to mail // please comment jika localhost
// 				/*
// 				$.post("//akun.us10.list-manage.com/subscribe/post?u=397e2516a23c291663a40cf2b&amp;id=a0b5ba8b27", {			
//       				EMAIL: reg_mail,
// 					FNAME: reg_nama,
// 					USERID: userid,
// 					BAHASA: reg_lang,
// 					PREMIUM: '0',
// 					b_397e2516a23c291663a40cf2b_a0b5ba8b27: '',
// 					subscribe: 'Subscribe'
//     			}, function(data,status){});
//     			*/
// 				// success
// 				var selamat = '<h2>'+lang.welcome+'</h2><p><strong>'+lang.hello+', '+reg_nama+'!</strong><br />'+lang.th_reg+'</p><p>'+lang.th_next+'</p><p><input type="button" class="buttonreg" id="buttonreg" value="'+lang.th_continue+' &raquo;" onclick="window.location.href=\''+global_url+'/?cash=0\'"/></p>';
// 				$('#selamatbox').html(selamat).delay(1600).slideDown(2000);
// 			} else if ( status == 'success' && data.indexOf('emailwrong')>= 0)  {
// 				$('#buttonreg').prop('disabled', false);
// 				$('#regloader').fadeOut(300);
// 				$('#reg_mail').val('');
// 				$('#mainregnotif').hide();
// 				$('#mainregnotif').html('<div class="notifno">'+lang.email_used+'</div>')
// 					.fadeIn(500).delay(3000).fadeOut(500);
// 			} else {
// 				$('#buttonreg').prop('disabled', false);
// 				$('#regloader').fadeOut(300);
// 				$('#mainregnotif').hide();
// 				$('#mainregnotif').html('<div class="notifno">'+lang.wrong+'</div>')
// 					.fadeIn(500).delay(3000).fadeOut(500);
// 			}
// 			});
	}
}
// User login
function user_login(event) {
	event.preventDefault();
	$('#mainlognotif').hide();
	var login_mail = $('#login_mail').val();
	var login_pass = $('#login_pass').val();
	if ( $('#checkbox').is(':checked') == true ) { var ingatsaya = '1'; }
	else { var ingatsaya = '0'; }
	if ( login_mail == '' || login_mail == 'Your Email' || login_pass == '' || login_pass == 'r4akW6n*' ) {
		$('#mainlognotif').hide();
		$('#mainlognotif').html('<div class="notifno">'+lang.fill_all+'</div>').slideDown(500).delay(3000).slideUp(500);
	} else {
		$('#logloader').fadeIn(300);
		
		$.ajaxSetup({
		    url : global_url+"/penumpang/kirim.php",
            headers : {
                'content': $('meta[name="viewcontent"]').attr('content')
            },
            global: false,
            type : 'POST'
        });
        
        $.ajax({
            data : {
                login_mail: login_mail,
    			login_pass: login_pass,
    			ingatsaya: ingatsaya,
    			login_serial: global_var
            },
            success : function(data,status){
                console.log(data);
                to_content()
                if ( status == 'success' && data.indexOf('berhasil')>= 0 ) {
    				$('#logloader').fadeOut(300);
    				window.location.href = global_url;
    			} else if ( status == 'success' && data.indexOf('gagal')>= 0 )  {
    				$('#logloader').fadeOut(300);
    				var salahpass = '<div class="notifno">'+lang.wrong_pass+'<br />';
    				salahpass += '<strong><a href="?user=getpass">'+lang.forgot_pass+' &raquo;</a></strong></div>';
    				$('#mainlognotif').html(salahpass).slideDown(500);
    					
    			} else {
    				$('#logloader').fadeOut(300);
    				$('#mainlognotif').html('<div class="notifno">'+data+'<br>'+lang.wrong+'</div>')
    					.slideDown(500).delay(3000).slideUp(500);
    			}
            }, error : function(){
                $('#logloader').fadeOut(300);
    			$('#mainlognotif').html('<div class="notifno">'+lang.wrong+'</div>')
    				.slideDown(500).delay(3000).slideUp(500)
            }
            
        })
        
// 		$.post(global_url+"/penumpang/kirim.php", { 			
//       		login_mail: login_mail,
// 			login_pass: login_pass,
// 			ingatsaya: ingatsaya,
// 			login_serial: global_var
//     	}, function(data,status){
// 			if ( status == 'success' && data.indexOf('berhasil')>= 0 ) {
// 				$('#logloader').fadeOut(300);
// 				window.location.href = global_url;
// 			} else if ( status == 'success' && data.indexOf('gagal')>= 0 )  {
// 				$('#logloader').fadeOut(300);
// 				var salahpass = '<div class="notifno">'+lang.wrong_pass+'<br />';
// 				salahpass += '<strong><a href="?user=getpass">'+lang.forgot_pass+' &raquo;</a></strong></div>';
// 				$('#mainlognotif').html(salahpass).slideDown(500);
					
// 			} else {
// 				$('#logloader').fadeOut(300);
// 				$('#mainlognotif').html('<div class="notifno">'+lang.wrong+'</div>')
// 					.slideDown(500).delay(3000).slideUp(500);
// 			}
// 			});
	}
	return false;
}
// User pass
function get_pass(event) {
	event.preventDefault();
	$('#mainlognotif').hide();
	var user_mail = $('#user_mail').val();
	var atpos = user_mail.indexOf("@");
	var dotpos = user_mail.lastIndexOf(".");
	if ( user_mail == '' || user_mail == 'Your Email' ) {
		$('#mainlognotif').hide();
		$('#mainlognotif').html('<div class="notifno">'+lang.fill_email+'</div>').slideDown(500).delay(3000).slideUp(500);
	} else if ( atpos<1 || dotpos<atpos+2 || dotpos+2>=user_mail.length ) {
		$('#mainlognotif').hide();
		$('#mainlognotif').html('<div class="notifno">'+lang.req_email+'</div>').slideDown(200).delay(5000).slideUp(200);
	} else {
		$('#passloader').fadeIn(300);
		
		$.ajaxSetup({
		    url : global_url+"/penumpang/kirim.php",
            headers : {
                'content': $('meta[name="viewcontent"]').attr('content')
            },
            global: false,
            type : 'POST'
        });
        
        $.ajax({
            data : {
                user_mail: user_mail,
			    pass_serial: global_var
            },
            success : function(data,status){
                to_content()
                if ( status == 'success' && data.indexOf('berhasil')>= 0 ) {
    				$('#passloader').fadeOut(300);
    				$('#getpassbox').slideUp(1000);
    				$('#getpasspretext').slideUp(1000);
    				var getpasstext = '<strong>'+lang.success+'</strong><br />'+lang.sent_mail+' <strong>'+user_mail+'</strong>.<br />';
    				getpasstext += lang.click_link+'<br />';
    				getpasstext += lang.check_spam+'<br /><br /><br />';
    				getpasstext += '<a href="https://www.akun.biz" class="button">&laquo; '+lang.back_home+'</a>';
    				$('#getpasstext').html(getpasstext).delay(1200).slideDown(1000);
    			} else if ( status == 'success' && data.indexOf('noemail')>= 0 )  {
    				$('#passloader').fadeOut(300);
    				$('#user_mail').val('Email Anda');
    				$('#mainlognotif').html('<div class="notifno">'+lang.no_mail+'</div>')
    					.slideDown(500).delay(4000).slideUp(500);
    			} else {
    				$('#passloader').fadeOut(300);
    				$('#mainlognotif').html('<div class="notifno">'+lang.wrong+'</div>')
    					.slideDown(500).delay(4000).slideUp(500);
    			}
            }, error : function(){
                $('#passloader').fadeOut(300);
    			$('#mainlognotif').html('<div class="notifno">'+lang.wrong+'</div>')
    				.slideDown(500).delay(3000).slideUp(500)
            }
            
        })
		
// 		$.post("penumpang/kirim.php", {			
//       		user_mail: user_mail,
// 			pass_serial: global_var
//     	}, function(data,status){
// 			if ( status == 'success' && data.indexOf('berhasil')>= 0 ) {
// 				$('#passloader').fadeOut(300);
// 				$('#getpassbox').slideUp(1000);
// 				$('#getpasspretext').slideUp(1000);
// 				var getpasstext = '<strong>'+lang.success+'</strong><br />'+lang.sent_mail+' <strong>'+user_mail+'</strong>.<br />';
// 				getpasstext += lang.click_link+'<br />';
// 				getpasstext += lang.check_spam+'<br /><br /><br />';
// 				getpasstext += '<a href="https://www.akun.biz" class="button">&laquo; '+lang.back_home+'</a>';
// 				$('#getpasstext').html(getpasstext).delay(1200).slideDown(1000);
// 			} else if ( status == 'success' && data.indexOf('noemail')>= 0 )  {
// 				$('#passloader').fadeOut(300);
// 				$('#user_mail').val('Email Anda');
// 				$('#mainlognotif').html('<div class="notifno">'+lang.no_mail+'</div>')
// 					.slideDown(500).delay(4000).slideUp(500);
// 			} else {
// 				$('#passloader').fadeOut(300);
// 				$('#mainlognotif').html('<div class="notifno">'+lang.wrong+'</div>')
// 					.slideDown(500).delay(4000).slideUp(500);
// 			}
//     	});
	}
	return false;
}