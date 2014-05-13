function onloadlogin(){
	var sessionDisplayName=document.getElementById("sessionDisplayName").value;
	var logForm=document.getElementById("loginForm");
	var p=document.getElementById("postItem");
	p.style.visibility="visible";
	logForm.innerHTML="Welcome "+sessionDisplayName+"<br><br> <a style='cursor:pointer;color:red;' onclick='logoutt()' id='logout' >Logout</a>";	
}

function onloadloginExt(){
	var sessionDisplayName=document.getElementById("sessionDisplayName").value;
	var logForm=document.getElementById("loginForm");
	logForm.innerHTML="Welcome "+sessionDisplayName+"<br><br> <a style='cursor:pointer;color:red;' onclick='logoutt()' id='logout' >Logout</a>";	
}


function loginn()
{
	var loghttp;
	var username=document.getElementById("username").value;
	var password=document.getElementById("password").value;
	var logForm=document.getElementById("loginForm");
	loghttp=new XMLHttpRequest();
	var data="username="+username+"&password="+password;
	loghttp.onreadystatechange=function()
	{
		if (loghttp.readyState < 4) return;
		if (loghttp.status != 200) return;
		else {
			if(loghttp.responseText=="Welcome")
			{
				location.reload();
			}
			else{
				alert("Wrong username/password");
			}
		}
	}
	loghttp.open("POST","mylogin.php",true);
	loghttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	loghttp.send(data);
}
function loginn2()
{
	var loghttp;
	var username=document.getElementById("username").value;
	var password=document.getElementById("password").value;
	var logForm=document.getElementById("registerForm");
	loghttp=new XMLHttpRequest();
	var data="username="+username+"&password="+password;
	loghttp.onreadystatechange=function()
	{
		if (loghttp.readyState < 4) return;
		if (loghttp.status != 200) return;
		else {
			if(loghttp.responseText=="Welcome")
			{
				location.reload();
			}
			else{
				alert("Wrong username/password");
			}
		}
	}
	loghttp.open("POST","mylogin.php",true);
	loghttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	loghttp.send(data);
}

function logoutt()
{
	var loghttp;
	var logoutText=document.getElementById("logout");
	loghttp=new XMLHttpRequest();
loghttp.onreadystatechange=function()
  {
	if (loghttp.readyState < 4) return;
    if (loghttp.status != 200) return;
	location.reload(true);
  
  }
loghttp.open("POST","logout.php",true);
loghttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
loghttp.send();

}
