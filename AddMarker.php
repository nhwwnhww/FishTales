<?php
session_start();
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];
$fishName = $_SESSION['fishName'];
// echo $_SESSION['fishName'];

if ($username == 'guest'){
  echo "<h1 style='background:red'>You are not logged in yet. Please log in.<a href='index.php' style='background:red'>Click me to login</a></h1>";
}

$backLink = $_SESSION['backLink'];

$required = 'required';
$display = 'Cancel';

//If the user has sent through their username
if (isset($_POST['Latitude'])) {
  //Create database connection -> 4 variables are 'localhost', username for the localhost (should be 'root', password for loacalhost (should be nothing), and database name
  $conn = new mysqli("localhost", "root", "", "fishtales");

  //This line makes the sql
  $sql = "SELECT * FROM `markers` JOIN users ON users.userID = markers.userID WHERE `username` = '$username' AND `fishName` = '$fishName' AND `Latitude` = '{$_POST['Latitude']}' AND `Longitude` = '{$_POST['Longitude']}' AND `date` = '{$_POST['date']}'";

  //This line runs the query
  $result = $conn->query($sql);

  //Check if in database
  // yess??!!!  alert!!!! :(((
  if ($result->num_rows > 0) {
    echo "<h1 style='background:red'>The location has already been marked on the map. Please avoid duplicate markers.</h1>";
  }
  // no?? good, I can add one :)
  else {
    $sql = "INSERT INTO `markers`(`userID`, `fishName`, `Latitude`, `Longitude`, `date`, `comment`) VALUES ('$userID','$fishName','{$_POST['Latitude']}','{$_POST['Longitude']}','{$_POST['date']}','{$_POST['comment']}')";
    //This line runs the query
    $result = $conn->query($sql);
    echo "<h1 style='background:green'>The location has been marked on the map. :)</h1>";
    $required = 'disabled';
    $display = 'Go Back';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous" />

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js" integrity="sha384-lpyLfhYuitXl2zRZ5Bn2fqnhNAKOAaM/0Kr9laMspuaMiZfGmfwRNFh8HlMy49eQ" crossorigin="anonymous"></script>
  <style>
    /* Set the size of the div element that contains the map */
    #map {
      height: 400px;
      /* The height is 400 pixels */
      width: 100%;
      /* The width is the width of the web page */
    }

    /* 
 * Optional: Makes the sample page fill the window. 
 */
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>

  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
</head>

<body>
  <h3>My Google Maps Demo</h3>
  <!--The div element for the map -->
  <section>
    <div class="row">
      <div class="col-md-6 mb-8">
        <div id="map"></div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="mb-0">Add a new marker</h5>
          </div>
          <div class="card-body">
            <!-- marker form -->
            <form action="./AddMarker.php" method="post" class="w-75 align-middle">
              <div class="form-outline mb-4">
                <label class="form-label" for="username">Username</label>
                <input id="username" type="text" class="form-control" name="username" value="<?php echo $username ?>" disabled />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="username">Fishname</label>
                <input id="fishName" type="text" class="form-control" name="fishName" value="<?php echo $fishName ?>" disabled />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="Latitude">Latitude</label>
                <input id="latitude" type="text" class="form-control" name="Latitude" <?php echo $required?> />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="Longitude">Longitude</label>
                <input id="longitude" type="text" class="form-control" name="Longitude" <?php echo $required?> />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="Date">Date</label>
                <input id="Date" type="date" name="date" <?php echo $required?> />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="comment">comment</label>
                <input id="comment" type="text" class="form-control" name="comment" <?php echo $required?>/>
              </div>
              <button class="btn btn-primary btn-lg btn-block" type="submit" <?php echo $required?>>
                Add Marker
              </button>
              <a href="<?php echo $backLink?>"><?php echo $display?></a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>


  <script>
    var map, marker

    function initMap() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            }
            map = new google.maps.Map(document.getElementById('map'), {
              zoom: 17,
              center: pos
            })

            marker = new google.maps.Marker({
              position: pos,
              map: map,
              draggable: true
            })

            updateInputs(pos.lat, pos.lng)
            marker.addListener('dragend', function() {
              var position = marker.getPosition()
              updateInputs(position.lat(), position.lng())
            })
          },
          function() {
            handleLocationError(true, infoWindow, map.getCenter())
          }
        )
      } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter())
      }
    }

    // Function to update the input boxes
    function updateInputs(lat, lng) {
      document.getElementById('latitude').value = lat
      document.getElementById('longitude').value = lng
    }

    // Event listeners for the input boxes
    document.getElementById('latitude').addEventListener('change', moveMarker)
    document
      .getElementById('longitude')
      .addEventListener('change', moveMarker)

    // Function to move the marker when input values change
    function moveMarker() {
      var lat = parseFloat(document.getElementById('latitude').value)
      var lng = parseFloat(document.getElementById('longitude').value)
      if (!isNaN(lat) && !isNaN(lng)) {
        var newPosition = new google.maps.LatLng(lat, lng)
        marker.setPosition(newPosition)
        map.setCenter(newPosition)
      }
    }
  </script>

  <!-- prettier-ignore -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK9qIGI_CU2QkIREeJJ0B2dMwZzxLyA_I&callback=initMap&v=weekly">
    (g => {
      var h, a, k, p = "The Google Maps JavaScript API",
        c = "google",
        l = "importLibrary",
        q = "__ib__",
        m = document,
        b = window;
      b = b[c] || (b[c] = {});
      var d = b.maps || (b.maps = {}),
        r = new Set,
        e = new URLSearchParams,
        u = () => h || (h = new Promise(async (f, n) => {
          await (a = m.createElement("script"));
          e.set("libraries", [...r] + "");
          for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
          e.set("callback", c + ".maps." + q);
          a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
          d[q] = f;
          a.onerror = () => h = n(Error(p + " could not load."));
          a.nonce = m.querySelector("script[nonce]")?.nonce || "";
          m.head.append(a)
        }));
      d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n))
    })
    ({
      key: "AIzaSyBK9qIGI_CU2QkIREeJJ0B2dMwZzxLyA_I",
      v: "weekly"
    });
  </script>
</body>

</html>