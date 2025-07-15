<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'] ?? '';
    $size = intval($_POST['size'] ?? 200);

    if (empty($text)) {
        $error = 'Text or URL cannot be empty.';
    } elseif ($size < 50 || $size > 1000) {
        $error = 'QR Code size must be between 50 and 1000 pixels.';
    } else {
        // Using Google Charts API for QR Code generation
        $qr_url = 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($text);
        $result = $qr_url;
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-qr-code me-2"></i>QR Code Generator</h5>
        <p class="card-text">Generate QR codes from any text or URL. Customize the size of your QR code.</p>
        <form method="POST" action="?page=qr_code_generator">
            <div class="mb-3">
                <label for="text" class="form-label">Text or URL</label>
                <textarea class="form-control" id="text" name="text" rows="3" required><?php echo htmlspecialchars($_POST['text'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="size" class="form-label">Size (pixels)</label>
                <input type="number" class="form-control" id="size" name="size" value="<?php echo htmlspecialchars($_POST['size'] ?? 200); ?>" min="50" max="1000" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate QR Code</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4 text-center">
                <label class="form-label">Generated QR Code</label>
                <img src="<?php echo htmlspecialchars($result); ?>" alt="QR Code" class="img-fluid border p-2 rounded" style="max-width: 250px;">
                <div class="input-group mt-3">
                    <input type="text" class="form-control" id="qrCodeUrl" value="<?php echo htmlspecialchars($result); ?>" readonly>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="qrCodeUrl">Copy URL</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
