<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Leads & Needs</title>
	<meta name="author" content="Brian Russell">
	<link rel="stylesheet" href="css/jquery.cluetip.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />		
</head>
<body>
	<div id="top">
			<b>Welcome to Leads and Needs.</b>
			<a class="show_connection" href="">Connections</a> | <a class="add_person" href="">Add contact</a> | <a href="">Upload</a> | <a class="skill_ln" href=""> Skills</a> | <a class="settings_ln" href=""> Settings</a>
	</div>
<div id="container">
	
	<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
	<div id="left">
		<div>People with <em>leads</em> on work.</div>
		<div class='list_menu'><table class='left_right' width='100%'><tr><td><a href='' class='add_person'>add</a> | <a href='' rel='filters.php?area=1' class='filter_lnk' title='Filter for lead'>filter</a></td><td>sort <a href='' class='ps' value='1'>name</a> | <a href='' class='ps_act' value='1'>date</a></td></tr></table></div>
		<div class='scrollable'>
			<ul class='river' id='lpanel'>
			<?php
				include_once('ln_library.php');
				$cont = explode('##',get_plist(1,1));
				echo $cont[0];
			?>
			</ul>
		</div>
		<div class='list_status' id='lpanel_summ'>
			<?php
				echo $cont[1];
			?>
		</div>
	</div>
	<!-- <div id='rblock'> -->
		<div id="middle">
		<!-- This form is based on hcard. Its a microformat. http://microformats.org/wiki/hcard -->
		
		<!-- The names of the form fields also describe the database field names. -->
		
		<!-- several types of data entry into the database is possible. Manually - addentry.php, Automatically - upload.php, -->
		
		<!-- You can display both the leads and needs and their relationships on - connections.php -->
		
		<?php
			//include 'connections.php';
			echo get_connlist();
		?>
		
		</div>			
		<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
		<div id="right">
			<div>People with <em>needs</em> on work.</div>
			<div class='list_menu'><table class='left_right' width='100%'><tr><td><a href='' class='add_person'>add</a> | <a href='' rel='filters.php?area=2' class='filter_lnk' title='Filter for need'>filter</a></td><td>sort <a href='' class='ps' value='2'>name</a> | <a href='' class='ps_act' value='2'>date</a></td></tr></table></div>
			<div class='scrollable'>
				<ul class='river' id='rpanel'>
				<?php
					$cont = explode('##',get_plist(2,1));
					echo $cont[0];
				?>
				</ul>
			</div>
			<div class='list_status' id='rpanel_summ'>
				<?php
					echo $cont[1];
				?>
			</div>
		</div>
	<!-- </div> -->
</div>
<div id="footer">
	Footer stuff --> I love Signal37 design. Can you tell?
</div>

<script src="js/jquery-1.3.min.js" type="text/javascript"></script>
<script src="js/jquery.dimensions.js" type="text/javascript"></script>
<script src="js/jquery.hoverIntent.js" type="text/javascript"></script> <!-- optional -->
<script src="js/jquery.cluetip.js" type="text/javascript"></script>
<script type="text/javascript">
	var g_ln_sc_loaded = 0;
	
	var bind_fdinput = function (el_list_id){ 
		//example el_list_id = '#id1,#id2,....'
		$(el_list_id).keyup(function(event){
			var val = filterDigitField($(this).val());
			$(this).val(val);
		});
	}
	
	var cnt_edit = function(event,id_el){
		if (event) event.preventDefault();						
		var r_data = "action=edit&id=" + $('#' + id_el).val();
		after = function(new_content){
			$('#middle').html(new_content);
			if (g_ln_sc_loaded == 0) {
				$.ajax({
						type: 'GET',
						url: 'js/ln.js',
						dataType: 'script',
						cache: false,
						success: function(){g_ln_sc_loaded = 1;}
				});						
			}
			bind_fdinput('#zip,#phone');
		}
		$.ajax({
			type: 'POST',
			url: 'contact.php',
			data: r_data,
			cache: false,
			success: after
		})
	};
					
	var cnt_disable = function(event,id_el,ptype){
		if (event) event.preventDefault();	
		var old = Number($('#' + ptype).val());
		r_data = "action=disable&id=" + $('#' + id_el).val() + "&ptype=" + old;
		after = function(new_content){
			$('#middle').html(new_content);
			var pan = '#lpanel';						
			if (old == 2) pan = '#rpanel'; 
			$(pan).load('ln_library.php',{ptype: old, fn: 'get_plist'},function(){$('a.tips').cluetip(bind_cltips_events)});
		};
		$.ajax({
			type: 'POST',
			url: 'contact.php',
			data: r_data,
			cache: false,
			success: after
		})
	}
