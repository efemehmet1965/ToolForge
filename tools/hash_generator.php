<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'] ?? '';
    $algorithm = $_POST['algorithm'] ?? 'md5';

    if (!empty($text)) {
        if ($algorithm === 'md5') {
            $result = md5($text);
        } elseif ($algorithm === 'sha1') {
            $result = sha1($text);
        } else {
            $error = 'Invalid hash algorithm selected.';
        }
    } else {
        $error = 'Input text cannot be empty.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-hash me-2"></i>MD5 / SHA1 Hash Generator</h5>
        <p class="card-text">Generate MD5 or SHA1 hash from a given text. Hashing is commonly used for data integrity checks and password storage.</p>
        <form method="POST" action="?page=hash_generator">
            <div class="mb-3">
                <label for="text" class="form-label">Input Text</label>
                <textarea class="form-control" id="text" name="text" rows="5" required><?php echo htmlspecialchars($_POST['text'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="algorithm" id="md5" value="md5" checked>
                    <label class="form-check-label" for="md5">MD5</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="algorithm" id="sha1" value="sha1" <?php echo (isset($_POST['algorithm']) && $_POST['algorithm'] === 'sha1') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="sha1">SHA1</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Generate Hash</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Generated Hash</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="hashResult" value="<?php echo htmlspecialchars($result); ?>" readonly>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="hashResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
