<?php
// Fetch usage data from log file
$log_file = __DIR__ . '/../logs/usage.log';
$usage_data = [];
$recent_uses = [];
$total_uses = 0;

if (file_exists($log_file)) {
    $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_reverse($lines); // Get most recent first

    foreach ($lines as $line) {
        preg_match('/^\[(.*)\] (.*)$/', $line, $matches);
        if (count($matches) === 3) {
            $timestamp = $matches[1];
            $tool_name = $matches[2];

            // For most used tools
            if (!isset($usage_data[$tool_name])) {
                $usage_data[$tool_name] = 0;
            }
            $usage_data[$tool_name]++;
            $total_uses++;

            // For recent uses (limit to 10)
            if (count($recent_uses) < 10) {
                $recent_uses[] = ['tool_name' => $tool_name, 'timestamp' => $timestamp];
            }
        }
    }
    arsort($usage_data); // Sort by count, descending
}

?>

<h2 class="mb-4">Tool Usage Statistics</h2>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-bar-chart-fill me-2"></i>Most Used Tools</h5>
                <?php if (empty($usage_data)): ?>
                    <p class="card-text">No usage data available yet. Start using tools to see statistics!</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($usage_data as $tool_name => $count): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($tool_name); ?>
                                <span class="badge bg-primary rounded-pill"><?php echo $count; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-clock-history me-2"></i>Recent Tool Uses</h5>
                <?php if (empty($recent_uses)): ?>
                    <p class="card-text">No recent usage data available yet.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($recent_uses as $data): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($data['tool_name']); ?>
                                <small class="text-muted"><?php echo $data['timestamp']; ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body text-center">
        <h5 class="card-title"><i class="bi bi-activity me-2"></i>Total Tool Uses</h5>
        <p class="display-4 text-primary"><?php echo $total_uses; ?></p>
    </div>
</div>