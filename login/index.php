<!DOCTYPE html>
<html lang="id">

<head>
  
<!-- agar website mendukung karakter Indonesia.: -->
    <meta charset="UTF-8"> 
<!-- agar responsive di HP. -->
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Login | Sistem Pembayaran Listrik</title>

            <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body style="background: linear-gradient(135deg,#0d6efd,#6ea8fe);">
    <div class="container">

    <div class="row vh-100 justify-content-center align-items-center">

        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">

            <div class="card-body" >
                <div class="text-center mb-3">

                    <i class="bi bi-lightning-charge-fill text-warning"
                    style="font-size:70px;"></i>

                </div>
                <h2 class="text-center mb-4">

                    Sistem Pembayaran Listrik

                </h2>
                    <p class="text-center text-muted mb-4">
                        Silakan login untuk melanjutkan
                    </p>
                <form action="proses_login.php" method="POST">

                    <div class="mb-3">

                        <label class="form-label">

                            Username

                        </label>

                        <div class="input-group">

                        <span class="input-group-text">

                            <i class="bi bi-person-fill"></i>

                        </span>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                required>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Password

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-lock-fill"></i>

                             </span>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                    </div>
                                        
                    <button
                        type="submit"
                        name="login"
                        class="btn btn-primary w-100">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Login

                    </button>
                    <hr>

                        <p class="text-center text-muted mb-0">

                        © 2026 Sistem Pembayaran Listrik

                        </p>
                </form>


         </div>

        </div>
     </div>

    </div>

</div>
</body>

</html>