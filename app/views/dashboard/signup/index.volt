{% extends "_templates/signin_base.volt" %}
{% block content %}
    <div id="signup">
        <h3>Create Your Account Now</h3>
        <div class="content">
            {{ form('signup/do', 'method' : 'post', "class" : "signup-form" , "novalidate" : "true") }}
            <div class="fields">
                <strong>Your access data</strong>
                {{ form.render('email', ['class': 'form-control', 'placeholder': 'Your Business Email', 'autofocus':'']) }}
                {{ form.render('password', ['class': 'form-control', 'placeholder': 'Password']) }}
                {{ form.render('password_confirmation', ['class': 'form-control', 'placeholder': 'Password Confirmation']) }}
            </div>
            <div class="fields">
                <strong>Your information</strong>
                {{ form.render('name', ['class' : 'form-control', 'placeholder' : 'First Name']) }}
                {{ form.render('lname', ['class' : 'form-control', 'placeholder' : 'Last Name']) }}
                {{ form.render('websiteUrl', ['class' : 'form-control', 'placeholder' : 'Your Website URL']) }}
                {{ form.render('companyName', ['class' : 'form-control', 'placeholder' : 'Your Company Name']) }}
                {{ form.render('companyCountry', ['class' : 'form-control', 'placeholder' : 'Your Company Country']) }}
                {{ form.render('companyCity', ['class' : 'form-control', 'placeholder' : 'Your Company City']) }}
            </div>

            <div class="info">
                Your 14-day trial will start now. After your trial ends, you will be required to subscribe <strong>$999 USD/month
                </strong>
            </div>
            <div class="signup">
                {{ form.render('submit', ['class': 'btn btn-success btn-lg', 'value': 'Create my account']) }}
            </div>
            {{ endform() }}
        </div>

        <div class="bottom-wrapper">
            <div class="message">
                <span>Already have an account?</span>
                <a href="signin.html">Sign in here</a>.
            </div>
        </div>
    </div>

{% endblock %}




