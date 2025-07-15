<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $minute = $_POST['minute'] ?? '*' ;
    $hour = $_POST['hour'] ?? '*' ;
    $day_of_month = $_POST['day_of_month'] ?? '*' ;
    $month = $_POST['month'] ?? '*' ;
    $day_of_week = $_POST['day_of_week'] ?? '*' ;

    $cron_expression = "{$minute} {$hour} {$day_of_month} {$month} {$day_of_week}";
    $result = $cron_expression;
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-calendar-check me-2"></i>CRON Job Expression Generator</h5>
        <p class="card-text">Easily generate CRON job expressions for scheduling tasks on your server.</p>
        <form method="POST" action="?page=cron_job_generator">
            <div class="mb-3">
                <label for="minute" class="form-label">Minute (0-59 or *)</label>
                <input type="text" class="form-control" id="minute" name="minute" value="<?php echo htmlspecialchars($_POST['minute'] ?? '* '); ?>" placeholder="*" required>
            </div>
            <div class="mb-3">
                <label for="hour" class="form-label">Hour (0-23 or *)</label>
                <input type="text" class="form-control" id="hour" name="hour" value="<?php echo htmlspecialchars($_POST['hour'] ?? '* '); ?>" placeholder="*" required>
            </div>
            <div class="mb-3">
                <label for="day_of_month" class="form-label">Day of Month (1-31 or *)</label>
                <input type="text" class="form-control" id="day_of_month" name="day_of_month" value="<?php echo htmlspecialchars($_POST['day_of_month'] ?? '* '); ?>" placeholder="*" required>
            </div>
            <div class="mb-3">
                <label for="month" class="form-label">Month (1-12 or *)</label>
                <input type="text" class="form-control" id="month" name="month" value="<?php echo htmlspecialchars($_POST['month'] ?? '* '); ?>" placeholder="*" required>
            </div>
            <div class="mb-3">
                <label for="day_of_week" class="form-label">Day of Week (0-7 or *)</label>
                <input type="text" class="form-control" id="day_of_week" name="day_of_week" value="<?php echo htmlspecialchars($_POST['day_of_week'] ?? '* '); ?>" placeholder="*" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate CRON Expression</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Generated CRON Expression</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="cronExpressionResult" value="<?php echo htmlspecialchars($result); ?>" readonly>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="cronExpressionResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
