/**
**某个函数需浏览器加载后运行，调用此函数即可
**调用参数即函数名
**2015-10-23
*/
function addLoadEvent(func){
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    }else {
        window.onload = function(){
            oldonload();
            func();
        }
    }
}

/**
**幻灯片
**样式：slides-image-m1
*/ 
function slidesImageM1(){
    if(!document.getElementById) return false;
    if(!document.getElementsByClassName) return false;
    if(!document.getElementById("banner")) return false;
    if(!document.getElementById("banner").hasChildNodes) return false;
    if(!document.createElement) return false;
    var banObj = document.getElementById("banner");
    if(banObj.getElementsByTagName("div").length > 0){
    	var picObj = banObj.getElementsByTagName("div")[0];
    }else {
    	return false;
    }
    //获取幻灯片数量
    var imgNum = picObj.getElementsByTagName("li");

    if(imgNum.length > 0){
    	//确定宽度和偏移量百分比 
        var imgWid = 100/imgNum.length; 
    }else {
    	return false;
    }

    // 创建幻灯片数字
    var newNum = document.createElement("div");
    newNum.className = "slides-num";
    var newNumUl  = document.createElement("ul");

    // 创建幻灯片缩略图
    var newThu = document.createElement("div");
    newThu.className = "slides-thu";
    var newThuUl  = document.createElement("ul");

    var index  = 0;
    for (var i = 0; i<imgNum.length; i++){
        var newNumLi = document.createElement("li");
        newNumLi.style.width = imgWid + "%";
        newNumLi.setAttribute("order",i);
        if(i == imgNum.length - 1){
        	newNumLi.style.borderRight = "0";
        }
        newNumUl.appendChild(newNumLi);

        var newThuLi = document.createElement("li");
        newThuLi.style.width = imgWid + "%";
        newThuLi.setAttribute("order",i);
        newThuUl.appendChild(newThuLi);
        var newThuLiImg = document.createElement("img");
        newThuLiImg.src = imgNum[i].getElementsByTagName("img")[0].getAttribute("src");
        newThuLi.appendChild(newThuLiImg);

        newNumLi.onmouseover = function(){
        	if(playTimer){
        		clearInterval(playTimer);
        	}
        	onMouseImgEnter(this.getAttribute("order"));
        }

        newNumLi.onmouseout = function(){
        	if(playTimer){
        		clearInterval(playTimer);
        	}
        	onMouseImgLeave(this.getAttribute("order"));
        	autoPlay(currentPlayIndex);
        }

        newNumLi.onclick = function(){
        	currentImage(this.getAttribute("order"));
        	currentPlayIndex = this.getAttribute("order");
        }
    }
    
    // 添加幻灯片数字
    newNum.appendChild(newNumUl);
    banObj.appendChild(newNum);
    
    // 添加幻灯片缩略图
    newThu.appendChild(newThuUl);
    banObj.appendChild(newThu);

    // 默认显示第一页
    currentImage(index);

    // 自动循环播放 
    autoPlay(index);
   


}

//自动循环播放
function autoPlay(order){
	var banObj = document.getElementById("banner");
	var divLis = banObj.getElementsByTagName("div");
	for(var i=0; i<divLis.length; i++){
		if(divLis[i].className == "slides-pic"){
			var thuObj = divLis[i];
		}
	}
	var thuLis = thuObj.getElementsByTagName("li");
    playTimer = setInterval(function(){
        order++;
        if(order >= thuLis.length){
        order = 0;
    }

    //当前循环参数
    currentPlayIndex = order;

    currentImage(order);
  },3000);
}

//当前图片
function currentImage(order){
	var banObj = document.getElementById("banner");
	var divLis = banObj.getElementsByTagName("div");
	for(var i=0; i<divLis.length; i++){
		if(divLis[i].className == "slides-pic"){
			var thuObj = divLis[i];
		}
		if(divLis[i].className == "slides-num"){
			var numObj = divLis[i];
		}
	}
	var thuLis = thuObj.getElementsByTagName("li");
	var numLis = numObj.getElementsByTagName("li");
	for (var i = 0; i < thuLis.length; i++) {
		thuLis[i].className = "";
		numLis[i].className = "";
	}
	thuLis[order].className = "current";
	numLis[order].className = "current";
}

