<?php
require_once "includes.php";

// For demo purposes, show student_id = 1
$student_id = 1;

// Fetch student info
$stmt = $pdo->prepare("
    SELECT s.*, d.dept_name 
    FROM Students s 
    LEFT JOIN Departments d ON s.dept_id = d.dept_id
    WHERE s.student_id = :id
");
$stmt->execute([':id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch enrollments + grades
$stmt = $pdo->prepare("
    SELECT c.course_code, c.course_name, c.credits, e.semester, e.year, g.grade, g.grade_point
    FROM Enrollments e
    JOIN Courses c ON e.course_id = c.course_id
    LEFT JOIN Grades g ON e.enrollment_id = g.enrollment_id
    WHERE e.student_id = :id
");
$stmt->execute([':id' => $student_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Attendance summary
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) AS total_classes,
        SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) AS present_count
    FROM Attendance
    WHERE student_id = :id
");
$stmt->execute([':id' => $student_id]);
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);
$attendance_percent = ($attendance['total_classes'] > 0)
    ? round(($attendance['present_count'] / $attendance['total_classes']) * 100, 2)
    : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row mb-4">
        <div class="col text-center">
            <h1 class="fw-bold text-primary">🎓 Student Dashboard</h1>
            <p class="text-muted">Welcome, <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
        </div>
    </div>

    <!-- Student Info -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fa-solid fa-user-graduate me-2"></i> Profile Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Student ID:</strong> <?= $student['student_id'] ?></p>
                    <p><strong>Name:</strong> <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
                    <p><strong>Department:</strong> <?= htmlspecialchars($student['dept_name']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($student['phone']) ?></p>
                    <p><strong>CGPA:</strong> <?= $student['cgpa'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Course and Grades -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <i class="fa-solid fa-book-open me-2"></i> Enrollments & Grades
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Semester</th>
                        <th>Year</th>
                        <th>Grade</th>
                        <th>Grade Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['course_code']) ?></td>
                        <td><?= htmlspecialchars($c['course_name']) ?></td>
                        <td><?= $c['credits'] ?></td>
                        <td><?= $c['semester'] ?></td>
                        <td><?= $c['year'] ?></td>
                        <td><?= $c['grade'] ?: '-' ?></td>
                        <td><?= $c['grade_point'] ?: '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attendance -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <i class="fa-solid fa-calendar-check me-2"></i> Attendance Summary
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <h5>Total Classes</h5>
                    <p class="display-6"><?= $attendance['total_classes'] ?></p>
                </div>
                <div class="col-md-4">
                    <h5>Classes Attended</h5>
                    <p class="display-6"><?= $attendance['present_count'] ?></p>
                </div>
                <div class="col-md-4">
                    <h5>Attendance %</h5>
                    <p class="display-6 text-primary"><?= $attendance_percent ?>%</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center text-muted mt-5">
        <hr>
        <small>© <?= date('Y') ?> Student Dashboard | Built with ❤️ using PHP & Bootstrap</small>
    </footer>
</div>

</body>
</html>
