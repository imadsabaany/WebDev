var lastUpdate="";

function getResults()
{
	var updatehttp;
	var noResults=document.getElementById("noResults");
	var jsonResponse;
	var searchString=document.getElementById("query").value;
	var searchTable=document.getElementById("searchTable");
	var data="query="+searchString;
	updatehttp=new XMLHttpRequest();
	updatehttp.onreadystatechange=function()
	{
		if (updatehttp.readyState < 4) return;
		if (updatehttp.status != 200) return;
		else {
			eval('jsonResponse='+updatehttp.responseText+';');
			if(lastUpdate=="")
			{
				lastUpdate=jsonResponse;
			}
			else{
				if(jsonResponse!="false" && !(JSON.stringify(lastUpdate)==JSON.stringify(jsonResponse))){
					if(noResults)
						noResults.innerHTML="";
					for(var i = searchTable.rows.length - 1; i > 0; i--)
					{
						searchTable.deleteRow(i);
					}
					for(var i=0;jsonResponse[i];i++)
					{
						addTableRow(searchTable,jsonResponse[i]);
					}
				}
			}
			lastUpdate=jsonResponse;
		}
	}
	updatehttp.open("POST","getItems.php",true);
	updatehttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	updatehttp.send(data);
}

function addTableRow(table,node)
{
	var row = table.insertRow(-1);
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	var cell4 = row.insertCell(3);
	var cell5 = row.insertCell(4);
	var cell6 = row.insertCell(5);
	cell1.innerHTML=node['owner'];
	cell2.innerHTML=node['itemName'];
	cell3.innerHTML=node['description'];
	if(node['sellTime']==0)
		cell4.innerHTML="Auction Completed";
	else
		cell4.innerHTML=node['currentBid']+"$";
	if(node['sellTime']==0){
		if(node['currentBid']==node['openingPrice'])
			cell5.innerHTML="Time out item not sold";
		else
			cell5.innerHTML="Sold for "+ node['maxBid']+"$";
	}
	else
		cell5.innerHTML=node['sellTime'];
	cell6.innerHTML="<a href='itemPage.php?itemId="+node['itemId']+"'>Go to item page</a>";
}

function activeInterval(){
	setInterval(getResults,5000);	
}

