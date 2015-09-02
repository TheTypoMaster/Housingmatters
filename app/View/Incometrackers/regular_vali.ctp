<?php
if($cc == 1)
{
echo "<p style='color:red;'>Date Invalid (To Date is small than From Date)</p>";
}
else if($cc == 2)
{
echo "<p style='color:red;'>Date Invalid(Dates is not in open year Please Check)</p>";	
}
else if($cc == 3)
{
echo "<p style='color:red;'>Date Invalid (Due Date is small Than From Date)</p>";	
}
else if($cc == 5)
{
echo "<p style='color:red;'>Bill already generated for the mentioned period, Kindly select another period</p>";		
}
else if($cc == 505)
{
echo "<p style='color:red;'>Due Date could not be greater then billing end date, Kindely select anouther date</p>";		
}
else
{
	
}

?>