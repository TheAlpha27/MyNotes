<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>MyNotes</title>
</head>

<body>
    <!-- Edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Update Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/MyNotes/index.php" method="post">
                        <input type="hidden" name="sno" id="sno">
                        <div class="mb-3">
                            <label for="edittitle" class="form-label">Title</label>
                            <input type="text" class="form-control" name="edittitle" id="edittitle">
                        </div>
                        <label for="editdescription">Description</label>
                        <div class="form-floating mb-3 mt-3">
                            <textarea class="form-control editdescription" placeholder="Leave a comment here"
                                id="editdescription" name="editdescription" style="height: 100px"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MyNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "mynotes";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Die if connection was not successful
if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Deleting a note
    if(isset($_POST['DelSno'])){
        $sno = $_POST['DelSno'];
        $sql = "DELETE FROM `notes` WHERE `sno` = '$sno' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your note has been deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
        else{
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error</strong> Your note was not deleted.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
    }
    // Updating a note
    else if(isset($_POST['sno'])){
        $sno = $_POST['sno'];
        $title = $_POST['edittitle'];
        $description = $_POST['editdescription'];
        $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` = $sno;";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your note has been updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
        else{
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error</strong> Your note was not updated.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
    }
    // Inserting a note
    else{
        $title = $_POST['title'];
        $description = $_POST['description'];
        $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description');";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your entry has been submitted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
          }
          else{
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> Not able to add your note due to this error: '. mysqli_error($conn) . '
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
          }
    }
}
?>
    <div class="container">
        <div class="row">
            <div class="col col-12 pt-3">
                <form action="/MyNotes/index.php" method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                    <label for="description">Description</label>
                    <div class="form-floating mb-3 mt-3">
                        <textarea class="form-control" placeholder="Leave a comment here" id="description"
                            name="description" style="height: 100px"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Add Note</button>
                </form>
            </div>
            <?php 
            $sql = "SELECT * FROM `notes`";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);
            if($num > 0){
                $sno = 0;
                while($row = mysqli_fetch_assoc($result)){
                    $sno = $sno + 1;
                    echo "
                    <div class='card my-2 mx-1 col-md-4 col-sm-6' id='".$row['sno']."'>
                        <h5 class='card-header'> Note " . $sno . "</h5>
                        <div class='card-body'>
                            <div class='content'>
                                <h5 class='card-title'>" . $row['title'] . "</h5>
                                <p class='card-text' >" . $row['description'] . "</p>
                            </div>
                            <div class='btn-container d-flex'>
                                <button class='btn btn-sm btn-primary edit' data-bs-toggle='modal' data-bs-target='#editModal'>Update</button>
                                <form class='mx-1' id='delForm' action='/MyNotes/index.php' method='post'>
                                    <input type='hidden' name='DelSno' id='DelSno' value='".$row['sno']."'>
                                    <button class='btn btn-sm btn-primary delete'>Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>";
                }
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
    <script>
        let edits = document.getElementsByClassName("edit");
        Array.from(edits).forEach((e) => {
            e.addEventListener("click", (element) => {
                parentNode = element.target.parentNode.parentNode.parentNode;
                sno = parentNode.id;
                title = parentNode.getElementsByClassName('card-title')[0].innerText;
                description = parentNode.getElementsByClassName('card-text')[0].innerText;
                document.getElementById('sno').value = sno;
                document.getElementById('edittitle').value = title;
                document.getElementById('editdescription').value = description;
            })
        })
        let deletes = document.getElementsByClassName("delete");
        Array.from(deletes).forEach((e) => {
            e.addEventListener("click", (element) => {
                ParentNode = element.target.parentNode.parentNode.parentNode;
                sno = ParentNode.id;
                if (confirm("Do you want to delete it?")) {
                    element.target.setAttribute("type", "submit");
                }
                else {
                    element.target.setAttribute("type", "button");
                }
            })
        })
    </script>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>

</html>