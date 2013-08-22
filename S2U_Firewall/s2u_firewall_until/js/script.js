var $j=jQuery.noConflict();var time;var timer;var t;var cok;var url=document.referrer;var op = new Array;var loading=false;var timers;
$j(document).ready(function(){
	if(top.location != self.location){top.location = self.location}
	t=$j('#container');
	cok=getCookie('timeout');
	if(cok>0&&t.text()<1){
		time=cok;
	} else {
		time=t.text();
	}
	if(time>0){
		initSC();
	} else {
		setCookie('timeout','',0,'/');
	}
	var k=$j("#home");
	k.bind('keydown', 'ctrl+s', function(e){
		e.preventDefault();
		callFunction('save',0)
	});
	setInterval("detectPageActive()", 550);
	op['#trangchu'] = '';
	op['#cauhinh'] = '<li><a onClick="callFunction(\'default\',0)">Tối ưu</a></li>'+
					'<li><a onClick="callFunction(\'restore\',0)">Cập nhật lại</a></li>'+
					'<li><a onClick="callFunction(\'save\',0)">Lưu lại</a></li>';
	op['#kiemtra'] = '<li><a onClick="callFunction(\'check\',0)">Kiểm tra</a></li>';
	op['#tinhtrang'] = '<li><a onClick="callFunction(\'updatefw\',0)">Cập nhật</a></li>'+
					'<li><a onClick="callFunction(\'stopud\',0)">Dừng</a></li>'+
					'<li><a onClick="callFunction(\'autoud\',0)">Tự động</a></li>'+
					'<li><a onClick="callFunction(\'status\',0)">Lấy thông tin</a></li>';
	op['#congcu'] = '<li><a onClick="callFunction(\'add\',0)">Lưu</a></li>';
	changeActive(window.location.hash)
	$j('#trangchubtn').click(function(){nextPage('#trangchu');});
	$j('#cauhinhbtn').click(function(){nextPage('#cauhinh');});
	$j('#kiemtrabtn').click(function(){nextPage('#kiemtra');});
	$j('#tinhtrangbtn').click(function(){nextPage('#tinhtrang');});
	$j('#congcubtn').click(function(){nextPage('#congcu');});
});
function detectPageActive(){
	var s=$j('body').scrollTop();
	//$j('.note').css({"display":""});$j('.note').html(s);
	if(s>0){changeActive('#trangchu');}
	if(s>500){changeActive('#cauhinh');}
	if(s>1555){changeActive('#kiemtra');}
	if(s>3000){changeActive('#tinhtrang');}
	if(s>3300){changeActive('#congcu');}
}
function nextPage(id){
	if($j(id+"btn").attr("class")!='active'){$j('.note').css({"display":"none"});$j('.pop').css({"display":"none"});}
	changeActive(id);
}
function changeActive(id){if($j(id+"btn").html()!=null){$j(".option").html(op[id]);}
	$j('#trangchubtn').attr("class", "");
	$j('#cauhinhbtn').attr("class", "");
	$j('#kiemtrabtn').attr("class", "");
	$j('#tinhtrangbtn').attr("class", "");
	$j('#congcubtn').attr("class", "");
	$j(id+"btn").attr("class", "active");
}
function initSC(){
	if(t.text()!=''){
		t.html('Chuyển trang trong: '+time+'s');
		timer=setInterval(countDown, 900);
	} else {
		setTimeout(initSC, 900);
	}
}
function setCookie(name,value,expires,path,domain,secure){
	var today = new Date();today.setTime( today.getTime() );
	if(expires){expires = expires * 1000 * 60 * 60 * 24;}
	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}
