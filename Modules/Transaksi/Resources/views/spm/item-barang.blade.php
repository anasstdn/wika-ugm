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
      <th class="text-center">Jumlah</th>
      <th class="text-center">Keterangan</th>
      <th class="text-center">Aksi</th>
    </tr>
  </thead>
  <tbody id="table-list-material-body">
  </tbody>
</table>

@push('js')
<script>
  const table_list_pegawai = $('#table-list-material');
  let flag_new_table = true;
  $(function(){
    $('#add_material').click(()=>{
      if(flag_new_table){
        flag_new_table = false;
      }
      add_row_to_table(table_list_pegawai);
    })

    $("#table-list-material-body").on( "click", ".delete-material-btn", function(e) {

      let row = $(this).closest('tr');
      remove_row_table(table_list_pegawai,row);

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
        <select class="form-control select keluar isi valid-material" name="material_id[]" data-plugin="select2">
        </select>
        <span class="help-block"></span>
        </div>
        </th>
        <th class="text-left">
        <div class="form-group">
        <input name="volume[]" class="form-control isi valid-material" value="" step="0.01" type="number" autocomplete="on">
        <span class="help-block"></span>
        </div>
        </th>
        <th class="text-left">
        <div class="form-group">
        <input name="keterangan[]" class="form-control isi valid-material" value="" type="text" autocomplete="on">
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

          $(".select").select2({
            dropdownParent: $("#form"),
            width: '100%',
            minimumInputLength: 1,
            ajax: {
              url: '{{ url('spm/material-search') }}',
              dataType: 'json',
            },
          });

          $('.valid-material').each(function(index,element){
            $(this).on('keypress change',function(){
              if($(this).hasClass('valid-material'))
              {
                if($(this).val() !== '')
                {
                  $('#simpan').html('Simpan').prop('disabled',false);
                }
              }
            })
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
            });    
          });
        };
</script>
@endpush