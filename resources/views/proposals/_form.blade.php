<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Proposal Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4361ee;
            --success: #06d6a0;
            --warning: #ffd166;
            --info: #118ab2;
            --secondary: #6c757d;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            border-radius: 15px;
            background: linear-gradient(120deg, var(--primary), #3a0ca3);
            color: white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .form-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            padding: 18px 25px;
            font-weight: 700;
            font-size: 1.25rem;
            border: none;
        }

        .card-body {
            padding: 30px;
        }

        .form-control,
        .form-select {
            padding: 14px 16px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.2);
        }

        .form-control-lg {
            padding: 16px 18px;
            font-size: 1.05rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 4px;
            background-color: #e9ecef;
            z-index: 1;
        }

        .step {
            text-align: center;
            position: relative;
            z-index: 2;
            width: 20%;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 700;
            color: #6c757d;
            border: 3px solid white;
        }

        .step.active .step-circle {
            background-color: var(--primary);
            color: white;
        }

        .step.completed .step-circle {
            background-color: var(--success);
            color: white;
        }

        .step-text {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
        }

        .step.active .step-text {
            color: var(--primary);
        }

        .btn-submit {
            background: linear-gradient(120deg, var(--primary), #3a0ca3);
            border: none;
            padding: 14px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            flex: 1;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-cancel {
            background: linear-gradient(120deg, #6c757d, #495057);
            border: none;
            padding: 14px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
            flex: 1;
        }

        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(108, 117, 125, 0.4);
            background: linear-gradient(120deg, #5a6268, #343a40);
        }

        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: var(--primary);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .file-upload-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .file-input {
            display: none;
        }

        .form-section {
            margin-bottom: 25px;
        }

        .character-count {
            font-size: 0.85rem;
            color: #6c757d;
            text-align: right;
            margin-top: 5px;
        }

        .progress {
            height: 8px;
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .button-container {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .step-text {
                font-size: 0.75rem;
            }

            .card-body {
                padding: 20px;
            }

            .button-container {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="form-header">
            <h1><i class="bi bi-file-earmark-text me-2"></i>Project Proposal Submission</h1>
            <p>Submit your project proposal for review and funding consideration</p>
        </div>

        <div class="progress">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="step-indicator">
            <div class="step completed">
                <div class="step-circle">1</div>
                <div class="step-text">Overview</div>
            </div>
            <div class="step completed">
                <div class="step-circle">2</div>
                <div class="step-text">Organization</div>
            </div>
            <div class="step active">
                <div class="step-circle">3</div>
                <div class="step-text">Details</div>
            </div>
            <div class="step">
                <div class="step-circle">4</div>
                <div class="step-text">Attachments</div>
            </div>
            <!-- <div class="step">
                <div class="step-circle">5</div>
                <div class="step-text">Review</div>
            </div> -->
        </div>

        <form class="needs-validation" novalidate>
            <!-- Proposal Overview Card -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard-check fs-4"></i>
                    <span>Proposal Overview</span>
                </div>
                <div class="card-body">
                    <div class="form-section">
                        <label class="form-label">Proposal Title</label>
                        <input type="text" name="title" class="form-control form-control-lg"
                            value="{{ old('title', $proposal->title) }}"
                            placeholder="e.g. Community Health Initiative Program" required>
                        <div class="invalid-feedback">Please enter a proposal title.</div>
                    </div>

                    <!-- <div class="form-section">
            <label class="form-label">Submitted By</label>
            <input type="text" name="submitted_by" class="form-control form-control-lg"
                   value="{{ old('submitted_by', $proposal->submitted_by) }}"
                   placeholder="Your full name" required>
            <div class="invalid-feedback">Please enter your name.</div>
        </div> -->

                    <div class="form-section">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg"
                            value="{{ old('email', $proposal->email) }}"
                            placeholder="you@organization.com" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                </div>
            </div>

            <!-- Organization Info Card -->
            <div class="card">
                <div class="card-header bg-success text-white d-flex align-items-center gap-2">
                    <i class="bi bi-building fs-4"></i>
                    <span>Organization Info</span>
                </div>
                <div class="card-body">
                    <div class="form-section">
                        <label class="form-label">Organization Name</label>
                        <input type="text" name="organization_name" class="form-control form-control-lg"
                            value="{{ old('organization_name', $proposal->organization_name) }}"
                            placeholder="e.g. Global Health Foundation" required>
                        <div class="invalid-feedback">Please enter organization name.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-section">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control form-control-lg"
                                    value="{{ old('phone', $proposal->phone) }}"
                                    placeholder="(123) 456-7890" required>
                                <div class="invalid-feedback">Please enter phone number.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-section">
                                <label class="form-label">Organization Type</label>
                                <select name="organization_type" class="form-select form-select-lg" required>
                                    <option value="">Select type</option>
                                    @foreach(['Non-profit', 'Educational Institution', 'Government Agency', 'Private Company', 'Community Group'] as $type)
                                    <option value="{{ $type }}" {{ old('organization_type', $proposal->organization_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select organization type.</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Organization Address</label>
                        <input type="text" name="address" class="form-control form-control-lg"
                            value="{{ old('address', $proposal->address) }}"
                            placeholder="123 Main St, City, Country" required>
                        <div class="invalid-feedback">Please enter organization address.</div>
                    </div>
                </div>
            </div>
<!-- Proposal Details Card -->
<div class="card">
    <div class="card-header bg-warning text-dark d-flex align-items-center gap-2">
        <i class="bi bi-journal-text fs-4"></i>
        <span>Proposal Details</span>
    </div>
    <div class="card-body">

        <!-- 🆕 Project Category Selection -->
        <div class="form-section">
            <label class="form-label">Project Category</label>
            <select name="project_category_id" class="form-select" required>
                <option value="">-- Select Category --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('project_category_id', $proposal->project_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback">Please select a project category.</div>
        </div>

        <!-- Project Summary -->
        <div class="form-section">
            <label class="form-label">Project Summary</label>
            <textarea name="summary" class="form-control" rows="3" maxlength="200" required>{{ old('summary', $proposal->summary) }}</textarea>
            <div class="character-count">{{ strlen(old('summary', $proposal->summary)) }}/200 characters</div>
            <div class="invalid-feedback">Please enter a project summary.</div>
        </div>

        <div class="form-section">
            <label class="form-label">Background / Problem Statement</label>
            <textarea name="background" class="form-control" rows="4" required>{{ old('background', $proposal->background) }}</textarea>
            <div class="invalid-feedback">Please describe the problem statement.</div>
        </div>

        <div class="form-section">
            <label class="form-label">Proposal Goals</label>
            <textarea name="proposal_goals" class="form-control" rows="3" required>{{ old('proposal_goals', $proposal->proposal_goals) }}</textarea>
            <div class="invalid-feedback">Please enter proposal goals.</div>
        </div>

        <div class="form-section">
            <label class="form-label">Key Activities</label>
            <textarea name="activities" class="form-control" rows="3" required>{{ old('activities', $proposal->activities) }}</textarea>
            <div class="invalid-feedback">Please enter key activities.</div>
        </div>

        <div class="col-md-6">
            <div class="form-section">
                <label class="form-label">Proposed Budget</label>
                <div class="input-group input-group-lg">
                    <select name="currency" class="form-select" style="max-width: 120px;" required>
                        @foreach(['USD', 'EUR', 'GBP', 'KES', 'NGN', 'INR', 'ZAR'] as $currency)
                        <option value="{{ $currency }}" {{ old('currency', $proposal->currency) == $currency ? 'selected' : '' }}>
                            {{ $currency }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="budget" class="form-control"
                        value="{{ old('budget', $proposal->budget) }}"
                        placeholder="e.g. 10,000" required>
                </div>
                <div class="invalid-feedback">Please enter a budget amount.</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-section">
                <label class="form-label">Project Duration</label>
                <select name="duration" class="form-select form-select-lg" required>
                    <option value="">Select duration</option>
                    @foreach(['Less than 3 months', '3-6 months', '6-12 months', '1-2 years', 'More than 2 years'] as $duration)
                    <option value="{{ $duration }}" {{ old('duration', $proposal->duration) == $duration ? 'selected' : '' }}>
                        {{ $duration }}
                    </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select project duration.</div>
            </div>
        </div>

    </div>
</div>


    <!-- Attachments Card -->
    <div class="card">
        <div class="card-header bg-info text-white d-flex align-items-center gap-2">
            <i class="bi bi-paperclip fs-4"></i>
            <span>Attachments</span>
        </div>
        <div class="card-body">
            <div class="file-upload-area" id="dropArea">
                <i class="bi bi-cloud-arrow-up file-upload-icon"></i>
                <h5>Upload Proposal Documents</h5>
                <p class="text-muted">Drag & drop files here or click to browse</p>
                <p class="small text-muted">Supported formats: PDF, DOC, DOCX (Max size: 5MB each)</p>
                <input type="file" class="file-input" id="fileInput" name="attachments[]" multiple accept=".pdf,.doc,.docx">
            </div>
            <div class="mt-3" id="fileList">
                @if($proposal->attachments && is_array($proposal->attachments))
                <ul>
                    @foreach($proposal->attachments as $file)
                    <li>
                        <a href="{{ asset('storage/'.$file) }}" target="_blank">
                            {{ basename($file) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>


    <div class="button-container">
        <button type="button" class="btn btn-light btn-lg btn-cancel" id="cancelButton">
            <i class="bi bi-x-circle me-2"></i>Cancel & Return
        </button>

        <button type="submit" class="btn btn-primary btn-lg btn-submit">
            <i class="bi bi-send me-2"></i>Submit Proposal
        </button>
    </div>

    <div class="text-center mt-4 text-muted">
        <p>Your proposal will be reviewed within 5-7 business days. You'll receive an email notification once a decision has been made.</p>
    </div>
    </form>
    </div>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-lg rounded-lg border border-green-500 bg-green-100 text-green-800 px-6 py-4 my-4 relative" role="alert">
        <strong class="font-semibold">Success!</strong> {{ session('success') }}
        <button type="button" class="absolute top-2 right-4 text-green-700 hover:text-green-900" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.style.display='none';">
            ✖
        </button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-lg rounded-lg border border-red-500 bg-red-100 text-red-800 px-6 py-4 my-4 relative" role="alert">
        <strong class="font-semibold">Error!</strong> {{ session('error') }}
        <button type="button" class="absolute top-2 right-4 text-red-700 hover:text-red-900" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.style.display='none';">
            ✖
        </button>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Character counter for summary
        const summaryTextarea = document.querySelector('textarea[name="summary"]');
        const charCount = document.querySelector('.character-count');

        summaryTextarea.addEventListener('input', () => {
            const length = summaryTextarea.value.length;
            charCount.textContent = `${length}/200 characters`;
        });

        // File upload functionality
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');

        // Open file browser when the drop area is clicked
        dropArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Handle file selection
        fileInput.addEventListener('change', handleFiles);

        // Handle drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.style.borderColor = '#4361ee';
            dropArea.style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
        }

        function unhighlight() {
            dropArea.style.borderColor = '#dee2e6';
            dropArea.style.backgroundColor = '#f8f9fa';
        }

        function handleFiles(e) {
            const files = e.target.files || e.dataTransfer.files;
            if (files.length > 0) {
                fileList.innerHTML = '';

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File ${file.name} is too large (max 5MB)`);
                        continue;
                    }

                    const fileItem = document.createElement('div');
                    fileItem.className = 'alert alert-light d-flex justify-content-between align-items-center';
                    fileItem.innerHTML = `
                        <div>
                            <i class="bi bi-file-earmark-text me-2"></i>
                            ${file.name} (${formatFileSize(file.size)})
                        </div>
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    `;

                    const closeBtn = fileItem.querySelector('.btn-close');
                    closeBtn.addEventListener('click', () => {
                        fileItem.remove();
                    });

                    fileList.appendChild(fileItem);
                }
            }
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' bytes';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / 1048576).toFixed(1) + ' MB';
        }

        // Form validation
        (function() {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Cancel button functionality

        document.getElementById('cancelButton').addEventListener('click', function() {
            // Show confirmation dialog
            const confirmCancel = confirm('Are you sure you want to cancel? Any unsaved changes will be lost.');

            if (confirmCancel) {
                // Redirect to proposal.index page
                window.location.href = "{{ route('proposals.index') }}";
            }


            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => alert.style.display = 'none');
            }, 5000); // Hide after 5 seconds
        });
    </script>
</body>

</html>