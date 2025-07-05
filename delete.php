<?php
include('/database.php');

// Cek jika ada ID yang diteruskan di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Pertama, hapus referensi dari user_choices
    $queryDeleteChoices = "DELETE FROM user_choices WHERE item_id = '$id'";
    $conn->query($queryDeleteChoices);

    // Hapus item dari tabel items
    $queryDelete = "DELETE FROM items WHERE id = '$id'";

    if ($conn->query($queryDelete)) {
        header('Location: index.php'); // Redirect kembali ke index.php setelah menghapus
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "ID tidak ditemukan!";
}
?>
