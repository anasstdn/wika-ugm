  <div class="form-row">
    <div class="form-group col-12 text-right">
     <button id="add_material" type="button" class="btn btn-primary">Tambah</button>
   </div>
 </div>
 <table id="table-list-material"  class="table borderless table-hover table-striped table-bordered w-full">
  <thead>
    <tr>
      <th class="text-center">No</th>
      <th class="text-center" width="30%">Nama Material</th>
      <th class="text-center">Satuan</th>
      <th class="text-center">Spesifikasi</th>
      <th class="text-center">Jumlah</th>
      <th class="text-center">Tanggal Digunakan</th>
      <th class="text-center">Keterangan</th>
      <th class="text-center">Aksi</th>
    </tr>
  </thead>
  <tbody id="table-list-material-body">
    @if(isset($data_detail) && !$data_detail->isEmpty())
    @foreach($data_detail as $key => $value)
    <tr class="table-new-row-material" data-row-id-pegawai = "">
      <th class="text-center" style="text-align:center">
        <span class="isi number"></span>
        <input type="hidden" name="id_detail_spm[]" value="{{ $value->id }}">
      </th>
      <th class="text-left">
        <div class="form-group">
          <select class="form-control form-control-sm select keluar isi valid-material" name="material_id[]" data-plugin="select2">
            @if(isset($value->material_id) && !empty($value->material_id))
            <option value="{{ $value->material_id }}">{{ $value->material->kode_material }} - {{ $value->material->material }}</option>
            @endif
          </select>
          <span class="help-block"></span>
        </div>
      </th>
      <th class="text-center" style="text-align:center">
        <span class="isi satuan">{{ $value->material->satuan }}</span>
      </th>
      <th class="text-center" style="text-align:center">
        <span class="isi spesifikasi">{{ $value->material->spesifikasi }}</span>
      </th>
      <th class="text-left">
        <div class="form-group">
          <input name="volume[]" class="form-control form-control-sm isi valid-material" value="{{ $value->volume }}" step="0.01" type="number" autocomplete="on">
          <span class="help-block"></span>
        </div>
      </th>
      <th class="text-left">
        <div class="form-group">
          <input name="tgl_penggunaan[]" class="form-control form-control-sm isi valid-material datepicker" value="{{ date('d-m-Y',strtotime($value->tgl_penggunaan)) }}" type="text" autocomplete="on">
          <span class="help-block"></span>
        </div>
      </th>
      <th class="text-left">
        <div class="form-group">
          <input name="keterangan[]" class="form-control form-control-sm isi valid-material" value="{{ $value->keterangan }}" type="text" autocomplete="on">
          <span class="help-block"></span>
        </div>
      </th>
      <th class="text-center">
        <button type="button" id="{{ $value->id }}" class="btn btn-danger btn-icon btn-xs delete-material-btn" aria-label="Left Align">
          <span class="fa fa-trash" aria-hidden="true" data-id-pegawai=""></span>
        </button>
      </th>
    </tr>
    @endforeach
    @endif
  </tbody>
</table>

