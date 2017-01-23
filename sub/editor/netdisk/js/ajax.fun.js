function goIn(s_id) {
	try {
		location = 'netdisk.php?folderid=' + s_id;

	} catch (e) {

	}
}
function goUp(s_id) {
	location = 'netdisk.php?folderid=' + s_id;
}
function goInAttachMent(s_id) {
	try {
		location = 'netdisk_attchment.php?folderid=' + s_id;

	} catch (e) {

	}
}
function goUpAttachMent(s_id) {
	location = 'netdisk_attchment.php?folderid=' + s_id;
}
function selected(obj, s_url) {
	var img = {};
	img.src = s_url;
	img.data_ue_src = s_url;
	if (obj.className == "off") {
		obj.className = "on"
		parent.ImgObjs.push(img);
	} else {
		obj.className = "off"
		// 去掉数组中的值
		parent.ImgObjs.removeByValue(s_url)
	}

}
function selectedFile(obj, s_url,filename,suffix) {
	var img = {};
	img.url = s_url;
	img.type='.'+suffix
	img.original=filename
	if (obj.className == "off") {
		obj.className = "on"
		parent.filesList.push(img);
	} else {
		obj.className = "off"
		// 去掉数组中的值
		parent.filesList.removeByValue(s_url)
	}

}
Array.prototype.removeByValue = function(val) {
	for (var i = 0; i < this.length; i++) {
		if (this[i].url == val) {
			this.splice(i, 1);
			break;
		}
	}
}
