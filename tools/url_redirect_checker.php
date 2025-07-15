<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url'] ?? '');
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $redirects = [];
        $current_url = $url;
        $max_redirects = 10; // Prevent infinite loops
        $count = 0;

        while ($current_url && $count < $max_redirects) {
            $headers = @get_headers($current_url, 1);
            if (!$headers) {
                $error = 'Could not fetch headers for ' . htmlspecialchars($current_url) . '.';
                break;
            }

            $status_line = $headers[0];
            preg_match('/^HTTP\/\d\.\d\s(\d{3})/', $status_line, $matches);
            $status_code = isset($matches[1]) ? $matches[1] : null;

            $location = isset($headers['Location']) ? (is_array($headers['Location']) ? end($headers['Location']) : $headers['Location']) : null;

            $redirects[] = [
                'url' => $current_url,
                'status' => $status_code,
                'location' => $location
            ];

            if ($status_code >= 300 && $status_code < 400 && $location) {
                $current_url = $location;
                $count++;
            } else {
                $current_url = null; // No more redirects
            }
        }

        if (!$error) {
            $result = $redirects;
        }

    } else {
        $error = 'Please enter a valid URL.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-arrow-repeat me-2"></i>URL Redirect Checker</h5>
        <p class="card-text">Trace URL redirects and see the full redirect chain. Essential for SEO to ensure proper link equity transfer and user experience.</p>
        <form method="POST" action="?page=url_redirect_checker">
            <div class="mb-3">
                <label for="url" class="form-label">URL to Check</label>
                <input type="url" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>" placeholder="https://example.com/old-page" required>
            </div>
            <button type="submit" class="btn btn-primary">Check Redirects</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Redirect Chain</label>
                <ul class="list-group">
                    <?php foreach($result as $index => $redirect): ?>
                        <li class="list-group-item">
                            <strong>Step <?php echo $index + 1; ?>:</strong><br>
                            URL: <?php echo htmlspecialchars($redirect['url']); ?><br>
                            Status: <?php echo htmlspecialchars($redirect['status']); ?>
                            <?php if ($redirect['location']): ?>
                                <i class="bi bi-arrow-right"></i> Redirects to: <?php echo htmlspecialchars($redirect['location']); ?><br>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
