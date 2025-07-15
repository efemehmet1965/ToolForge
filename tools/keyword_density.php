<?php
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = strtolower(trim($_POST['text'] ?? ''));
    if (!empty($text)) {
        $words = str_word_count($text, 1);
        $total_words = count($words);
        if ($total_words > 0) {
            $word_counts = array_count_values($words);
            arsort($word_counts);
            $density = [];
            foreach ($word_counts as $word => $count) {
                $density[$word] = [
                    'count' => $count,
                    'percentage' => round(($count / $total_words) * 100, 2)
                ];
            }
            $result = array_slice($density, 0, 20); // Show top 20
        } else {
            $error = 'No words found to analyze.';
        }
    } else {
        $error = 'Please enter some text to analyze.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-file-earmark-text me-2"></i>Keyword Density Analyzer</h5>
        <p class="card-text">Paste your text to analyze keyword frequency and density. This helps in optimizing content for SEO by ensuring proper keyword distribution.</p>
        <form method="POST" action="?page=keyword_density">
            <div class="mb-3">
                <label for="text" class="form-label">Text Input</label>
                <textarea class="form-control" id="text" name="text" rows="10" required><?php echo htmlspecialchars($_POST['text'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Analyze</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Top 20 Keywords</label>
                <div class="input-group">
                    <textarea class="form-control" id="keywordDensityResult" rows="10" readonly><?php 
                        $density_output = '';
                        foreach($result as $word => $data) {
                            $density_output .= htmlspecialchars($word) . ': ' . $data['count'] . ' (' . $data['percentage'] . '%)' . "\n";
                        }
                        echo $density_output;
                    ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="keywordDensityResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
