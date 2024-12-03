<?php 
    $title = "Sign Up";
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
        <h2>Sign Up</h2>
    </div>
    
    <!-- Login Form -->
    <div class="login-signup">
        <?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            { 
                $first_name = test_input($_POST['first_name']);
                $last_name = test_input($_POST['last_name']);
                $email = test_input($_POST['email']);
                $password = test_input($_POST['password']);
        
                $stmt = $conn->prepare('SELECT * FROM users WHERE email_address=?');
                $stmt->bindParam(1, $email);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if($row)
                {
                    ?>
                        <p class="submit-error">Email Exists</p>
                    <?php
                } else{
                    $sql = "INSERT INTO users (first_name, last_name, email_address, password) VALUES (?,?,?,?)";
                    $stmt= $conn->prepare($sql);
                    if($stmt->execute([$first_name, $last_name, $email, $password])){
                        header("Location: ./login.php");
                    }
                }       
            }
        ?>
        <form id="sign-up" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateForm(event)">
            <!-- First Name -->
            <div class="mb-3">
                <label for="first-name" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="first-name" name="first_name" placeholder="Ex: Jhon" required autocomplete="on">
                <p id="first-name-error"></p>
            </div>
            <!-- Last Name -->
            <div class="mb-3">
                <label for="last-name" class="form-label">Last Name:</label>
                <input type="text" class="form-control" id="last-name" name="last_name" placeholder="Ex: Smith" required autocomplete="on">
                <p id="last-name-error"></p>
            </div>
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Ex: email@example.com" required autocomplete="on">
                <p id="email-error"></p>
            </div>
            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Put your password her" required autocomplete="on">
                <p id="password-error"></p>
            </div>
            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm-password" placeholder="Retype your password" required autocomplete="on">
                <p id="confirm-password-error"></p>
            </div>
            <!-- Sign Up Button -->
            <button id="sign-up-button" type="submit" name="submit" class="btn btn-dark mt-3">Sign Up</button>
            <p class="text-center mt-3">Already Have an Account. <a href="./signUp.php">Login</a></p>
        </form>
    </div>

<?php
    $page = "sign-up";
    include('./includes/footer.php')
?>