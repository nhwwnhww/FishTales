<?php
session_start();
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];
$fishName = $_SESSION['fishName'];
// echo $_SESSION['fishName'];

if ($username == 'guest') {
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
  <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css?h=11cd6552f0cfd1243be7481c40111374">
  <link rel="stylesheet" href="./assets/css/styles.min.css?h=0d5b31d952cc11431e4c46fcad9256e7">
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
  <!-- Start: Navbar Centered Links -->
  <nav class="navbar navbar-light navbar-expand-md sticky-top navbar-shrink py-3" id="mainNav">
    <div class="container"><a class="navbar-brand d-flex align-items-center" href="/"><span class="bs-icon-sm bs-icon-circle bs-icon-primary shadow d-flex justify-content-center align-items-center me-2 bs-icon"><img src="./assets/img/icon.png?h=df782b38b1aad0744e7d7bf50dad6203" width="50" height="61" style="width: 86px;height: 62px;margin: 0px;padding: 0px;"></span><span style="margin-left: 25px;">FishTales</span></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse text-center d-xl-flex justify-content-xl-center align-items-xl-center" id="navcol-1">
        <ul class="navbar-nav text-center d-xl-flex mx-auto justify-content-xl-end">
          <li class="nav-item"><a class="nav-link active" href="./index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="./search.php">Search</a></li>
          <li class="nav-item"><a class="nav-link" href="./login.php">Log in</a></li>
      </div>
    </div>
  </nav><!-- End: Navbar Centered Links -->
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
                <input id="latitude" type="text" class="form-control" name="Latitude" <?php echo $required ?> />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="Longitude">Longitude</label>
                <input id="longitude" type="text" class="form-control" name="Longitude" <?php echo $required ?> />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="Date">Date</label>
                <input id="Date" type="date" name="date" <?php echo $required ?> />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label" for="comment">comment</label>
                <input id="comment" type="text" class="form-control" name="comment" <?php echo $required ?> />
              </div>
              <button class="btn btn-primary btn-lg btn-block" type="submit" <?php echo $required ?>>
                Add Marker
              </button>
              <a href="<?php echo $backLink ?>"><?php echo $display ?></a>
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
  <section></section><!-- Start: Footer Multi Column -->
  <footer class="bg-primary-gradient">
    <div class="container py-4 py-lg-5">
      <hr>
      <div class="text-muted d-flex justify-content-between align-items-center pt-3">
        <ul class="list-inline mb-0">
          <li class="list-inline-item"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-facebook">
              <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z">
              </path>
            </svg></li>
          <li class="list-inline-item"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-twitter">
              <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z">
              </path>
            </svg></li>
          <li class="list-inline-item"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-instagram">
              <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z">
              </path>
            </svg></li>
        </ul>
      </div>
    </div>
  </footer><!-- End: Footer Multi Column -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/js/script.min.js?h=8484aee73bce2fd1b2891d66c7fab362"></script>
</body>

</html>