<?php
include "db.php";

// CREATE
if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    $sql = "INSERT INTO students (name, email, course)
            VALUES ('$name', '$email', '$course')";

    mysqli_query($conn, $sql);

    header("Location: index.php");
    exit();
}


// DELETE
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM students WHERE id=$id");

    header("Location: index.php");
    exit();
}


// UPDATE
if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    $sql = "UPDATE students
            SET name='$name',
                email='$email',
                course='$course'
            WHERE id=$id";

    mysqli_query($conn, $sql);

    header("Location: index.php");
    exit();
}


// GET STUDENT FOR EDIT
$editStudent = null;

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM students WHERE id=$id"
    );

    $editStudent = mysqli_fetch_assoc($result);
}


// READ
$result = mysqli_query($conn, "SELECT * FROM students");

?>

<!DOCTYPE html>
<html>

<head>

    <title>Student CRUD</title>

    <style>

        body {
            font-family: Arial;
            margin: 40px;
            background: #f5f5f5;
        }

        .container {
            width: 800px;
            margin: auto;
            background: white;
            padding: 25px;
        }

        input {
            padding: 10px;
            margin: 5px;
        }

        button {
            padding: 10px 20px;
            background: blue;
            color: white;
            border: none;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background: #333;
            color: white;
        }

        a {
            text-decoration: none;
            margin: 5px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Student CRUD</h1>


    <!-- ADD / UPDATE FORM -->

    <?php if ($editStudent): ?>

        <h2>Edit Student</h2>

        <form method="POST">

            <input type="hidden"
                   name="id"
                   value="<?php echo $editStudent['id']; ?>">

            <input type="text"
                   name="name"
                   value="<?php echo $editStudent['name']; ?>"
                   placeholder="Name"
                   required>

            <input type="email"
                   name="email"
                   value="<?php echo $editStudent['email']; ?>"
                   placeholder="Email"
                   required>

            <input type="text"
                   name="course"
                   value="<?php echo $editStudent['course']; ?>"
                   placeholder="Course"
                   required>

            <button type="submit" name="update">
                Update
            </button>

            <a href="index.php">Cancel</a>

        </form>

    <?php else: ?>

        <h2>Add Student</h2>

        <form method="POST">

            <input type="text"
                   name="name"
                   placeholder="Name"
                   required>

            <input type="email"
                   name="email"
                   placeholder="Email"
                   required>

            <input type="text"
                   name="course"
                   placeholder="Course"
                   required>

            <button type="submit" name="add">
                Add Student
            </button>

        </form>

    <?php endif; ?>


    <!-- DISPLAY STUDENTS -->

    <h2>Students</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>

        <tr>

            <td>
                <?php echo $row['id']; ?>
            </td>

            <td>
                <?php echo $row['name']; ?>
            </td>

            <td>
                <?php echo $row['email']; ?>
            </td>

            <td>
                <?php echo $row['course']; ?>
            </td>

            <td>

                <a href="index.php?edit=<?php echo $row['id']; ?>">
                    Edit
                </a>

                <a href="index.php?delete=<?php echo $row['id']; ?>"
                   onclick="return confirm('Delete this student?')">
                    Delete
                </a>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

</body>

</html>