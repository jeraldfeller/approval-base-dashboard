<div id="sidebar-dark" class="main-sidebar">

    <div class="current-user current-user-logo">
        <div class="logo logo-white logo-header" style="background-image: url('{{ url('dashboard_assets/images/logo-white.png') }}')"></div>
    </div>
    {% include "includes/leftmenu-user.volt" %}
    {% if loggedInUser.isAdministrator() %}
        {% include "admin/_includes/leftmenu.volt" %}
    {% endif %}
    <div class="bottom-menu hidden-sm">
        <ul>
            <li><a href="{{ url('support') }}"><i class="ion-help"></i></a></li>
            <li>
                <a href="#">
                    <i class="ion-archive"></i>
                    <span class="flag"></span>
                </a>
                <ul class="menu">
                    <li><a href="#">5 unread messages</a></li>
                    <li><a href="#">12 tasks completed</a></li>
                    <!-- <li><a href="#">3 features added</a></li> -->
                </ul>
            </li>
            <li><a href="{{ url("login/destroy") }}"><i class="ion-log-out"></i></a></li>
        </ul>
    </div>
</div>
