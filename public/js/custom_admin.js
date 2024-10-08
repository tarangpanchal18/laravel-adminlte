/**
 * ------------------------------------------------------------------------------------------------------------------------------------------------
 *  Important Information
 *  Any document ready queris should be written below this line
 */
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
            type: "PATCH",
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
                if (response.success) {
                    setTimeout(() => {
                        toastFire(response.message, 'success')
                        reloadListingTable()
                    }, 1500);
                } else {
                    toastFire('Something went wrong !', 'error')
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastFire('Something went wrong while changing status !', 'error')
            }
        });
    });

    $(document).on('change', '.multi-select-all', function () {
        var isChecked = $(this).is(':checked');
        if (isChecked) {
            $('#select-operation').removeClass('d-none');
        } else {
            $('#select-operation').addClass('d-none');
        }
        $('.multi-select').prop('checked', isChecked);
    });

    $(document).on('change', '.multi-select', function () {
        if ($('.multi-select:checked').length > 0) {
            $('#select-operation').removeClass('d-none');
        } else {
            $('#select-operation').addClass('d-none');
        }
    });

    $(document).on('change', '#select-operation', function () {
        var selectedCb = [];
        var operationType = $(this).val();
        var dataurl = $(this).attr('data-url');
        var operationTypeText = getOperationTypeText(operationType);

        if (!operationType || operationType == undefined || operationType == 0) {
            return false;
        }
        if (!dataurl || dataurl == undefined || dataurl == '') {
            return false;
        }

        $("input:checkbox[name=multi-select-cb]:checked").each(function(){
            selectedCb.push($(this).attr('data-id'));
        });

        Swal.fire({
            icon: 'info',
            title: 'Are you sure ?',
            html: selectedCb.length + ' rows has been selected<br>All selected rows status will be updated to <strong>' + operationTypeText + '</strong>',
            showConfirmButton: true,
            showCancelButton: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        }).then((result) => {
            if (result.value) {
                handleMultipleCheckboxStatus(dataurl, selectedCb, operationType)
            }
        });
    });
});


function reloadListingTable() {
    //resetting multiple checkboxes operation selection
    $("#select-operation").val('');
    $('.multi-select-all').prop('checked', false)
    $('.multi-select').prop('checked', false)

    //reloading the datatable
    $('#data-table').DataTable().ajax.reload();
}

function getOperationTypeText(operationType) {
    if (operationType == 1) {
        return 'Active';
    } else if (operationType == 2) {
        return 'InActive';
    } else if (operationType == 3) {
        return 'Delete';
    } else {
        return 'n/a';
    }
}

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

function generateDataTable(dataUrl, coloumnsData, filterData = []) {
    // $('#data-table tfoot th').each( function (counter) {
	// 	var title = $(this).text();
	// 	var totalLen = $('#data-table tfoot th').length;
    //     if (counter == 0 || counter == (totalLen-1) || title == "Status") {
    //         $(this).html( '<input class="form-control" disabled type="text" />' );
    //     } else {
    //         $(this).html( '<input class="form-control" type="text" placeholder="'+ title +' Search" />' );
    //     }
	// });

    var dtTable = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dataUrl,
            data: function (d) {
                d.filterData = filterData;
            }
        },
        columns: filterColoumnsData(coloumnsData),
        // dom: 'Bfrtip',
        order: [],
        iDisplayLength: 50,
        'columnDefs': [
            {
                "targets": '_all',
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).css('padding', '7px')
                }
            },
            {
                "targets": 0,
                "className": "text-center",
                orderable: false,
                sortable: false,
           },
        ],
    });

    // dtTable.columns().every( function () {
	// 	var that = this;
	// 	$( 'input', this.footer() ).on( 'keyup change', function () {
	// 		if ( that.search() !== this.value ) {
	// 			that
	// 				.search( this.value )
	// 				.draw();
	// 		}
	// 	});
	// });
}

function filterColoumnsData(coloumnsData) {
    for(key in coloumnsData){
        let curElement = coloumnsData[key];
        if (curElement.data == "DT_RowIndex") {
            coloumnsData[key]['sWidth'] = "5%";
            coloumnsData[key]['orderable'] = false;
            coloumnsData[key]['searchable'] = false;
            coloumnsData[key]['data'] = 'cb';
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

function removeDataFromDatabase(deleteUrl, id, htmlMessage = '') {
    Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        html: (htmlMessage ? htmlMessage : "You won't be able to revert this!"),
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
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
                    if (response.success) {
                        toastFire(response.message, 'success')
                    } else {
                        toastFire('We encoutered some error !', 'error')
                    }

                    reloadListingTable()
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastFire('We encoutered some error !', 'error')
                }
            });
        }
    });
}

function handleMultipleCheckboxStatus(dataurl, dataIds, operationType) {
    $.ajax({
        type: 'PATCH',
        dataType : 'json',
        url: dataurl + '/mass-update',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            ids : dataIds,
            operationType: operationType
        },
        success : function(response) {
            if (response.success) {
                toastFire(response.message, 'success')
            } else {
                toastFire('We encoutered some error !', 'error')
            }

            reloadListingTable()
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            toastFire('We encoutered some error !', 'error')
        }
    });
}