@push('js')
<script>
  const table_list_pegawai = $('#table-list-material');
  let flag_new_table = true;

  $.fn.hasAttr = function(name) {  
   return this.attr(name) !== undefined;
};

  $(function(){
    updateRows();

    $('#add_material').click(()=>{
      if(flag_new_table){
        flag_new_table = false;
      }
      add_row_to_table(table_list_pegawai);
    })

    $("#table-list-material-body").on( "click", ".delete-material-btn", function(e) {
      if(confirm('Apakah anda yakin untuk menghapus data ini?')) {
        if($(e.target).hasAttr('id')) {
          Codebase.layout('header_loader_on');
          $('#simpan').html('<i class="fa fa-circle-o-notch fa-spin"><i>').prop('disabled',true);
          $.ajax({
            url : '{{ url('spm/delete-detail-spm') }}',
            type: 'GET',
            dataType: "JSON",
            data:{
              "id" : $(e.target).attr('id')
            },
            success: function(data){
              if(data.status == true)
              {
                notification(data.msg,'sukses');
                Codebase.layout('header_loader_off');
                $('#simpan').html('Simpan').prop('disabled',false);
              }
              else
              {
                notification(data.msg,'gagal');
                Codebase.layout('header_loader_off');
                $('#simpan').html('Simpan').prop('disabled',false);
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.log(errorThrown);
            }
          })
        }

        let row = $(this).closest('tr');
        remove_row_table(table_list_pegawai,row);
      }
    });
  })

  const add_row_to_table = (table) => {
        row = `
        <tr class="table-new-row-material" data-row-id-pegawai = "">
        <th class="text-center" style="text-align:center">
        <span class="isi number"></span>
        </th>
        <th class="text-left">
        <div class="form-group">
        <select class="form-control form-control-sm select keluar isi valid-material" name="material_id[]" data-plugin="select2">
        </select>
        <span class="help-block"></span>
        </div>
        </th>
        <th class="text-center" style="text-align:center">
        <span class="isi satuan"></span>
        </th>
        <th class="text-center" style="text-align:center">
        <span class="isi spesifikasi"></span>
        </th>
        <th class="text-left">
        <div class="form-group">
        <input name="volume[]" class="form-control form-control-sm isi valid-material" value="" step="0.01" type="number" autocomplete="on">
        <span class="help-block"></span>
        </div>
        </th>
        <th class="text-left">
        <div class="form-group">
        <input name="tgl_penggunaan[]" class="form-control form-control-sm isi valid-material datepicker" value="" type="text" autocomplete="on">
        <span class="help-block"></span>
        </div>
        </th>
        <th class="text-left">
        <div class="form-group">
        <input name="keterangan[]" class="form-control form-control-sm isi valid-material" value="" type="text" autocomplete="on">
        <span class="help-block"></span>
        </div>
        </th>
        <th class="text-center">
        <button type="button" class="btn btn-danger btn-icon btn-xs delete-material-btn" aria-label="Left Align">
        <span class="fa fa-trash" aria-hidden="true" data-id-pegawai=""></span>
        </button>
        </th>
        </tr>
        `;

            let tbody = table.find("tbody").attr('id'); // tbody of table
            $('#'+tbody).append(row);
            updateRows();

            $('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            locale: 'id',
            autoclose: true,
            todayHighlight: true,
          }).on('change', function (ev) {
            // hitungUmur(count);
          });
          
        }

        const remove_row_table = (table,row = null) => {

            if(row){
                let tbody = row.parent();
                row.remove();
                if(tbody.children().length < 1) {
                    flag_new_table = true;
                } else {
                    updateRows();
                }
            } else {
                let row = table.find("tbody>tr");
                row.remove();
            }
        }

        function updateRows(){
          $('#table-list-material tbody tr').each( (tr_index,tr) =>{
            $(tr).children('th').each( (th_index, th) => {
              $(th).find('.number').html(tr_index + 1);

              $(".select").select2({
                dropdownParent: $("#form"),
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                  url: '{{ url('spm/material-search') }}',
                  dataType: 'json',
                },
              });

            });    
          });

          $('.valid-material').each(function(index,element){
            $(this).on('keypress change',function(){
              var element_satuan;
              var element_spesifikasi;
              if($(this).hasClass('valid-material'))
              {
                if($(this).val() !== '')
                {
                  $('#simpan').html('Simpan').prop('disabled',false);
                }
              }
              
              if($(this).hasClass('select'))
              {
                var formData = new FormData();
                formData.append('material_id', $(this).val());

                load_data_material('{{ url('spm/load-data-material') }}','POST',formData).then(function(result){
                  $(element).parent().parent().parent().find('.satuan').html(result.satuan);
                  $(element).parent().parent().parent().find('.spesifikasi').html(result.spesifikasi);
                })
              }
              
            })
          });
        };

        function load_data_material(url,method, params = {})
        {
          return new Promise((resolve, reject) => {
           $.ajax({
            url : url,
            type: method,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: "JSON",
            data:params,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
              resolve(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
              reject(errorThrown);
            }
          })
         })
        }
</script>
@endpush