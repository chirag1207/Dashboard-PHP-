<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f5f5;
    }
    .auth-container {
      max-width: 450px;
      margin: 80px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .nav-tabs .nav-link.active {
      background-color: #0d6efd;
      color: #fff !important;
      border-radius: 8px;
    }
    .nav-tabs .nav-link {
      border-radius: 8px;
    }
    .btn-primary {
      width: 100%;
    }
  </style>
</head>
<body>

<div class="auth-container">
  <!-- Tabs -->
  <ul class="nav nav-tabs mb-3" id="authTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup" type="button" role="tab">Signup</button>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content" id="authTabContent">
    <!-- Login Form -->
    <div class="tab-pane fade show active" id="login" role="tabpanel">
      <form action="./signup.php" method="POST">
        <div class="mb-3">
          <label for="loginEmail" class="form-label">Email address</label>
          <input type="email" class="form-control" id="loginEmail" name="email" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
          <label for="loginPassword" class="form-label">Password</label>
          <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
          <label class="form-check-label" for="rememberMe">Remember me</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <p class="mt-3 text-center"><a href="#">Forgot password?</a></p>
      </form>
    </div>

    <!-- Signup Form -->
    <div class="tab-pane fade" id="signup" role="tabpanel">
      <form action="signup.php" method="POST">
        <div class="mb-3">
          <label for="signupName" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="signupName" name="name" placeholder="Enter your name" required>
        </div>
        <div class="mb-3">
          <label for="signupEmail" class="form-label">Email address</label>
          <input type="email" class="form-control" id="signupEmail" name="email" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
          <label for="signupPassword" class="form-label">Password</label>
          <input type="password" class="form-control" id="signupPassword" name="password" placeholder="Password" required>
        </div>
        <div class="mb-3">
          <label for="signupConfirmPassword" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" id="signupConfirmPassword" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Signup</button>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <body>
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>