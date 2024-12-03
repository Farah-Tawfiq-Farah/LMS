<?php
    $title = "Login";
    include('./includes/header.php');
    if(isset($_SESSION['user'])){
        if($_SESSION['user']["member_type"]=="Admin"){
            header("Location: ./edit-return.php");
        } else {
            header("Location: ./browse-borrow.php");
        }
    }
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

    <!-- General Header -->
    <div class="general-heading text-bg-secondary">
        <h2>Login</h2>
    </div>
    
    <!-- Login Form -->
    <div class="login-signup">
        <?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            { 
                $email = test_input($_POST['email']);
                $password = test_input($_POST['password']);
        
                $stmt = $conn->prepare('SELECT * FROM users WHERE email_address=? and password=?');
                $stmt->bindParam(1, $email);
                $stmt->bindParam(2, $password);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if($row)
                {
                    $_SESSION["user"] = $row;
                    $_SESSION["start_time"] = time();
    
                    if($row["member_type"]=="Admin"){
                        header("Location: ./edit-return.php");
                    } else {
                        header("Location: ./browse-borrow.php");
                    }
                } else{
                    ?>
                        <p class="submit-error">Credintials are wrong</p>
                    <?php
                }       
            }
        ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Ex: email@example.com" required autocomplete="on">
            </div>
            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Put your password her" required autocomplete="on">
            </div>
            <!-- Login Button -->
            <button type="submit" class="btn btn-dark mt-3">Login</button>
            <p class="text-center mt-3">Don't Have an Account. <a href="./signUp.php">Sign-Up</a></p>
        </form>
    </div>

<?php 
    include('./includes/footer.php')
?>