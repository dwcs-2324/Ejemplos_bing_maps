<html>

<head>
  <title>Using PHP and Bing Maps REST Services APIs</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <form action="BingMaps_REST_LocationsSample.php" method="post">
    Bing Maps Key: <input type="text" name="key" value="<?php echo (isset($_POST['key']) ? $_POST['key'] : '') ?>"><br>
    Street Address: <input type="text" name="address"
      value="<?php echo (isset($_POST['address']) ? $_POST['address'] : '') ?>"><br>
    City: <input type="text" name="city" value="<?php echo (isset($_POST['city']) ? $_POST['city'] : '') ?>"><br>
    State: <input type="text" name="state" value="<?php echo (isset($_POST['state']) ? $_POST['state'] : '') ?>"><br>
    Zip Code: <input type="text" name="zipcode"
      value="<?php echo (isset($_POST['zipcode']) ? $_POST['zipcode'] : '') ?>"><br>
    <input type="submit" value="Submit">
  </form>
  <?php
  // Code goes here  
  
  // URL of Bing Maps REST Services Locations API   
  $baseURL = "http://dev.virtualearth.net/REST/v1/Locations";
  if (isset($_POST['key'])) {
    // Create variables for search parameters (encode all spaces by specifying '%20' in the URI)  
    $key = $_POST['key'];
    $country = "US";
    $addressLine = str_ireplace(" ", "%20", $_POST['address']);
    $adminDistrict = str_ireplace(" ", "%20", $_POST['state']);
    $locality = str_ireplace(" ", "%20", $_POST['city']);
    $postalCode = str_ireplace(" ", "%20", $_POST['zipcode']);

    // Compose URI for Locations API request  
    $findURL = $baseURL . "/" . $country . "/" . $adminDistrict . "/" . $postalCode . "/" . $locality . "/"
      . $addressLine . "?output=xml&key=" . $key;


    // get the response from the Locations API and store it in a string  
    $output = file_get_contents($findURL);

    // create an XML element based on the XML string    
    $response = new SimpleXMLElement($output);

    // Extract data (e.g. latitude and longitude) from the results  
    $latitude =
      $response->ResourceSets->ResourceSet->Resources->Location->Point->Latitude;
    $longitude =
      $response->ResourceSets->ResourceSet->Resources->Location->Point->Longitude;

    echo "latitud " . $latitude + "<br/>";
    echo "long: " . $longitude . "<br/>";


    // Save the base URL for the Imagery API to a string  
    $imageryBaseURL = "http://dev.virtualearth.net/REST/v1/Imagery/Map";

    // Setup the parameters for the Imagery API request (using a center point)    
    $imagerySet = "Road";
    $centerPoint = $latitude . "," . $longitude;
    $pushpin = $centerPoint . ";4;ID";
    $zoomLevel = "15";

    // Display the image in the browser    
    echo "<img src='" . $imageryURL = $imageryBaseURL . "/" . $imagerySet . "/" . $centerPoint . "/" . $zoomLevel . "?pushpin=" . $pushpin . "&key=" . $key . "'>";
  } else {
    echo "<p class='error'> Insert API Key </p>";
  }
  ?>
</body>

</html>