<?php
session_start();

// Initialize the array to store member names
if (!isset($_SESSION['namaMember'])) {
    $_SESSION['namaMember'] = [];
}

// Check if the form for joining member is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["joinMember"])) {
    // Get the full name from the form
    $fullName = $_POST['full_name'];

    // Push the full name into the session array
    array_push($_SESSION['namaMember'], $fullName);

    // Show success message
    echo "<p class='alert alert-success'>You have successfully joined as a member!</p>";
}

// Check if the form for rental transaction is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sewa"])) {
    $hargaMotor = [
        "Honda Beat" => 50000.00,
        "Yamaha NMAX" => 75000.00,
        "Suzuki Address" => 60000.00,
        "Kawasaki Ninja" => 100000.00,
    ];

    $namaPelanggan = $_POST["nama_pelanggan"];
    $lamaWaktuRental = $_POST["lama_waktu_rental"];
    $jenisMotor = $_POST["jenis"];

    $totalSewa = $hargaMotor[$jenisMotor] * $lamaWaktuRental;
    $pajak = 10000.00; // Tambahan pajak Rp. 10.000,00
    $totalPembayaran = $totalSewa + $pajak;

    // Jika nama pelanggan merupakan nama member, berikan potongan harga 5%
    $diskon = 0;
    if (in_array($namaPelanggan, $_SESSION['namaMember'])) {
        $diskon = $totalPembayaran * 0.05;
        $totalPembayaran -= $diskon;
    }
}

