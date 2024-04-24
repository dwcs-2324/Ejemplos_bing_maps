<html>

<head>
  <title>Using PHP and Bing Maps REST Services Routes API</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>

  <form action="BingMaps_REST_RoutingSample.php" method="post">
    Bing Maps Key: <input type="text" name="key" value="" <?php echo (isset($_POST['key']) ? $_POST['key'] : '') ?>"><br>
    Origin: <input type="text" name="origin" value="" <?php echo (isset($_POST['origin']) ? $_POST['origin'] : '') ?>"><br>
    Destination: <input type="text" name="destination" value="" <?php echo (isset($_POST['destination']) ? $_POST['destination'] : '') ?>"><br>
    <input type="submit" value="Submit">
  </form>
  <?php
  // PHP code goes here  
  
  // URL of Bing Maps REST Services Routes API;   
  $baseURL = "http://dev.virtualearth.net/REST/v1/Routes";
  if (isset($_POST['key'])) {
    // Get the Bing Maps key from the user    
    $key = $_POST['key'];

    // construct parameter variables for Routes call  
    $wayPoint0 = str_ireplace(" ", "%20", $_POST['origin']);
    $wayPoint1 = str_ireplace(" ", "%20", $_POST['destination']);
    $optimize = "time";
    $routePathOutput = "Points";
    $distanceUnit = "km";
    $travelMode = "Driving";

    // Construct final URL for call to Routes API  
    $routesURL =
      $baseURL . "/" . $travelMode . "?wp.0=" . $wayPoint0 . "&wp.1=" . $wayPoint1
      . "&optimize=" . $optimize . "&routePathOutput=" . $routePathOutput
      . "&distanceUnit=" . $distanceUnit . "&output=xml&key=" . $key;

   

    // Get output from Routes API and convert to XML element using php_xml  
    $output = file_get_contents($routesURL);
    $response = new SimpleXMLElement($output);

    // Extract and print number of routes from response  
    $numRoutes = $response->ResourceSets->ResourceSet->EstimatedTotal;
    echo "Number of routes found: " . $numRoutes . "<br>";



    // Extract and print route instructions from response  
    $itinerary =
      $response->ResourceSets->ResourceSet->Resources->Route->RouteLeg->ItineraryItem;

    echo "<ol>";
    for ($i = 0; $i < count($itinerary); $i++) {
      $instruction = $itinerary[$i]->Instruction;
      echo "<li>" . $instruction . "</li>";
    }
    echo "</ol>";
  }
  else{
    echo "<p class='error'> Insert API Key </p>";
  }

  ?>



</body>

</html>