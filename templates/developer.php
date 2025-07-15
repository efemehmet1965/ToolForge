<?php
$tools = [
    'base64' => ['icon' => 'bi-braces-asterisk', 'name' => 'Base64 Encoder/Decoder', 'desc' => 'Encode or decode Base64 strings.'],
    'json_validator' => ['icon' => 'bi-filetype-json', 'name' => 'JSON Validator & Formatter', 'desc' => 'Validate and format JSON data.'],
    'url_encoder' => ['icon' => 'bi-link-45deg', 'name' => 'URL Encoder/Decoder', 'desc' => 'Encode or decode strings for URLs.'],
    'unix_timestamp_converter' => ['icon' => 'bi-clock-history', 'name' => 'Unix Timestamp Converter', 'desc' => 'Convert between date/time and Unix timestamps.'],
    'hash_generator' => ['icon' => 'bi-hash', 'name' => 'MD5 / SHA1 Hash Generator', 'desc' => 'Generate MD5 or SHA1 hash from text.'],
    'number_base_converter' => ['icon' => 'bi-calculator', 'name' => 'Number Base Converter', 'desc' => 'Convert numbers between different bases.'],
    'lorem_ipsum_generator' => ['icon' => 'bi-file-text', 'name' => 'Lorem Ipsum Generator', 'desc' => 'Generate placeholder text for designs.'],
    'cron_job_generator' => ['icon' => 'bi-calendar-check', 'name' => 'CRON Job Expression Generator', 'desc' => 'Generate CRON job expressions for scheduling tasks.'],
    'regex_tester' => ['icon' => 'bi-regex', 'name' => 'Regex Tester', 'desc' => 'Test regular expressions against a given text.'],
    'qr_code_generator' => ['icon' => 'bi-qr-code', 'name' => 'QR Code Generator', 'desc' => 'Generate QR codes from text or URLs.']
];
?>
<h2 class="mb-4">Developer Tools</h2>
<p class="lead mb-4">Streamline your coding workflow with our versatile developer tools. From data encoding to format validation, simplify your daily programming tasks.</p>
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