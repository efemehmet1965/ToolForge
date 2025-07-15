<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'paragraphs';
    $count = intval($_POST['count'] ?? 5);

    if ($count <= 0) {
        $error = 'Count must be greater than 0.';
    } else {
        $lorem_ipsum_base = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
        $words = explode(' ', $lorem_ipsum_base);
        $num_words = count($words);

        $generated_text = '';

        if ($type === 'paragraphs') {
            for ($p = 0; $p < $count; $p++) {
                $paragraph = '';
                $paragraph_word_count = rand(50, 150); // Random words per paragraph
                for ($i = 0; $i < $paragraph_word_count; $i++) {
                    $paragraph .= $words[rand(0, $num_words - 1)] . ' ';
                }
                $generated_text .= ucfirst(trim($paragraph)) . ".\n\n";
            }
        } elseif ($type === 'words') {
            for ($i = 0; $i < $count; $i++) {
                $generated_text .= $words[rand(0, $num_words - 1)] . ' ';
            }
            $generated_text = trim($generated_text);
        } elseif ($type === 'sentences') {
            for ($s = 0; $s < $count; $s++) {
                $sentence = '';
                $sentence_word_count = rand(10, 20); // Random words per sentence
                for ($i = 0; $i < $sentence_word_count; $i++) {
                    $sentence .= $words[rand(0, $num_words - 1)] . ' ';
                }
                $generated_text .= ucfirst(trim($sentence)) . ". ";
            }
            $generated_text = trim($generated_text);
        }
        $result = $generated_text;
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-file-text me-2"></i>Lorem Ipsum Generator</h5>
        <p class="card-text">Generate placeholder text (Lorem Ipsum) for your designs and layouts.</p>
        <form method="POST" action="?page=lorem_ipsum_generator">
            <div class="mb-3">
                <label for="type" class="form-label">Generate By</label>
                <select class="form-select" id="type" name="type">
                    <option value="paragraphs" <?php echo (isset($_POST['type']) && $_POST['type'] === 'paragraphs') ? 'selected' : ''; ?>>Paragraphs</option>
                    <option value="words" <?php echo (isset($_POST['type']) && $_POST['type'] === 'words') ? 'selected' : ''; ?>>Words</option>
                    <option value="sentences" <?php echo (isset($_POST['type']) && $_POST['type'] === 'sentences') ? 'selected' : ''; ?>>Sentences</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="count" class="form-label">Count</label>
                <input type="number" class="form-control" id="count" name="count" value="<?php echo htmlspecialchars($_POST['count'] ?? 5); ?>" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Generated Text</label>
                <div class="input-group">
                    <textarea class="form-control" id="loremIpsumResult" rows="10" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="loremIpsumResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>