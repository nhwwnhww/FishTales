<?php

session_start();
session_destroy();

//If the user has sent through their username
if(isset($_POST['email'])){
	//Create database connection -> 4 variables are 'localhost', username for the localhost (should be 'root', password for loacalhost (should be nothing), and database name
	$conn = new mysqli("localhost", "root", "","fishtales");
	
	//This line makes the sql
	$sql = "SELECT * FROM users WHERE email = '{$_POST['email']}' AND password = '{$_POST['password']}'";
	
	//This line runs the query
	$result = $conn->query($sql);
	
	//Check if in database
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
        $User_id = $row["User_id"];
        $username = $row["username"];
        // echo "<script>alert($player_id)</script>";
        session_start();
        $_SESSION['User_id'] = $User_id;
        $_SESSION['username'] = $username;
		exit(header("Location: search.php"));	
        }

	}else{
		echo "<h1 style='background:red'>Username or password is wrong!</h1>";
	}	
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Home Page</title>
    <script src="jquery-3.4.1.min.js"></script>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
      crossorigin="anonymous"
    />

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8"
      crossorigin="anonymous"
    ></script>
  </head>

  <body>
    <form id="login" action="index.php" method="post">
      <input type="text" id="email" placeholder="Email" name="email" required/>
      <input type="password" id="password" placeholder="Password" name="password" required/>
      <button type="submit">Sign in</button>
    </form>
    <a href="./Register.php">register</a>
    
  </body>
</html>
