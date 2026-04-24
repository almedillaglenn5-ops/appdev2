
<?php
require 'db.php';
if (!isset($_GET['id'])) {
    die("ID not found");
}
$id = $_GET['id'];
// FETCH CURRENT DATA
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) {
    die("Student not found");
}
// UPDATE DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?";
    $pdo->prepare($sql)->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['course'],
        $id
    ]);
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #74a1e5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 25px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #2196F3;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        button:hover {
            background: #1976D2;
        }
        .back {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #666;
        }
        .back:hover {
            color: #000;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>✏️ Edit Student</h2>
    <form method="POST">
        <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        <input type="text" name="course" value="<?= htmlspecialchars($student['course']) ?>" required>
        <button type="submit">Update Student</button>
    </form>
    <a class="back" href="index.php">⬅ Back to List</a>
</div>
</body>
</html>
