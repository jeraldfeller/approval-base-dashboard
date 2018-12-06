{% set _ns = dispatcher.getNamespaceName() %}
{% set _cn = dispatcher.getControllerName() %}
{% set _an = dispatcher.getActionName() %}


<div class="menu-section">
    <ul>
        {# Dashboard / Index #}
        {% set isActive = (_ns == "Aiden\Controllers" and _cn == "index" and (_an == "index" or _an == "")) %}
        <li class="search-nav">
            <a href="{{ url("search") }}" class="{% if isActive == true %} active{% endif %}">
                <i class="ion-ios7-search"></i>
                <span>Search</span>
            </a>
        </li>
        {# Leads #}
        {% set isActive = (_ns == "Aiden\Controllers" and _cn == "leads" and _an == "index") %}
        <li class="nav-item leads-nav">
            <a href="{{ url("leads") }}" class="{% if isActive == true %} active{% endif %}">
                <i class="ion-ios7-plus-empty"></i>
                <span>Alerts </span>
            </a>
        </li>

        {# Saved #}
        {% set isActive = (_ns == "Aiden\Controllers" and _cn == "leads" and _an == "indexSaved") %}
        <li class="nav-item">
            <a href="{{ url("leads/saved") }}" class="{% if isActive == true %} active{% endif %}">
                <i class="ion-ios7-star"></i>
                <span>Saved </span>
            </a>
        </li>



        {# Phrases #}
        {% set isActive = (_ns == "Aiden\Controllers" and _cn == "phrases" and (_an == "index" or _an == "")) %}
        <li class="nav-item phrase-nav">
            <a href="{{ url("phrases") }}" class="{% if isActive == true %} active{% endif %}">
                <i class="ion-ios7-pricetag"></i>
                <span class="title">Phrases</span>
            </a>
        </li>

        {# Councils #}
        {% set isActive = (_ns == "Controllers\Admin" and _cn == "councils" and (_an == "index" or _an == "")) %}
        <li class="nav-item">
            <a href="{{ url("councils") }}" class="{% if isActive == true %} active{% endif %}">
                <i class="ion-ios7-briefcase"></i>
                <span class="title">Councils</span>
            </a>
        </li>
    </ul>
</div>


