<!DOCTYPE html>
<html>
<head>
    <title>Mac Address Matching</title>
</head>
<body onload="document.getElementById('pcode').focus()" style="background-color:#F4FBFD;">
<center>

{% block content %}
<form action="{{url_for('match')}}" method="GET">
  <label><h1>Scan or Input:</h1></label><br>
  <input type="text" id="pcode" name="pcode" maxlength="20" style="font-size:80px; background-color : #d1d1d1;" oninput="this.value = this.value.toUpperCase()" required><br>
  <input type="text" id="mcad" name="mcad" maxlength="12" style="font-size:70px; background-color : #d1d1d1;" oninput="this.value = this.value.toUpperCase()" required><br><br>
  <input type="submit" value="Compare">
  <button type="button" onclick="window.location.href='{{url_for('displaydata')}}';">Query Page</button>
</form>
  {% if result == "OK" %}
      <h1><b><p style="color:white; font-size:100px; background-color: green;">OK</p></b></h1>
  {% else %}
      <h1><b><p style="color:white; font-size:100px; background-color: red;">NG</p></b></h1>
  {% endif %}

{% endblock %}

</center>
</body>
</html>