// Check if the form for deleting member is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteMember"])) {
    // Jika nilai deleteMember tidak sama dengan "true", itu berarti hanya satu anggota yang ingin dihapus
    if ($_POST["deleteMember"] !== "true") {
        // Hapus anggota sesuai dengan indeks yang diberikan
        $index = $_POST["memberIndex"];
        unset($_SESSION['namaMember'][$index]);
    } else {
        // Jika nilai deleteMember adalah "true", hapus semua anggota
        $_SESSION['namaMember'] = [];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Rental Motor</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #074173;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            text-align: center;
            font-weight: bold;
            padding: 20px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.3), 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .card-header:before {
            content: "";
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background-color: rgba(255, 255, 255, 0.3);
            z-index: -1;
            transform: rotate(5deg) translateZ(-1px);
            transition: transform 0.3s ease-in-out;
        }

        .card-header:hover:before {
            transform: rotate(0deg) translateZ(-1px);
        }

        .form-container form {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-left: -25px;
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            opacity: 0;
            /* Initially hidden */
            animation: fadeInAnimation 1s ease-in-out forwards;
            /* Animation */
            transform: perspective(1000px) rotateY(5deg);
            /* Apply 3D rotation */
            transition: transform 0.5s ease;
            /* Add transition for smooth effect */
        }

        .form-container form:hover {
            transform: perspective(1000px) rotateY(0deg);
            /* Rotate back to normal on hover */
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.3), 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:before {
            content: "";
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background-color: rgba(255, 255, 255, 0.3);
            z-index: -1;
            transform: rotate(5deg) translateZ(-1px);
            transition: transform 0.3s ease-in-out;
        }

        .btn-primary:hover:before {
            transform: rotate(0deg) translateZ(-1px);
        }

        .output-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            /* margin-top: 20px; */
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            opacity: 0;
            /* Initially hidden */
            animation: fadeInAnimation 1s ease-in-out forwards;
            /* Animation */
        }

        .output-container h3 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .output-container p {
            margin-bottom: 5px;
        }

        .print-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .print-button:hover {
            background-color: #0056b3;
        }

        /* Gaya untuk pesan sukses */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        /* Gaya untuk daftar member */
        .member-list ul {
            list-style-type: none;
            padding: 0;
        }

        .member-list ul li {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
            position: relative;
            font-size: 20px;
        }

        .member-list ul li:last-child {
            border-bottom: none;
        }

        .delete-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .delete-button i {
            font-size: 16px;
        }

        /* Gaya tombol hapus semua */
        .delete-all-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .delete-all-button:hover {
            background-color: #c82333;
        }

        /* Fade-in animation */
        @keyframes fadeInAnimation {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @media print {
            form,
            .card-header button,
            button {
                display: none;
            }

            .output-container {
                width: 250%;
                margin: 0 auto;
                /* Untuk membuat konten di tengah */
                padding: 20px;
                border-radius: 0;
                font-size: 45px;
                margin-left: -350px;
            }

            .output-container h3 {
                font-size: 50px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1><i class="fas fa-motorcycle"></i> Rental Motor</h1>
                        <button id="joinButton" class="btn btn-primary mt-3"><i class="fas fa-user-plus"></i> Join
                            member</button>
                        <button id="memberButton" class="btn btn-primary mt-3"><i class="fas fa-user"></i> Member
                            list</button>
                    </div>
                    <div class="row">
                        <!-- Form Rental Motor -->
                        <div class="col-md-6">
                            <div class="form-container">
                                <!-- Form rental motor -->
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                                    class="mt-4">
                                    <div class="form-group">
                                        <label for="nama_pelanggan">Nama Pelanggan:</label>
                                        <input type="text" id="nama_pelanggan" name="nama_pelanggan"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lama_waktu_rental">Lama Waktu Rental (per hari):</label>
                                        <input type="number" id="lama_waktu_rental" name="lama_waktu_rental" min="1"
                                            step="1" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis">Jenis Motor:</label>
                                        <select id="jenis" name="jenis" class="form-control" required>
                                            <option value="Honda Beat">Honda Beat</option>
                                            <option value="Yamaha NMAX">Yamaha NMAX</option>
                                            <option value="Suzuki Address">Suzuki Address</option>
                                            <option value="Kawasaki Ninja">Kawasaki Ninja</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="sewa" class="btn btn-primary btn-block"><i
                                            class="fas fa-key"></i> Sewa</button>
                                </form>
                            </div>
                        </div>
                        <!-- Bukti Transaksi -->
                        <div class="col-md-6">
                            <!-- Output bukti transaksi -->
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sewa"])) {
                                // Output bukti transaksi
                                echo "<div class='output-container'>";
                                echo "<h3>Bukti Transaksi:</h3>";
                                if ($diskon > 0) {
                                    echo "<p>" . $namaPelanggan . " berstatus sebagai member mendapatkan diskon sebesar 5%</p>";
                                    echo "<p><b>Jenis motor :</b> " . $jenisMotor . "</p>";
                                    echo "<p><b>Rentang waktu/hari :</b> " . $lamaWaktuRental . " hari </p>";
                                    echo "<p><b>Harga rental per-harinya :</b> Rp " . number_format($hargaMotor[$jenisMotor], 2, ',', '.') . "</p>";
                                    echo "<p><b>Total sewa sebelum diskon :</b> Rp " . number_format($totalSewa, 2, ',', '.') . "</p>";
                                    echo "<p><b>Diskon (5%) :</b> Rp " . number_format($diskon, 2, ',', '.') . "</p>";
                                    echo "<p><b>Harga setelah diskon :</b> Rp " . number_format($totalPembayaran, 2, ',', '.') . "</p>";
                                    echo "<hr>";
                                } else {
                                    echo "<p>" . $namaPelanggan . " tidak berstatus sebagai member</p>";
                                    echo "<p><b>Jenis motor :</b> " . $jenisMotor . "</p>";
                                    echo "<p><b>Rentang waktu/hari :</b> " . $lamaWaktuRental . " hari </p>";
                                    echo "<p><b>Harga rental per-harinya :</b> Rp " . number_format($hargaMotor[$jenisMotor], 2, ',', '.') . "</p>";
                                    echo "<p><b>Harga bayar :</b> Rp " . number_format($totalPembayaran, 2, ',', '.') . "</p>";
                                    echo "<hr>";
                                }
                                // Tampilkan tombol cetak
                                echo "<div class='text-center'>";
                                echo "<button class='print-button btn-primary' onclick='window.print()'><i class='fas fa-print'></i> Cetak Bukti Transaksi</button>                                ";
                                echo "</div>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                    <!-- Join Member Section -->
                    <div class="join-member">
                        <!-- Join form -->
                        <div id="joinForm" class="card-body" style="display: none;">
                            <hr>
                            <h2>Join Member</h2>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <input type="hidden" name="joinMember" value="true">
                                <div class="form-group">
                                    <label for="full_name">Full Name:</label>
                                    <input type="text" id="full_name" name="full_name" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i>
                                    Join Now</button>
                            </form>
                        </div>
                    </div>
                    <!-- End Join Member Section -->
                    <!-- Member List Section -->
                    <div class="member-list">
                        <div id="memberList" class="card-body" style="display: none;">
                            <hr style="">
                            <h2>Member List</h2>
                            <?php
                            if (!empty($_SESSION['namaMember'])) {
                                echo "<ul>";
                                foreach ($_SESSION['namaMember'] as $index => $member) {
                                    echo "<li>" . $member . "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST' style='display: inline;'><input type='hidden' name='deleteMember'><input type='hidden' name='memberIndex' value='" . $index . "'><button class='delete-button' type='submit'><i class='fas fa-trash'></i></button></form></li>";
                                }
                                echo "</ul>";
                                // Tampilkan tombol hapus semua nama anggota
                                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>";
                                echo "<input type='hidden' name='deleteMember' value='true'>";
                                echo "<button class='delete-all-button btn btn-danger' type='submit'>Delete All Members</button>";
                                echo "</form>";
                            } else {
                                echo "<p>No members yet.</p>";
                            }
                            ?>
                        </div>
                    </div>
                    <!-- End Member List Section -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript to toggle the visibility of the join form
        document.getElementById('joinButton').addEventListener('click', function () {
            var joinForm = document.getElementById('joinForm');
            if (joinForm.style.display === 'none') {
                joinForm.style.display = 'block';
            } else {
                joinForm.style.display = 'none';
            }
        });

        document.getElementById('memberButton').addEventListener('click', function () {
            var memberList = document.getElementById('memberList');
            if (memberList.style.display === 'none') {
                memberList.style.display = 'block';
            } else {
                memberList.style.display = 'none';
            }
        });
    </script>
</body>

</html>