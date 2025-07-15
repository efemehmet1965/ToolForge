<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $html_input = $_POST['html_input'] ?? '';

    if (!empty($html_input)) {
        // Remove comments
        $html_input = preg_replace('/<!--.*?-->/s', '', $html_input);
        // Remove whitespace between tags
        $html_input = preg_replace('/>\s+<</', '><', $html_input);
        // Remove multiple spaces and newlines
        $html_input = preg_replace(['/\s{2,}/', '/\r\n|\r|\n/'], [' ', ''], $html_input);
        
        $result = trim($html_input);
    } else {
        $error = 'HTML input cannot be empty.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-filetype-html me-2"></i>HTML Minifier</h5>
        <p class="card-text">Minify your HTML code to reduce file size and improve page load times.</p>
        <form method="POST" action="?page=html_minifier">
            <div class="mb-3">
                <label for="html_input" class="form-label">HTML Code</label>
                <textarea class="form-control" id="html_input" name="html_input" rows="10" required><?php echo htmlspecialchars($_POST['html_input'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Minify HTML</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Minified HTML</label>
                <div class="input-group">
                    <textarea class="form-control" id="minifiedHtmlResult" rows="10" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="minifiedHtmlResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
