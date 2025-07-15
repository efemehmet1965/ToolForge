<?php
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonInput = $_POST['jsonInput'] ?? '';
    if (!empty($jsonInput)) {
        $decoded = json_decode($jsonInput);
        if (json_last_error() === JSON_ERROR_NONE) {
            $result = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            $error = 'Invalid JSON format: ' . json_last_error_msg();
        }
    } else {
        $error = 'JSON input cannot be empty.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-filetype-json me-2"></i>JSON Validator & Formatter</h5>
        <p class="card-text">Paste your JSON data to validate and format it. Ensure your JSON is well-formed for proper parsing and readability.</p>
        <form method="POST" action="?page=json_validator">
            <div class="mb-3">
                <label for="jsonInput" class="form-label">JSON Input</label>
                <textarea class="form-control" id="jsonInput" name="jsonInput" rows="8" required><?php echo htmlspecialchars($_POST['jsonInput'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Validate & Format</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Formatted JSON</label>
                <div class="input-group">
                    <textarea class="form-control" id="jsonResult" rows="12" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="jsonResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
