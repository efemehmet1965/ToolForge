<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $url = trim($_POST['url'] ?? '');

    if (empty($title) || empty($description) || empty($url)) {
        $error = 'All fields are required.';
    } else {
        $result = [
            'title' => $title,
            'description' => $description,
            'url' => $url
        ];
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-google me-2"></i>Google SERP Simulator</h5>
        <p class="card-text">See how your title, description, and URL will appear in Google search results.</p>
        <form method="POST" action="?page=serp_simulator">
            <div class="mb-3">
                <label for="title" class="form-label">Title (Max 60 characters)</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" maxlength="60" required>
                <div class="form-text">Characters: <span id="title_char_count">0</span></div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description (Max 160 characters)</label>
                <textarea class="form-control" id="description" name="description" rows="3" maxlength="160" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                <div class="form-text">Characters: <span id="description_char_count">0</span></div>
            </div>
            <div class="mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="url" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>" placeholder="https://example.com/your-page" required>
            </div>
            <button type="submit" class="btn btn-primary">Simulate SERP</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">SERP Preview</label>
                <div class="serp-preview p-3 border rounded bg-light text-dark">
                    <h6 class="text-primary mb-0"><?php echo htmlspecialchars($result['url']); ?></h6>
                    <h4 class="text-info mb-1"><?php echo htmlspecialchars($result['title']); ?></h4>
                    <p class="text-secondary mb-0"><?php echo htmlspecialchars($result['description']); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const titleCharCount = document.getElementById('title_char_count');
        const descriptionInput = document.getElementById('description');
        const descriptionCharCount = document.getElementById('description_char_count');

        function updateCharCount(input, counter) {
            counter.textContent = input.value.length;
        }

        titleInput.addEventListener('input', () => updateCharCount(titleInput, titleCharCount));
        descriptionInput.addEventListener('input', () => updateCharCount(descriptionInput, descriptionCharCount));

        // Initial count
        updateCharCount(titleInput, titleCharCount);
        updateCharCount(descriptionInput, descriptionCharCount);
    });
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