//鼠标放上，图片进入
function onMouseImgEnter(order){
	var banObj = document.getElementById("banner");
	var divLis = banObj.getElementsByTagName("div");
	for(var i=0; i<divLis.length; i++){
		if(divLis[i].className == "slides-thu"){
			var thuObj = divLis[i];
		}
	}
	var thuLis = thuObj.getElementsByTagName("li");
	var curImg = thuLis[order].getElementsByTagName("img")[0];
	curImg.style.display = "block";
  	curImg.visibility = "visible";
    var alpha  = 0;
    var offset = 0;
    var speed  = 1;
    var timer  = setInterval(function(){
        if (alpha == 100 && offset == 60) {
            clearInterval(timer);
        }else {
            if (alpha < 100){
        		alpha += speed;
                speed += 1; 
            }else {
            	alpha = 100;
            }
            if (offset < 60){
          	    offset += Math.ceil((60-offset)/10);
            }else {
            	offset = 60;
            }
        }
        curImg.style.filter = "alpha(opacity='+alpha+')";
        curImg.style.opacity = alpha/100;
        curImg.style.top = offset + "px";
    },15);
}
//鼠标离开，图片消失
function onMouseImgLeave(order){
	var banObj = document.getElementById("banner");
	var divLis = banObj.getElementsByTagName("div");
	for(var i=0; i<divLis.length; i++){
		if(divLis[i].className == "slides-thu"){
			var thuObj = divLis[i];
		}
	}
	var thuLis = thuObj.getElementsByTagName("li");
	var curImg = thuLis[order].getElementsByTagName("img")[0];
    var alpha  = 100;
    var offset = 60;
    var timer  = setInterval(function(){
  	    if (alpha == 0 && offset == 0){
  		    clearInterval(timer);
  		    curImg.style.display = "none";
  		    curImg.visibility = "hidden";
  	    }else {
  		    if (alpha > 0){
  			    alpha -= 100/60;
  		    }else {
  		    	alpha = 0;
  		    }
  		    if (offset > 0){
  			    offset -= 1;
  		    }else {
  		    	offset = 0;
  		    }
  	    }
  	    curImg.style.filter = "alpha(opacity='+alpha+')";
        curImg.style.opacity = alpha/100;
        curImg.style.top = offset + "px";
    },5);
}

// 加载后调用slidesImageM1
addLoadEvent(slidesImageM1);



/*
****
*幻灯片
*图片大小自适应
*移动设备优先
****
*/
// var slidesMobile=(function(){
//   var oBann = document.getElementById("banner");
//   var oUl   = oBann.getElementsByTagName("ul");
//   var iImgs = oUl[0].getElementsByTagName("li");
//   var timer = play = null;
//   var i = index = 0;  
//   //在ul后面创建slides-count
//   var oCoun = document.createElement("div");
//   oCoun.className = "slides-count text-center";//text-center居中
//   if(oBann.lastChild == oUl){
//     oBann.appendChild(oCoun);
//   }else{
//     oBann.insertBefore(oCoun, oUl.nextSibling); 
//   }
//   //在slides-count下创建span
//   for (i = 0; i < iImgs.length; i++){
//     oCoun.appendChild(document.createElement("span"));
//   }
//   //获取oCoun下span
//   var oSpan = oCoun.getElementsByTagName("span");
//   //默认第一张
//   iImgs[0].className = "current";
//   oSpan[0].className = "current";
//   //切换按钮
//   for (i = 0; i < oSpan.length; i++)
//   {
//     oSpan[i].index = i;
//     oSpan[i].onmouseover = function ()
//     {
//       show(this.index);
//     }
//   }
//   //鼠标划过关闭定时器
//   oBann.onmouseover = function ()
//   {
//     clearInterval(play);  
//   };
//   //鼠标离开启动自动播放
//   oBann.onmouseout = function ()
//   {
//     autoPlay();
//   };  
//   //自动播放函数
//   function autoPlay ()
//   {
//     play = setInterval(function () {
//       index++;
//       index >= iImgs.length && (index = 0);
//       show(index);    
//     },3000);  
//   }
//   autoPlay();//应用
//   //图片切换, 淡入淡出效果
//   function show (a)
//   {
//     index = a;
//     var alpha = 0;
//     for (i = 0; i < iImgs.length; i++){
//       iImgs[i].className = "";
//       oSpan[i].className = "";
//     }
//     iImgs[index].className = "current";
//     oSpan[index].className = "current";
//     clearInterval(timer);     
    
//     for (i = 0; i < iImgs.length; i++)
//     {
//       iImgs[i].style.opacity = 0;
//       iImgs[i].style.filter = "alpha(opacity=0)"; 
//     }
    
//     timer = setInterval(function () {
//       alpha += 2;
//       alpha > 100 && (alpha =100);
//       iImgs[index].style.opacity = alpha / 100;
//       iImgs[index].style.filter = "alpha(opacity = " + alpha + ")";
//       alpha == 100 && clearInterval(timer)
//     },20);
//   }
// })()

