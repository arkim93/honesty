$(document).ready(function() {
    var route = "/cekhakakses/ubah_member";
    var bolehUbah;
    
    RekeningOtomatis();

    var route1 = "/cekhakakses/hapus_member";
    var bolehHapus;
    $.get(route1, function (res) {
        bolehHapus = res;
    });
    $.get(route, function (res) {
            bolehUbah = res;
        });

    $('#dataTableBuilder').DataTable({
        responsive: true,
        'ajax': {
            'url': '/rekenings',
        },

        'columnDefs': [
            {
                'targets':0,
                'sClass': "text-center col-md-2"
            },{
                'targets':1,
                'sClass': "col-md-2"
            },{
                'targets': 2,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-2 td-aksi",
                'render': function (data, type, full, meta) {
                    var kembali = '';
                    
                    if (bolehHapus == true) {
                        kembali += '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash fa-fw"></i> </button>';
                    }
                    if (bolehUbah == true) {
                        kembali += '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="fa fa-pencil-square-o fa-fw"></i> </button>';
                    }

                    return kembali;

                }
            }
        ],
        'rowCallback': function (row, data, dataIndex) {
            if (bolehUbah == true) {
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[2]);
            }
            if (bolehHapus == true) {
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[2]);
            }

        }
    });

    document.getElementById("no_rek").maxLength = 5;
    document.getElementById("deskripsi").maxLength = 100;
});

function TambahClick() {
    
    $('#deskripsi').val(null);
    $('#deskripsi').focus();
    RekeningOtomatis();

}
function reloadTable() {
    var table = $('#dataTableBuilder').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

function RekeningOtomatis(){
      route = "/rekeningautokode";
    $.get(route, function (res) {
        $('#no_rek').val(res);
    });
}

$('#simpantambah').click(function() {
    var route = "/rekening";

    var no_rek = $('#no_rek').val();
    var deskripsi = $('#deskripsi').val();
    if (jQuery.trim(deskripsi) == '' || deskripsi == undefined) {
        alert('Deskripsi rekening tidak boleh dikosongkan');
        $('#deskripsi').focus();
        return;
    }

    var token = $('#token').val();
        $.ajax({
        url: route,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            dataType: 'json',
        data: {
            no_rek : no_rek,
            deskripsi: deskripsi,
            _token: token
        },
        error: function (res) {
            var errors = res.responseJSON;
            var pesan = '';
            $.each(errors, function (index, value) {
                pesan += value + "\n";
            });

            alert(pesan);
        },
        success: function () {
            reloadTable();

            TambahClick();
            alert('Sukses Menyimpan Data');

           
        }
    });
});

function UbahClick(btn) {
    route = "/rekening/" + btn.value + "/edit";

    $.get(route, function (res) {
        $('#idubah').val(res.id);

        $('#no_rekubah').val(res.no_rek);
        $('#deskripsiubah').val(res.deskripsi);
        $('#deskripsiubah').focus();

    });

}

$('#simpanubah').click(function () {
    var id = $('#idubah').val();
    var route = "/rekening/" + id;

    var deskripsi = $('#deskripsiubah').val();
    if (jQuery.trim(deskripsi) == '' || deskripsi == undefined) {
        alert('Deskripsi tidak boleh dikosongkan');
        $('#deskripsiubah').focus();
        return;
    }

    var token = $('#token').val();

    $.ajax({
        url: route,
        type: 'PUT',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        data: {
            deskripsi: deskripsi,
            _token: token
        },
        error: function (res) {
            var errors = res.responseJSON;
            var pesan = '';
            $.each(errors, function (index, value) {
                pesan += value + "\n";
            });

            alert(pesan);
        },
        success: function () {
            reloadTable();
            alert('Sukses Mengubah Data');
            $('#modalUbah').modal('toggle');
        }
    });
});

function HapusClick(btn) {
    $('#idHapus').val(btn.value);
}

$('#yakinhapus').click(function () {
    var id = $('#idHapus').val();
    var route = "/rekening/" + id;
    var token = $('#token').val();

    $.ajax({
        url: route,
        type: 'DELETE',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        error: function (res) {
            var errors = res.responseJSON;
            var pesan = '';
            $.each(errors, function (index, value) {
                pesan += value + "\n";
            });

            alert(pesan);
        },
        success: function () {
            reloadTable();
            alert('Sukses Menghapus Data');
            $('#modalHapus').modal('toggle');
        }
    });
});