/*
	var cnt_connadd = function(event,id_el,ptype){
		if (event) event.preventDefault();
		r_data = "action=add&id=" + $('#' + id_el).val();
		after = function(new_content){
			//var 
			$('#conn_b tr:first-child').before(new_content);
			//$('#conn_b td:first-child').html(new_content);
		};
		$.ajax({
			type: 'POST',
			url: 'connections.php',
			data: r_data,
			cache: false,
			success: after
		})
	}
*/	
	var cnt_match = function(event,id_el,ptype){
		if (event) event.preventDefault();
		var r_data = "action=match&id=" + $('#' + id_el).val() + "&ptype=" + $('#' + ptype).val();
		var after = function(new_content){
			var container = event.target.parentNode;
			var panel = document.createElement('div');
			panel.setAttribute('id','join_with');
			container.appendChild(panel);
			$('#join_with').css({'width': container.clientWidth + 'px', 'height': container.clientHeight + 'px', 'display': 'block'});
			$('#join_with').html("<div><a href='#' id='join_back'>back</a> | <a href='#' id='join_do'>join</a></div><br>" + new_content);
			$('#join_back').bind('click',function(event){$('#join_with').html(''); $('#join_with').css({'display': 'none'});});
			$('#join_do').bind('click',function(event){
						//$('#join_with').html(''); 
						//$('#join_with').css({'display': 'none'});
						var matched_p = $('#matched_p,[input:radio][checked]').val();
						var r_data = "action=join&id=" + $('#' + id_el).val() + "&ptype=" + $('#' + ptype).val() + "&mid=" + matched_p;
						$.ajax({
							type: 'POST',
							url: 'connections.php',
							data: r_data,
							cache: false,
							success: function(context){
								$('#middle').html(context);
								$('#join_back').bind('click',function(event){$('#join_with').html(''); $('#join_with').css({'display': 'none'});});
							}
						})	
			});
		}
		$.ajax({
			type: 'POST',
			url: 'connections.php',
			data: r_data,
			cache: false,
			success: after
		})
	}
	
	var bind_cltips_events = {mouseOutClose: true, onShow: function(ct,c) {
								$('#cl_cont_edit').bind('click',function(event){cnt_edit(event,'tipp_id');});
								$('#cl_cont_dis').bind('click',function(event){cnt_disable(event,'tipp_id','tipp_type');});
								//$('#cl_cont_connadd').bind('click',function(event){cnt_connadd(event,'tipp_id','tipp_type');});
								$('#cl_cont_match').bind('click',function(event){cnt_match(event,'tipp_id','tipp_type');});
							}
						};
//--------------------------------------- end contact ---------------------

//--------------------------------------- letter --------------------------

	var let_save = function(event,act_type){
		return;
		$('div.progress').css({'display': 'inline-block'});
		var r_data = "action=" + act_type + "&conn_id=" + $('#conn_id').val() + '&subject=' + $('#subject').val() + '&letter=' + $('#letter').val();
		$.ajax({
			type: 'POST',
			url: 'connections.php',
			data: r_data,
			cache: false,
			success: function(new_content){
						var progr = $('div.progress');
						progr.html(new_content);
						progr.fadeOut(2000);
					}
		})		
	}
						
	var bind_letter_events = { width: '410px', height: '370px', ajaxCache: false,
								onShow: function(ct,c) {
									$('#let_save').bind('click',function(event){let_save(event,'letsave');});
									$('#let_ss').bind('click',function(event){let_save(event,'letss');});
								},
	}
