<?php
include('/database.php');

// Ambil data produk
$queryProducts = "SELECT * FROM pekerjaan";
$resultProducts = $conn->query($queryProducts);

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];  // ID produk yang dipilih
    $price = $_POST['point'];  // Harga

    // Query untuk memasukkan item ke dalam tabel items tanpa kategori
    $queryInsert = "INSERT INTO items (product_id, price) VALUES ('$product_id', '$price')";

    if ($conn->query($queryInsert)) {
        header('Location: index.php'); // Redirect ke halaman utama setelah menambah
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah</title>
    <link rel="stylesheet" href="style.css">
    <!-- Select2 CSS dan JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <style>
        /* Lebarkan lebar select produk */
        #product_id {
            width: 100%;  /* Mengisi 100% dari elemen induknya (kontainer) */
            padding: 10px; /* Memberikan padding agar tampil lebih nyaman */
            font-size: 16px; /* Menambah ukuran font agar lebih jelas */
            border-radius: 4px; /* Menambahkan border-radius untuk tampilan rounded */
            border: 1px solid #ccc; /* Warna border yang lebih lembut */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Tambah Job</h1>
    <form method="POST">
        <!-- Dropdown untuk memilih produk dengan pencarian -->
        <select name="product_id" id="product_id" required>
            <option value="" disabled selected>Pilih indikator</option>
            <?php while ($row = $resultProducts->fetch_assoc()): ?>
                <option value="<?php echo $row['pekerjaan_id']; ?>" data-price="<?php echo $row['point']; ?>">
                    <?php echo $row['pekerjaan']; ?> - <?php echo number_format($row['point'], 2); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Input untuk harga (otomatis terisi setelah memilih produk) -->
        <input type="number" step="0.01" name="point" id="price" placeholder="Poin" required readonly>

        <button type="submit">Tambah</button>
    </form>
</div>

<!-- Select2 dan jQuery Script -->
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 pada dropdown produk
        $('#product_id').select2();

        // Menangani perubahan pada dropdown produk
        $('#product_id').on('change', function() {
            var selectedPrice = $(this).find(':selected').data('price');
            $('#price').val(selectedPrice); // Update harga berdasarkan produk yang dipilih
        });
    });
</script>

</body>
</html>

<?php $conn->close(); ?>
