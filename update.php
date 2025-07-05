<?php
include('/database.php');

// Cek apakah ada ID yang diteruskan untuk update
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data item berdasarkan ID
    $queryItem = "SELECT * FROM items WHERE id = '$id'";
    $resultItem = $conn->query($queryItem);

    if ($resultItem->num_rows > 0) {
        $item = $resultItem->fetch_assoc();
        $product_id = $item['product_id'];
        $price = $item['price'];
    } else {
        echo "Item tidak ditemukan!";
        exit();
    }

    // Ambil data produk
    $queryProducts = "SELECT * FROM products";
    $resultProducts = $conn->query($queryProducts);

    // Cek jika form disubmit untuk update data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_id = $_POST['product_id'];  // ID produk yang dipilih
        $price = $_POST['price'];  // Harga baru

        // Query untuk update data
        $queryUpdate = "UPDATE items SET product_id = '$product_id', price = '$price' WHERE id = '$id'";

        if ($conn->query($queryUpdate)) {
            header('Location: index.php'); // Redirect ke halaman utama setelah update
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
} else {
    echo "ID tidak ditemukan!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Update Item</h1>
    <form method="POST">
        <!-- Dropdown untuk memilih produk -->
        <select name="product_id" required>
            <option value="" disabled>Select Product</option>
            <?php while ($row = $resultProducts->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $product_id) ? 'selected' : ''; ?>>
                    <?php echo $row['name']; ?> - <?php echo number_format($row['price'], 2); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Input untuk harga -->
        <input type="number" step="0.01" name="price" value="<?php echo $price; ?>" placeholder="Price" required>

        <button type="submit">Update Item</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
