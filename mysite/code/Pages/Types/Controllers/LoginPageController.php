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
      echo json_encode(array('successful' => false));
      return;
    }

    $formData = $form->getData();
    $email = isset($formData['Email']) ? $formData['Email'] : null;
    $password = isset($formData['Password']) ? $formData['Password'] : null;

    if (!($email && $password)) {
      header('Content-Type: application/json');
      echo json_encode(array('successful' => false));
      return;
    }

    // Log the user in
    // Note: session_id will be regenerated
    $form->performLogin($formData);
    $member = Member::currentUser();

    $newIdToken = session_id();

    if ($member === null || !$newIdToken) {
      header('Content-Type: application/json');
      echo json_encode(array('successful' => false));
      return;
    }

    header('Content-Type: application/json');
    echo json_encode(array(
      'successful' => true,
      'first-name' => $member->FirstName,
      'last-name' => $member->LastName,
      'new-id-token' => $newIdToken
    ));
    return;
  }
}
