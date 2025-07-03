?

        <!-- Proposal Details -->
        <div class="card-body">
            <div class="row g-4 mb-4">
                <!-- Timeline Progress -->
                <div class="col-12">
                    <div class="timeline-steps">
                        @foreach([1 => 'Submission', 2 => 'Review', 3 => 'Approval'] as $stage => $label)
                        <div class="timeline-step {{ $proposal->stage >= $stage ? 'active' : '' }}">
                            <div class="timeline-icon">
                                @if($proposal->stage > $stage)
                                <i class="bi bi-check-circle-fill"></i>
                                @else
                                {{ $stage }}
                                @endif
                            </div>
                            <div class="timeline-label">{{ $label }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="detail-card bg-light p-3 rounded-3 h-100">
                        <h5 class="text-primary mb-3">
                            <i class="bi bi-building me-2"></i>Organization Details
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Submitted By</dt>
                            <dd class="col-sm-7">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-initial bg-primary text-white rounded-circle">
                                            {{ strtoupper(substr($proposal->user?->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div>{{ $proposal->user?->name ?? 'Unknown User' }}</div>
                                        <small class="text-muted">{{ $proposal->user?->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </dd>

                            <dt class="col-sm-5">Submission Date</dt>
                            <dd class="col-sm-7">
                                <i class="bi bi-calendar me-1"></i>
                               {{ $proposal->created_at ? $proposal->created_at->format('d M Y') : 'N/A' }}

                            </dd>

                            <dt class="col-sm-5">Last Updated</dt>
                            <dd class="col-sm-7">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $proposal->updated_at ? $proposal->updated_at->format('d M Y, H:i') : 'N/A' }}

                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="detail-card bg-light p-3 rounded-3 h-100">
                        <h5 class="text-primary mb-3">
                            <i class="bi bi-card-checklist me-2"></i>Proposal Summary
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Current Status</dt>
                            <dd class="col-sm-7">
                                @switch($proposal->status)
                                    @case('accepted') 
                                        <span class="badge bg-success-subtle text-success rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i> Accepted
                                        </span> @break
                                    @case('rejected') 
                                        <span class="badge bg-danger-subtle text-danger rounded-pill">
                                            <i class="bi bi-x-circle me-1"></i> Rejected
                                        </span> @break
                                    @default 
                                        <span class="badge bg-warning-subtle text-warning rounded-pill">
                                            <i class="bi bi-clock-history me-1"></i> Pending
                                        </span>
                                @endswitch
                            </dd>

                            <dt class="col-sm-5">Budget Estimate</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-primary-subtle text-primary rounded-pill">
                                    ${{ number_format($proposal->budget, 2) }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Proposal Content -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="detail-card bg-light p-3 rounded-3">
                        <h5 class="text-primary mb-3">
                            <i class="bi bi-file-text me-2"></i>Proposal Details
                        </h5>
                        <div class="content-scroll" style="max-height: 300px; overflow-y: auto;">
                            <h6 class="text-uppercase text-muted small mb-3">Background</h6>
                            <div class="proposal-content bg-white p-3 rounded-2 mb-4">
                                {{ $proposal->background }}
                            </div>

                            <h6 class="text-uppercase text-muted small mb-3">Planned Activities</h6>
                            <div class="proposal-content bg-white p-3 rounded-2">
                                {{ $proposal->activities }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <form action="{{ route('admin.proposals.review', $proposal->id) }}" method="POST" class="border-top pt-4">
                @csrf

                <h5 class="text-primary mb-4">
                    <i class="bi bi-clipboard-check me-2"></i>Review Actions
                </h5>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="stage" class="form-select" id="stageSelect">
                                @for ($i = 1; $i <= 3; $i++)
                                    <option value="{{ $i }}" {{ $proposal->stage == $i ? 'selected' : '' }}>
                                        Stage {{ $i }} - {{ ['Submission', 'Evaluation', 'Approval'][$i-1] }}
                                    </option>
                                @endfor
                            </select>
                            <label for="stageSelect">
                                <i class="bi bi-speedometer2 me-1"></i>Progress Stage
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="status" class="form-select" id="statusSelect">
                                <option value="" {{ !$proposal->status ? 'selected' : '' }}>Pending Review</option>
                                <option value="accepted" {{ $proposal->status == 'accepted' ? 'selected' : '' }}>
                                    ✅ Accept Proposal
                                </option>
                                <option value="rejected" {{ $proposal->status == 'rejected' ? 'selected' : '' }}>
                                    ❌ Reject Proposal
                                </option>
                            </select>
                            <label for="statusSelect">
                                <i class="bi bi-clipboard-x me-1"></i>Review Decision
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <textarea name="comments" id="comments" class="form-control" 
                                      style="height: 100px" placeholder=" "></textarea>
                            <label for="comments">
                                <i class="bi bi-chat-left-text me-1"></i>Review Comments
                            </label>
                            <small class="text-muted">Optional remarks or feedback</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 border-top pt-4">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-success px-4 rounded-pill">
                        <i class="bi bi-check-circle me-1"></i>Save Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
    }

    .timeline-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        padding: 1rem 0;
    }

    .timeline-step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .timeline-step:not(:last-child):after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background: #e9ecef;
        top: 20%;
        left: 50%;
        z-index: 0;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .timeline-step.active .timeline-icon {
        background: #0d6efd;
        color: white;
    }

    .proposal-content {
        white-space: pre-wrap;
        line-height: 1.6;
    }

    .content-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .content-scroll::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .avatar-initial {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }

    .detail-card {
        transition: transform 0.2s ease;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .detail-card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection  -->