<?php
session_start();
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];

if ($username == 'guest') {
    echo "<h1 style='background:red'>You are not logged in yet. Please log in to .</h1><br><a href='index.php'>Click me to login</a>";
}

//Create database connection -> 4 variables are 'localhost', username for the localhost (should be 'root', password for loacalhost (should be nothing), and database name
$conn = new mysqli("localhost", "root", "", "fishtales");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="jquery-3.4.1.min.js"></script>
    <title>Search</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>

    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <style>
        /* Set the size of the div element that contains the map */
        #map {
            height: 600px;
            /* The height is 400 pixels */
            width: 600px;
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
</head>

<body>
    <h1>Hi! <?php echo $username ?></h1>
    <a href="./index.php">log out</a>

    <form id="searchForm">
        <input type="text" id="searchInput" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>
    <h2>Search Result:</h2>
    <div id="output"></div>

    <h1>Suggestion</h1>
    <form id="suggestionForm">
        <button type="submit">Suggestion 5 fishes</button>
    </form>
    <div id="suggestionOutput"></div>

    <script>
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();

            // Clear the previous results
            $('#output').empty();

            var searchTerm = $('#searchInput').val();
            var apiUrl = 'https://www.data.qld.gov.au/api/3/action/datastore_search?resource_id=32af9a35-d4db-41e9-b152-d52609ff6372&q=';

            apiUrl += searchTerm;

            $.getJSON(apiUrl, function(data) {
                var total = data.result.total;
                if (total == 0) {
                    $('#output').html('No results found for "' + searchTerm + '".');
                } else {
                    var items = [];
                    $.each(data.result.records, function(key, val) {
                        items.push('<li id="' + key + '"> FamilyName: ' + val.FamilyName1 + ' CommonName: ' + val.CommonName + ' scientificName: ' + val.ScientificName + ' <a href="detail.php?scientificName=' + val.ScientificName + '">More Details</a></li>');
                    });
                    $('<ul/>', {
                        'class': 'my-new-list',
                        html: items.join('')
                    }).appendTo('#output');
                }
            });
        });
        $('#suggestionForm').on('submit', function(e) {
            e.preventDefault();

            // Clear the previous results
            $('#suggestionOutput').empty();

            var apiUrl = 'https://www.data.qld.gov.au/api/3/action/datastore_search?resource_id=32af9a35-d4db-41e9-b152-d52609ff6372&limit=5';


            $.getJSON(apiUrl, function(data) {
                var total = data.result.total;
                if (total == 0) {
                    $('#suggestionOutput').html('No results found for "' + searchTerm + '".');
                } else {
                    var items = [];
                    $.each(data.result.records, function(key, val) {
                        items.push('<li id="' + key + '"> FamilyName: ' + val.FamilyName1 + ' CommonName: ' + val.CommonName + '</li>');
                    });
                    $('<ul/>', {
                        'class': 'my-new-list',
                        html: items.join('')
                    }).appendTo('#suggestionOutput');
                }
            });
        });
    </script>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Columm3</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Cell 1</td>
                <td>Cell 1</td>
                <td>Cell 2</td>
            </tr>
            <tr>
                <td>Cell 1</td>
                <td>Cell 3</td>
                <td>Cell 4</td>
            </tr>
        </tbody>
    </table>


    <h1>All fish</h1>

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

    <h3>My Google Maps Demo</h3>
    <!--The div element for the map -->
    <div id="map"></div>

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
</body>

</html>