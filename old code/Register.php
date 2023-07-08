<?php
//If the user has sent through their username
if (isset($_POST['email'])) {
  if ($_POST['email'] != '' && $_POST['password'] != '') {
    //Create database connection -> 4 variables are 'localhost', username for the localhost (should be 'root', password for loacalhost (should be nothing), and database name
    $conn = new mysqli("localhost", "root", "", "fishtales");

    //This line makes the sql
    $sql = "INSERT INTO `users`(`email`, `username`, `password`) VALUES ('{$_POST['email']}','{$_POST['username']}','{$_POST['password']}')";
    //This line runs the query and checks for an error
    if (!$conn->query($sql)) {
      echo $sql;
      echo "Error description: " . $mysqli->error;
    } else {
      exit(header("Location: index.php"));
    }
  } else {
    echo "You can't leave anything empty!";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous" />

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
</head>

<body>
<form id="Register" action="Register.php" method="post">
      <input type="text" id="email" placeholder="Email" name="email" required/>
      <input type="text" id="username" placeholder="username" name="username" required/>
      <input type="password" id="password" placeholder="Password" name="password" required/>
      <button type="submit">Sign Up</button>
      <a href="./index.php">cancel</a>
    </form>
</body>

</html>