function show_post_content(q)
{
	var id = q;
	var linkid = document.getElementById(q);
	if(linkid.title=="show")
	{
		var pid = document.getElementById("post_java_id").value;
		if(pid=="")
		{
			document.getElementById("sline"+id).style.display = 'block';	
			document.getElementById("cont"+id).style.display = 'block';	
			document.getElementById(id).className = "showpost selected greend borderwhite";
			linkid.title="hide";
			document.getElementById("post_java_id").value=id;
			exit();
		}
		else
		{
			document.getElementById("cont"+pid).style.display = 'none';	
			document.getElementById("sline"+pid).style.display = 'none';
			document.getElementById(pid).className = "showpost";
			var finkid = document.getElementById(pid);
			finkid.title = "show";
			
			document.getElementById("sline"+id).style.display = 'block';	
			document.getElementById("cont"+id).style.display = 'block';
			document.getElementById(id).className = "showpost selected greend borderwhite";
			document.getElementById("post_java_id").value=id;
			linkid.title="hide";
			exit();
		}
	}
	if(linkid.title=="hide")
	{
		document.getElementById("cont"+id).style.display = 'none';	
		document.getElementById("sline"+id).style.display = 'none';
		document.getElementById(id).className = "showpost";
		linkid.title="show";
		
	}
}
var xmlhttp;
function alph_list(str,mainurl)
{
		var nonceValue = document.getElementById("nonce_value").innerHTML;
		var id = document.getElementById("atozlist").value;
		if(id=="")
		{
			document.getElementById("li_A").className = "active";
			document.getElementById("atozlist").value = "A";
		}
		else
		{
			document.getElementById("li_"+id).className = "";
			document.getElementById("li_"+str).className = "active";
			document.getElementById("atozlist").value = str;
		}

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
	  alert ("Browser does not support HTTP Request");
	  return;
  }
var url=mainurl+"/wp-content/plugins/a-to-z-category-listing/post_retrive_ajax.php";
url=url+"?R="+str+"&_ajax_nonce="+nonceValue;
xmlhttp.onreadystatechange=stateChanged;
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
}

function stateChanged()
{
		if(xmlhttp.readyState<4)
		{
			//document.getElementById("areaHint").src = "loading6.gif";
			document.getElementById("areaHint").innerHTML = "<div align='center'><img src='/wp-content/plugins/a-to-z-category-listing/images/loading.gif' /></div>";
		}
		if (xmlhttp.readyState==4)
		{
		document.getElementById("areaHint").innerHTML=xmlhttp.responseText;
		}
}
//###################################################################################################
function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}

