<?php
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'] ?? '';
    if (!empty($text)) {
        $word_count = str_word_count($text);
        $char_count = mb_strlen($text, 'UTF-8');
        $char_count_no_spaces = mb_strlen(str_replace(' ', '', $text), 'UTF-8');
        $line_count = count(explode("\n", $text));
        $paragraph_count = count(array_filter(explode("\n\n", $text)));

        $result = [
            'words' => $word_count,
            'characters' => $char_count,
            'characters_no_spaces' => $char_count_no_spaces,
            'lines' => $line_count,
            'paragraphs' => $paragraph_count
        ];
    } else {
        $error = 'Please enter some text to analyze.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-text-left me-2"></i>Word and Character Counter</h5>
        <p class="card-text">Count words, characters, lines, and paragraphs in your text. Useful for content creation, academic writing, and meeting specific length requirements.</p>
        <form method="POST" action="?page=word_character_counter">
            <div class="mb-3">
                <label for="text" class="form-label">Text Input</label>
                <textarea class="form-control" id="text" name="text" rows="10" required><?php echo htmlspecialchars($_POST['text'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Count</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Analysis Result</label>
                <div class="input-group">
                    <textarea class="form-control" id="wordCharCounterResult" rows="8" readonly><?php 
                        echo "Words: " . $result['words'] . "\n";
                        echo "Characters (with spaces): " . $result['characters'] . "\n";
                        echo "Characters (without spaces): " . $result['characters_no_spaces'] . "\n";
                        echo "Lines: " . $result['lines'] . "\n";
                        echo "Paragraphs: " . $result['paragraphs'] . "\n";
                    ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="wordCharCounterResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
