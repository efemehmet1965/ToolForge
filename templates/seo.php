<?php
$tools = [
    'meta_analyzer' => ['icon' => 'bi-card-checklist', 'name' => 'Meta Tag Analyzer', 'desc' => 'Analyze meta tags of a webpage.'],
    'keyword_density' => ['icon' => 'bi-file-earmark-text', 'name' => 'Keyword Density Analyzer', 'desc' => 'Analyze keyword frequency in a text.'],
    'word_character_counter' => ['icon' => 'bi-text-left', 'name' => 'Word and Character Counter', 'desc' => 'Count words, characters, lines, and paragraphs.'],
    'robots_txt_generator' => ['icon' => 'bi-robot', 'name' => 'Robots.txt Generator', 'desc' => 'Generate a robots.txt file.'],
    'url_redirect_checker' => ['icon' => 'bi-arrow-repeat', 'name' => 'URL Redirect Checker', 'desc' => 'Trace URL redirects and see the full chain.'],
    'html_minifier' => ['icon' => 'bi-filetype-html', 'name' => 'HTML Minifier', 'desc' => 'Minify HTML code to reduce file size.'],
    'serp_simulator' => ['icon' => 'bi-google', 'name' => 'Google SERP Simulator', 'desc' => 'Simulate Google search results appearance.'],
    'broken_link_checker' => ['icon' => 'bi-link-break', 'name' => 'Broken Link Checker', 'desc' => 'Scan a webpage for broken (404) links.'],
    'sitemap_generator' => ['icon' => 'bi-sitemap-fill', 'name' => 'Sitemap Generator', 'desc' => 'Generate an XML sitemap for your website.']
];
?>
<h2 class="mb-4">SEO Tools</h2>
<p class="lead mb-4">Boost your website's visibility and search engine ranking with our essential SEO tools. From meta tag analysis to keyword density checks, optimize your content effectively.</p>
<div class="row">
    <?php foreach ($tools as $page => $details): ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <a href="index.php?page=<?php echo $page; ?>" class="text-decoration-none">
            <div class="card h-100 p-2">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi <?php echo $details['icon']; ?> me-2"></i><?php echo $details['name']; ?></h5>
                    <p class="card-text"><?php echo $details['desc']; ?></p>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>