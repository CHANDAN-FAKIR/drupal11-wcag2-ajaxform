<?php


//Ajaxform

namespace Drupal\ajaxform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Implements an Ajaxform.
 */
class Ajaxform extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'example_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['message'] = [
      '#weight' => -100,
    ];
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your name'),
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your email'),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
      '#ajax' => array(
        'callback' => '::ajax_test',
        'wrapper' => 'res-wrapper',
        'effect' => 'fade',
      ),
    ];

    $form['result_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'res-wrapper'],
    ];

    if ($msg = $form_state->get('result')) {
      // print_r($msg['name']);exit;

      $form['result_container']['result'] = [
        '#markup' => '<div> 
          <table>
            <tr>
              <th>name</th>
              <th>email</th>
            </tr>
            <tr>
              <td>' .$msg['name'] .'</td>
              <td>'.$msg['email'].'</td>
            </tr>
          </table>
        </div>',
      ];
    }
    return $form;
  }


  /**
 * {@inheritdoc}
 */
public function validateForm(array &$form, FormStateInterface $form_state) {
  if (empty($form_state->getValue('name'))) {
    $error = $this->t('The phone number is too short. Please enter a full phone number.');
    $form_state->setErrorByName('name', $error);
   
  }
}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $name = $form_state->getValue('name');
    $email = $form_state->getValue('email');

    // print_r($msg);exit;
    $form_state->set('result', [
      'name' => $name,
      'email' => $email,
    ]);
    $form_state->setRebuild(True);
  }
  public function ajax_test(array &$form, FormStateInterface $form_state)
  {
    return $form['result_container'];
  }
}
