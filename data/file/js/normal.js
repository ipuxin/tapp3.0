
function setShareUrl(url){
	return shareHost + url;
}

/*if(typeof(openInWechat) == "undefined"){is_weixin();}
function is_weixin(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger") {
		//window.location.href="http://wx.ganxike.com";
		return true;
 	} else {
		return false;
	}
}*/

function setPageTurn(){$('.page').click(function(){pageTurn($(this).data('page'));});}
setPageTurn();
$(document).bind("selectstart",function(){return false;});//禁止选择文本

/*
store(key, data);                 //单个存储字符串数据
store({key: data, key2: data2});  //批量存储多个字符串数据
store(key);                       //获取key的字符串数据
store();                          //获取所有key/data
//store(false);（弃用）            //因为传入空值 或者报错很容易清空库
store(key,false);                 //删除key包括key的字符串数据

store.set(key, data[, overwrite]);//=== store(key, data);
store.setAll(data[, overwrite]);  //=== store({key: data, key2: data});
store.get(key[, alt]);            //=== store(key);
store.getAll();                   //=== store();
store.remove(key);                //===store(key,false)
store.clear();                    //清空所有key/data
store.keys();                     //返回所有key的数组
store.forEach(callback);          //循环遍历，返回false结束遍历

store.has(key);         //⇒判断是否存在返回true/false        
*/