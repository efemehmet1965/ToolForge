<?php
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputText = $_POST['inputText'] ?? '';
    $operation = $_POST['operation'] ?? 'encode';
    if (!empty($inputText)) {
        if ($operation === 'encode') {
            $result = urlencode($inputText);
        } else {
            $result = urldecode($inputText);
        }
    } else {
        $error = 'Input cannot be empty.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-link-45deg me-2"></i>URL Encoder / Decoder</h5>
        <p class="card-text">Encode or decode strings for safe use in URLs. URL encoding replaces unsafe ASCII characters with a "%" followed by two hexadecimal digits.</p>
        <form method="POST" action="?page=url_encoder">
            <div class="mb-3">
                <label for="inputText" class="form-label">Input String</label>
                <textarea class="form-control" id="inputText" name="inputText" rows="5" required><?php echo htmlspecialchars($_POST['inputText'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="operation" id="encode" value="encode" checked>
                    <label class="form-check-label" for="encode">Encode</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="operation" id="decode" value="decode" <?php echo (isset($_POST['operation']) && $_POST['operation'] === 'decode') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="decode">Decode</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Process</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Result</label>
                <div class="input-group">
                    <textarea class="form-control" id="urlEncoderResult" rows="5" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="urlEncoderResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
