<?php

use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;

class ContactUsPageController extends WebAppPageController {

  private static $url_handlers = array(
    'form-handler' => 'handleContactUs'
  );

  private static $allowed_actions = array (
    'handleContactUs'
  );

  /**
   * Handles the submission of the contact us form.
   */
  public function handleContactUs(HTTPRequest $request) {
    if (!$request->isPost()) {
      $this->httpError(405);
      return;
    }

    $form = $this->contactUsForm()->loadDataFrom($_POST);

    if (!$form->validationResult()->isValid()) {
      header('Content-Type: application/json');
      echo json_encode(array('successful' => false));
      return;
    }

    $data = $form->getData();

    $recaptchaResponse = $this->checkRecaptcha($data['g-recaptcha-response']);

    if ($recaptchaResponse['success'] === false) {
      header('Content-Type: application/json');
      echo json_encode(array('successful' => false));
      return;
    }

    // save the message in the database
    $message = new ContactMessage();
    $message->Name = $data['name'];
    $message->Phone = $data['phone'];
    $message->Email = $data['email'];
    $message->Location = $data['location'];
    $message->Subject = $data['subject'];
    $message->Message = $data['message'];
    $message->Done = false;
    $message->write();

    header('Content-Type: application/json');
    echo json_encode(array('successful' => true));
    return;
  }

  /**
   * Get the contact us form.
   *
   * @return Form
   */
  private function contactUsForm() {
    $form = Form::create(
      $this,
      __FUNCTION__,
      FieldList::create(
        TextField::create('name'),
        TextField::create('phone'),
        EmailField::create('email'),
        TextField::create('location'),
        TextField::create('subject'),
        TextareaField::create('message'),
        HiddenField::create('g-recaptcha-response')
      ),
      FieldList::create(
        FormAction::create('send')
      ),
      RequiredFields::create('name', 'phone', 'email', 'location', 'subject', 'message', 'g-recaptcha-response')
    )->disableSecurityToken();

    return $form;
  }
}
