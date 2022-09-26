<!DOCTYPE html>
<html>
<head>
    <title>Database</title>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
<style>
.topright {
  position: absolute;
  top: 8px;
  right: 16px;
  font-size: 18px;
}
</style>
<script>
    function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
</script>
</head>
<body style="margin: 50px;">

<button type="button" class="topright" onclick="window.location.href='{{url_for('report')}}';">Report</button>

{% block content %}

<table class="table" id="tblData">
    <thread>
        <td><b>Date/Time</b></td>
        <td><b>Product Code</b></td>
        <td><b>Mac Address</b></td>
        <td><b>Test Result</b></td>
    </thread>
    {% for row in db %}
    <tr>
        <td>{{row[4]}}</td>
        <td>{{row[1]}}</td>
        <td>{{row[2]}}</td>
        <td>{{row[3]}}</td>
    </tr>
    {% endfor %}
</table>

{% endblock %}
<button onclick="exportTableToExcel('tblData', 'current-data')">Export Table Data To Excel File</button>
</body>
</html>