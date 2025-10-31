/**
 * Dashboard View
 * Place in application/views/dashboard/index.php
 */

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - File Approval System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card {
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .table-responsive {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #842029;
        }
        .activity-log {
            max-height: 400px;
            overflow-y: auto;
        }
        .activity-item {
            padding: 12px;
            border-left: 3px solid #667eea;
            background: white;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-file-earmark-check me-2"></i>File Approval System
            </span>
            <div class="d-flex align-items-center text-white">
                <i class="bi bi-person-circle me-2"></i>
                <span class="me-3"><?php echo $user['name']; ?> (<?php echo ucfirst($user['role']); ?>)</span>
                <a href="<?php echo site_url('logout'); ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-files text-primary" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0"><?php echo $stats['total']; ?></h3>
                        <p class="text-muted mb-0">Total Files</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0"><?php echo $stats['pending']; ?></h3>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0"><?php echo $stats['approved']; ?></h3>
                        <p class="text-muted mb-0">Approved</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0"><?php echo $stats['rejected']; ?></h3>
                        <p class="text-muted mb-0">Rejected</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Files Panel -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-folder2-open me-2"></i>
                            <?php echo $user['role'] === 'manager' ? 'All Files' : 'My Files'; ?>
                        </h5>
                        <?php if($user['role'] === 'employee'): ?>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-cloud-upload me-1"></i>Upload File
                        </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="filesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>File Name</th>
                                        <?php if($user['role'] === 'manager'): ?>
                                        <th>Uploaded By</th>
                                        <?php endif; ?>
                                        <th>Size</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($files)): ?>
                                    <tr>
                                        <td colspan="<?php echo $user['role'] === 'manager' ? '6' : '5'; ?>" class="text-center py-4">
                                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                            <p class="text-muted mt-2">No files found</p>
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach($files as $file): ?>
                                        <tr>
                                            <td>
                                                <i class="bi bi-file-earmark me-1"></i>
                                                <?php echo $file->file_name; ?>
                                            </td>
                                            <?php if($user['role'] === 'manager'): ?>
                                            <td><?php echo $file->uploader_name; ?></td>
                                            <?php endif; ?>
                                            <td><?php echo number_format($file->file_size); ?> KB</td>
                                            <td>
                                                <span class="status-badge status-<?php echo $file->status; ?>">
                                                    <?php echo ucfirst($file->status); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($file->created_at)); ?></td>
                                            <td>
                                                <?php if($user['role'] === 'manager' && $file->status === 'pending'): ?>
                                                <button class="btn btn-success btn-sm" onclick="approveFile(<?php echo $file->id; ?>)">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="showRejectModal(<?php echo $file->id; ?>)">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                                <?php endif; ?>
                                                <a href="<?php echo site_url('files/download/'.$file->id); ?>" 
                                                   class="btn btn-info btn-sm" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log Panel -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-activity me-2"></i>Activity Log
                        </h5>
                    </div>
                    <div class="card-body activity-log">
                        <?php foreach($logs as $log): ?>
                        <div class="activity-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?php echo $log->user_name; ?></strong>
                                    <p class="mb-0 small text-muted"><?php echo $log->description; ?></p>
                                </div>
                                <span class="badge bg-secondary small">
                                    <?php echo date('H:i', strtotime($log->created_at)); ?>
                                </span>
                            </div>
                            <small class="text-muted">
                                <?php echo date('M d, Y', strtotime($log->created_at)); ?>
                            </small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal (Employee Only) -->
    <?php if($user['role'] === 'employee'): ?>
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" 
                               value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="mb-3">
                            <label for="file" class="form-label">Select File</label>
                            <input type="file" class="form-control" id="file" name="file" 
                                   accept=".pdf,.jpg,.jpeg,.png,.docx" required>
                            <div class="form-text">
                                Allowed: PDF, JPG, PNG, DOCX (Max 3MB)
                            </div>
                        </div>
                        <div id="uploadProgress" class="d-none">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Reject Modal (Manager Only) -->
    <?php if($user['role'] === 'manager'): ?>
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm">
                    <div class="modal-body">
                        <input type="hidden" id="rejectFileId" name="file_id">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" 
                               value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="mb-3">
                            <label for="reason" class="form-label">Rejection Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const baseUrl = '<?php echo site_url(); ?>';
        const csrfToken = '<?php echo $this->security->get_csrf_hash(); ?>';
        
        // File Upload
        <?php if($user['role'] === 'employee'): ?>
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            $('#uploadProgress').removeClass('d-none');
            
            $.ajax({
                url: baseUrl + '/files/upload',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const data = JSON.parse(response);
                    if(data.success) {
                        alert('File uploaded successfully!');
                        $('#uploadModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                    $('#uploadProgress').addClass('d-none');
                },
                error: function() {
                    alert('Upload failed. Please try again.');
                    $('#uploadProgress').addClass('d-none');
                }
            });
        });
        <?php endif; ?>
        
        // Approve File
        <?php if($user['role'] === 'manager'): ?>
        function approveFile(fileId) {
            if(!confirm('Are you sure you want to approve this file?')) return;
            
            $.ajax({
                url: baseUrl + '/files/approve/' + fileId,
                type: 'POST',
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': csrfToken
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if(data.success) {
                        alert('File approved successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function() {
                    alert('Action failed. Please try again.');
                }
            });
        }
        
        function showRejectModal(fileId) {
            $('#rejectFileId').val(fileId);
            $('#rejectModal').modal('show');
        }
        
        $('#rejectForm').on('submit', function(e) {
            e.preventDefault();
            
            const fileId = $('#rejectFileId').val();
            const reason = $('#reason').val();
            
            $.ajax({
                url: baseUrl + '/files/reject/' + fileId,
                type: 'POST',
                data: {
                    reason: reason,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': csrfToken
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if(data.success) {
                        alert('File rejected successfully!');
                        $('#rejectModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function() {
                    alert('Action failed. Please try again.');
                }
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>