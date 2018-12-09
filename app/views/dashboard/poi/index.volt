{% extends "_templates/base.volt" %}
{% block extra_css %}
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.51.0/mapbox-gl.css' rel='stylesheet' />
    <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css' type='text/css' />

    <style>
        .pad2 {
            padding: 20px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .map {
            min-height: 600px;
        }
        .marker {
            border: none;
            cursor: pointer;
            height: 56px;
            width: 56px;
            background-image: url({{ url('dashboard_assets/images/marker.png') }});
            background-color: rgba(0, 0, 0, 0);
        }
    </style>
{% endblock %}
{% block extra_js %}

{% endblock %}
{% block content %}
    <div id="content">
        <div class="menubar">
            <div class="sidebar-toggler visible-xs">
                <i class="ion-navicon"></i>
            </div>
            {% if page_title is defined %}
                <div class="page-title">
                    <h4>{{ page_title }}</h4>
                </div>
            {% endif %}

            {% include "includes/user-dropdown.volt" %}
        </div>
        <div id="datatables">
            <div class="content-wrapper">
                {% include "includes/flashMessages.volt" %}
                <div class="row">
                    <div class="col-md-3">
                        <div class='sidebar'>
                            <div class='heading'>
                                List
                            </div>
                            <div id='listings' class='listings'></div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div id='map' class='map pad2'>Map</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {% include "poi/_indexJs.volt" %}
{% endblock %}
