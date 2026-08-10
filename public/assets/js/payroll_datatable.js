var buttonCommon = {
        exportOptions: {
            format: {
                body: function ( data, row, column, node ) {
                    if(column === 2 ){
                     var reg = /<a[^>]*>([^<]+)<\/a>/g;

                          return ( reg.exec(data)[1]);

                }
                else
                 return    data; 
                    
                }
            }
        }
    };


 
