<?php

$conn = new mysqli("localhost", "root", "root", "user_db", 8889);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['age'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $stmt = $conn->prepare("INSERT INTO users (name, age) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $age);
    $stmt->execute();
}

if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $result = $conn->query("SELECT status FROM users WHERE id = $id");
    $row = $result->fetch_assoc();
    $new_status = $row['status'] == 1 ? 0 : 1;
    $conn->query("UPDATE users SET status = $new_status WHERE id = $id");
    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Form</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        input[type="text"], input[type="number"] { padding: 5px; margin: 5px; }
        table { border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #000; text-align: center; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>

<form method="POST">
    Name: <input type="text" name="name" required>
    Age: <input type="number" name="age" required>
    <input type="submit" value="Submit">
</form>

<table>
    <tr>
        <th>ID</th><th>Name</th><th>Age</th><th>Status</th><th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['age'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><a href="?toggle=<?= $row['id'] ?>"><button type="button">Toggle</button></a></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>