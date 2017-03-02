<?php

use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\PasswordField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Security\Member;
use SilverStripe\Security\MemberLoginForm;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;

class LoginPageController extends WebAppPageController {

  private static $url_handlers = array(
    'login-handler' => 'handleLogin',
    'signup-handler' => 'handleSignUp'
  );

  private static $allowed_actions = array (
    'handleLogin',
    'handleSignUp'
  );

  const ERROR_CODE_UNKNOWN = 1;
  const ERROR_CODE_INVALID_FORM = 2;
  const ERROR_CODE_BAD_CREDENTIALS = 3;
  const ERROR_CODE_ALREADY_SIGNED_IN = 4;
  const ERROR_CODE_USER_ALREADY_EXIST = 5;

  /**
   * Get the user data information of the current user.
   *
   * @return Array|null
   */
  public static function getUserData() {
    $member = Member::currentUser();
    if ($member === null) {
      return null;
    }
    return array(
      'first-name' => $member->FirstName,
      'last-name' => $member->Surname,
      'email' => $member->Email
    );
  }

  /**
   * Get the login form.
   *
   * @return Form
   */
  private function appLoginForm() {
    $form = MemberLoginForm::create(
      $this,
      __FUNCTION__,
      FieldList::create(
        EmailField::create('Email'),
        PasswordField::create('Password')
      ),
      FieldList::create(
        FormAction::create('login')
      ),
      RequiredFields::create('Email', 'Password')
    )->disableSecurityToken();

    return $form;
  }

  /**
   * Get the sign up form.
   *
   * @return Form
   */
  private function appSignupForm() {
    $form = Form::create(
      $this,
      __FUNCTION__,
      FieldList::create(
        TextField::create('FirstName'),
        TextField::create('Surname'),
        EmailField::create('Email'),
        PasswordField::create('Password')
      ),
      FieldList::create(
        FormAction::create('signup')
      ),
      RequiredFields::create('Email', 'Password')
    )->disableSecurityToken();

    return $form;
  }

  /**
   * Handles the submission of the login form.
   */
  public function handleLogin(HTTPRequest $request) {
    if (!$request->isPost()) {
      $this->httpError(405);
      return;
    }

    $form = $this->appLoginForm()->loadDataFrom($_POST);

    // if already logged in
    if (Member::currentUser() !== null) {
      $form->logout();
      $form = $this->appLoginForm()->loadDataFrom($_POST);
    }

    if (!$form->validationResult()->isValid()) {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_INVALID_FORM
      ));
      return;
    }

    $formData = $form->getData();
    $email = isset($formData['Email']) ? $formData['Email'] : null;
    $password = isset($formData['Password']) ? $formData['Password'] : null;

    if (!($email && $password)) {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_INVALID_FORM
      ));
      return;
    }

    $this->logUserIn($email, $password);
  }

  /**
   * Handles the submission of the sign up form.
   */
  public function handleSignUp(HTTPRequest $request) {
    if (!$request->isPost()) {
      $this->httpError(405);
      return;
    }

    // if already logged in
    if (Member::currentUser() !== null) {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_ALREADY_SIGNED_IN
      ));
      return;
    }

    $form = $this->appSignUpForm()->loadDataFrom($_POST);

    if (!$form->validationResult()->isValid()) {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_INVALID_FORM
      ));
      return;
    }

    $formData = $form->getData();
    $email = isset($formData['Email']) ? $formData['Email'] : null;
    $password = isset($formData['Password']) ? $formData['Password'] : null;

    if (!($email && $password)) {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_INVALID_FORM
      ));
      return;
    }

    // Make sure the user doesn't already exist
    if (DataObject::get_one('SilverStripe\Security\Member', "Email = '" . $email . "'") !== false) {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_USER_ALREADY_EXIST
      ));
      return;
    }

    // signup user

    // Create a new Member object and load the form data into it
    $member = new Member();
    $form->saveInto($member);

    // Write it to the database. This needs to happen before we add it to a group
    $member->write();

    // Add the member to group.
    if ($group = DataObject::get_one('SilverStripe\Security\Group', "Code = 'users'")) {
      $this->logUserIn($email, $password);
    } else {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_UNKNOWN
      ));
      return;
    }
  }

  /**
   * Log the user in from the form data given.
   *
   * Note: session_id will be regenerated
   *
   * @param {Array} $formData
   */
  private function logUserIn($email, $password) {
    $form = $this->appLoginForm()->performLogin(array(
      'Email' => $email,
      'Password' => $password
    ));
    $member = Member::currentUser();

    $newIdToken = session_id();

    if (!$newIdToken) {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_UNKNOWN
      ));
      return;
    }

    if ($member === null) {
      header('Content-Type: application/json');
      echo json_encode(array(
        'successful' => false,
        'errorCode' => self::ERROR_CODE_BAD_CREDENTIALS
      ));
      return;
    }

    header('Content-Type: application/json');
    echo json_encode(array_merge(
      self::getUserData(),
      array(
        'successful' => true,
        'new-id-token' => $newIdToken
      )
    ));
    return;
  }
}
