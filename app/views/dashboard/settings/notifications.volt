<div id="panel" class="profile">
    <h3>
        {{ page_title }}
    </h3>
    <div class="row mrg-top-20">
        <div class="col-md-10">
            <ul class="list-unstyled list-info">
                <li>
                                            <span class="thumb-img pdd-top-5">
                                                    <i class="ti-mobile font-size-30"></i>
                                                </span>
                    <div class="info">
                        <b class="text-dark font-size-16">Send Email Notification on Leads</b>
                        <p>Email notification will be sent when a keyword is found within document</p>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-2 text-right">
            <div class="toggle-checkbox toggle-success checkbox-inline toggle-sm mrg-top-15">
                <input type="checkbox" class="notifications-checkbox" data-action="notifications_leads" name="toggle2" id="toggle2" {% if user['sendNotificationsOnLeads'] == true %}checked=""{% endif %}>
                <label for="toggle2"></label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <ul class="list-unstyled list-info">
                <li>
                                            <span class="thumb-img pdd-top-5">
                                                    <i class="ti-location-pin font-size-30"></i>
                                                </span>
                    <div class="info">
                        <b class="text-dark font-size-16">Show alerts</b>
                        <p>Show alert modal upon login into dashboard</p>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-2 text-right">
            <div class="toggle-checkbox toggle-success checkbox-inline toggle-sm mrg-top-15">
                <input type="checkbox" class="notifications-checkbox" data-action="show_alerts" name="toggle3" id="toggle3" {% if user['showAlerts'] == true %}checked=""{% endif %}>
                <label for="toggle3"></label>
            </div>
        </div>
    </div>
</div>
{% include "settings/_notificationsJs.volt" %}
