<?php
require 'db.php';

// INSERT DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    $sql = "INSERT INTO students (name, email, course) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $course]);

    header("Location: index.php");
    exit();
}

// 🔍 SEARCH FUNCTION (ADDED)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// FETCH DATA (MODIFIED FOR SEARCH)
if ($search != '') {
    $stmt = $pdo->prepare("SELECT * FROM students 
                           WHERE name LIKE ? 
                           OR email LIKE ? 
                           OR course LIKE ?");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM students");
}

$students = $stmt->fetchAll();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #53729b;
        margin: 0;
        padding: 20px;
    }
    .container {
        max-width: 900px;
        margin: auto;
    }
    h2 {
        color: #333;
        margin-bottom: 10px;
    }
    /* FORM CARD */
    .form-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    input {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
    button {
        background: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background: #45a049;
    }
    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    th {
        background: #4CAF50;
        color: white;
        padding: 12px;
        text-align: left;
    }
    td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }
    tr:hover {
        background: #f1f1f1;
    }
    a {
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
    }
    a[href*="edit"] {
        background: #2196F3;
        color: white;
    }
    a[href*="delete"] {
        background: #f44336;
        color: white;
    }
</style>

<div class="container">
    <div class="form-card">
        <h2>➕ Add Student</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="course" placeholder="Course" required>
            <button type="submit">Save Student</button>
        </form>
    </div>

    <h2>📋 Student List</h2>

    <!-- 🔍 SEARCH BAR (ADDED) -->
    <form method="GET" style="margin-bottom: 15px;">
        <input type="text" name="search" placeholder="Search..."
               value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Action</th>
        </tr>

        <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td><?= htmlspecialchars($student['course']) ?></td>
            <td>
                <a href="edit.php?id=<?= $student['id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $student['id'] ?>"
                   onclick="return confirm('Delete this student?')">
                   Delete
                </a>
            </td>
        </tr>
        <?php endforeach; ?>

        <!-- 🔍 NO RESULTS MESSAGE (ADDED) -->
        <?php if (count($students) == 0): ?>
        <tr>
            <td colspan="4" style="text-align:center;">No results found</td>
        </tr>
        <?php endif; ?>
    </table>
</div>