function getCookie(c_name){
var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++){
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name){
			return unescape(y);
		}
	}
}
function popup(url){
	mywindow=window.open (url, "webbanner");
	mywindow.moveTo(0, 0);
}
function countDown(){
	t.html('Chuyển trang trong: '+time+'s');
	setCookie('timeout',time,1,'/');
	if(time <= 0){
		setCookie('timeout','',0,'/');
		clearInterval(timer);
		if(location.href==url||url==''){
			url=window.location.protocol+"//"+location.hostname+location.pathname;
		}
		var u=$j('#url');
		if(u.val()!=''&&u.val()!=undefined){
			url=u.val();
		}
		window.location=url;
		t.html("Đang tải...");
	}
	time--;
}
var buttonstate=0;
function onoff(element){
  buttonstate= 1 - buttonstate;
  var blabel, bstyle, bcolor;
  if(buttonstate)
  {
    blabel="Mở";
    bstyle="green";
    bcolor="lightgreen";
  }
  else
  {
    blabel="Tắt";
    bstyle="lightgray";
    bcolor="gray";
  }
  var child=element.firstChild;
  child.style.background=bstyle;
  child.style.color=bcolor;
  child.innerHTML=blabel;
}
var keyStr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
function encode64(input) {
	input=escape(input);var output="";var chr1, chr2, chr3="";var enc1, enc2, enc3, enc4="";var i=0;
	do {
		chr1=input.charCodeAt(i++);chr2=input.charCodeAt(i++);chr3=input.charCodeAt(i++);
		enc1=chr1 >> 2;enc2=((chr1 & 3) << 4) | (chr2 >> 4);enc3=((chr2 & 15) << 2) | (chr3 >> 6);enc4=chr3 & 63;
		if (isNaN(chr2)) {enc3=enc4=64;
		} else if (isNaN(chr3)) {enc4=64;}
		output=output + keyStr.charAt(enc1) +	keyStr.charAt(enc2) + keyStr.charAt(enc3) +	keyStr.charAt(enc4);
		chr1=chr2=chr3="";enc1=enc2=enc3=enc4="";
	} while (i < input.length);
	return output;
}
function decode64(input) {
	var output="";var chr1, chr2, chr3="";var enc1, enc2, enc3, enc4="";var i=0;
	var base64test=/[^A-Za-z0-9\+\/\=]/g;
	input=input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
	do {
		enc1=keyStr.indexOf(input.charAt(i++));enc2=keyStr.indexOf(input.charAt(i++));
		enc3=keyStr.indexOf(input.charAt(i++));enc4=keyStr.indexOf(input.charAt(i++));
		chr1=(enc1 << 2) | (enc2 >> 4);chr2=((enc2 & 15) << 4) | (enc3 >> 2);chr3=((enc3 & 3) << 6) | enc4;output=output + String.fromCharCode(chr1);
	if (enc3 != 64) {output=output + String.fromCharCode(chr2);}
	if (enc4 != 64) {output=output + String.fromCharCode(chr3);}
	chr1=chr2=chr3="";enc1=enc2=enc3=enc4="";
	} while (i < input.length);
	return unescape(output);
}

