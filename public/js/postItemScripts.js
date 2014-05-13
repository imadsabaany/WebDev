	function validForm(){
				var itemName=document.forms["postItemForm"]["itemName"].value;
				var itemNameErr="";var descriptionErr="";var sellTimeErr="";var openingPriceErr="";
				var description=document.forms["postItemForm"]["description"].value;
				var sellTime=document.forms["postItemForm"]["sellTime"].value;
				var openingPrice=document.forms["postItemForm"]["openingPrice"].value;
				var errFlag=0;
				if(!itemName || itemName.trim()=="")
					{
					itemNameErr="No Item Name \n";
					errFlag=1;
					}
				if(!openingPrice || openingPrice.trim()=="")
					{
					openingPriceErr="No opening price \n";
					errFlag=1;
					}
				else if(!openingPrice.match(/^[0-9]+(\.)?[0-9]*\$$/))
				{
					openingPriceErr="Not valid opening price.opening price must contain only digits and end with $ sign. \n";
					errFlag=1;
					}
				if(!sellTime || sellTime.trim()=="")
					{
					sellTimeErr="No sell time \n";
					errFlag=1;
					}
					else if(!sellTime.match(/^[1-9][0-9]*$/))
				{
					sellTimeErr="Not valid sell time \n";
					errFlag=1;
					}
				if(!description || description.trim()=="")
					{
					descriptionErr="No description \n";
					errFlag=1;
					}
					if(errFlag)
					{
					alert(itemNameErr+openingPriceErr+sellTimeErr+descriptionErr);
					return flase;
					}
			
			}
			
		