// ajax.js
// These are basically borrowed from the CRS ajaxlib

function makeRequest(url,runFunction) {
	var http_request = false;
	var timestamp = new Date();

	// Modify URL with querystring to ensure uniqueness (prevent browser caching)
	// If URL already has a querystring, append a new querystring (i.e. "&qs=qs")
	if (url.search(/\?/) > 0) url += "&timestamp=" + timestamp.getTime();
	// Otherwise, append a querystring (i.e. "?qs=qs")
	else url += "?timestamp=" + timestamp.getTime();
	//dump("Making request: ",url);

	if (window.ActiveXObject) { // IE
    try {
      http_request = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
       http_request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
			}
    }
  } else if (window.XMLHttpRequest) { // Mozilla, Safari,...
    http_request = new XMLHttpRequest();
    if (http_request.overrideMimeType) {
      http_request.overrideMimeType('text/xml');
      // See note below about this line
    }
  }
  if (!http_request) {
    alert('Giving up :( Cannot create an XMLHTTP instance');
    return false;
  }
  http_request.onreadystatechange = function() { getRequestResponse(http_request,runFunction); };
  http_request.open('GET', url, true);
  // The following helps prevent the user's browser from using cached data
  http_request.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT" );
  http_request.send(null);
}


function getRequestResponse(http_request, runFunction) {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			if (http_request.responseXML.documentElement) xmldoc = http_request.responseXML.documentElement;
			else xmldoc = http_request.responseXML;
			runFunction(xmldoc);
		} else {
			report('There was a problem with the request: '+ http_request.status);
		}
	}
}

function setVar(xmlObj,element) {
	// Given an xml object and an element name, return the element value
	if (xmlObj.getElementsByTagName(element).length>0 && xmlObj.getElementsByTagName(element)[0].hasChildNodes()) {
		return xmlObj.getElementsByTagName(element)[0].firstChild.data;
	} else {
		return "No data";
	}
}

function report(str) {
	alert(str);	
}