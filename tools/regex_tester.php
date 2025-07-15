<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pattern = $_POST['pattern'] ?? '';
    $text = $_POST['text'] ?? '';

    if (empty($pattern) || empty($text)) {
        $error = 'Pattern and text cannot be empty.';
    } else {
        // Add delimiters if not present
        if (substr($pattern, 0, 1) !== '/' || substr($pattern, -1, 1) !== '/') {
            $pattern = '/' . $pattern . '/';
        }

        if (@preg_match($pattern, $text) === false) {
            $error = 'Invalid regex pattern.';
        } else {
            $matches = [];
            preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

            if (!empty($matches[0])) {
                $formatted_matches = [];
                foreach ($matches[0] as $match) {
                    $formatted_matches[] = 'Match: ' . $match[0] . ' (at position ' . $match[1] . ')';
                }
                $result = implode("\n", $formatted_matches);
            } else {
                $result = 'No matches found.';
            }
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-regex me-2"></i>Regex Tester</h5>
        <p class="card-text">Test regular expressions against a given text to find matches.</p>
        <form method="POST" action="?page=regex_tester">
            <div class="mb-3">
                <label for="pattern" class="form-label">Regex Pattern</label>
                <input type="text" class="form-control" id="pattern" name="pattern" value="<?php echo htmlspecialchars($_POST['pattern'] ?? ''); ?>" placeholder="e.g., /\d+/" required>
                <div class="form-text">No need to add delimiters (e.g., /pattern/).</div>
            </div>
            <div class="mb-3">
                <label for="text" class="form-label">Test Text</label>
                <textarea class="form-control" id="text" name="text" rows="5" required><?php echo htmlspecialchars($_POST['text'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Test Regex</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Matches</label>
                <div class="input-group">
                    <textarea class="form-control" id="regexResult" rows="10" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="regexResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>