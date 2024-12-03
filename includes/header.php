<?php
    include("db.php");
    session_start();

    if(isset($_SESSION["start_time"])) {
        $now = time();
        $hours = 2;
        if($now > $_SESSION['start_time'] + (60 * 60 * $hours)) {
            header("Location: ./logout.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-dark border-bottom border-body fixed-top" data-bs-theme="dark">
        <div class="container-fluid">
            <!-- Logo -->
            <h1 class="navbar-brand mb-0 mr-0">
                <a href="">
                <img src="./img/logo.png" alt="logo">
                </a>
            </h1>
            <!-- Hamburger -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php if($title === 'Login' || $title === 'Sign Up'){
                            echo ".";
                        }else{
                            echo " ";
                        } ?>">Home</a>
                    </li>
                    <?php 
                        if($title === 'Login'){
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="./signUp.php">Sign Up</a>
                                </li>
                            <?php
                        }elseif($title === 'Sign Up'){
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="./login.php">Login</a>
                                </li>
                            <?php 
                        } else{
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="./logout.php">Logout</a>
                                </li>
                            <?php 
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>