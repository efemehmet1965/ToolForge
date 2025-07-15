<?php
$tools = [
    'ping_tool' => ['icon' => 'bi-arrow-left-right', 'name' => 'Ping Tool', 'desc' => 'Check host reachability and latency.'],
    'traceroute_tool' => ['icon' => 'bi-diagram-3', 'name' => 'Traceroute Tool', 'desc' => 'Trace the network path to a host.'],
    'subdomain_finder' => ['icon' => 'bi-globe', 'name' => 'Subdomain Finder', 'desc' => 'Discover subdomains for a given domain.']
];
?>
<h2 class="mb-4">Network Tools</h2>
<p class="lead mb-4">Diagnose network issues, analyze connectivity, and explore network infrastructure with our specialized tools. From basic ping to advanced subdomain discovery.</p>
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
