
function updateStatistics(){
	var total=document.getElementById("total");
	var available=document.getElementById("available");
	var getRecenthttp;
	var jsonResponse;
	getRecenthttp=new XMLHttpRequest();
	getRecenthttp.onreadystatechange=function()
		{
			if (getRecenthttp.readyState < 4) return;
			if (getRecenthttp.status != 200) return;
			else {
					eval('jsonResponse='+getRecenthttp.responseText+';');
					total.innerHTML=jsonResponse['total'];
					available.innerHTML=jsonResponse['available'];
			}
		
		}
		getRecenthttp.open("POST","siteStatistics.php",true);
		getRecenthttp.send(null);
}

function updateMostRecent()
	{
		var img;
		var link;	
		var getRecenthttp;
		var jsonResponse;
		getRecenthttp=new XMLHttpRequest();
		getRecenthttp.onreadystatechange=function()
		{
			if (getRecenthttp.readyState < 4) return;
			if (getRecenthttp.status != 200) return;
			else {
				eval('jsonResponse='+getRecenthttp.responseText+';');
			//	alert(getRecenthttp.responseText);
			//	alert(jsonResponse['item'+(1).toString()]['itemId']);
				for(var i=1;i<5;i++){
				if(jsonResponse['item'+(i-1).toString()]){
					img=document.getElementById("im"+(i).toString());
					img.src=jsonResponse['item'+(i-1).toString()]['imageURL'];
					link=document.getElementById("im"+i.toString()+"link");
					link.href="itemPage.php?itemId="+jsonResponse['item'+(i-1).toString()]['itemId'];
				}
				}
			}
		}
		getRecenthttp.open("POST","getMostRecent.php",true);
		getRecenthttp.send(null);
	}

function activeIntervals(){
	var mostRecentInterval=setInterval(updateMostRecent,5000);
	var statisticInterval=setInterval(updateStatistics,5000);

}