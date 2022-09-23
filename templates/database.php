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
</head>
<body style="margin: 50px;">

<button type="button" class="topright" onclick="window.location.href='{{url_for('report')}}';">Report</button>

{% block content %}

<table class="table" id="report-table">
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

</body>
</html>