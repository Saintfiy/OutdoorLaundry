<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) ||
    ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'kurir')) {

    header("Location: index.php");
    exit;
}

const ROLE_ADMIN = 'admin';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? 'update_status';
    try {
        switch ($action) {
            case 'validate_payment':
                validatePayment($pdo, $_POST['booking_id']);
                break;

            case 'assign_kurir':
                assignKurir(
                    $pdo,
                    $_POST['booking_id'],
                    $_POST['kurir_id']
                );
                break;

            case 'update_status':
                updateBookingStatus(
                    $pdo,
                    $_POST['booking_id'],
                    $_POST['status'],
                    $_FILES['photo'] ?? null
                );
                break;
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
    redirectDashboard();
}

/* =========================
   PAYMENT
========================= */

function validatePayment($pdo, $bookingId)
{
    checkAdmin();
    $stmt = $pdo->prepare(
        "UPDATE bookings
         SET payment_status = 'Paid'
         WHERE id = ?"
    );
    $stmt->execute([$bookingId]);
    addTrackingLog(
        $pdo,
        $bookingId,
        'Payment Validated',
        'Pembayaran telah divalidasi oleh admin'
    );
}

/* =========================
   ASSIGN KURIR
========================= */

function assignKurir($pdo, $bookingId, $kurirId)
{
    checkAdmin();
    $stmt = $pdo->prepare(
        "UPDATE bookings
         SET kurir_id = ?
         WHERE id = ?"
    );
    $stmt->execute([$kurirId, $bookingId]);
    $stmt = $pdo->prepare(
        "SELECT name FROM users WHERE id = ?"
    );
    $stmt->execute([$kurirId]);
    $kurirName = $stmt->fetchColumn();
    addTrackingLog(
        $pdo,
        $bookingId,
        'Assigned to Kurir',
        'Order di-assign ke kurir: ' . $kurirName
    );
}

/* =========================
   UPDATE STATUS
========================= */

function updateBookingStatus($pdo, $bookingId, $status, $photoFile)
{
    $stmt = $pdo->prepare(
        "UPDATE bookings
         SET status = ?
         WHERE id = ?"
    );
    $stmt->execute([$status, $bookingId]);
    $photoPath = uploadPhoto($photoFile);
    addTrackingLog(
        $pdo,
        $bookingId,
        $status,
        "Status diupdate menjadi " . $status,
        $photoPath
    );
}

/* =========================
   TRACKING
========================= */

function addTrackingLog(
    $pdo,
    $bookingId,
    $status,
    $description,
    $photo = null
) {
    $stmt = $pdo->prepare(
        "INSERT INTO tracking_logs
        (booking_id, status, description, photo_proof)
        VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        $bookingId,
        $status,
        $description,
        $photo
    ]);
}

/* =========================
   UPLOAD
========================= */

function uploadPhoto($file)
{
    if (!$file || $file['error'] != 0) {
        return null;
    }
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );
    if (!in_array($ext, $allowed)) {
        throw new Exception("Format file tidak valid");
    }
    $newFilename = uniqid('proof_') . '.' . $ext;
    $destination = 'uploads/' . $newFilename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Upload gagal");
    }
    return $destination;
}

/* =========================
   HELPER
========================= */

function checkAdmin()
{
    if ($_SESSION['role'] != ROLE_ADMIN) {
        throw new Exception("Unauthorized");
    }
}

function redirectDashboard()
{
    if ($_SESSION['role'] == ROLE_ADMIN) {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_kurir.php");
    }
    exit;
}
?>