function urldecode(url) {
	return decodeURIComponent(url.replace(/\+/g, ' '));
}
function showNote(note,c,id){
	$j('.note').css({"display":""});
	$j('.note').html(urldecode(note));
	if(c==''){
		$j('.pop').css({"display":"none"});
		$j('.pop').html('');
	} else {
		showPop(c,id);
	}
}
function showPop(con,id){
	var str=decode64(con);
	str=str.replace(/<\/?li>/g,'');
	if(id==2){str=str.replace(/value=".*?"\//g,'value=""/');}
	$j('.pop').css({"display":""});
	$j('.pop').css({"padding":"5px 3px"});
	$j('.pop').html(str+"<input type=\"button\" onClick=\"callFunction('save',0)\" value=\"Áp dụng\"/>");
}
function onoff(id,v,nx,n){
	if(v==0){
		var idi='id=\"'+id+'\"';var show="onMouseOver=\"showNote('"+urldecode(n)+"','"+nx+"',"+id+")\"";
		if(nx!=''){
			idi='';showPop(nx,id);
			$j('.note').css({"display":""});
			$j('.note').html(urldecode(n));
		} else {show='';}
		$j('#btn'+id).html('= <input '+idi+' type=\"button\" '+show+' onClick=\"onoff('+id+',1,\''+nx+'\',\''+n+'\')\" value=\"Bật\"/>');
	} else {
		$j('.note').css({"display":"none"});
		$j('.pop').css({"display":"none"});
		$j('#btn'+id).html('= <input id=\"'+id+'\" type=\"button\" onClick=\"onoff('+id+',0,\''+nx+'\',\''+n+'\')\" value=\"Tắt\"/>');
	}
}
function callFunction(cmd,s){
	if(cmd=='login'){var key=(s.which)?s.which:s.keyCode;if(key!=13){return;}}
	if(loading!=true){
		loading=true;$j('.note').css({"display":""});$j('.note').html('Đang xử lý...');
		var arrs=new Array();var i=0;
		if(cmd=='save'){while(i<35){arrs.push(fixVal($j('#'+i).val()));i++;}}
		if(cmd=='add'){var i=35;while(i<38){arrs.push(fixVal($j('#'+i).val()));i++;}}
		if(cmd=='login'){arrs.push($j('#'+cmd).val());}
		if(cmd=='unlockip'||cmd=='closeas'||cmd=='closesp'){arrs.push(s);}
		$j.ajax({
			type: "POST",
			url: "s2u_firewall_admin.php",
			data: 'go='+cmd+'&value='+arrs,
			dataType: "html",
			success: function(data){
				loading=false;var str;
				var datas = utf8_decode(decode64(data));
				if(datas.match(/Tình trạng/g)==null){datas = utf8_decode(datas);}
				//alert(cmd);
				if(cmd=='save'||cmd=='logout'||cmd=='login'||cmd=='add'){
					location.reload(true);
				} else if(cmd=='check'||cmd=='status'||cmd=='unlockip'||cmd=='closeas'||cmd=='closesp'){
					if($j('#status').html().match(/Điểm hệ thống/g)!=null){str='OK';}
					if(cmd=='unlockip'||cmd=='closeas'||cmd=='closesp'){cmd='status';}$j('#'+cmd).html(datas);
				} else if(cmd=='autoud'||cmd=='stopud'){
					callFunction('status');
					if(data==1){timers = setInterval(function(){callFunction('status')}, 5000);}else{clearInterval(timers);}
				} else if(cmd=='dellog'){$j('#LOGSG').html('Không có thông tin về nhật ký hệ thống.');$j('#LOGS').html('<br/>');
				} else if(cmd=='clearip'){$j('#IPSG').html('Không có địa chỉ IP theo dõi trong hệ thống.<br/>');$j('#IPS').html('<br/>');
				} else if(cmd=='updatefw'){$j('.pop').css({"display":""});$j('.pop').css({"padding":"5px 3px"});$j('.pop').html(datas);
				} else {$j('#conf').html(datas);}
				if(cmd=='status'){
					var myID = new Array('FWS','IPS','LOGS');var count=0;
					for (s in myID){
						str=$j('#'+myID[s]).html();i=null;
						if(str.match(/<br\/?>/g)!=null){i=str.match(/<br\/?>/g);}
						str=$j('#'+myID[s]+'G').html();
						
						if(str!=null&&str.match(/<br\/?>/g)!=null){
							count=str.match(/<br\/?>/g).length;
						}
						if(i===null||i==undefined){i=0;}else{i=i.length;}
						if(count>i){
							if(str=='OK'){i=count-i;}
							if(myID[s]!='FWS'){i=i+1;}
							while(i<=count){$j('#'+myID[s]).append('<br>');i++;}
						} else if(count<i&&myID[s]!='FWS'){
							i=i-count;count=count+i;
							$j('#'+myID[s]).empty();
							while(i<count){$j('#'+myID[s]).append('<br>');i++;}
						}count=0;
					}
				}
				$j('.note').html('Hoàn tất !');
			}
		});
	}
}
function fixVal(vl){
	var str=vl||"NUL";
	str=str.replace("Bật",1);
	str=str.replace("Tắt",0);
	return str;
}
function utf8_decode(str_data){
  var tmp_arr = [],
    i = 0,ac = 0,c1 = 0,c2 = 0,c3 = 0;
  str_data += '';
  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 < 128) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 > 191 && c1 < 224) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    }
  }
  return tmp_arr.join('');
}