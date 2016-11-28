function printTable(title,header,createdDateTime,tableId) {

    function printPageBuilderDefault(buildedTable) {

    return '<html><head>' +
        '<style type="text/css" media="print">' +
        '  @page { size: auto;   margin: 25px 0 25px 0; }' +
        '</style>' +
        '</style><style type="text/css" media="all">'+'html{font-family:sans-serif}\n' +
        'table{border-collapse: collapse; font-size: 12px; }\n' +
        'table, th, td {border: 1px solid grey}\n' +
        'th, td {text-align: center; vertical-align: middle; border: 1px solid #CCC; height: 30px;}\n' +
        'th { background: #DFDFDF; font-weight: bold;}\n' +
        'td {background: #FAFAFA; text-align: center;}\n' +
        'p2 {font-weight: bold; font-size: 14px; margin-left:3%}\n' +
        'div.row {margin-left:3%}\n' +
        'p {font-size: 12px; margin-left:3%}\n' +
        'table { width:94%; margin-left:3%; margin-right:3%}\n' +
        'div.bs-table-print { text-align:center;}\n' +
        '</style><title>'+title+'</title></head><body>' + 
        '<p2>'+ header +' </p2>' + 
        '<p>Creado en: '+ createdDateTime +' </p>' + 
        '<div class="bs-table-print">' + buildedTable + "</div></body></html>";
    }  

    function buildTable(data,columns) {
        var out = "<table><thead><tr>";
        for(var h = 0; h < columns.length; h++) {
            if(!columns[h].printIgnore) {
                out += ("<th>"+columns[h].title+"</th>");
            }
        }
        out += "</tr></thead><tbody>";
        for(var i = 0; i < data.length; i++) {
            out += "<tr>";
            for(var j = 0; j < columns.length; j++) {
                if(!columns[j].printIgnore) {
                    out += ("<td>"+(data[i][columns[j].field]||"-")+"</td>");
                }
            }
            out += "</tr>";
        }
        out += "</tbody></table>";
        return out;
    }

    var options = $(tableId).bootstrapTable('getOptions');
    var data = options.data.slice(0);
    var buildedTable=buildTable(data,options.columns[0]);
    var newWin = window.open("");
    newWin.document.write(printPageBuilderDefault(buildedTable));
    newWin.print();
    newWin.close();
}