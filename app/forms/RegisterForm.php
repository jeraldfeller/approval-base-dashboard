<?php

namespace Aiden\Forms;

class RegisterForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {

        // Name
        $name = new \Phalcon\Forms\Element\Text('name', [
            'class' => 'form-control',
            'placeholder' => 'Enter a name',
            'required' => '',
            'value' => 'Test Account'
        ]);
        $name
            ->setLabel('Name:')
            ->addValidators([
                new \Phalcon\Validation\Validator\StringLength([
                    'max' => 100,
                    'min' => 1,
                    'messageMaximum' => 'The first name is too long.',
                    'messageMinimum' => 'The first name is too short.',
                    'cancelOnFail' => true,
                ]),
            ]);
        $this->add($name);

        // Name
        $lname = new \Phalcon\Forms\Element\Text('lname', [
            'class' => 'form-control',
            'placeholder' => 'Enter a name',
            'required' => '',
        ]);
        $lname
            ->setLabel('Name:')
            ->addValidators([
                new \Phalcon\Validation\Validator\StringLength([
                    'max' => 100,
                    'min' => 1,
                    'messageMaximum' => 'The last name is too long.',
                    'messageMinimum' => 'The last name is too short.',
                    'cancelOnFail' => false,
                ]),
            ]);
        $this->add($lname);


        // Website Url
        $websiteUrl = new \Phalcon\Forms\Element\Text('websiteUrl', [
            'class' => 'form-control',
            'placeholder' => 'Your Website URL'
        ]);
        $websiteUrl
            ->setLabel('Website URL:')
            ->addValidators([
                new \Phalcon\Validation\Validator\StringLength([
                    'max' => 100,
                    'min' => 0,
                    'messageMaximum' => 'The website url is too long.',
                    'messageMinimum' => 'The website url is too short.',
                    'cancelOnFail' => false,
                ])
            ]);
        $this->add($websiteUrl);


        // Company Name
        $companyName = new \Phalcon\Forms\Element\Text('companyName', [
            'class' => 'form-control',
            'placeholder' => 'Your Company Name'
        ]);
        $companyName
            ->setLabel('Company Name:')
            ->addValidators([
                new \Phalcon\Validation\Validator\StringLength([
                    'max' => 100,
                    'min' => 0,
                    'messageMaximum' => 'The company name is too long.',
                    'messageMinimum' => 'The company name is too short.',
                    'cancelOnFail' => false,
                ])
            ]);
        $this->add($companyName);

        // Company Country
        $companyCountry = new \Phalcon\Forms\Element\Select('companyCountry', [
            'Australia' => 'Australia'
        ]);
        $companyCountry
            ->setLabel('Company Country:')
            ->addValidators([
                new \Phalcon\Validation\Validator\StringLength([
                    'max' => 100,
                    'min' => 0,
                    'messageMaximum' => 'The company country is too long.',
                    'messageMinimum' => 'The company country is too short.',
                    'cancelOnFail' => false,
                ])
            ]);
        $this->add($companyCountry);
        // Company City
        $companyCity = new \Phalcon\Forms\Element\Text('companyCity', [
            'class' => 'form-control',
            'placeholder' => 'Your Company City'
        ]);
        $companyCity
            ->setLabel('Company City:')
            ->addValidators([
                new \Phalcon\Validation\Validator\StringLength([
                    'max' => 100,
                    'min' => 0,
                    'messageMaximum' => 'The company city is too long.',
                    'messageMinimum' => 'The company city is too short.',
                    'cancelOnFail' => false,
                ])
            ]);
        $this->add($companyCity);
        // Email
        $email = new \Phalcon\Forms\Element\Email('email', [
            'class' => 'form-control',
            'placeholder' => 'Enter an email address',
            'required' => '',
            'value' => 'someone@somewhere.com'
        ]);
        $email
            ->setLabel('Email:')
            ->addValidators([
                new \Phalcon\Validation\Validator\StringLength([
                    'max' => 254,
                    'min' => 1,
                    'messageMaximum' => 'The specified email is too long.',
                    'messageMinimum' => 'The specified email is too short.',
                    'cancelOnFail' => true,
                ]),
                new \Phalcon\Validation\Validator\Email([
                    'message' => 'Please use a valid email address.',
                    'cancelOnFail' => true,
                ]),
                new \Phalcon\Validation\Validator\Uniqueness([
                    'model' => new \Aiden\Models\Users(),
                    'with' => 'email',
                    'message' => 'The email address is already being used',
                    'cancelOnFail' => true,
                ]),
            ]);
        $this->add($email);

        // Password
        $password = new \Phalcon\Forms\Element\Password('password', [
            'class' => 'form-control',
            'placeholder' => 'Enter a password',
            'required' => '',
        ]);
        $password
            ->setLabel('Password:')
            ->addValidator(
                new \Phalcon\Validation\Validator\PresenceOf([
                    'message' => 'Please enter a password.',
                    'cancelOnFail' => true,
                ])
            );
        $this->add($password);

        // Password confirmation
        $passwordConfirmation = new \Phalcon\Forms\Element\Password('password_confirmation', [
            'class' => 'form-control',
            'placeholder' => 'Enter a password confirmation',
            'required' => '',
        ]);
        $passwordConfirmation
            ->setLabel('Password Confirmation:')
            ->addValidators([
                new \Phalcon\Validation\Validator\PresenceOf([
                    'message' => 'Please enter a password confirmation.',
                    'cancelOnFail' => true,
                ]),
                new \Phalcon\Validation\Validator\Confirmation([
                    'message' => 'The passwords do not match',
                    'with' => 'password'
                ]),
            ]);
        $this->add($passwordConfirmation);

        // Submit
        $submit = new \Phalcon\Forms\Element\Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-success'
        ]);
        $this->add($submit);

    }

}
