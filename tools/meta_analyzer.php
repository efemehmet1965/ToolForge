<?php
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url'] ?? '');
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $html = @file_get_contents($url);
        if ($html) {
            $doc = new DOMDocument();
            @$doc->loadHTML($html);
            
            $meta_data = [
                'General' => [],
                'Open Graph' => [],
                'Twitter Card' => [],
                'Links' => []
            ];

            // Title Tag
            $title_nodes = $doc->getElementsByTagName('title');
            $meta_data['General']['Title'] = $title_nodes->length > 0 ? $title_nodes->item(0)->nodeValue : 'Not Found';

            // Meta Tags
            $metaTags = $doc->getElementsByTagName('meta');
            foreach ($metaTags as $meta) {
                $name = $meta->getAttribute('name');
                $property = $meta->getAttribute('property');
                $content = $meta->getAttribute('content');

                if (!empty($name)) {
                    $meta_data['General'][ucwords(str_replace('-', ' ', $name))] = $content;
                } elseif (!empty($property)) {
                    if (strpos($property, 'og:') === 0) {
                        $meta_data['Open Graph'][ucwords(str_replace('og:', '', $property))] = $content;
                    } elseif (strpos($property, 'twitter:') === 0) {
                        $meta_data['Twitter Card'][ucwords(str_replace('twitter:', '', $property))] = $content;
                    } else {
                        $meta_data['General'][ucwords(str_replace('-', ' ', $property))] = $content;
                    }
                }
            }

            // Canonical Link
            $link_nodes = $doc->getElementsByTagName('link');
            foreach ($link_nodes as $link) {
                if ($link->getAttribute('rel') === 'canonical') {
                    $meta_data['Links']['Canonical URL'] = $link->getAttribute('href');
                }
            }

            $result = $meta_data;
        } else {
            $error = 'Could not fetch content from the URL. Please ensure the URL is correct and accessible.';
        }
    } else {
        $error = 'Please enter a valid URL.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-card-checklist me-2"></i>Meta Tag Analyzer</h5>
        <p class="card-text">Enter a URL to analyze its meta tags. This helps in optimizing your webpage for search engines and social media sharing.</p>
        <form method="POST" action="?page=meta_analyzer">
            <div class="mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="url" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>" placeholder="https://example.com" required>
            </div>
            <button type="submit" class="btn btn-primary">Analyze</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Analysis Result for <?php echo htmlspecialchars($_POST['url'] ?? ''); ?></label>
                <div class="input-group">
                    <textarea class="form-control" id="metaAnalyzerResult" rows="15" readonly><?php 
                        $output = '';
                        foreach ($result as $category => $tags) {
                            if (!empty($tags)) {
                                $output .= "\n### {$category} ###\n";
                                foreach ($tags as $key => $value) {
                                    $output .= "{$key}: {$value}\n";
                                }
                            }
                        }
                        echo trim($output);
                    ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="metaAnalyzerResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>