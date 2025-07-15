<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['input'] ?? '');
    $operation = $_POST['operation'] ?? 'to_timestamp';

    if (!empty($input)) {
        if ($operation === 'to_timestamp') {
            // Convert date/time to timestamp
            $timestamp = strtotime($input);
            if ($timestamp !== false) {
                $result = $timestamp;
            } else {
                $error = 'Invalid date/time format.';
            }
        } else {
            // Convert timestamp to date/time
            if (is_numeric($input) && (int)$input == $input) {
                $result = date('Y-m-d H:i:s', (int)$input);
            } else {
                $error = 'Invalid Unix timestamp.';
            }
        }
    } else {
        $error = 'Input cannot be empty.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-clock-history me-2"></i>Unix Timestamp Converter</h5>
        <p class="card-text">Convert between human-readable date/time and Unix timestamps. Unix timestamps are a common way to represent points in time in computing.</p>
        <form method="POST" action="?page=unix_timestamp_converter">
            <div class="mb-3">
                <label for="input" class="form-label">Input</label>
                <input type="text" class="form-control" id="input" name="input" value="<?php echo htmlspecialchars($_POST['input'] ?? ''); ?>" placeholder="e.g., 1678886400 or 2023-03-15 00:00:00" required>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="operation" id="to_timestamp" value="to_timestamp" checked>
                    <label class="form-check-label" for="to_timestamp">Date/Time to Timestamp</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="operation" id="to_datetime" value="to_datetime" <?php echo (isset($_POST['operation']) && $_POST['operation'] === 'to_datetime') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="to_datetime">Timestamp to Date/Time</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Convert</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Result</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="timestampResult" value="<?php echo htmlspecialchars($result); ?>" readonly>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="timestampResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
