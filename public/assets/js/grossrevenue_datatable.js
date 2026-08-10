var grossRevenueTable = $('#gross_revenue_table').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    ordering: false,
    ajax: {
        url: baseurl + "/report/get_gross_revenue",
        type: "POST",
        data: function (d) {
            d.report_type = $("#report_type").val();
        }
    },
    columns: [
        { data: "period" },
        { data: "total_jobs" },
        { data: "gross_revenue" }
    ]
});

$('#gross_revenue_tabs a[data-report-type]').on('click', function (e) {
    e.preventDefault();
    var type = $(this).data('report-type');
    $('#gross_revenue_tabs a').removeClass('active');
    $(this).addClass('active');
    $('#report_type').val(type);
    grossRevenueTable.ajax.reload();
});

$("#export_excel").on("click", function () {
    var type = $("#report_type").val();
    window.location.href = baseurl + "/report/export_gross_revenue?report_type=" + encodeURIComponent(type);
});
