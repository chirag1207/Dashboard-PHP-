<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["email"];
    $password = $_POST["password"];

    try {
        require_once "includes.php"; // this should create $pdo = new PDO(...)
        $password_sha = hash('sha256', $password);
        // Use named placeholders and password_verify if you’re storing hashed passwords
        $query = "SELECT * FROM users WHERE username = :username AND password_hash = :password_hash";;
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $login, ':password_hash' => $password_sha ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id']; // if you have user_id
            $_SESSION['role'] = $user['role'];
            echo "<h2>Login successful!</h2>";
            header("Location: dashboard.php");

        } else {
            echo "<h2> Invalid username or password!</h2>";
        }
    } catch (PDOException $e) {
        echo "<h2>Database Error:</h2> " . $e->getMessage();
    } catch (Exception $e) {
        echo "<h2>General Error:</h2> " . $e->getMessage();
    }
}
?>
