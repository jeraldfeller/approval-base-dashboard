<div id="panel" class="billing">
    <h3>
        {{ page_title }}
    </h3>

    {% if user['subscriptionStatus'] == 'trial' OR  user['subscriptionStatus'] == 'expired' %}
        <div class="row">
            <div class="col-md-12">
                <div id="pricing">
                    <div class="pricing-wizard">
                        <div class="step-panel active choose-plan">
                            <div class="instructions">
                                <strong>Please choose a plan below</strong> that best suites your needs, you can cancel
                                your
                                account, upgrade or downgrade any time.
                            </div>

                            <div class="plans">
                                <div class="plan clearfix selected">
                                    <div class="price">
                                        $999/mo
                                    </div>
                                    <div class="info">
                                        <div class="name">
                                            Basic
                                        </div>
                                        <div class="details">
                                            lorem ipsum dolor amit
                                        </div>
                                        <div class="select">
                                            <i class="fa fa-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button id="customButton" class="btn btn-success">Purchase</button>
            </div>
        </div>
    {% endif %}
    {% if user['subscriptionStatus'] == 'active' %}
        <div class="plan">

            <div class="current-plan">
                <div class="field">
                    <label>Plan:</label> Subscription (${{ current['amount'] }}/month)
                </div>
                <div class="field">
                    <label>Date:</label> {{ current['startDate'] }} to {{ current['endDate'] }}
                </div>
                {% set statusClass = (current['status'] == 'Active' ? 'status' : 'status-danger') %}
                <div class="field {{ statusClass }}">
                    <label>Status:</label> <span class="value">{{ current['status'] }}</span>
                </div>
            </div>


            <!-- <a class="btn btn-danger suspend-sub" href="#">Suspend my subscription</a> -->

            <div class="invoices">
                <h3>Invoices</h3>
                <table class="table">
                    <tr>
                        <td>
                            ID
                        </td>
                        <td>
                            Date
                        </td>
                        <td>
                            Amount
                        </td>
                        <td></td>
                    </tr>
                    {% for row in invoices %}
                    <tr>
                        <td>
                            <a href="{{ url('billing/invoice') }}?id={{ row['chargeId'] }}">{{ row['chargeId'] }}</a>
                        </td>
                        <td>
                            {{ row['startDate'] }} to {{ row['endDate'] }}
                        </td>
                        <td>
                            ${{ row['amount'] }}
                        </td>
                    </tr>
                    {% endfor %}
                </table>
            </div>
        </div>
    {% endif %}

</div>

{% include "settings/_billingJs.volt" %}