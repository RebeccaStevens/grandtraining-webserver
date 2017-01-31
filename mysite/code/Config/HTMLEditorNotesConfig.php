<?php

use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;

class HTMLEditorNotesConfig extends TinyMCEConfig {

  protected $buttons = array(
    1 => array(
      'bold', 'italic', 'underline', 'removeformat', '|',
      'fontsizeselect', '|',
      'alignleft', 'aligncenter', 'alignright', 'alignjustify', '|',
      'bullist', 'numlist', 'outdent', 'indent',
    ),
    2 => array(
      'formatselect', '|',
      'paste', 'pastetext', '|',
      'forecolor', 'backcolor', '|',
      'table', 'ssmedia', 'sslink', 'unlink', '|',
      'code'
    ),
    3 => array()
  );

  /**
   * Holder list of enabled plugins
   *
   * @var array
   */
  protected $plugins = array(
    'table' => null,
    'emoticons' => null,
    'paste' => null,
    'code' => null,
    'link' => null,
    'importcss' => null,
    'textcolor' => null,
    'colorpicker' => null
  );
}
