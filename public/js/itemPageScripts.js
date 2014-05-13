function bidForThisItem()
	{
		var bidhttp;
		var jsonResponse;
		var bid=document.getElementById("bidText");
		var itemId=document.getElementById("ITEMID");
		bidhttp=new XMLHttpRequest();
		var data="bid="+bid.value+"&itemId="+itemId.innerHTML;
		bidhttp.onreadystatechange=function()
		{
			if (bidhttp.readyState < 4) return;
			if (bidhttp.status != 200) return;
			else {
				eval('jsonResponse='+bidhttp.responseText+';');
				if(jsonResponse['bidAdded']=="bid added"){
					var currPrice=document.getElementById("CurrentPrice");
					alert(jsonResponse['bidAdded']);
					currPrice.innerHTML=jsonResponse['newCurrent']+"$";
				}
				else 
					alert(jsonResponse['bidAdded']);
				bid.value="";
			}
		}
		bidhttp.open("POST","bidItem.php",true);
		bidhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		bidhttp.send(data);
	}

	function addCommentToDB()
	{
		var loghttp;
		var d=new Date();
		var options = {weekday: "long", year: "numeric", month: "short",day: "numeric", hour: "2-digit", minute: "2-digit"};
		var itemId=document.getElementById("ITEMID").innerHTML;
		var sessionUsername=document.getElementById("sessionUsername").value;
		var comment=document.getElementById("newCommentText").value;
		var data="comment="+comment+"&author="+sessionUsername+"&posted="+d.toLocaleTimeString("en-us", options)+"&itemId="+itemId;
		loghttp=new XMLHttpRequest();
		loghttp.onreadystatechange=function()
		{
			if (loghttp.readyState < 4) return;
			if (loghttp.status != 200) return;
			else {
				if("Comment added"==loghttp.responseText){
					alert(loghttp.responseText);
					location.reload();
				}
				else{
					alert(loghttp.responseText);
				}
			}
		  //logForm.innerHTML = loghttp.responseText;
		}
		loghttp.open("POST","addComment.php",true);
		loghttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		loghttp.send(data);
	}
	function showCommentText(){
		var commentText=document.getElementById("newCommentText");
		var commentButton=document.getElementById("commentButton");
		commentText.style.visibility="visible";
		commentButton.style.visibility="visible";


	}

	function refreshComments(){
		var refreshHTTP;
		var jsonResponse;
		var itemId=document.getElementById("ITEMID").innerHTML;
		var data="itemId="+itemId;
		refreshHTTP=new XMLHttpRequest();
		refreshHTTP.onreadystatechange=function()
		{
			if (refreshHTTP.readyState < 4) return;
			if (refreshHTTP.status != 200) return;
			else {
			if(refreshHTTP.responseText!="false"){
				eval('jsonResponse='+refreshHTTP.responseText+';');
				for (var i = 0; i < jsonResponse.length; i++) {
				//					alert(jsonResponse[i]);

				addCommentDynamic(jsonResponse[i]['body'],jsonResponse[i]['author'],jsonResponse[i]['created']);
					//alert(jsonResponse[i]);
					}
			
			}
				//alert(refreshHTTP.responseText);
			}
		  //logForm.innerHTML = refreshHTTP.responseText;
		}
		refreshHTTP.open("POST","refreshComments.php",true);
		refreshHTTP.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		refreshHTTP.send(data);
	
	
	
	
	}
	
	
	function getServerDate(){
		var dateString = "";
		var newDate = new Date();
		// Get the month, day, and year.
		dateString += newDate.getFullYear()+ "-";
		dateString += (("0" + (newDate.getMonth()+1)).slice(-2)) + "-";
		dateString += (("0" + newDate.getDate()).slice(-2)+" ");
		dateString += (("0" + newDate.getHours()).slice(-2)+":");
		dateString += (("0" + newDate.getMinutes()).slice(-2)+":");
		dateString += (("0" + newDate.getSeconds()).slice(-2));
		return dateString;
	}
	
	function autoRefreshItem()
	{
		var updatehttp;
		var jsonResponse;
		var itemId=document.getElementById("ITEMID").innerHTML;
		var data="itemId="+itemId;
		updatehttp=new XMLHttpRequest();
		
		updatehttp.onreadystatechange=function()
		{
			if (updatehttp.readyState < 4) return;
			if (updatehttp.status != 200) return;
			else {
				//alert(updatehttp.responseText);
				eval('jsonResponse='+updatehttp.responseText+';');
				if(jsonResponse['accepted']=="true"){
					if(jsonResponse['sellTime']==0){
						location.reload();
						clearInterval(itemInterval);
						}
					else{
						var currPrice=document.getElementById("CurrentPrice");
						var sellTime=document.getElementById("sellTime");
						currPrice.innerHTML=jsonResponse['currentPrice']+"$";
						sellTime.innerHTML=jsonResponse['sellTime'];
					}
				}
				else 
					alert(jsonResponse['accepted']);
			}
		}
		updatehttp.open("POST","getItemDetails.php",true);
		updatehttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		updatehttp.send(data);
	}		
	
	
	
	
	
	
	
	function addCommentDynamic(comment,author,date)
	{	
		var rowNode=document.createElement("tr");
		var inRowNode=document.createElement("tr");
		var colNode=document.createElement("td");
		var colText=document.createElement("p");//document.createTextNode(document.getElementById("newCommentText").value);
		colText.innerHTML=comment;
		colText.classList.add("commentBodyText");
		colNode.appendChild(colText);
		colNode.classList.add("commentBodyTd");
		rowNode.appendChild(colNode);
		colNode=document.createElement("td");
		colText=document.createElement("strong");
		colText.innerHTML="Posted by: "+author+" at "+date;
		colNode.classList.add("commentDetailsTd");
		colText.classList.add("commentDetailsText");
		colNode.appendChild(colText);
		inRowNode.appendChild(colNode);
		var commTable = document.getElementById("commentsTable");
		var tableHead=commTable.children[1];
		tableHead.insertBefore(inRowNode, tableHead.children[2]);	
		tableHead.insertBefore(rowNode, tableHead.children[2]);	
}

function activeIntervals()
		{
			var sellTime=document.getElementById("sellTime").innerHTML;
			var index=sellTime.indexOf("Auction completed");
			if(index<0)
				itemInterval=setInterval(autoRefreshItem,5000);
			commentInterval=setInterval(refreshComments,5000);
				
		}