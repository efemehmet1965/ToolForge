<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $robots_content = $_POST['robots_content'] ?? '';

    if (empty($robots_content)) {
        $error = 'Robots.txt content cannot be empty.';
    } else {
        $lines = explode("\n", $robots_content);
        $errors = [];
        $warnings = [];
        $user_agent_found = false;

        foreach ($lines as $line_num => $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue; // Skip empty lines and comments
            }

            if (preg_match('/^(User-agent|Allow|Disallow|Sitemap):\s*(.*)$/i', $line, $matches)) {
                $directive = strtolower($matches[1]);
                $value = trim($matches[2]);

                if ($directive === 'user-agent') {
                    $user_agent_found = true;
                } elseif (!$user_agent_found) {
                    $errors[] = "Line " . ($line_num + 1) . ": '{$directive}' directive found before any User-agent.";
                }

                if (empty($value) && ($directive === 'allow' || $directive === 'disallow')) {
                    $warnings[] = "Line " . ($line_num + 1) . ": '{$directive}' directive has an empty value. This might not work as expected.";
                }
            } else {
                $errors[] = "Line " . ($line_num + 1) . ": Invalid robots.txt directive: '{$line}'.";
            }
        }

        if (!$user_agent_found) {
            $warnings[] = 'No User-agent directive found. This robots.txt might not be effective.';
        }

        if (empty($errors) && empty($warnings)) {
            $result = 'Robots.txt syntax is valid.';
        } else {
            $result = 'Robots.txt validation completed with issues.';
            if (!empty($errors)) {
                $result .= "\n\nErrors:\n" . implode("\n", $errors);
            } 
            if (!empty($warnings)) {
                $result .= "\n\nWarnings:\n" . implode("\n", $warnings);
            }
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-robot me-2"></i>Robots.txt Validator</h5>
        <p class="card-text">Validate the syntax and structure of your robots.txt file to ensure proper search engine crawling.</p>
        <form method="POST" action="?page=robots_txt_validator">
            <div class="mb-3">
                <label for="robots_content" class="form-label">Robots.txt Content</label>
                <textarea class="form-control" id="robots_content" name="robots_content" rows="10" required><?php echo htmlspecialchars($_POST['robots_content'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Validate robots.txt</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Validation Result</label>
                <div class="input-group">
                    <textarea class="form-control" id="robotsValidatorResult" rows="10" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="robotsValidatorResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>