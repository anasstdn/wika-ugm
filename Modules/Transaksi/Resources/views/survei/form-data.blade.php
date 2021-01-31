<div class="table-responsive">
 <table class="table table-bordered table-striped table-vcenter">
  <tr>
    <th width="2%">No</th>
    <th>No SPM</th>
    <th >Tanggal Pengajuan</th>
    <th >Tanggal Penggunaan</th>
    <th>Material</th>
    <th>Qty</th>
    <th>Satuan</th>
    <th>Keterangan</th>
    <th>Check</th>
  </tr>
  <tbody>
    @if(isset($data) && !$data->isEmpty())
    @foreach($data as $key=>$row)
    <tr>
     <td style="text-align: center">{{ $data->firstItem() + $key }}</td>
     <td>{{ $row->no_spm }}</td>
     <td>{{ date_indo(date('Y-m-d',strtotime($row->tgl_spm))) }}</td>
     <td>{{ date_indo(date('Y-m-d',strtotime($row->tgl_penggunaan))) }}</td>
     <td>{{ $row->kode_material }}&nbsp-&nbsp{{ $row->material }}</td>
     <td>{{ $row->volume }}</td>
     <td>{{ $row->satuan }}</td>
     <td>{{ $row->keterangan }}</td>
     <td style="text-align: center">
       <label class="css-control css-control-primary css-checkbox">
        <input type="checkbox" class="css-control-input" name="check[]" value="{{ $row->id }}">
        <span class="css-control-indicator"></span>
      </label>
    </td>
  </tr>
  @endforeach
  @else
  <tr>
    <td colspan="9" style="text-align: center">Data Tidak Ditemukan</td>
  </tr>
  @endif
</tbody>
</table>

<?php
// config
$link_limit = 5; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if (isset($data) && $data->lastPage() > 1)
<nav aria-label="Page navigation">
  <ul class="pagination pagination-sm">
    <li class="page-item {{ ($data->currentPage() == 1) ? ' disabled' : '' }}">
      <a class="page-link" href="{{ $data->url(1) }}"><<</a>
    </li>
    @for ($i = 1; $i <= $data->lastPage(); $i++)
    <?php
    $half_total_links = floor($link_limit / 2);
    $from = $data->currentPage() - $half_total_links;
    $to = $data->currentPage() + $half_total_links;
    if ($data->currentPage() < $half_total_links) {
     $to += $half_total_links - $data->currentPage();
   }
   if ($data->lastPage() - $data->currentPage() < $half_total_links) {
    $from -= $half_total_links - ($data->lastPage() - $data->currentPage()) - 1;
  }
  ?>
  @if ($from < $i && $i < $to)
  <li class="page-item {{ ($data->currentPage() == $i) ? ' active' : '' }}">
    <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
  </li>
  @endif
  @endfor
  <li class="page-item {{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }}">
    <a class="page-link" href="{{ $data->url($data->lastPage()) }}">>></a>
  </li>
</ul>
</nav>
@endif

</div>