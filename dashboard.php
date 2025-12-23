<?php
require_once "includes.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // redirect to login if not logged in
    exit();
}

// Access username
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id']; // optional if needed
$role = $_SESSION['role'];


// Demo: student_id = 1
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$stmt->execute([':username' => $username]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $student_id = $result['user_id'];
} else {
    // If somehow the username doesn't exist in users table
    echo "Error: User not found!";
    exit();
}

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
    SELECT c.course_name, c.credits, e.semester, g.grade_point
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
        SUM(CASE WHEN status='Present' THEN 1 ELSE 0 END) AS present_count
    FROM Attendance
    WHERE student_id=:id
");
$stmt->execute([':id' => $student_id]);
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);
$attendance_percent = ($attendance['total_classes']>0) ? round(($attendance['present_count']/$attendance['total_classes'])*100,2) : 0;

// GPA calculation (average of grade points)
$total_points = 0;
$total_courses = count($courses);
foreach($courses as $c){
    $total_points += $c['grade_point'] ?? 0;
}
$gpa = ($total_courses>0) ? round($total_points/$total_courses,2) : 0;

// Prepare data for charts
$course_names = [];
$grade_points = [];
foreach($courses as $c){
    $course_names[] = $c['course_name'];
    $grade_points[] = $c['grade_point'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { background-color: #f8f9fa; }
.sidebar { height: 100vh; background: #343a40; color: white; padding-top: 20px; }
.sidebar a { color: white; display: block; padding: 10px 20px; text-decoration: none; }
.sidebar a:hover { background: #495057; text-decoration: none; }
.card { border-radius: 1rem; }
</style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
        <h3 class="text-center mb-4">Dashboard</h3>
        <a href="#"><i class="fa-solid fa-home me-2"></i>Home</a>
        <a href="#"><i class="fa-solid fa-book me-2"></i>Courses</a>
        <a href="#"><i class="fa-solid fa-graduation-cap me-2"></i>Grades</a>
        <a href="#"><i class="fa-solid fa-calendar-check me-2"></i>Attendance</a>
        <a href="#"><i class="fa-solid fa-user me-2"></i>Profile</a>
    </div>

    <!-- Main content -->
    <div class="col-md-10 p-4">
        <h2 class="mb-4">Welcome, <?= htmlspecialchars($username) ?></h2>

        <!-- Stats cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Courses</h5>
                        <h3><?= $total_courses ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5>GPA</h5>
                        <h3><?= $gpa ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center bg-warning text-dark">
                    <div class="card-body">
                        <h5>Total Classes</h5>
                        <h3><?= $attendance['total_classes'] ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h5>Attendance %</h5>
                        <h3><?= $attendance_percent ?>%</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Grade Points per Course</h5>
                    <canvas id="gradesChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Attendance Pie</h5>
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

    </div>
  </div>
</div>

<script>
// Grades Chart
const gradesCtx = document.getElementById('gradesChart').getContext('2d');
const gradesChart = new Chart(gradesCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($course_names) ?>,
        datasets: [{
            label: 'Grade Points',
            data: <?= json_encode($grade_points) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: { scales: { y: { beginAtZero:true } } }
});

// Attendance Chart
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
const attendanceChart = new Chart(attendanceCtx, {
    type: 'doughnut',
    data: {
        labels: ['Present','Absent'],
        datasets: [{
            data: [<?= $attendance['present_count'] ?>, <?= $attendance['total_classes'] - $attendance['present_count'] ?>],
            backgroundColor: ['rgba(75, 192, 192, 0.7)','rgba(255, 99, 132, 0.7)']
        }]
    },
    options: { responsive:true, plugins:{ legend:{ position:'bottom' } } }
});
</script>

</body>
</html>