//--------------------------------------- end letter ----------------------
//--------------------------------------- filter --------------------------

	var filt_save = function(event,act_type){
		var area = $('#farea').val();
		var page = "";
		var panel = "";
		var sk = "(";
		var act = 0;
		if ($('#fapply').attr('checked')) act = 1;
		var lname ='';
		if ($('#flname').size() > 0) lname = $('#flname').val();
		var nname ='';
		if ($('#fnname').size() > 0) nname = $('#fnname').val();
		var intrs = 0;
		if ($('#fintrs').attr('checked')) intrs = 1;
		var intrn = 0;
		if ($('#fintrn').attr('checked')) intrn = 1;
		if (area < 3) {
			var skills = $('#f_block [input:checkbox][checked][id^="s"]');
			var size = skills.size();
			var sk = "(";
			for (i = 0; i < size; i++){
				sk += skills.eq(i).attr('id').replace(/^(s_)/,'') + ",";
			}
		}
		if (area == 1) panel = '#lpanel';
		else if (area == 2) panel = '#rpanel';
			 else {panel = '#middle'; page = '&page=' + $('#curr_pg').html();}

		var r_data = "action=" + act_type + "&area=" + $('#farea').val() + "&apply=" + act + "&lname=" + lname + "&nname=" + nname + "&intrs=" + intrs + "&intrn=" + intrn + "&skills=" + sk.replace(/,$/,'') + ")" + page;
		$.ajax({
			type: 'POST',
			url: 'filters.php',
			data: r_data,
			cache: false,
			success: function(new_content){
						if (area == 3) {$(panel).html(new_content); $('a.filter_lnk').cluetip(bind_filter_events);}
						else {
							var result = new_content.split('##');					
							$(panel).html(result[0]);
							$(panel + '_summ').html(result[1]);
						}
						$('a.tips').cluetip(bind_cltips_events);
					}
		})		
	}
						
	var bind_filter_events = { width: '390px', height: '350px', ajaxCache: false, activation: 'click',
								onShow: function(ct,c) {
									$('#filt_save').bind('click',function(event){filt_save(event,'f_save');});
									//$('#filt_canc').bind('click',function(event){filt_save(event,'f_canc');});
								},
	}
