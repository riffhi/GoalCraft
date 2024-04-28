<?php
    session_start();
    if(isset($_SESSION['username'])){
        header("Location: welcome.php");
    }
?>
<?php
    $login = false;
    include('connection.php');
    if (isset($_POST['submit'])) {
        $username = $_POST['user'];
        $password = $_POST['pass'];
        echo $password;
        $sql = "select * from users where username = '$username'or email = '$username'";  
        $result = mysqli_query($conn, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);  
        
        if($row){  
            echo $count;

            if(password_verify($password, $row["password"])){
                $login=true;
                session_start();

                $sql = "select username from users where username = '$username'or email = '$username'";     
                $r = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);  

                $_SESSION['username']= $r['username'];
                $_SESSION['loggedin'] = true;
                header("Location: welcome.php");
            }
        }  
        else{  
            echo  '<script>
                        
                        alert("Login failed. Invalid username or password!!")
                        window.location.href = "login.php";
                    </script>';
        }     
    }
    ?>
    <?php 
    include("connection.php");
    include("navbar.php");

    ?>
    
<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Seymour+One&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=swap">

    </head>
    <body>
    <div class=unlock>
    <p><bold>Welcome to GoalCraft-</bold></p>
    <p>Where Productivity
Begins</p>
    
          </div>
  <div class= welcome>
  Unlock your potential and stay organized with</p>
  <p>our intuitive task management platform.</p>
  <p>Login now to take control of your </p>
  <p>tasks, projects, and deadlines
          effortlessly.</p>
          <p>Let's conquer your to-do list together!!</p>
  </div>
        <br><br>
        <div id="form">
            <h1 id="heading">Login Form</h1>
            <form name="form" action="login.php" method="POST" required>
                <label>Enter Username/Email: </label>
                <input type="text" id="user" name="user"></br></br>
                <label>Password: </label>
                <input type="password" id="pass" name="pass" required></br></br>
                <input type="submit" id="btn" value="Login" name = "submit"/>
            </form>
        </div>
        <script>
            function isvalid(){
                var user = document.form.user.value;
                if(user.length==""){
                    alert(" Enter username or email id!");
                    return false;
                }
                
            }
        </script>
    </body>
</html>