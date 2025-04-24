<?php session_start(); ?>
<?php
  $error = '';
  if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    rel="stylesheet"
  />
  <style>
    /* Entrance fade-in and scale animation */
    @keyframes fadeScaleIn {
      0% {
        opacity: 0;
        transform: scale(0.8);
        box-shadow: none;
      }
      100% {
        opacity: 1;
        transform: scale(1);
        box-shadow:
          0 15px 30px rgba(0, 0, 0, 0.25),
          0 8px 10px rgba(0, 0, 0, 0.15);
      }
    }

    .card-3d {
      border-radius: 0.375rem; /* rounded-md */
      background-clip: padding-box;
      animation: fadeScaleIn 0.8s ease forwards;
      box-shadow:
        0 15px 30px rgba(0, 0, 0, 0.25),
        0 8px 10px rgba(0, 0, 0, 0.15);
      transition: box-shadow 0.3s ease;
    }

    /* Slightly stronger shadow on hover */
    .card-3d:hover {
      box-shadow:
        0 25px 45px rgba(0, 0, 0, 0.3),
        0 12px 15px rgba(0, 0, 0, 0.2);
    }

    body {
      background-color: #ffffff; /* White background for body */
    }

    /* Style for left side card */
    .card-left {
      background-color: #f1f8e9; /* Light green background */
      color: #333333;
    }

    /* Style for buttons */
    .btn-login {
      background-color: #4CAF50; /* Green button */
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-login:hover {
      background-color: #388E3C; /* Darker green on hover */
    }

    /* Style for the right side */
    .card-right {
      background-color: #66BB6A; /* Medium green background */
      color: white;
      text-align: center;
      border-radius: 0 0.375rem 0.375rem 0;
    }

    .card-right img {
      width: 100%;
      max-width: 350px; /* Limit image size */
      height: auto;
      margin-bottom: 20px;
    }

    /* Input fields styles */
    input[type="text"],
    input[type="password"] {
      border-radius: 5px;
      border: 1px solid #4CAF50;
      padding-left: 35px; /* Add space for icons */
    }

  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-4xl">
    <div class="card-3d flex flex-col md:flex-row bg-white w-full">
      <div class="flex flex-col justify-center p-10 w-full md:w-1/2 card-left">
        <h1 class="text-2xl font-normal mb-1 text-gray-900">Login</h1>
        <p class="text-xs text-gray-400 mb-6">Kasir</p>
        <?php if (!empty($error)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Gagal!</strong>
    <span class="block sm:inline"><?php echo $error; ?></span>
  </div>
<?php endif; ?>

        <form method="POST" action="auth.php">
          <div class="relative mb-4">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
              <i class="fas fa-user"></i>
            </span>
            <input
              class="pl-10 pr-3 py-2 border border-blue-400 rounded text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white placeholder-gray-600"
              name="username"
              placeholder="Username"
              required
              type="text"
            />
          </div>

          <div class="relative mb-6">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
              <i class="fas fa-lock"></i>
            </span>
            <input
              class="pl-10 pr-3 py-2 border border-gray-300 rounded text-gray-700 w-full focus:outline-none focus:ring-2 focus:ring-blue-400"
              name="password" placeholder="Password" required
              type="password"
            />
          </div>
          <button
            class="btn-login text-white px-6 py-2 rounded text-sm font-normal transition-colors"
            type="submit"
          >
            LOGIN
          </button>
        </form>
      </div>
      <!-- Right side -->
      <div class="card-right flex flex-col justify-center items-center p-10 w-full md:w-1/2 text-white">
        <img
          alt="Logo"
          class="mb-6"
          height="600"
          src="images/Logo.png"
          width="600"
        />
      </div>
    </div>
  </div>
</body>
</html>
