<?php 
    $title = "Browse-Borrow";
    include('./includes/header.php');

    if(isset($_SESSION["user"])) {
        if($_SESSION['user']["member_type"] == "Admin") {
            header("Location: ./edit-return.php");
        }
    } else {
        header("Location: ./login.php");
    }

    $member_id = $_SESSION['user']["member_id"];

    $filters = array("title" => "", "category" => "", "author" => "", "language" => "");

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["filters"])) 
    {
        $filters = $_GET["filters"];
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["reset"])) 
    {
        $filters = array("title" => "", "category" => "", "author" => "", "language" => "");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    { 
        $status = "Onloan";
        $book_id = $_POST['book_id'];

        $sql = "UPDATE book_status SET member_id = ?, status = ?, applied_date = Now() WHERE `book_id` = ?";
        $stmt= $conn->prepare($sql);
        $stmt->execute([$member_id, $status, $book_id]);
    }

    $title_query = !empty($filters["title"]) ? "%".$filters["title"]."%" : "";

    $sql = "SELECT books.*, book_status.status AS status FROM books
    INNER JOIN book_status ON books.book_id = book_status.book_id
    where status != 'Deleted'";
    if(!empty($filters["title"])) $sql .= "AND books.book_title like :title";
    if(!empty($filters["category"])) $sql .= " AND books.category = :category";
    if(!empty($filters["author"])) $sql .= " AND books.author = :author";
    if(!empty($filters["language"])) $sql .= " AND books.language = :language";
    
    $stmt = $conn->prepare($sql);

    if(!empty($filters["title"])) $stmt->bindParam(":title", $title_query);
    if(!empty($filters["category"])) $stmt->bindParam(":category", $filters["category"]);
    if(!empty($filters["author"])) $stmt->bindParam(":author", $filters["author"]);
    if(!empty($filters["language"])) $stmt->bindParam(":language", $filters["language"]);

    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <!-- General Header -->
    <div class="general-heading text-bg-secondary">
        <h2>Browse-Borrow</h2>
    </div>

    <!-- Search -->
    <div class="row cards search-filters">
        <div class="col-md-12">
            <form class="d-flex" role="search" method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input class="form-control me-2" type="text" name="filters[title]" value="<?= !empty($filters["title"]) ? $filters["title"] : "" ?>" placeholder="Search" aria-label="Search">
                <button class="btn btn-dark" type="submit">Search</button>
            </form>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row cards search-filters mt-3">
        <div class="col-md-12">
            <form class="d-flex" method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="row flex-grow-1">
                    <div class="col-md-3">
                        <label for="category">Category</label>
                        <select id="category" name="filters[category]" class="form-control mt-2" onchange="this.form.submit()">
                            <option value="" selected>Choose...</option>
                            <option value="Fiction" <?= isset($filters["category"]) && $filters["category"] == "Fiction" ? "selected" : "" ?>>Fiction</option>
                            <option value="Nonfiction" <?= isset($filters["category"]) && $filters["category"] == "Nonfiction" ? "selected" : "" ?>>Nonfiction</option>
                            <option value="Reference" <?= isset($filters["category"]) && $filters["category"] == "Reference" ? "selected" : "" ?>>Reference</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="author">Author</label>
                        <select id="author" name="filters[author]" class="form-control mt-2" onchange="this.form.submit()">
                            <option value="" selected>Choose...</option>
                            <option value="Charles Dickens" <?= isset($filters["author"]) && $filters["author"] == "Charles Dickens" ? "selected" : "" ?>>Charles Dickens</option>
                            <option value="Al Gore" <?= isset($filters["author"]) && $filters["author"] == "Al Gore" ? "selected" : "" ?>>Al Gore</option>
                            <option value="Oxford Press" <?= isset($filters["author"]) && $filters["author"] == "Oxford Press" ? "selected" : "" ?>>Oxford Press</option>
                            <option value="Leo Tolstoy" <?= isset($filters["author"]) && $filters["author"] == "Leo Tolstoy" ? "selected" : "" ?>>Leo Tolstoy</option>
                            <option value="Murasaki Shikibu" <?= isset($filters["author"]) && $filters["author"] == "Murasaki Shikibu" ? "selected" : "" ?>>Murasaki Shikibu</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="language">Language</label>
                        <select id="language" name="filters[language]" class="form-control mt-2" onchange="this.form.submit()">
                            <option value="" selected>Choose...</option>
                            <option value="English" <?= isset($filters["language"]) && $filters["language"] == "English" ? "selected" : "" ?>>English</option>
                            <option value="Spanish" <?= isset($filters["language"]) && $filters["language"] == "Spanish" ? "selected" : "" ?>>Spanish</option>
                            <option value="French" <?= isset($filters["language"]) && $filters["language"] == "French" ? "selected" : "" ?>>French</option>
                            <option value="Russian" <?= isset($filters["language"]) && $filters["language"] == "Russian" ? "selected" : "" ?>>Russian</option>
                            <option value="Mandarin" <?= isset($filters["language"]) && $filters["language"] == "Mandarin" ? "selected" : "" ?>>Mandarin</option>
                            <option value="German" <?= isset($filters["language"]) &&  $filters["language"] == "German" ? "selected" : "" ?>>German</option>
                            <option value="German" <?= isset($filters["language"]) && $filters["language"] == "Japanese" ? "selected" : "" ?>>Japanese</option>
                            <option value="Other" <?= isset($filters["language"]) && $filters["language"] == "Other" ? "selected" : "" ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end mt-2">
                        <button type="submit" name="reset" value="clear" class="btn btn-secondary">Clear Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Books -->
    <div class="row row-cols-1 row-cols-md-3 g-4 cards">
        <?php 
            if(count($rows) == 0)
            {
                ?>
                    <p class="submit-error">Sorry, No books in the library</p>
                <?php
            } else {
                foreach($rows as $row){
                    ?>
                        <div class="col">
                            <div class="card text-bg-secondary">
                                <img class="card-img-top" src="./img/<?=$row['image'] ?>" alt="<?=$row['author'].": ".$row['book_title'] ?>">
                                <div class="card-body">
                                    <h3 class="card-title"><?=$row['book_title']?></h3>
                                    <h4 class="card-subtitle mb-2">Author: <?=$row['author']?></h4>
                                    <h4 class="card-subtitle mb-2">Publisher: <?=$row['publisher']?></h4>
                                    <h4 class="card-subtitle mb-2">Language: <?=$row['language']?></h4>
                                    <h4 class="card-subtitle mb-2">Category: <?=$row['category']?></h4>
                                    <div class="text-center">
                                        <?php
                                            if($row['status'] == 'Onloan'){
                                                ?>
                                                    <button type="button" class="btn btn-danger disabled">Not Available</button>
                                                <?php
                                            } else {
                                                ?>
                                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                                    <button type="submit" name="book_id" class="btn btn-dark" value="<?= $row["book_id"] ?>">Borrow</button></form>
                                                <?php
                                            }
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            }
        ?>
    </div>

<?php 
    include('./includes/footer.php')
?>