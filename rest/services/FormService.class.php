<?php
require_once __DIR__ . '/../dao/FormDao.php';

class FormService
{
    private $form_dao;

    public function __construct(FormDao $form_dao)
    {
        $this->form_dao = $form_dao;
    }

    public function add_formtest($form)
    {
        return $this->form_dao->add_formtest($form);
    }

    public function get_form()
    {
        return $this->form_dao->get_form();
    }

    public function delete_form($name)
    {
        return $this->form_dao->delete_form($name);
    }
}
