{% extends "_templates/base.volt" %}
{% block extra_css %}
    <link rel="stylesheet" type="text/css"
          href="{{ url('dashboard_assets/css/vendor/bootstrap-daterangepicker.css') }}"/>

    <link rel="stylesheet" type="text/css" href="{{ url('dashboard_assets/css/vendor/select2.css?v=1.2') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('dashboard_assets/css/vendor/select2-bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('dashboard_assets/css/vendor/shepherd.css') }}"/>
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
                <div class="row mrg-btm-20 no-pdd-left no-margin-left search-area">
                    <div class="col-md-2 col-sm-12 col-xs-12 council-dropdown no-margin-left no-pdd-left-search">
                        <input type="text" class="form-control searchFilter" id="searchFilter" placeholder="Create Phrase">
                    </div>
                    <div class=" col-md-2 col-sm-12 col-xs-12 council-dropdown step-filter">
                        <select id="filter1"  class="select2 item-info display-none" multiple>
                            <option value="applicant">Applicant</option>
                            <option value="description">Description</option>
                        </select>
                    </div>

                    <div class=" col-md-2 col-sm-12 col-xs-12 council-dropdown step-councils">
                        <select id="councils" name="councils" multiple class="item-info select2 display-none ">
                            {% for row in councils %}
                                <option value="{{ row.getId() }}">{{ row.getName() }}</option>
                            {% endfor %}
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-12 col-xs-12 cost-range pull-left step-cost">
                        <div class="form-group search">
                            <div class="col-sm-12 col-md-12 no-mrg-left no-mrg-right no-pdd-left no-pdd-right text-center">
                                <div class="col-md-5 no-mrg-left no-mrg-right no-pdd-left no-pdd-right">
                                    <select id="cost-from" class="form-control cost-select" data-smart-select>
                                        <option value="0">Any($)</option>
                                        <option value="250000">$250,000</option>
                                        <option value="1000000">$1m</option>
                                        <option value="5000000">$5m</option>
                                        <option value="15000000">$15m</option>
                                        <option value="25000000">$25m</option>
                                        <option value="50000000">$50m</option>
                                        <option value="100000000">$100m+</option>
                                    </select>
                                </div>
                                <div class="col-md-1 no-mrg-left no-mrg-right no-pdd-left no-pdd-right">
                                    <span class="separator lh-2-5">-</span>
                                </div>
                                <div class="col-md-5 no-mrg-left no-mrg-right no-pdd-left no-pdd-right">
                                    <select id="cost-to" class="form-control cost-select" data-smart-select>
                                        <option value="100000000">Any($)</option>
                                        <option value="250000">$250,000</option>
                                        <option value="1000000">$1m</option>
                                        <option value="5000000">$5m</option>
                                        <option value="15000000">$15m</option>
                                        <option value="25000000">$25m</option>
                                        <option value="50000000">$50m</option>
                                        <option value="100000000">$100m+</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-12 col-xs-12 date-range-picker">
                        <div class="date-range">
                            <div class="input-group input-group-sm step-date">
					  	<span class="input-group-addon">
					  		<i class="fa fa-calendar-o"></i>
					  	</span>
                                <input type="text" id="date-range-picker" class="form-control"
                                       placeholder="{{ defaultDateRange[0] }} - {{ defaultDateRange[1] }}"/>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row mrg-btm-40 no-pdd-left no-margin-left">

                    <div class="col-md-2 no-pdd-left no-margin-left">
                        <div class="checkbox no-pdd-left">
                            <input type="checkbox" class="checkbox-filter" id="input_metadata" name="input_case_sensitive">
                            <label for="input_metadata">Metadata</label>
                        </div>
                    </div>

                    <div class="col-md-2 no-pdd-left no-margin-left">
                        <div class="checkbox no-pdd-left">
                            <input type="checkbox" class="checkbox-filter" id="input_case_sensitive" name="input_case_sensitive">
                            <label for="input_case_sensitive">Case sensitive</label>
                        </div>
                    </div>
                    <div class="col-md-2 no-pdd-left no-margin-left">
                        <div class="checkbox display-inline-block no-pdd-left">
                            <input type="checkbox" class="checkbox-filter" id="input_literal_search" name="input_literal_search">
                            <label for="input_literal_search">Literal search</label>
                        </div>
                        <div class="display-inline-block" data-toggle="tooltip" data-html="true" data-placement="bottom" title="A phrase with Literal Search enabled doesn't allow
                                        the phrase to be found within other words."><i class="fa fa-question-circle"></i></div>
                    </div>
                    <div class="col-md-2 no-pdd-left no-margin-left">
                        <div class="checkbox no-mrg-left no-pdd-left display-inline-block">
                            <input type="checkbox" class="checkbox-filter" id="input_exclude_phrase" name="input_exclude_phrase">
                            <label for="input_exclude_phrase">Exclude phrase</label>
                        </div>
                        <div class="display-inline-block" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Disqualifies a DA from being added to your inbox if
                                        this phrase is found."><i class="fa fa-question-circle"></i></div>
                    </div>
                </div>
                {% include "includes/flashMessages.volt" %}



                {#Context Menu#}
                <div id="context-menu">
                    <ul class="dropdown-menu pull-left" role="menu">
                        <li onclick="openLink(this)" class="sendTo" data-action="_blank">
                            <a href="javascript:;">
                                <span>Open link in new tab</span>
                            </a>
                        </li>
                        <li onclick="openLink(this)" class="sendTo" data-action="">
                            <a href="javascript:;">
                                <span>Open link in new window</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- end context menu -->

                <div class="row">
                    {# Search Table #}
                    <table id="dt-opt"
                           class="table table-responsive-sm table-responsive-md table-responsive-lg datatables-table table-hover">
                        <thead>
                        <tr>
                            <th>Action</th>
                            <th>Council</th>
                            <th>Uploaded</th>
                            <th>Construction Value</th>
                            <th>Applicant</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody class="tbody">
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
    {% include "search/_searchJs.volt" %}
{% endblock %}
