<?php

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\PasswordField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Security\Member;
use SilverStripe\Security\MemberLoginForm;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;

class LoginPageController extends WebAppPageController {

  private static $url_handlers = array(
    'form-handler' => 'handleLogin'
  );

  private static $allowed_actions = array (
    'handleLogin'
  );

  const ERROR_CODE_UNKNOWN = 1;
  const ERROR_CODE_INVALID_FORM = 2;
  const ERROR_CODE_BAD_CREDENTIALS = 3;

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
   * Get the contact us form.
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
   * Handles the submission of the contact us form.
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

    // Log the user in
    // Note: session_id will be regenerated
    $form->performLogin($formData);
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
