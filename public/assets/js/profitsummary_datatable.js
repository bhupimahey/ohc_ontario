var table = $('#summary_table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: baseurl + "/report/get_profit_summary",
            type: "POST",
            data: function(d){
                d.search        = $("#filter_search").val();
                d.report_type   = $("#report_type").val();
                d.daterange     = $("#filter_daterange").val();
            }
        },
        columns: [
            {data: "period"},
            {data: "total_jobs"},
            {data: "total_labour"},
            {data: "total_material"},
             {data: "total_cost"},
            {data: "total_profit"}
        ]
    });
    
    
     $('#search_btn').on("click",function(){ 
       $('#summary_table').DataTable().ajax.reload();
       
} );
    
    
    $("#export_excel").click(function(){

    let search = $("#filter_search").val();
    let type   = $("#report_type").val();
    let range  = $("#filter_daterange").val();

    window.location.href =
        baseurl + "/report/export_profit_summary"
        + "?search=" + search
        + "&report_type=" + type
        + "&daterange=" + range;
});