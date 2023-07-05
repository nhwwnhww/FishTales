<?php
session_start();
$User_id = $_SESSION['User_id'];
$username = $_SESSION['username'];

//Create database connection -> 4 variables are 'localhost', username for the localhost (should be 'root', password for loacalhost (should be nothing), and database name
$conn = new mysqli("localhost", "root", "", "fishtales");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="jquery-3.4.1.min.js"></script>
    <title>Document</title>
</head>

<body>
    <h1>Hi! <?php echo $username ?></h1>

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
                        items.push('<li id="' + key + '"> FamilyName: ' + val.FamilyName1 + ' CommonName: ' + val.CommonName + '</li>');
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
                        items.push('<li id="' + key + '"> FamilyName: ' + val.FamilyName1 + ' CommonName: ' + val.CommonName + '</li>');
                    });
                    $('<ul/>', {
                        'class': 'my-new-list',
                        html: items.join('')
                    }).appendTo('#output');
                }
            });
        });
    </script>
</body>

</html>