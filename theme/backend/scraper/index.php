<?php
$websettings = $this->db->get_where('websettings', ['id' => 1])->row();

// Helper functions for the view
function get_log_badge_class($type) {
    switch ($type) {
        case 'success': return 'success';
        case 'error': return 'danger';
        case 'warning': return 'warning';
        case 'info': 
        default: return 'info';
    }
}

function get_log_text_class($type) {
    switch ($type) {
        case 'success': return 'text-success';
        case 'error': return 'text-danger';
        case 'warning': return 'text-warning';
        case 'info':
        default: return 'text-dark';
    }
}

function format_interval($seconds) {
    if ($seconds < 60) return $seconds . ' seconds';
    if ($seconds < 3600) return ($seconds / 60) . ' minutes';
    if ($seconds < 86400) return ($seconds / 3600) . ' hours';
    return ($seconds / 86400) . ' days';
}
?>

<div class="content">
    <div class="container-fluid">
        
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Auto Update Scraper</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Auto Update Scraper</h4>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-television widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Total Anime">Total Anime</h5>
                        <h3 class="mt-3 mb-3"><?= number_format($stats['total_anime']) ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-play-circle widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Total Episodes">Total Episodes</h5>
                        <h3 class="mt-3 mb-3"><?= number_format($stats['total_episodes']) ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-clock widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Recent Additions (24h)">Recent (24h)</h5>
                        <h3 class="mt-3 mb-3"><?= $stats['recent_anime'] ?> / <?= $stats['recent_episodes'] ?></h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">Anime / Episodes</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-update widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Last Update">Last Update</h5>
                        <h3 class="mt-3 mb-3">
                            <?php if ($stats['last_update']): ?>
                                <?= date('M j, H:i', strtotime($stats['last_update'])) ?>
                            <?php else: ?>
                                Never
                            <?php endif; ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Settings Panel -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Scraper Settings</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/scraper/update_settings') ?>" method="post">
                            
                            <!-- Auto Update Toggle -->
                            <div class="mb-3">
                                <label class="form-label">Auto Update</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="auto_update_enabled" 
                                           name="auto_update_enabled" <?= $settings->auto_update_enabled ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="auto_update_enabled">
                                        Enable automatic updates
                                    </label>
                                </div>
                                <small class="text-muted">When enabled, scraper will run automatically based on the interval below.</small>
                            </div>

                            <!-- Update Interval -->
                            <div class="mb-3">
                                <label for="update_interval" class="form-label">Update Interval (seconds)</label>
                                <select class="form-select" id="update_interval" name="update_interval">
                                    <option value="1800" <?= $settings->update_interval == 1800 ? 'selected' : '' ?>>30 minutes</option>
                                    <option value="3600" <?= $settings->update_interval == 3600 ? 'selected' : '' ?>>1 hour</option>
                                    <option value="7200" <?= $settings->update_interval == 7200 ? 'selected' : '' ?>>2 hours</option>
                                    <option value="21600" <?= $settings->update_interval == 21600 ? 'selected' : '' ?>>6 hours</option>
                                    <option value="43200" <?= $settings->update_interval == 43200 ? 'selected' : '' ?>>12 hours</option>
                                    <option value="86400" <?= $settings->update_interval == 86400 ? 'selected' : '' ?>>24 hours</option>
                                </select>
                                <small class="text-muted">How often to check for updates when auto-update is enabled.</small>
                            </div>

                            <!-- Max Episodes -->
                            <div class="mb-3">
                                <label for="max_episodes_per_anime" class="form-label">Max Episodes per Anime</label>
                                <input type="number" class="form-control" id="max_episodes_per_anime" 
                                       name="max_episodes_per_anime" value="<?= $settings->max_episodes_per_anime ?>" 
                                       min="1" max="200">
                                <small class="text-muted">Maximum number of episodes to scrape per anime (to prevent timeouts).</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save Settings</button>
                        </form>
                    </div>
                </div>

                <!-- Manual Actions -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Manual Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin/scraper/run') ?>" class="btn btn-success">
                                <i class="mdi mdi-play me-1"></i> Run Scraper Now
                            </a>
                            
                            <a href="<?= base_url('admin/scraper/test_connection') ?>" class="btn btn-info">
                                <i class="mdi mdi-wifi me-1"></i> Test Connection
                            </a>
                            
                            <a href="<?= base_url('admin/scraper/clear_logs') ?>" class="btn btn-warning" 
                               onclick="return confirm('Are you sure you want to clear all logs?')">
                                <i class="mdi mdi-delete me-1"></i> Clear Logs
                            </a>
                        </div>

                        <!-- Cron Job Instructions -->
                        <div class="mt-4">
                            <h6 class="text-muted">Cron Job Setup</h6>
                            <p class="small text-muted mb-2">To enable automatic updates, add this to your crontab:</p>
                            <div class="bg-light p-2 rounded">
                                <code class="small">
                                    0,30 * * * * /usr/bin/php <?= FCPATH ?>index.php admin/scraper/auto_update
                                </code>
                            </div>
                            <small class="text-muted">This will check for updates every 30 minutes.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs Panel -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="header-title mb-0">Scraper Logs</h4>
                        <button class="btn btn-sm btn-outline-secondary" onclick="refreshLogs()">
                            <i class="mdi mdi-refresh"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="logs-container" style="height: 500px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 13px;">
                            <?php if (!empty($logs)): ?>
                                <?php foreach (array_reverse($logs) as $log): ?>
                                    <div class="log-entry mb-1">
                                        <span class="text-muted">[<?= date('Y-m-d H:i:s', strtotime($log->created_at)) ?>]</span>
                                        <span class="badge badge-<?= get_log_badge_class($log->type) ?> me-1"><?= strtoupper($log->type) ?></span>
                                        <span class="<?= get_log_text_class($log->type) ?>"><?= htmlspecialchars($log->message) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="mdi mdi-information-outline mdi-24px"></i>
                                    <p class="mt-2">No logs available. Run the scraper to see activity logs here.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">System Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Target Site:</strong></td>
                                        <td>https://v1.animasu.top/</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Auto Update Status:</strong></td>
                                        <td>
                                            <?php if ($settings->auto_update_enabled): ?>
                                                <span class="badge bg-success">Enabled</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Disabled</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Update Interval:</strong></td>
                                        <td><?= format_interval($settings->update_interval) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>PHP Version:</strong></td>
                                        <td><?= PHP_VERSION ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>cURL Available:</strong></td>
                                        <td>
                                            <?php if (function_exists('curl_init')): ?>
                                                <span class="badge bg-success">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">No</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>DOMDocument Available:</strong></td>
                                        <td>
                                            <?php if (class_exists('DOMDocument')): ?>
                                                <span class="badge bg-success">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">No</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.log-entry {
    line-height: 1.4;
    word-wrap: break-word;
}

.widget-icon {
    font-size: 1.5rem;
    color: #6c757d;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.7em;
}

code {
    font-size: 0.8em;
    word-break: break-all;
}

#logs-container {
    border: 1px solid #dee2e6;
}

.log-entry:hover {
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: 3px;
    padding: 2px 5px;
    margin: -2px -5px;
}
</style>

<script>
function refreshLogs() {
    location.reload();
}

// Auto-refresh logs every 30 seconds when scraper is running
let autoRefresh = false;
function toggleAutoRefresh() {
    autoRefresh = !autoRefresh;
    if (autoRefresh) {
        setInterval(function() {
            if (autoRefresh) {
                refreshLogs();
            }
        }, 30000);
    }
}

// Scroll to bottom of logs
document.addEventListener('DOMContentLoaded', function() {
    const logsContainer = document.getElementById('logs-container');
    logsContainer.scrollTop = logsContainer.scrollHeight;
});
</script>
