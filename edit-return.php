<?php
    $title = "Edit-Return";
    include('./includes/header.php');

    if(isset($_SESSION["user"])) {
        if($_SESSION['user']["member_type"] == "Member") {
            header("Location: ./browse-borrow.php");
        }
    } else {
        header("Location: ./login.php");
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

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
        $status = $_POST["status"];
        $book_id = $_POST['book_id'];

        if($status == "save") {
            $book_title = test_input($_POST['book_title']);
            $author = test_input($_POST['author']);
            $publisher = test_input($_POST['publisher']);
            $language = test_input($_POST['language']);
            $category = test_input($_POST['category']);
            
            $sql = "UPDATE books SET book_title = ?, author = ?, publisher = ?, language = ?, category = ? WHERE `book_id` = ?";
            $stmt= $conn->prepare($sql);
            $stmt->execute([$book_title, $author, $publisher, $language, $category, $book_id]);
        } else {
            $sql = "UPDATE book_status SET member_id = NULL, status = ?, applied_date = Now() WHERE `book_id` = ?";
            $stmt= $conn->prepare($sql);
            $stmt->execute([$status, $book_id]);
        }

    }

    $title_query = !empty($filters["title"]) ? "%".$filters["title"]."%" : "";

    $sql = "SELECT books.*, book_status.status AS status, DATE_ADD(book_status.applied_date, INTERVAL 21 DAY) AS due_date FROM books
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
        <h2>Edit-Return</h2>
    </div>

    <!-- Search -->
    <div class="row cards search-filters">
        <div class="col-md-12">
            <form class="d-flex" role="search" method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input class="form-control me-2" type="search" name="filters[title]" value="<?= !empty($filters["title"]) ? $filters["title"] : "" ?>" placeholder="Search" aria-label="Search">
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
                            <option value="Nonfiction" <?= isset($filters["category"]) &&  $filters["category"] == "Nonfiction" ? "selected" : "" ?>>Nonfiction</option>
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
                            <option value="German" <?= isset($filters["language"]) && $filters["language"] == "German" ? "selected" : "" ?>>German</option>
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

    <!-- Books Table -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Language</th>
                    <th>Category</th>
                    <th>Return due date</th>
                    <th>Edit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(count($rows) == 0)
                {
                    ?>
                        <p class="submit-error">Sorry, No books in the library</p>
                    <?php
                } else {
                    foreach($rows as $row){ ?>
                        <tr>
                            <td><?=$row['book_title']?></td>
                            <td><?=$row['author']?></td>
                            <td><?=$row['publisher']?></td>
                            <td><?=$row['language']?></td>
                            <td><?=$row['category']?></td>                            
                            <td>
                                <?php
                                    if($row["status"] == "Onloan") {
                                        echo $row["due_date"];
                                    }
                                ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editBookModal_<?= $row["book_id"] ?>">Edit</button>
                            </td>
                            <td>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <input type="hidden" name="book_id" value="<?= $row["book_id"] ?>" />
                                <?php
                                    if($row['status'] == 'Onloan'){
                                    ?>
                                        <button type="submit" class="btn btn-warning" name="status" value="Available">Return</button>
                                        <?php
                                    } else {
                                        ?>
                                        <button type="submit" class="btn btn-danger" name="status" value="Deleted">Delete</button>
                                    <?php
                                    }
                                    ?>
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- Edit Book Modal -->
    <?php  foreach($rows as $row)
        { 
    ?> 
    <div class="modal fade" id="editBookModal_<?= $row["book_id"] ?>" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBookForm">
                        <div class="form-group">
                            <label for="title" class="form-label">Book Title:</label>
                            <input class="form-control" id="title" name="book_title" type="text" value="<?=$row['book_title']?>" aria-label="default input example">
                        </div>
                        <div class="form-group">
                            <label for="author" class="form-label">Author:</label>
                            <input class="form-control" id="author" name="author" type="text" value="<?=$row['author']?>" aria-label="default input example">
                        </div>
                          <div class="form-group">
                            <label for="publisher" class="form-label">Publisher:</label>
                            <input class="form-control" id="publisher" name="publisher" type="text" value="<?=$row['publisher']?>" aria-label="default input example">
                        </div>
                        <div class="form-group">
                            <label for="edit-language">Language</label>
                            <select id="edit-language" name="language" class="form-control mt-2">
                                <option value="English" <?= $row["language"] == "English" ? "selected": "" ?>>English</option>
                                <option value="Spanish" <?= $row["language"] == "Spanish" ? "selected": "" ?>>Spanish</option>
                                <option value="French" <?= $row["language"] == "French" ? "selected": "" ?>>French</option>
                                <option value="Mandarin" <?= $row["language"] == "Mandarin" ? "selected": "" ?>>Mandarin</option>
                                <option value="Russian" <?= $row["language"] == "Russian" ? "selected": "" ?>>Russian</option>
                                <option value="German" <?= $row["language"] == "German" ? "selected": "" ?>>German</option>
                                <option value="Japanese" <?= $row["language"] == "Japanese" ? "selected": "" ?>>Japanese</option>
                                <option value="Other" <?= $row["language"] == "Other" ? "selected": "" ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-category">Category</label>
                            <select id="edit-category" name="category" class="form-control mt-2">
                                <option value="Fiction" <?= $row["category"] == "Fiction" ? "selected": "" ?>>Fiction</option>
                                <option value="Nonfiction" <?= $row["category"] == "Nonfiction" ? "selected": "" ?>>Nonfiction</option>
                                <option value="Reference" <?= $row["category"] == "Reference" ? "selected": "" ?>>Reference</option>
                            </select>
                        </div>       
                        <input type="hidden" name="book_id" value="<?= $row["book_id"] ?>" />                   
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-2" name="status" value="save">Save</button>
                        </div>
                    </form>
                </div>
            </form>
        </div>
    </div>
    <?php 
        }
    ?>

<?php 
    include('./includes/footer.php');
?>