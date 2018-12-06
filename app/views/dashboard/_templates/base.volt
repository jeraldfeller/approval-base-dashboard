<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>{% if page_title is defined %}{{ page_title }}{% endif %}</title>
    <link rel="shortcut icon" href="{{ url("/favicon.png") }}">

    {% block extra_css %}
    {% endblock %}

    {% include "_templates/_head.css.volt" %}
    {% include "_templates/_head.js.volt" %}


    {% block extra_js %}
    {% endblock %}


</head>

<body>
<div id="wrapper">
    <!-- left menu -->
    {% include "includes/leftmenu.volt" %}

    <!-- Content -->
    {% block content %}
    {% endblock %}
</div>
{% include "includes/messengerNotification.volt" %}

</body>
</html>
