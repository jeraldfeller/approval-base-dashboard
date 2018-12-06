<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
        <title>{% if page_title is defined %}{{ page_title }}{% endif %}</title>
        <link rel="shortcut icon" href="{{ url("/favicon.png") }}">

        {% block extra_css %}
        {% endblock %}

        {% include "_templates/_head.css.volt" %}
        {% include "_templates/_head.js.volt" %}


        {% block extra_js %}
        {% endblock %}


    </head>

    <body id="signin">
    <a href="{{ url() }}" class="logo">
        <img class="title-logo"
             src={{ url("front-end/assets/images/logo-white.png") }} alt="">
    </a>
    {% block content  %}
    {% endblock %}
    </body>
</html>
