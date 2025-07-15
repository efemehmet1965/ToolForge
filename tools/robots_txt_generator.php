<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_agents = $_POST['user_agents'] ?? [];
    $allow_paths = $_POST['allow_paths'] ?? [];
    $disallow_paths = $_POST['disallow_paths'] ?? [];
    $sitemap_url = trim($_POST['sitemap_url'] ?? '');

    $robots_content = "";

    if (empty($user_agents)) {
        $user_agents = ['*']; // Default to all user agents if none selected
    }

    foreach ($user_agents as $ua) {
        $robots_content .= "User-agent: {$ua}\n";
        foreach ($disallow_paths as $path) {
            $path = trim($path);
            if (!empty($path)) {
                $robots_content .= "Disallow: {$path}\n";
            }
        }
        foreach ($allow_paths as $path) {
            $path = trim($path);
            if (!empty($path)) {
                $robots_content .= "Allow: {$path}\n";
            }
        }
        $robots_content .= "\n";
    }

    if (!empty($sitemap_url) && filter_var($sitemap_url, FILTER_VALIDATE_URL)) {
        $robots_content .= "Sitemap: {$sitemap_url}\n";
    }

    $result = $robots_content;
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-robot me-2"></i>Robots.txt Generator</h5>
        <p class="card-text">Generate a robots.txt file to control search engine crawling. This file tells web robots which areas of your site they can or cannot crawl.</p>
        <form method="POST" action="?page=robots_txt_generator">
            <div class="mb-3">
                <label class="form-label">User-agents</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="user_agents[]" value="*" id="ua_all" checked>
                    <label class="form-check-label" for="ua_all">All (User-agent: *)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="user_agents[]" value="Googlebot" id="ua_googlebot">
                    <label class="form-check-label" for="ua_googlebot">Googlebot</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="user_agents[]" value="Bingbot" id="ua_bingbot">
                    <label class="form-check-label" for="ua_bingbot">Bingbot</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="disallow_paths" class="form-label">Disallow Paths (one per line)</label>
                <textarea class="form-control" id="disallow_paths" name="disallow_paths[]" rows="4" placeholder="/private/\n/admin/"><?php echo htmlspecialchars(implode("\n", $_POST['disallow_paths'] ?? [])); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="allow_paths" class="form-label">Allow Paths (one per line)</label>
                <textarea class="form-control" id="allow_paths" name="allow_paths[]" rows="4" placeholder="/public/images/"><?php echo htmlspecialchars(implode("\n", $_POST['allow_paths'] ?? [])); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="sitemap_url" class="form-label">Sitemap URL (optional)</label>
                <input type="url" class="form-control" id="sitemap_url" name="sitemap_url" value="<?php echo htmlspecialchars($_POST['sitemap_url'] ?? ''); ?>" placeholder="https://example.com/sitemap.xml">
            </div>

            <button type="submit" class="btn btn-primary">Generate robots.txt</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Generated robots.txt</label>
                <div class="input-group">
                    <textarea class="form-control" id="robotsTxtResult" rows="10" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="robotsTxtResult">Copy</button>
                </div>
                <a href="data:text/plain;charset=utf-8,<?php echo rawurlencode($result); ?>" download="robots.txt" class="btn btn-secondary mt-2">Download robots.txt</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
