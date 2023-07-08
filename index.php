<?php

session_start();
session_destroy();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Home - FishTales</title>
  <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css?h=11cd6552f0cfd1243be7481c40111374">
  <link rel="stylesheet" href="./assets/css/styles.min.css?h=0d5b31d952cc11431e4c46fcad9256e7">
</head>

<body><!-- Start: Navbar Centered Links -->
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
  <header class="bg-primary-gradient"><!-- Start: Hero Clean Reverse -->
    <div class="container pt-4 pt-xl-5">
      <div class="row pt-5">
        <div class="col-md-8 col-xl-6 text-center text-md-start mx-auto">
          <div class="text-center">
            <p class="fw-bold text-success mb-2">Voted #1 Worldwide</p>
            <h1 class="fw-bold">The best website for your fish</h1>
          </div>
        </div>
        <div class="col-12 col-lg-10 mx-auto">
          <div class="position-relative" style="display: flex;flex-wrap: wrap;justify-content: flex-end;"></div>
        </div>
      </div>
    </div><!-- End: Hero Clean Reverse -->
  </header>
  <section class="py-5"><!-- Start: Features Cards -->
    <div class="container text-center d-xl-flex justify-content-xl-center align-items-xl-center py-4 py-xl-5" style="display: flex;text-align: center;">
      <div class="row gy-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
        <div class="col">
          <div class="card">
            <div class="card-body p-4" style="width: 800px;height: 440px;" id="map">
              <!-- map function -->
              <?php

              //Create database connection -> 4 variables are 'localhost', username for the localhost (should be 'root', password for loacalhost (should be nothing), and database name
              $conn = new mysqli("localhost", "root", "", "fishtales");

              //This line makes the sql
              $sql = "SELECT * FROM `markers` JOIN `users` ON markers.userID = users.userID";
              // echo $sql;


              //This line runs the query
              $result = $conn->query($sql);

              //Check if in database
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $username = $row["username"];
                  $fishName = $row["fishName"];
                  $Latitude = $row["Latitude"];
                  $Longitude = $row["Longitude"];
                  $date = $row["date"];
                  $comment = $row["comment"];


                  $markers[] = [
                    'position' => ['lat' => floatval($row['Latitude']), 'lng' => floatval($row['Longitude'])],
                    'title' => $row['fishName'],
                    'content' => [
                      'username' => $row['username'],
                      'fishName' => $row['fishName'],
                      'date' => $row['date'],
                      'Latitude' => $row['Latitude'],
                      'Longitude' => $row['Longitude'],
                      'comment' => $row['comment']
                    ]
                  ];
                }
              } else {
                $markers = [];
              }
              ?>
              <script>
                var map

                var markers = <?php echo json_encode($markers); ?>;
                console.log(markers);

                function initMap() {
                  navigator.geolocation.getCurrentPosition(
                    function(position) {
                      var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                      }
                      console.log("My latitude is:" + position.coords.latitude + "and my longitude is: " + position.coords.longitude);
                      map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 17,
                        center: pos
                      })
                      markers.forEach(function(marker) {
                        addMarker(marker)
                      })
                    },
                    function() {
                      handleLocationError(true, infoWindow, map.getCenter())
                    }
                  )
                }

                function addMarker(data) {
                  var marker = new google.maps.Marker({
                    position: data.position,
                    map: map,
                    title: data.title
                  })

                  var infowindow = new google.maps.InfoWindow({
                    content: buildContent(data.content)
                  })

                  marker.addListener('click', function() {
                    infowindow.open(map, marker)
                  })
                }

                function buildContent(data) {
                  // console.log(`<div><h2>${name}</h2><p>${date}</p></div>`)
                  return `
        <div>
        <h3>username: ${data.username}</h3>
        <h5>fishname: ${data.fishName}</h5>
        <p>Location: Latitude: ${data.Latitude},Longitude: ${data.Longitude}</p>
        <p>date: ${data.date}</p>
        <p>comment: ${data.comment}</p>
        <a href="./Detail.php?scientificName=${data.fishName}">More info</a>
        </div>`;
                }
              </script>

              <!-- prettier-ignore -->
              <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK9qIGI_CU2QkIREeJJ0B2dMwZzxLyA_I&callback=initMap&v=weekly">
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
            </div>
          </div>
        </div>
      </div>
    </div><!-- End: Features Cards -->
  </section>
  <section><!-- Start: Features Cards -->
    <div class="container bg-primary-gradient py-5">
      <div class="row">
        <div class="col-md-8 col-xl-6 text-center mx-auto">
          <p class="fw-bold text-success mb-2">Our Services</p>
          <h3 class="fw-bold">What we can do for you</h3>
        </div>
      </div>
      <div class="py-5 p-lg-5">
        <div class="row row-cols-1 row-cols-md-2 mx-auto" style="max-width: 900px;">
          <div class="col mb-5">
            <div class="card shadow-sm">
              <div class="card-body px-4 py-5 px-md-5">
                <div class="bs-icon-lg d-flex justify-content-center align-items-center mb-3 bs-icon" style="top: 1rem;right: 1rem;position: absolute;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                    <path d="M15.4857 20H19.4857C20.5903 20 21.4857 19.1046 21.4857 18V6C21.4857 4.89543 20.5903 4 19.4857 4H15.4857V6H19.4857V18H15.4857V20Z" fill="currentColor"></path>
                    <path d="M10.1582 17.385L8.73801 15.9768L12.6572 12.0242L3.51428 12.0242C2.96199 12.0242 2.51428 11.5765 2.51428 11.0242C2.51429 10.4719 2.962 10.0242 3.51429 10.0242L12.6765 10.0242L8.69599 6.0774L10.1042 4.6572L16.4951 10.9941L10.1582 17.385Z" fill="currentColor"></path>
                  </svg></div>
                <h5 class="fw-bold card-title">Login as a user</h5>
                <p class="text-muted card-text mb-4">Login to access additional features of the website.</p><button class="btn btn-primary shadow" type="button" onclick="location.href='./login.php';">Login</button>
              </div>
            </div>
          </div>
          <div class="col mb-5">
            <div class="card shadow-sm">
              <div class="card-body px-4 py-5 px-md-5">
                <div class="bs-icon-lg d-flex justify-content-center align-items-center mb-3 bs-icon" style="top: 1rem;right: 1rem;position: absolute;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-search">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                    </path>
                  </svg></div>
                <h5 class="fw-bold card-title">Search a fish species</h5>
                <p class="text-muted card-text mb-4">Search for a fish species using keywords.</p><button class="btn btn-primary shadow" type="button" onclick="location.href='./search.php';">Learn more</button>
              </div>
            </div>
          </div>
          <div class="col mb-4">
            <div class="card shadow-sm"></div>
          </div>
          <div class="col mb-4">
            <div class="card shadow-sm"></div>
          </div>
        </div>
      </div>
    </div><!-- End: Features Cards -->
  </section>
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