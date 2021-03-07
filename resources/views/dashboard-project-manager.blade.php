<h5>Surat Permohonan Material (SPM)</h5><hr/>
<div class="row gutters-tiny push">
    <div class="col-6 col-md-4 col-xl-3">
        <a class="block block-rounded block-bordered block-link-shadow text-center ribbon ribbon-primary" href="{{ url('verifikasi-spm') }}">
            <div class="ribbon-box">{{ dashboard_project_manager()['total_all_spm'] }}</div>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-folder fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Total Pengajuan SPM</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <a class="block block-rounded block-bordered block-link-shadow ribbon ribbon-warning text-center" href="{{ url('verifikasi-spm') }}">
            <div class="ribbon-box">{{ dashboard_project_manager()['total_belum_diverif'] }}</div>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-docs fa-3x text-muted"></i>
                </p>
                <p class="font-w600">SPM Belum Diverifikasi</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <a class="block block-rounded block-bordered block-link-shadow ribbon ribbon-success text-center" href="{{ url('verifikasi-spm') }}">
            <div class="ribbon-box">{{ dashboard_project_manager()['total_verif_diterima'] }}</div>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-docs fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Verifikasi SPM Diterima</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <a class="block block-rounded block-bordered block-link-shadow ribbon ribbon-danger text-center" href="{{ url('verifikasi-spm') }}">
            <div class="ribbon-box">{{ dashboard_project_manager()['total_verif_ditolak'] }}</div>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-docs fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Verifikasi SPM Ditolak</p>
            </div>
        </a>
    </div>
</div>
<h5>Purchase Order (PO)</h5><hr/>
<div class="row gutters-tiny push">
    <div class="col-6 col-md-4 col-xl-3">
        <a class="block block-rounded block-bordered block-link-shadow text-center ribbon ribbon-primary" href="{{ url('verifikasi-po') }}">
            <div class="ribbon-box">{{ dashboard_project_manager()['total_all_po'] }}</div>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-folder fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Total Pengajuan Purchase Order</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <a class="block block-rounded block-bordered block-link-shadow ribbon ribbon-warning text-center" href="{{ url('verifikasi-po') }}">
            <div class="ribbon-box">{{ dashboard_project_manager()['total_po_belum_verif'] }}</div>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-docs fa-3x text-muted"></i>
                </p>
                <p class="font-w600">PO Belum Diverifikasi</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <a class="block block-rounded block-bordered block-link-shadow ribbon ribbon-success text-center" href="{{ url('verifikasi-po') }}">
            <div class="ribbon-box">{{ dashboard_project_manager()['total_po_verif_diterima'] }}</div>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-docs fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Verifikasi PO Diterima</p>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <a class="block block-rounded block-bordered block-link-shadow ribbon ribbon-danger text-center" href="{{ url('verifikasi-po') }}">
            <div class="ribbon-box">{{ dashboard_project_manager()['total_po_verif_ditolak'] }}</div>
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-docs fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Verifikasi PO Ditolak / Dibatalkan</p>
            </div>
        </a>
    </div>
</div>
