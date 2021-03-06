<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class fabrikViewMedia extends JView
{

	function display($tmpl = 'default')
	{
		FabrikHelperHTML::framework();
		FabrikHelperHTML::script('media/com_fabrik/js/list.js');
		$model = $this->getModel();
		$usersConfig = JComponentHelper::getParams('com_fabrik');
		$model->setId(JRequest::getVar('id', $usersConfig->get('visualizationid', JRequest::getInt('visualizationid', 0) )));
		$this->row = $model->getVisualization();
		$model->setListIds();

		if ($this->row->published == 0) {
			JError::raiseWarning(500, JText::_('ALERTNOTAUTH'));
			return '';
		}
		$calendar = $model->_row;
		$this->media = $model->getMedia();

		$viewName = $this->getName();
		$pluginManager = FabrikWorker::getPluginManager();
		$plugin = $pluginManager->getPlugIn('media', 'visualization');
		$this->assign('containerId', $this->get('ContainerId'));
		$this->assign('showFilters', JRequest::getInt('showfilters', 1) === 1 ?  1 : 0);
		$this->assignRef('filters', $this->get('Filters'));
		$pluginParams = $model->getPluginParams();
		$tmpl = $pluginParams->get('media_layout', $tmpl);
		$tmplpath = JPATH_ROOT.DS.'plugins'.DS.'fabrik_visualization'.DS.'media'.DS.'views'.DS.'media'.DS.'tmpl'.DS.$tmpl;
		$this->_setPath('template', $tmplpath);

		$ab_css_file = $tmplpath.DS."template.css";

		if (JFile::exists($ab_css_file))
		{
			JHTML::stylesheet('plugins/fabrik_visualization/media/views/media/tmpl/'.$tmpl.'/template.css');
		}
		echo parent::display();
	}

}
?>