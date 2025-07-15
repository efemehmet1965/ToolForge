<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url'] ?? '');

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $broken_links = [];
        $html = @file_get_contents($url);

        if ($html) {
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);

            $links = $xpath->query('//a[@href]');
            foreach ($links as $link_node) {
                $href = $link_node->getAttribute('href');
                $full_url = parse_url($href, PHP_URL_SCHEME) === null ? rtrim($url, '/') . '/' . ltrim($href, '/') : $href;
                
                // Skip mailto, tel, javascript links
                if (strpos($full_url, 'mailto:') === 0 || strpos($full_url, 'tel:') === 0 || strpos($full_url, 'javascript:') === 0) {
                    continue;
                }

                // Check if the link is reachable
                $headers = @get_headers($full_url);
                if (!$headers || strpos($headers[0], '200 OK') === false) {
                    $broken_links[] = ['link' => $href, 'status' => ($headers ? $headers[0] : 'Unreachable')];
                }
            }
            $result = $broken_links;
        } else {
            $error = 'Could not fetch content from the URL. Make sure the URL is accessible.';
        }
    } else {
        $error = 'Please enter a valid URL.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-link-break me-2"></i>Broken Link Checker</h5>
        <p class="card-text">Scan a webpage for broken (404) links. Essential for maintaining SEO and user experience.</p>
        <form method="POST" action="?page=broken_link_checker">
            <div class="mb-3">
                <label for="url" class="form-label">URL to Scan</label>
                <input type="url" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>" placeholder="https://example.com" required>
            </div>
            <button type="submit" class="btn btn-primary">Check Links</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Broken Links Found</label>
                <?php if (empty($result)): ?>
                    <div class="alert alert-success">No broken links found on this page!</div>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach($result as $link): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Link: <?php echo htmlspecialchars($link['link']); ?></div>
                                    Status: <?php echo htmlspecialchars($link['status']); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
