{% extends "_templates/base.volt" %}
{% block content %}
    {% include "includes/flashMessages.volt" %}

    {% if page.items|length > 0 %}

        {# Search on top #}
        <div class="form-group">
            <input class="form-control input-lg" placeholder="input-default" type="text">
        </div>


        {% for development_application in page.items %}

            {% set current_date = development_application.getCreated() %}

            <div class="card">
                <div class="feed-header m-3">
                    <ul class="list-unstyled list-info">
                        <li>
                            {% if development_application.Council.getLogoUrl()|length > 0 %}
                                <img class="thumb-img" src="{{ development_application.Council.getLogoUrl() }}" />
                            {% else %}
                                <img class="thumb-img" src="{{ url("aiden-assets/images/aiden-anonymous.jpg") }}" />
                            {% endif %}
                            <div class="info">
                                <a href="" class="title no-pdd-vertical text-semibold inline-block">
                                    {{ development_application.Council.getName() }}
                                </a>
                                <span class="sub-title">
                                    Uploaded a new development application</a>
                                </span>
                                <span class="sub-title">
                                    <i class="ti-timer pdd-right-5"></i>
                                    <span
                                        <time class="timeago" datetime="{{ current_date.format('c') }}">{{ current_date.format('d-m-Y') }}</time>
                                    </span>
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
                {% if development_application.getDescription()|length > 0 %}
                    <div class="feed-body mb-3 mx-3">
                        {{ development_application.getHighlightedDescription(loggedInUser.getPhrases()) }}
                    </div>
                {% endif %}

                <div class="card-footer border top">

                    <ul class="list-unstyled list-inline text-right">
                        {% for phrase in development_application.getContainedPhrases(loggedInUser.getPhrases()) %}
                            <li class="list-inline-item">
                                <span class="label label-primary">{{ phrase.getPhrase() }}</span>
                            </li>
                        {% endfor %}
                        <li class="list-inline-item">
                            <a href="{{ url('leads/' ~ development_application.getId() ~ '/view') }}" class="btn btn-flat">View</a>
                        </li>
                    </ul>
                </div>
            </div>

        {% endfor %}

        {# Pagination #}
        <div class="text-center">
            {% include "includes/pagination.volt" %}
        </div>

    {% else %}

        <div class="text-center">
            <p class="lead">
                Nothing here yet, create a phrase to get started.
            </p>

            <div class="action-buttons">
                <a class="btn btn-lg btn-primary" href="{{ url("phrases") }}">Create a Phrase</a>
            </div>

        </div>

    {% endif %}
{% endblock %}