//--------------------------------------- end filter ----------------------
	
	$(document).ready(function() {
		$('a.tips').cluetip(bind_cltips_events);
		$('a.intro_lnk').cluetip(bind_letter_events);
		$('a.filter_lnk').cluetip(bind_filter_events);
		
		/*
		$('#houdini').cluetip({
			splitTitle: '|', // use the invoking element's title attribute to populate the clueTip...
							 // ...and split the contents into separate divs where there is a "|"
			showTitle: false // hide the clueTip's heading
		});
		*/

		$("a.add_person").click(function(event){
			event.preventDefault();
			$(this).css('cursor','wait');
			//$('#middle').html('<img src="img/bigrotation2.gif">');
			$('#middle').load('contact.php',{action: 'new'}, function(){
				$('a.add_person').css('cursor','pointer');
				if (g_ln_sc_loaded == 0) {
					$.ajax({
						type: 'GET',
						url: 'js/ln.js',
						dataType: 'script',
						cache: false,
						success: function(){g_ln_sc_loaded = 1;}//bind_fdinput('#zip,#phone');
					});
				}//else bind_fdinput('#zip,#phone');
				bind_fdinput('#zip,#phone');
			});
		});

		$("a.skill_ln").click(function(event){
			$('#middle').load('getskills.php');
			event.preventDefault();
		});

		$("a.settings_ln").click(function(event){
			$('#middle').load('getsettings.php');
			event.preventDefault();
		});

		$("a.show_connection").click(function(event){
				event.preventDefault();
				$('#middle').load('connections.php',{},function(){
					$('a.tips').cluetip(bind_cltips_events);
					$('a.intro_lnk').cluetip(bind_letter_events);
					$('a.filter_lnk').cluetip(bind_filter_events);
				});
		});
		
		
		$('a.ps,a.ps_act').click(function(event){
			event.preventDefault();
			var cl = $(this).attr('className');
			if (cl == 'ps_act') return;
			
			var ptype = $(this).attr('value');
			if (ptype == "1") {panel = '#lpanel'; div = '#left';}
			else {panel = '#rpanel'; div = '#right';}
			$(div + " a.ps_act").attr('className','ps');
			$(this).attr('className','ps_act');
			$.ajax({
				type: 'POST',
				url: 'ln_library.php',
				data: 'fn=get_plist&sortby=' + $(this).html() + '&ptype=' + ptype + '&summary=0',
				cache: false,
				success: function(new_content){
					$(panel).html(new_content);
					$('a.tips').cluetip(bind_cltips_events);
					$('a.filter_lnk').cluetip(bind_filter_events);
				}
			})
		});

		$('#middle').click(function(event){
			var $target = $(event.target);
			var tagname = $target[0].tagName.toLowerCase();
			if (tagname == 'input') {
				var type = $target[0].type.toLowerCase();
				if (type == 'button') {
					var skname = $('#sk_name').val();
					if (skname) {
						var sklen = skname.length;
						if (sklen > 0) {
							var but_id = $target.attr('id');
							if (but_id == 'sk_add') {
								if ($('td[innerHTML="' + skname + '"]').size() == 0)
									$('#middle').load('getskills.php',{sk_name : skname});
							}else if (but_id == 'sk_change'){
								var skid = $('#ed_sk_id').val();
								if (skid != skname)
									$('#middle').load('getskills.php',{sk_name: skname, id: skid, type: 1});				
							}else if (but_id == 'sk_remove'){
								var skid = $('#ed_sk_id').val();
								$('#middle').load('getskills.php',{sk_name : skname, id: skid, type: 0});
							}
						}
					}else {
						var but_id = $target.attr('id');
						if (but_id == 'cont_edit'){
							cnt_edit(event,'person_id');
							return;
						}
						if (but_id == 'cont_cancel'){
							$('#middle').load('connections.php');
							$('a.tips').cluetip(bind_cltips_events);
							$('a.intro_lnk').cluetip(bind_letter_events);
							return;
						}
						if (but_id == 'cont_disable'){
							cnt_disable(event,'person_id','old_ptype');
							return;
						}
						if ((but_id == 'cont_save') || (but_id == 'cont_add')){
							if (!validate_cont_info()) return;
							var mode = 0;
							var pers_ident = "";
							if (but_id == 'cont_save') {
								mode = '1';
								pers_ident = "&id=" + $('#person_id').val();
							}
							if ($('#leader').attr('checked')) {pers_ident += "&ptype=1"; panel = '#lpanel'; pt = 1; panel2 = '#rpanel'; pt2 = 2;}
							else {pers_ident += "&ptype=2"; panel = '#rpanel'; pt = 2; panel2 = '#lpanel'; pt2 = 1;}
							var r_data = "action=save" + pers_ident + "&mode=" + mode +
											"&gname=" + $('#gname').val() + "&lname=" + $('#lname').val() + 
											"&address=" + $('#address').val() + "&zip=" + $('#zip').val() + 
											"&phone=" + $('#phone').val() + "&email=" + $('#email').val() + 
											"&url=" + $('#url').val() + "&skills=";
							var skills = $('[input:checkbox][checked][id^="s"]');
							var size = skills.size();
							var sk = "";
							for (i = 0; i < size; i++){
								sk += skills.eq(i).attr('id').replace(/^(s_)/,'') + ",";
							}
							r_data += sk.replace(/,$/,'');
							after = function(new_content){
								$('#middle').html(new_content);
								if ((but_id == 'cont_save') || (but_id == 'cont_add')) {
									$(panel).load('ln_library.php',{ptype: pt, fn: 'get_plist'},function(){$('a.tips').cluetip(bind_cltips_events)});
									$(panel2).load('ln_library.php',{ptype: pt2, fn: 'get_plist'},function(){$('a.tips').cluetip(bind_cltips_events)});
								}
								//$(panel + '_summ').load('ln_library.php',{ptype: pt, fn: 'get_lsummary'});
								bind_fdinput('#zip,#phone');
							};
						}
						$.ajax({
							type: 'POST',
							url: 'contact.php',
							data: r_data,
							cache: false,
							success: after
						})
					}
				}else return; //skip preventDefault() in the end of function for 'radio' and 'checkbox'
			}else if (tagname == 'td'){
				if ($('#edit_skills_tb').size() > 0) {
					var clname = $target.html();
					$('#sk_name').val(clname);
					if ($('#sk_change').size() == 0) {
						$('#sk_add').before('<input type=\'button\' id=\'sk_change\' value=\'change\'>');
						$('#sk_add').after('<input type=\'button\' id=\'sk_remove\' value=\'remove\'>');
					}
					$('#ed_sk_id').val(clname);
					var old = $('#ed_sk_act').val();
					if (old.length > 0) $('#' + old).css('background-color','');
					$target.css('background-color','#B0C4DE');
					$('#ed_sk_act').val($target.attr('id'));
				}
			}else if (tagname == 'a'){
				var tar_id = $target.attr('id');
				if (tar_id == 'conn_remove'){
					var conn_id = $target[0].parentNode.parentNode.parentNode.getAttribute('id').replace(/^(cb)/,'');
					$.ajax({
						type: 'POST',
						url: 'connections.php',
						data: 'action=remove&conn_id=' + conn_id,
						cache: false,
						success: function(new_content){
							$("#conn tbody[id='cb" + conn_id + "']").css({'display': 'none'});
						}
					})
				}
				var cl = $target.attr('className');
				if (cl == 'xpage'){
					var pg = $target.html().replace(/^\s/,'').replace(/$\s/,'');
					$.ajax({
						type: 'POST',
						url: 'connections.php',
						//data: 'action=view&start=' + pg[0] + "&end=" + pg[1],
						data: 'action=view&page=' + pg,
						cache: false,
						success: function(new_content){
							$("#middle").html(new_content);
							$('a.tips').cluetip(bind_cltips_events);
							$('a.intro_lnk').cluetip(bind_letter_events);
						}
					})
				}
				if (cl == 'cs'){
					$.ajax({
						type: 'POST',
						url: 'connections.php',
						data: 'action=sort&sortby=' + $target.html() + '&page=' + $('#curr_pg').html(),
						cache: false,
						success: function(new_content){
							$("#middle").html(new_content);
							$('a.tips').cluetip(bind_cltips_events);
							$('a.intro_lnk').cluetip(bind_letter_events);
							$('a.filter_lnk').cluetip(bind_filter_events);
						}
					})
				}
			}else if (tagname == 'div'){
				var div_id = $target.attr('id');
				if (div_id == 'conn_showfilt'){
					var filt = $('#conn_filt');
					if (filt.css('display') == 'none')
						$('#conn_cont').slideUp('fast',function(){
							var filt = $('#conn_filt');
							$.ajax({type: 'POST', url: 'filters.php', data: 'action=f_show&fid=3', cache: true, success: function(new_content){$('#conn_filt').html(new_content)}});
							filt.css({width: $('#conn').css('width'), height: $('#conn_cont').css('height')});
							filt.slideDown('fast');
						});
				}
				if (div_id == 'conn_showlist')
					$('#conn_filt').slideUp('fast',function(){$('#conn_cont').slideDown('fast');});
			}
			event.preventDefault();
		});		
	});
	</script>	
</body>
</html>
