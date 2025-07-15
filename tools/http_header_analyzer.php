<?php
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url'] ?? '');
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Using get_headers with context to set a timeout
        $context = stream_context_create([
            'http' => [
                'timeout' => 5, // 5 seconds timeout
            ],
        ]);
        $headers = @get_headers($url, 1, $context);
        
        if ($headers) {
            $result = $headers;
        } else {
            $error = 'Could not retrieve headers from the URL. Make sure the URL is accessible or try a different URL.';
        }
    } else {
        $error = 'Please enter a valid URL.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-file-earmark-code me-2"></i>HTTP Header Analyzer</h5>
        <p class="card-text">Enter a URL to view its HTTP response headers. This can help in debugging, security analysis, and understanding server configurations.</p>
        <form method="POST" action="?page=http_header_analyzer">
            <div class="mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="url" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>" placeholder="https://example.com" required>
            </div>
            <button type="submit" class="btn btn-primary">Analyze Headers</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">HTTP Headers for <?php echo htmlspecialchars($_POST['url'] ?? ''); ?></label>
                <div class="input-group">
                    <textarea class="form-control" id="httpHeadersResult" rows="10" readonly><?php 
                        $header_output = '';
                        foreach($result as $key => $value) {
                            $header_output .= htmlspecialchars(is_numeric($key) ? 'Status' : $key) . ': ' . htmlspecialchars(is_array($value) ? implode(', ', $value) : $value) . "\n";
                        }
                        echo $header_output;
                    ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="httpHeadersResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
