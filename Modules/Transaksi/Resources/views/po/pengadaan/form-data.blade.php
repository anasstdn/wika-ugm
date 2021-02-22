<style>
  .borderless td, .borderless th {
    border: none;
}
</style>
<div class="table-responsive">
 <table class="table table-bordered table-striped table-vcenter">
  <tr>
    <th width="2%">No</th>
    <th >Tanggal Pembuatan</th>
    <th>User Pembuat</th>
    <th>Supplier</th>
    <th>Grand Total</th>
    <th>Aksi</th>
  </tr>
  <tbody>
    @php 
    $total = 0;
    @endphp
    @if(isset($data) && !$data->isEmpty())
    @foreach($data as $key=>$row)
    <tr>
     <td style="text-align: center">{{ $data->firstItem() + $key }}</td>
     <td>{{ date_indo(date('Y-m-d',strtotime($row->tgl_pembuatan))) }}</td>
     <td>
      @php
      $nama = null;
      $pembuat = \DB::table('user_profil')->where('user_id',$row->user_input)->first();

      if(isset($pembuat) && !empty($pembuat->profil_id))
      {
        $profil = \DB::table('profil')->find($pembuat->profil_id);
        $nama = $profil->nama;
      }
      else
      {
        $users = \DB::table('users')->find($row->user_input);
        $nama = $users->name;
      }
      @endphp
      {{ $nama }}
    </td>
    <td>{{ \App\Models\Supplier::find($row->supplier_id)->nama_supplier }}</td>
    <td>
      <table class="table borderless" width="100%">
        <tr>
          <td>Rp.</td>
          <td class="text-right">{{ number_format($row->total_harga,0,',','.') }}</td>
        </tr>
      </table> 
    </td>
    <td style="text-align: center">
     <a href="{{ url('po/'.$row->id.'/buat-po') }}" class="btn btn-sm btn-alt-success"><i class="fa fa-shopping-cart mr-5"></i> Buat PO</a>
    </label>
  </td>
</tr>
@php
$total += $row->total_harga;
@endphp
@endforeach
@else
<tr>
  <td colspan="9" style="text-align: center">Data Tidak Ditemukan</td>
</tr>
@endif
</tbody>
<tfoot>
  <tr>
    <td colspan="4" class="text-right"><b>TOTAL</b></td>
    <td>
      <b>
      <table class="table borderless" width="100%">
        <tr>
          <td>Rp.</td>
          <td class="text-right">{{ number_format($total,0,',','.') }}</td>
        </tr>
      </table>
      </b> 
    </td>
    <td></td>
  </tr>
</tfoot>
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