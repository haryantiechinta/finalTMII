<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <!-- Bootstrap 4.6.2 (versi dist) -->
  <link rel="stylesheet" href="bootstrap-4.6.2-dist/css/bootstrap.min.css">

  <style>
    body {
      /* Ganti background dengan foto tmmi + overlay lembut */
      background: 
        linear-gradient(rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)),
        url('uploads/tmmi.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .register-card {
      background: rgba(255, 255, 255, 0.9);
      padding: 2.5rem;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 380px;
      animation: fadeIn 0.6s ease-in-out;
      text-align: center;
      backdrop-filter: blur(5px); /* Efek kaca lembut */
    }

    .register-card h3 {
      margin-bottom: 2rem;
      font-weight: 600;
      color: #37474f;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem 1rem;
      border: 1px solid #cfd8dc;
      transition: all 0.3s ease;
      margin-bottom: 1rem;
      text-align: center;
    }

    .form-control:focus {
      border-color: #094c4eff;
      box-shadow:  0 0 8px rgba(79, 81, 199, 0.5);
    }

    .btn-custom {
      background-color: #5f9ea0;
      color: #fff;
      font-weight: 600;
      border-radius: 10px;
      padding: 0.75rem;
      width: 100%;
      margin-top: 0.5rem;
      transition: 0.3s;
    }

    .btn-custom:hover {
      background-color: #3cbfc9ff;
    }

    .login-link {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.95rem;
      color: #546e7a;
    }

    .login-link:hover {
      color: #5f9ea0;
      text-decoration: none;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-15px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="register-card">
    <h3>Create Account ✨</h3>
    <form action="register.php" method="POST">
      <input type="text" class="form-control" name="username" placeholder="Username" required>
      <input type="email" class="form-control" name="email" placeholder="Email" required>
      <input type="password" class="form-control" name="password" placeholder="Password" required>
      <button type="submit" class="btn btn-custom">Register</button>
    </form>
    <a href="login_form.php" class="login-link">Already have an account? Login</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="bootstrap-4.6.2-dist/js/jquery.min.js"></script>
  <script src="bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
