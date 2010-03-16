<?php
App::import('CORE', 'Sanitize');

class EventsController extends AppController
{

	public $name = 'Events';
	
	public $uses = array('Event');

	public function isAuthorized()
	{
		if($this->userLogged === TRUE && $this->params['prefix'] == User::get('type'))
		{
			return true;
		}
		
		return false;
	}
	
	/*
	 *  Ações para rota administrativa
	 */
	public function admin_index()
	{
		$this->Event->recursive = 0;
		
		$this->set('events', $this->paginate());
	}

	public function admin_view($id = null)
	{
		if (!$id)
		{
			$this->Session->setFlash(__('Evento inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('event', $this->Event->read(null, $id));
	}

	function admin_add()
	{
		if (!empty($this->data))
		{
			$this->Event->create();
			
			if ($this->Event->saveAll($this->data))
			{
				$this->Session->setFlash(__('Novo evento salvo!', true));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('Novo evento não pode ser salvo. Tente novamente.', true));
			}
		}
		
		$events = $this->Event->find('list');
		$this->set(compact('events'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Evento inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Event->save($this->data)) {
				$this->Session->setFlash(__('Evento atualizado!', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('O evento não pode ser salvo. Tente novamente.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Event->read(null, $id);
		}
		$parentEvents = $this->Event->ParentEvent->find('list');
		$this->set(compact('parentEvents'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id de evento inválido.', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Event->del($id)) {
			$this->Session->setFlash(__('Evento apagado!', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Evento não foi apagado!', true));
		$this->redirect(array('action' => 'index'));
	}
	
	/*
	 *  Ações para rota de participante
	 */
	public function participant_index()
	{
		$this->Event->recursive = 0;
		$this->set('events', $this->paginate());
	}

	public function participant_view($id = null)
	{
		if (!$id) 
		{
			$this->Session->setFlash(__('Evento inválido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('event', $this->Event->read(null, $id));
	}
	
	/*
	 * Ações assíncronaas (admin)
	 */
	
	public function admin_event_date_add()
	{
		if($this->RequestHandler->isAjax())
		{
			$this->__prepareAjax();
			$this->viewPath = '/elements/forms';
			
			if(isset($this->params['url']['lastDateIndex']) && $this->params['url']['lastDateIndex'] != null)
			{
				$i = Sanitize::paranoid($this->params['url']['lastDateIndex']) - 1;
			}
			else
			{
				$i = 0;
			}
			
			$this->set('i', $i);
			
			$this->render('event_date_add');
		}
	}
	
	public function admin_event_date_delete()
	{
		if($this->RequestHandler->isAjax())
		{
			
		}
	}
	
	public function admin_event_price_add()
	{
		if($this->RequestHandler->isAjax())
		{
			$this->__prepareAjax();
			$this->viewPath = '/elements/forms';
			
			if(isset($this->params['url']['lastPriceIndex']) && $this->params['url']['lastPriceIndex'] != null)
			{
				$i = Sanitize::paranoid($this->params['url']['lastPriceIndex']) - 1;
			}
			else
			{
				$i = 0;
			}
			
			$this->set('i', $i);
			
			$this->render('event_price_add');
		}
	}
	
}
?>