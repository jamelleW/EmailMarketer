<?php

// function to find and return email, returns False if no email found
function check_email($profileText)
{

    $email="";

    preg_match("/[\w|\d]+\.?[\w|\d]{2}\w*@\w*.com/i", $profileText, $email);

    if (filter_var($email[0], FILTER_VALIDATE_EMAIL))
    {
         return $email;
    }
    else 
    {
        return FALSE;
    }
}

// load html data to be processed from external file
$html = file_get_contents('search.html');

$dom = new DomDocument();

@$dom->loadHTML($html);

// get array of all cite tags
$allCites = $dom->getElementsByTagName("cite");

$i = 1;
$k = 1;

// Array to store url link and email address
$linkArray = array(array());

$badLinks = array();



// process each cite tag (each google result)
foreach($allCites as $cite)
{
    $url = $cite->nodeValue;
    
    $profileText = $cite->parentNode->parentNode->lastChild->nodeValue;
    
    $explode = explode("/", $url);  // explode url to check number of segments
   
    // echo number of current iteration
    echo '<br>' . $i . "</br>" . $url;
    
    
    // make sure info isn't from twitter status updates 
    if ((count($explode) < 4 && $explode[1] != "") || (count($explode) < 5 && $explode[1] == "") )
    {
       
        // use check mail to get email, returns false if no email found
        $email=check_email($profileText);
        
        if($email)
        {
            $linkArray[] = array( $url, $email, $profileText);  
            
            echo '</br> Email is: ' . $email[0];
            
        }
        else
        {
            $badLinks = array($url, $profileText);
            
            echo "</br> Email is FALSE!!";
        
        }
        
        
    }
    
    
    echo "</br> Profile Data:" . $profileText . '<br>';
    
    $i++;
       
}

echo "Number of raw links: $i";

$emailCount = count($linkArray);

echo "Number of emails: $emailCount";

$countBad = count($badLinks);
echo "Number of valid links with no email: $countBad";

//$badNum = 0;
//foreach($badLinks as $bad)
{
    echo "<br> $badNum $bad[0] <br> $bad[1]";
    $badNum ++;
}


foreach($linkArray as $data)
 {
     //echo $i . "<p> Twitter Address: " . $data[0] .
       //      "</br> Twitter Email: " . $data[1] . "</p>";
 
     $i ++ ;
 }
echo "<p>";
//var_dump($explode);
echo "</p>";