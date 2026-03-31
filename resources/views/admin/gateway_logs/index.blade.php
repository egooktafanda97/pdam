@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Log Gateway (Webhook iPaymu)</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Reference ID</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>Raw Payload</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $log->reference_id ?? '-' }}</td>
                                    <td>{{ $log->transaction_id ?? '-' }}</td>
                                    <td>
                                        @if($log->status == 'berhasil')
                                            <span class="badge bg-success">Berhasil</span>
                                        @elseif($log->status == 'gagal')
                                            <span class="badge bg-danger">Gagal</span>
                                        @elseif($log->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $log->status ?? 'Unknown' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#payloadModal{{ $log->id }}">
                                            Lihat JSON
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="payloadModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Raw Payload JSON</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <pre><code class="json">{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada log webhook dari iPaymu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
