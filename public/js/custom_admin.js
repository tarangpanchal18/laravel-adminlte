$(document).ready(function() {
    $( ".select2" ).select2();

    $("body").tooltip({ selector: '[data-toggle=tooltip]' });

    $(document).on('click', '.changestatus', function() {
        var dataId = $(this).attr("data-id");
        var dataUrl = $(this).attr("data-url");
        var dataValue = $(this).attr("data-value");
        if (!dataUrl || !dataId || !dataValue) {
            toastFire('Something went wrong !', 'error')
        }

        if (dataValue == "Active") {dataValue = "InActive"}
        else if (dataValue == "InActive") {dataValue = "Active"}
        else if (dataValue == "0") {dataValue = "1"}
        else if (dataValue == "1") {dataValue = "0"}

        $.ajax({
            type: "POST",
            dataType : 'json',
            url: dataUrl + '/' + dataId,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                _method: 'PATCH',
                status: dataValue
            },
            beforeSend: function() {
                toastFire('Please wait', 'info', false)
            },
            success : function(response) {
                setTimeout(() => {
                    toastFire('Status has been updated successfully !', 'success')
                    $('#data-table').DataTable().ajax.reload();
                }, 1500);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastFire('Something went wrong !', 'error')
            }
        });
    });
});

/**
 * ------------------------------------------------------------------------
 *  Important Information
 *  Any Function should be written below this line
 * ------------------------------------------------------------------------
 */

function toastFire(message, icon = 'info', showTimer = true) {
    if (showTimer) {
        Swal.fire({
            icon: icon,
            html: message,
            toast: true,
            position: "top-end",
            timer: 3500,
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    } else {
        Swal.fire({
            icon: icon,
            html: message,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
        });
    }
}

/**
 * Function to generate the datatable
 *
 * @param string url
 * @param array coloumnsData
 * @param array filterData
 * @param array coloumnsData
 */
function generateDataTable(dataUrl, coloumnsData, filterData = [], coloumnsToExport = [1,2,3,4]) {

    $('#data-table tfoot th').each( function (counter) {
		var title = $(this).text();
		var totalLen = $('#data-table tfoot th').length;
        if (counter == 0 || counter == (totalLen-1) || title == "Status") {
            $(this).html( '<input class="form-control" disabled type="text" />' );
        } else {
            $(this).html( '<input class="form-control" type="text" placeholder="'+ title +' Search" />' );
        }
	});

    coloumnsData = filterColoumnsData(coloumnsData)

    var dtTable = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dataUrl,
            data: function (d) {
                d.filterData = filterData;
            }
        },
        columns: coloumnsData,
        dom: 'Bfrtip',
        order: [],
        iDisplayLength: 50,
        'columnDefs': [
            {
                "targets": 0,
                "className": "text-center",
                "width": "18%",
                orderable: false,
           },
        ],
        buttons: [
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: coloumnsToExport
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: coloumnsToExport
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: coloumnsToExport
                }
            },
        ],
    });

    dtTable.columns().every( function () {
		var that = this;
		$( 'input', this.footer() ).on( 'keyup change', function () {
			if ( that.search() !== this.value ) {
				that
					.search( this.value )
					.draw();
			}
		});
	});
}

/**
 * Function to filter coloumn data like hide status set width etc.
 *
 * @param array coloumnsData
 * @return array coloumnsData
 */
function filterColoumnsData(coloumnsData)
{
    for(key in coloumnsData){
        let curElement = coloumnsData[key];
        if (curElement.data == "DT_RowIndex") {
            coloumnsData[key]['sWidth'] = "5%";
            coloumnsData[key]['orderable'] = false;
            coloumnsData[key]['searchable'] = false;
        }
        if (curElement.data == "status") {
            coloumnsData[key]['sWidth'] = "8%";
            coloumnsData[key]['class'] = "text-center";
            coloumnsData[key]['orderable'] = false;
            coloumnsData[key]['searchable'] = false;
        }
        if (curElement.data == "action") {
            coloumnsData[key]['sWidth'] = "10%";
            coloumnsData[key]['class'] = "text-center";
            coloumnsData[key]['orderable'] = false;
            coloumnsData[key]['searchable'] = false;
        }
    }

    return coloumnsData;
}

/**
 * Function to remove Data from the database
 *
 * @param string deleteUrl
 * @param integer id
 */
function removeDataFromDatabase(deleteUrl, id, csrf) {
    Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                dataType : 'json',
                url: deleteUrl + '/' + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    _method: 'DELETE'
                },
                success : function(response) {
                    toastFire('Data has been removed successfully !', 'success')
                    $('#data-table').DataTable().ajax.reload();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastFire('We encoutered some error !', 'error')
                }
            });
        }
    });
}
