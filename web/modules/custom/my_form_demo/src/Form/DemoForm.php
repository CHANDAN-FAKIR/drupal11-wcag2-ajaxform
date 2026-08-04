<?php

namespace Drupal\my_form_demo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DemoForm extends FormBase {

  public function getFormId() {
    return 'example_form';
  }

   public function buildForm(array $form, FormStateInterface $form_state) {

    // Wrapper for AJAX result.
    $form['result_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'result-wrapper'],
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
     $form['age'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Age'),
      '#required' => TRUE,
    ];
     $form['phone'] = [
      '#type' => 'number',
      '#title' => $this->t('Phone'),
      '#required' => TRUE,
    ];
     $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#required' => TRUE,
    ];

    $form['option'] = [
      '#type' => 'select',
      '#title' => $this->t('Option'),
      '#options' => [
        'yes' => $this->t('Yes'),
        'no'  => $this->t('No'),
      ],
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => 'result-wrapper',
        'effect' => 'fade',
      ],
    ];

    // Only show table after submit.
 if ($form_state->get('show_table')) {
  $v = $form_state->get('submitted_values');

  // Build all fields into one array (option excluded)
  $cols = [
    'id'    => $v['id'],
    'name'  => $v['name'],
    'email' => $v['email'],
    'age'   => $v['age'],
    'phone' => $v['option'] === 'yes' ? $v['phone'] : null, // dynamic
    'city'  => $v['city'],
  ];

  // Remove NULL items → phone disappears when option="no"
  $cols = array_filter($cols);

  // Header labels from keys
  $header = array_map('ucfirst', array_keys($cols));

  // Row values
  $row = array_values($cols);

  $form['result_wrapper']['result_table'] = [
    '#type' => 'table',
    '#header' => $header,
    '#rows' => [$row],
  ];
}



    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Store submitted data for AJAX callback.
    $form_state->set('submitted_values', $form_state->getValues());
    $form_state->set('show_table', TRUE);

    // Rebuild form to show table.
    $form_state->setRebuild(TRUE);
  }

  public function ajaxCallback(array &$form, FormStateInterface $form_state) {
    return $form['result_wrapper'];
  }

}