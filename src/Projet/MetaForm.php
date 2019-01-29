<?php

namespace Projet;

class MetaForm
{
	protected $form_id;

	public function __construct(string $form_id)
	{
		$this->form_id = $form_id;
	}

	public function listFields()
	{
		return DB::getInstance()->get('SELECT * FROM fields WHERE form = ?;', $this->form_id);
	}
}