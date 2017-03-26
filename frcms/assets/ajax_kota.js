var ajaxku;
var host = window.location.hostname;

/* ======================================== KOTA ====================================*/ 
function ajaxkota(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?q=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChanged;
	ajaxku.open("GET", url, true);
	//change table
	ajaxku.send(null);
}

function ajaxkota1(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?q=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChanged1;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

function ajaxkota2(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?q=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChanged2;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

function ajaxkota3(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?q=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChanged3;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}
/* ======================================== KECAMATAN ====================================*/ 
function ajaxkec(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?kec=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChangedKec;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

function ajaxkec1(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?kec=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChangedKec1;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

function ajaxkec2(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?kec=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChangedKec2;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

function ajaxkec3(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?kec=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChangedKec3;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

/* ======================================== KELUARGA ====================================*/ 
function ajaxkel(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?kel=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChangedKel;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

function ajaxkel1(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?kel=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChangedKel1;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

function ajaxkel2(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?kel=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChangedKel2;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

function ajaxkel3(id) {
	ajaxku = buatajax();
	var url = "http://" + host + "/dav/select_kota";
	url = url + "?kel=" + id;
	url = url + "&sid=" + Math.random();
	ajaxku.onreadystatechange = stateChangedKel3;
	ajaxku.open("GET", url, true);
	ajaxku.send(null);
}

/* ======================================== STATUS CHANGED ====================================*/ 
function buatajax() {
	if (window.XMLHttpRequest) {
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}
function stateChanged() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kota").innerHTML = data;
		} else {
			document.getElementById("kota").value = "<option selected>Pilih Kota/Kab</option>";
		}
	}
}

function stateChanged1() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kota1").innerHTML = data;
		} else {
			document.getElementById("kota1").value = "<option selected>Pilih Kota/Kab</option>";
		}
	}
}

function stateChanged2() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kota2").innerHTML = data;
		} else {
			document.getElementById("kota2").value = "<option selected>Pilih Kota/Kab</option>";
		}
	}
}

function stateChanged3() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kota3").innerHTML = data;
		} else {
			document.getElementById("kota3").value = "<option selected>Pilih Kota/Kab</option>";
		}
	}
}

/* ======================================== STATUS CHANGED 1 ====================================*/ 

function stateChangedKec() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kec").innerHTML = data;
		} else {
			document.getElementById("kec").value = "<option selected>Pilih Kecamatan</option>";
		}
	}
}

function stateChangedKec1() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kec1").innerHTML = data;
		} else {
			document.getElementById("kec1").value = "<option selected>Pilih Kecamatan</option>";
		}
	}
}

function stateChangedKec2() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kec2").innerHTML = data;
		} else {
			document.getElementById("kec2").value = "<option selected>Pilih Kecamatan</option>";
		}
	}
}

function stateChangedKec3() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kec3").innerHTML = data;
		} else {
			document.getElementById("kec3").value = "<option selected>Pilih Kecamatan</option>";
		}
	}
}

/* ======================================== STATUS CHANGED 2 ====================================*/ 

function stateChangedKel() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kel").innerHTML = data;
		} else {
			document.getElementById("kel").value = "<option selected>Pilih Kelurahan/Desa</option>";
		}
	}
}

function stateChangedKel1() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kel1").innerHTML = data;
		} else {
			document.getElementById("kel1").value = "<option selected>Pilih Kelurahan/Desa</option>";
		}
	}
}

function stateChangedKel2() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kel2").innerHTML = data;
		} else {
			document.getElementById("kel2").value = "<option selected>Pilih Kelurahan/Desa</option>";
		}
	}
}

function stateChangedKel3() {
	var data;
	if (ajaxku.readyState == 4) {
		data = ajaxku.responseText;
		if (data.length >= 0) {
			document.getElementById("kel3").innerHTML = data;
		} else {
			document.getElementById("kel3").value = "<option selected>Pilih Kelurahan/Desa</option>";
		}
	}
}