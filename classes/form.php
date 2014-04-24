<?php
// Generate a form from an array
//
// This class is far from complete as a form-generator. However, for the purposes
// it has, is good enough
class Form
  {
  // Allowed tags
  protected $tags = array('input', 'textarea', 'select');
  
  // Allowed types for the input
  protected $types = array(
    'checkbox', 'color', 'date', 'email', 'file', 'hidden', 'image', 'number',
    'password', 'radio', 'submit', 'tel', 'text', 'time', 'url');
  
  protected $fields = array();
  
  public function __construct(array $fields)
    {
    foreach ($fields as $Name => $Options)
      {
      $this->fields[] = $this->createfield($Name, $Options);
      }
    }
  
  public function gethtml()
    {
    $r  = '<form method = "POST">' . "\n  ";
    $r .= implode("\n  ", $this->fields);
    $r .= "\n</form>";
    
    return $r;
    }
  
  // http://stackoverflow.com/q/3534353
  protected function createfield($Name, array $Options = null)
    {
    //Default tags
    if (isset($Options['field']) && method_exists($this, $Options['field']))
      $field = $this->{$Options['field']}($Name, $Options);
    else
      $field = $this->input($Name, $Options);
    
    return $field;
    }
  
  protected function input($Name, $Options)
    {
    $r = "<label>\n    ";
    
    $labelbefore = (in_array($Options['type'], array('checkbox', 'radio'))) ? 0 : 1;
    
    $r .= ($labelbefore) ? $Options['label'] . "\n    " : "";
    
    $r .= '<input name = "' . $Name . '"';
    
    // All inputs have a type, even if it's text
    // Note: if it's not valid the browser interprets it as "text"
    foreach (array('type', 'value', 'placeholder', 'max', 'min') as $attr)
      {
      if (array_key_exists($attr, $Options))
        $r .= ' ' . $attr . ' = "' . $Options[$attr] . '"';
      }

    if (isset($Options['autocomplete']))
      $r .= ' autocomplete = "on"';
    
    foreach (array('checked', 'autofocus', 'required', 'disabled', 'readonly') as $Bool)
      if (in_array($Bool, $Options))
        $r .= ' ' . $Bool . ' = "' . $Bool . '"';
    
    // Close the tag
    $r .= ' />';
    
    // Close the label
    $r .= (!$labelbefore) ? "\n    " . $Options['label'] : "";
    
    $r .=  "\n  </label>";
    return $r;
    }
  
  protected function select($Name, $Options)
    {
    if (empty($Options['options']))
      throw new Exception("You cannot have a select without options");
    
    $r = '<select name = "' . $Name . '">';
    foreach ($Options['options'] as $Value => $Text)
      {
      $r .= '<option value = "' . $Value . '">' . $Text . '</option>';
      }
    
    $r .= '</select>';
    return $r;
    }
  
  protected function textarea($Name, $Options)
    {
    
    }
  }







