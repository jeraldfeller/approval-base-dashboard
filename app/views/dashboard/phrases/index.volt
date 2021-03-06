{% extends "_templates/base.volt" %}
{% block extra_css %}
    <link rel="stylesheet" type="text/css" href="{{ url('dashboard_assets/css/vendor/shepherd.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('dashboard_assets/css/vendor/select2.css?v=1.2') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('dashboard_assets/css/vendor/select2-bootstrap.css') }}"/>
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
                <div class="row mrg-btm-20">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" id="input_phrase" name="input_phrase"
                                           placeholder="Enter Phrase" class="form-control step-phrase" required>
                                </div>
                            </div>
                            <div class=" col-md-2 col-sm-12 col-xs-12  step-filter">
                                <select id="filter1"  class="select2 item-info display-none" name="filter1" multiple>
                                    <option value="applicant">Applicant</option>
                                    <option value="description">Description</option>
                                </select>
                            </div>
                            <div class=" col-md-2 col-sm-12 col-xs-12  step-councils">
                                <select id="councils" name="councils" multiple class="item-info select2 display-none ">
                                    {% for row in councils %}
                                        <option value="{{ row.getId() }}">{{ row.getName() }}</option>
                                    {% endfor %}
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12 pull-left step-cost">
                                <div class="form-group search">
                                    <div class="col-sm-12 col-md-12 no-mrg-left no-mrg-right no-pdd-left no-pdd-right text-center">
                                        <div class="col-md-5 no-mrg-left no-mrg-right no-pdd-left no-pdd-right">
                                            <select id="cost-from" name="cost-from" class="form-control cost-select" data-smart-select>
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
                                            <select id="cost-to" name="cost-to" class="form-control cost-select" data-smart-select>
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
                        </div>
                        <div class="row mrg-btm-40 no-pdd-left no-margin-left">
                            <div class="col-md-2 no-pdd-left no-margin-left">
                                <div class="checkbox">
                                    <input type="checkbox" class="checkbox-filter" id="input_metadata" name="input_case_sensitive">
                                    <label for="input_metadata">Metadata</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox ">
                                    <input type="checkbox" id="input_case_sensitive" name="input_case_sensitive">
                                    <label for="input_case_sensitive">Case sensitive</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox display-inline-block">
                                    <input type="checkbox" id="input_literal_search" name="input_literal_search">
                                    <label for="input_literal_search">Literal search</label>
                                </div>
                                <div class="display-inline-block" data-toggle="tooltip" data-html="true" data-placement="bottom" title="A phrase with Literal Search enabled doesn't allow
                                        the phrase to be found within other words."><i class="fa fa-question-circle"></i></div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox no-mrg-left no-pdd-left display-inline-block">
                                    <input type="checkbox" id="input_exclude_phrase" name="input_exclude_phrase">
                                    <label for="input_exclude_phrase">Exclude phrase</label>
                                </div>
                                <div class="display-inline-block" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Disqualifies a DA from being added to your inbox if
                                        this phrase is found."><i class="fa fa-question-circle"></i></div>
                            </div>
                            <div class="col-md-2">
                                <button id="createBtn" class="btn btn-default" >Create Phrase
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a id="bulk-delete" class="bulk-action-button btn btn-default disabled mrg-btm-10" href="javascript:void(0);">
                                    Delete
                                </a>
                            </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {# Phrases table #}
                        <table id="dt-opt"
                               class="table table-responsive-sm table-responsive-md table-responsive-lg datatables-table ">
                            <thead>
                            <tr>
                                <th>
                                    <div class="checkbox">
                                        <input id="checkbox-toggle-all" type="checkbox">
                                        <label for="checkbox-toggle-all"></label>
                                    </div>
                                </th>
                                <th>Phrase</th>
                                <th>Occurences</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="step-table">
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Form Modal -->
                    <div class="modal fade" id="form-modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="modalTitle">
                                            Edit Phrase
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12 text-center modal-loader">
                                                <i class="fa fa-spinner fa-spin fa-2x"></i>
                                            </div>
                                            <div class="display-none form-container no-mrg-left no-mrg-right no-pdd-left no-pdd-right">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" id="input_phrase_edit" name="input_phrase"
                                                               placeholder="Enter Phrase" class="form-control step-phrase" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mrg-btm-20">
                                                    <select id="filter1_edit"  class="select2 item-info " name="filter1" multiple>
                                                        <option value="applicant">Applicant</option>
                                                        <option value="description">Description</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mrg-btm-20">
                                                    <select id="councils_edit" name="councils" multiple class="item-info select2">
                                                        {% for row in councils %}
                                                            <option value="{{ row.getId() }}">{{ row.getName() }}</option>
                                                        {% endfor %}
                                                    </select>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group search">
                                                        <div class="col-sm-12 col-md-12 no-mrg-left no-mrg-right no-pdd-left no-pdd-right text-center">
                                                            <div class="col-md-5 no-mrg-left no-mrg-right no-pdd-left no-pdd-right">
                                                                <select id="cost-from_edit" name="cost-from" class="form-control cost-select" data-smart-select>
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
                                                            <div class="col-md-2 no-mrg-left no-mrg-right no-pdd-left no-pdd-right">
                                                                <span class="separator lh-2-5">-</span>
                                                            </div>
                                                            <div class="col-md-5 no-mrg-left no-mrg-right no-pdd-left no-pdd-right">
                                                                <select id="cost-to_edit" name="cost-to" class="form-control cost-select" data-smart-select>
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


                                                <div class="col-md-12 ">
                                                    <div class="checkbox no-mrg-left no-pdd-left">
                                                        <input type="checkbox" class="checkbox-filter" id="input_metadata_edit" name="input_case_sensitive">
                                                        <label for="input_metadata_edit">Metadata</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="checkbox no-mrg-left no-pdd-left">
                                                        <input type="checkbox" id="input_case_sensitive_edit" name="input_case_sensitive">
                                                        <label for="input_case_sensitive_edit">Case sensitive</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="checkbox display-inline-block no-mrg-left no-pdd-left">
                                                        <input type="checkbox" id="input_literal_search_edit" name="input_literal_search">
                                                        <label for="input_literal_search_edit">Literal search</label>
                                                    </div>
                                                    <div class="display-inline-block" data-toggle="tooltip" data-html="true" data-placement="bottom" title="A phrase with Literal Search enabled doesn't allow
                                        the phrase to be found within other words."><i class="fa fa-question-circle"></i></div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="checkbox display-inline-block no-mrg-left no-pdd-left">
                                                        <input type="checkbox" id="input_exclude_phrase_edit" name="input_exclude_phrase">
                                                        <label for="input_exclude_phrase_edit">Exclude phrase</label>
                                                    </div>
                                                    <div class="display-inline-block" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Disqualifies a DA from being added to your inbox if
                                        this phrase is found."><i class="fa fa-question-circle"></i></div>
                                                </div>

                                            </div>
                                            </div>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success editSave">Save</button>
                                    </div>
                            </div>
                        </div>
                    </div>

                    {# Create Phrase Form #}
                    {#<div class="col-lg-3 col-md-3">#}
                        {#<div class="card">#}

                            {#<div class="card-heading border bottom">#}
                                {#<h4 class="card-title">Create Phrase</h4>#}
                            {#</div>#}
                            {#<form class="form" role="form" method="post" action="{{ url('phrases/create') }}">#}
                            {#<div class="card-body">#}



                                    {#<div class="form-group">#}
                                        {#<label class="control-label" for="input_phrase">Phrase</label>#}
                                        {#<input type="text" id="input_phrase" name="input_phrase"#}
                                               {#placeholder="Enter Phrase" class="form-control step-phrase" required>#}
                                    {#</div>#}
                                    {#<a id="bulk-delete" class="bulk-action-button btn btn-default disabled mrg-btm-10" href="javascript:void(0);">#}
                                        {#Delete#}
                                    {#</a>#}
                                    {#<div class="checkbox no-mrg-left no-pdd-left ">#}
                                        {#<input type="checkbox" id="input_case_sensitive" name="input_case_sensitive">#}
                                        {#<label for="input_case_sensitive">Case sensitive</label>#}
                                    {#</div>#}

                                    {#<div class="checkbox no-mrg-left no-pdd-left mrg-top-10 display-inline-block">#}
                                        {#<input type="checkbox" id="input_literal_search" name="input_literal_search">#}
                                        {#<label for="input_literal_search">Literal search</label>#}
                                    {#</div>#}
                                    {#<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="A phrase with Literal Search enabled doesn't allow#}
                                        {#the phrase to be found within other words."><i class="fa fa-question-circle"></i></div>#}
                                    {#<br>#}

                                    {#<div class="checkbox no-mrg-left no-pdd-left display-inline-block">#}
                                        {#<input type="checkbox" id="input_exclude_phrase" name="input_exclude_phrase">#}
                                        {#<label for="input_exclude_phrase">Exclude phrase</label>#}
                                    {#</div>#}
                                    {#<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Disqualifies a DA from being added to your inbox if#}
                                        {#this phrase is found."><i class="fa fa-question-circle"></i></div>#}

                            {#</div>#}

                            {#<div class="card-footer text-center border top">#}
                                {#<button type="submit" class="btn btn-default" href="javascript:void(0);">Create Phrase#}
                                {#</button>#}
                            {#</div>#}

                            {#</form>#}

                        {#</div>#}

                    {#</div>#}
                </div>

            </div>
        </div>
    </div>

    {% include "phrases/_phrasesJs.volt" %}
{% endblock %}





