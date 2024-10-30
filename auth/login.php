<?php
session_start(); // Start the session

require_once('../config.php'); // Connect to the database

// Check if the form has been submitted
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute the SQL statement
    $stmt = mysqli_prepare($conn, "SELECT users.*, siswa.* FROM users JOIN siswa ON users.id_siswa = siswa.id WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // If username found
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Debugging
        error_log("Username found: " . $row['username']);

        // Verify the hashed password
        if (password_verify($password, $row["password"])) {
            // Debugging
            error_log("Password correct");

            // Check user status
            if ($row['status'] == 'Aktif') {
                // Set session variables for the logged-in user
                $_SESSION["login"] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['nisn'] = $row['nisn'];
                $_SESSION['kelas'] = $row['kelas'];
                $_SESSION['lokasi_presensi'] = $row['lokasi_presensi'];

                // Redirect to the appropriate page based on user role
                if ($row['role'] === 'admin') {
                    header("Location: ../admin/home/home.php");
                } else {
                    header("Location: ../siswa/home/home.php");
                }
                exit(); // Stop further execution after redirect
            } else {
                $_SESSION["gagal"] = "Akun anda belum aktif";
            }
        } else {
            $_SESSION["gagal"] = "Password salah";
        }
    } else {
        $_SESSION["gagal"] = "Username salah";
    }

    // Redirect to login page if login fails
    if (isset($_SESSION["gagal"])) {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 Dark Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5.0.12/dark.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-image: url('../asset/images/bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            background-color: #0B1739;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5);
        }

        .btn-custom {
            background: linear-gradient(45deg, #CB3CFF, #7F25FB);
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background: linear-gradient(45deg, #7F25FB, #CB3CFF);
        }

        .form-control {
            background-color: #1B2A4E;
            border: 1px solid #6c757d;
            color: white;
        }

        .form-control:focus {
            background-color: #1B2A4E;
            border-color: #8A9AA7;
            box-shadow: none;
            color: white;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .form-check-input {
            background-color: transparent;
            border: 1px solid #6c757d;
        }

        .form-check-input:focus {
            border-color: #6c757d;
            box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.5);
        }

        .input-group .btn-outline-secondary {
            color: white;
            border-color: #6c757d;
        }

        .input-group .btn-outline-secondary:hover {
            background-color: #6c757d;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 20px;
            }

            .login-container h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h3 class="text-center font-weight-bold mb-4">Login</h3>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-custom">Submit</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <small>Developed by <a href="#" style="color: #CB3CFF;">Cielynn</a></small>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            var passwordIcon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        });
    </script>

    <!-- SweetAlert for error message -->
    <?php if (isset($_SESSION['gagal'])) { ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "<?= $_SESSION['gagal']; ?>",
            });
        </script>
    <?php unset($_SESSION['gagal']);
    } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>