<?php

namespace App\Controllers;

use App\Libraries\Box as Box;
use App\Libraries\MyData as MyData;
use App\Libraries\Excel as Excel;
use App\Libraries\Revision as Revision;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Edit extends BaseController
{
	public function index()
	{
		$html = '';
		$html .= '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/style.css">';
		$html .= view('site/header');
		$html .= view('site/topnav');
		$html .= view('site/open_form');
		$html .= view('site/footer');
		return $html;
	}

	public function open()
	{
		$html = '';
		$html .= '<link rel="stylesheet" type="text/css" href="' . base_url() . 'css/style.css">';
		$html .= view('site/header');
		$html .= view('site/topnav');
		$html .= view('site/open_form');
		$request = $this->request->getPost();
		//var_dump($request);
		if (array_key_exists('revision_id', $request) and $request['revision_id']) {
			$revision_id = $request['revision_id'];
			$revision = new Revision();
			//var_dump($revision);
			$revise = $revision->getRevision($revision_id);
			$smp_titles = $revision->getSmpTitles()->getData();
			$inspector_titles = $revision->getInspectorTitles()->getData();
			if ($revise->isData()) {
				$data = $revise->getData();
				$html .= view('site/edit_form', ['smp_titles' => $smp_titles, 'inspector_titles' => $inspector_titles, 'defaults' => $data]);
				$html .= view('site/delete_form', ['hidden' => $request]);
			}
			if ($revise->isError()) {
				$html .= view('site/message_view', ['messages'=>$revise->getErrors()]);
			}
		}
		$html .= view('site/footer');
		return $html;
	}

	public function edit()
	{
		$revision = new Revision();
		$request = $this->request->getPost();
		$result = $revision->edit($request);
		$message = $result->isError() ? $result->getErrors() : ['Запись изменена'];

		$html = view('header');
		$html .= view('open_form');
		$html .= view('message_view', ['messages'=>$message]);
		return $html;
	}

	public function delete()
	{
		$revision = new Revision();
		$request = $this->request->getPost();

		$result = $revision->delete($request);
		$message = $result->isError() ? $result->getErrors() : ['Запись удалена'];

		$html = view('header');
		$html .= view('open_form');
		$html .= view('message_view', ['messages'=>$message]);
		return $html;
	}
}
