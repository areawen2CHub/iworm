
//系统加载后触发函数
function addLoadEvent(func){
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  }else{
    window.onload = function(){
      oldonload();
      func();
    }
  }
}

(function(window){
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {//判断浏览器是否支持classList
	hasClass = function( elem, c ) {
		return elem.classList.contains( c );
    };
    addClass = function( elem, c ) {
        elem.classList.add( c );
    };
    removeClass = function( elem, c ) {
        elem.classList.remove( c );
    };
}else{
	hasClass = function( elem, c ) {
        return classReg( c ).test( elem.className );
    };
    addClass = function( elem, c ) {
        if ( !hasClass( elem, c ) ) {
            elem.className = elem.className + ' ' + c;
        }
    };
    removeClass = function( elem, c ) {
        elem.className = elem.className.replace( classReg( c ), ' ' );
    };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

var classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( classie );
} else {
  // browser global
  window.classie = classie;
}
})(window);

/**************************封装XMLHttpRequest类*****************************/ 
function HttpRequest(sUrl,fpCallback){
  this.request = this.createXmlHttpRequest();
  this.request.open("GET",sUrl,true);
  var tempRequest = this.request;
  function request_readystatechange(){
    if(tempRequest.readyState == 4){
      if(tempRequest.status == 200){
        fpCallback(tempRequest.responseText);
      }else{
        alert("获取数据失败");
      }
    }else{
      //alert("连接失败");
    }
  }
  this.request.onreadystatechange = request_readystatechange;
}
//通过prototype给类HttpRequest添加方法
HttpRequest.prototype.createXmlHttpRequest = function(){
  if(window.XMLHttpRequest){
    var oHttp = new XMLHttpRequest();
    return oHttp;
  }else if(window.ActiveXObject){
    var versions = 
    [
      "MSXML2.XmlHttp.6.0",
      "MSXML2.XmlHttp.3.0"
    ];
    for(var i=0;i<versions.length;i++){
      try{
        var oHttp = new ActiveXObject(versions[i]);
        return oHttp;
      }catch(error){
        //do nothing here
      }
    }
  }
  return null;
}
HttpRequest.prototype.send = function(){
  this.request.send(null);
}
/**************************封装XMLHttpRequest类*****************************/ 

/*获取cookie
**c_name：cookie名称
*/
function getCookie(c_name){//获取cookie，参数是名称。
  //当cookie不为空的时候就开始查找名称 
  if (document.cookie.length > 0){
    //获取cookie
    var dCookie = document.cookie;
    coo_1 = (dCookie.indexOf(" "+c_name+"=")>0) ? (dCookie.indexOf(" "+c_name+"=")+1) : 0;
    coo_2 = (dCookie.indexOf(";"+c_name+"=")>0) ? (dCookie.indexOf(";"+c_name+"=")+1) : 0;
    coo_3 = (dCookie.indexOf(c_name + "=")==0) ? 0 : -1;
    var c_start = coo_1||coo_2||coo_3;
    if(c_start != -1){
      //如果开始的位置不为-1就是找到了、找到了之后就要确定结束的位置
      c_start = c_start + c_name.length + 1 ;
      //cookie的值存在名称和等号的后面，所以内容的开始位置应该是加上长度和1
      var c_end = document.cookie.indexOf(";",c_start);
      if(c_end == -1) {
        c_end = document.cookie.length;
      }
      return unescape(document.cookie.substring(c_start,c_end));//返回内容，解码。
    } 
  }
  return "";
 }
/*判断是否已经存在了cookie
**c_name：判断条件
*/
function checkCookie(c_name){
  var che_name  = 'vyahui_' + c_name;
  var che_value = getCookie(che_name);
  if(che_value!=null && che_value!="")return true;
  else return false;
}
 /*设置cookie
 **c_name：cookie名称
 **c_value：cookie值
 **c_days：有效期（天数）
 */
 function setCookie(c_name,c_value,c_days){
  var set_name  = 'vyahui_' + c_name;
  var str = set_name + "=" + escape(c_value);
  if(c_days > 0){
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + c_days);
    str += ";expires=" + exdate.toGMTString();
  }
  document.cookie = str;
}
 
/*清除cookie
**c_name：清除条件
*/
function deleteCookie(c_name) {
  var del_name = 'vyahui_' + c_name;
  var exdate = new Date();
  exdate.setDate(exdate.getDate() - 10000);
  var del_value = getCookie(del_name);
  if(del_value!=null){
     document.cookie= del_name + "=" + del_value + ";expires=" + exdate.toGMTString(); 
  }
} 

// 清空cookie
function clearCookie(){
  var keys=document.cookie.match(/[^ =;]+(?=\=)/g);
  if (keys) {
    for (var i = keys.length; i--;)
    document.cookie=keys[i]+'=0;expires=' + new Date( 0).toUTCString()
  }
} 

//移动滚动条动态调整导航
var moveScrollAdjustHeader=(function(){var b=document.documentElement,g=document.querySelector(".header-main"),e=false,a=30;function f(){window.addEventListener("scroll",function(){if(!e){e=true;setTimeout(d,100)}},false);}function d(){var h=c();if(h>=a){classie.add(g,"header-main-shrink");}else{classie.remove(g,"header-main-shrink");}e=false;}function c(){return window.pageYOffset||b.scrollTop;}f();})();
var moveScrollAdjustHeader=(function(){var b=document.documentElement,g=document.querySelector(".m-header-main"),e=false,a=56;function f(){window.addEventListener("scroll",function(){if(!e){e=true;setTimeout(d,100)}},false);}function d(){var h=c();if(h>=a){classie.add(g,"m-header-main-shrink");}else{classie.remove(g,"m-header-main-shrink");}e=false;}function c(){return window.pageYOffset||b.scrollTop;}f();})();


//加载时判断用户是否已点过赞
var checkPraise = (function(){
  //clearCookie();
  var pObj = document.getElementsByClassName('praise');
  for(var i=0; i<pObj.length; i++){
    var aObj = pObj[i].firstChild;
    var attrId = pObj[i].firstChild.getAttribute('id');
    if(checkCookie(attrId)==true) {
      aObj.firstChild.style.color = "#e3722e";
      classie.add(aObj.firstChild,"icon-heart");
    }else{
      aObj.firstChild.style.color = "#fff";
      classie.add(aObj.firstChild,"icon-heart-empty");
    }
  }
})();

function addDelHits(sRequestText){
  //alert(sRequestText);
}

// 点赞
function praise(obj){
  var nextSb = parseInt(obj.nextSibling.innerHTML);
  var attrId = obj.getAttribute('id');
  if(checkCookie(attrId)==true) {
    nextSb = nextSb - 1;
    obj.nextSibling.innerHTML = nextSb;
    deleteCookie(attrId);
    obj.firstChild.style.color = "#fff";
    classie.remove(obj.firstChild,"icon-heart");
    classie.add(obj.firstChild,"icon-heart-empty");
  }else{
    setCookie(attrId,attrId,1);
    nextSb = nextSb + 1;
    obj.nextSibling.innerHTML = nextSb;
    obj.firstChild.style.color = "#e3722e";
    classie.remove(obj.firstChild,"icon-heart-empty");
    classie.add(obj.firstChild,"icon-heart");
  }
  //异步请求
  var url = "/vyahui/hits.php?id=" + attrId + "&hits=" + nextSb;
  //创建一个HttpRequest对象，传递一个URL和一个方法名
  var ajaxRequest = new HttpRequest(url,addDelHits);
  ajaxRequest.send();
  return false;
}